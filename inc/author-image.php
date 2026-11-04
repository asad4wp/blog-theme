<?php
/**
 * Custom author profile image upload.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PUNS_AUTHOR_IMAGE_META', 'puns_author_image_id' );

/**
 * Whether the current user can manage an author's profile image.
 *
 * @param int $user_id User ID.
 * @return bool
 */
function puns_author_image_can_manage( $user_id ) {
	return current_user_can( 'edit_user', $user_id );
}

/**
 * Whether the current user can upload media for author images.
 *
 * @return bool
 */
function puns_author_image_can_upload() {
	return current_user_can( 'upload_files' );
}

/**
 * Get stored author image attachment ID.
 *
 * @param int $author_id User ID.
 * @return int
 */
function puns_get_author_image_id( $author_id ) {
	return absint( get_user_meta( $author_id, PUNS_AUTHOR_IMAGE_META, true ) );
}

/**
 * Resolve a user ID from common avatar identifier types.
 *
 * @param int|string|object $id_or_email User ID, email, comment object, or WP_User.
 * @return int
 */
function puns_author_image_resolve_user_id( $id_or_email ) {
	if ( is_numeric( $id_or_email ) ) {
		return absint( $id_or_email );
	}

	if ( $id_or_email instanceof WP_User ) {
		return (int) $id_or_email->ID;
	}

	if ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
		return absint( $id_or_email->user_id );
	}

	if ( is_string( $id_or_email ) && is_email( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );

		return $user ? (int) $user->ID : 0;
	}

	return 0;
}

/**
 * Get author avatar URL with custom image and Gravatar fallback.
 *
 * @param int $author_id Author user ID.
 * @param int $size      Image size in pixels.
 * @return string
 */
function puns_author_avatar_url( $author_id, $size = 96 ) {
	$author_id = absint( $author_id );
	$size      = max( 32, absint( $size ) );

	if ( $author_id ) {
		$attachment_id = puns_get_author_image_id( $author_id );

		if ( $attachment_id ) {
			$image = wp_get_attachment_image_src( $attachment_id, array( $size, $size ) );

			if ( ! empty( $image[0] ) ) {
				return (string) $image[0];
			}

			$image = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );

			if ( ! empty( $image[0] ) ) {
				return (string) $image[0];
			}

			$image = wp_get_attachment_image_src( $attachment_id, 'full' );

			if ( ! empty( $image[0] ) ) {
				return (string) $image[0];
			}
		}
	}

	$avatar = get_avatar_url(
		$author_id,
		array(
			'size' => $size,
		)
	);

	return $avatar ? (string) $avatar : '';
}

/**
 * Override WordPress avatars when a custom author image exists.
 *
 * @param array $args        Avatar data.
 * @param mixed $id_or_email User identifier.
 * @return array
 */
function puns_author_image_override_avatar( $args, $id_or_email ) {
	$user_id = puns_author_image_resolve_user_id( $id_or_email );

	if ( ! $user_id || ! puns_get_author_image_id( $user_id ) ) {
		return $args;
	}

	$size = isset( $args['size'] ) ? absint( $args['size'] ) : 96;
	$url  = puns_author_avatar_url( $user_id, $size );

	if ( '' !== $url ) {
		$args['url']          = $url;
		$args['found_avatar'] = true;
	}

	return $args;
}
add_filter( 'pre_get_avatar_data', 'puns_author_image_override_avatar', 10, 2 );

/**
 * Enqueue media uploader on user profile screens.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function puns_author_image_admin_assets( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'profile.php', 'user-edit.php' ), true ) ) {
		return;
	}

	$screen_user_id = 0;

	if ( 'profile.php' === $hook_suffix ) {
		$screen_user_id = get_current_user_id();
	} elseif ( isset( $_GET['user_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$screen_user_id = absint( wp_unslash( $_GET['user_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	if ( ! $screen_user_id || ! puns_author_image_can_manage( $screen_user_id ) ) {
		return;
	}

	if ( puns_author_image_can_upload() ) {
		wp_enqueue_media();
	}

	wp_enqueue_script(
		'puns-author-image',
		get_template_directory_uri() . '/assets/js/author-image.js',
		array( 'jquery' ),
		PUNS_VERSION,
		true
	);

	wp_localize_script(
		'puns-author-image',
		'punsBlogAuthorImage',
		array(
			'title'    => __( 'Select author image', 'puns' ),
			'button'   => __( 'Use this image', 'puns' ),
			'canUpload' => puns_author_image_can_upload(),
		)
	);

	wp_add_inline_style(
		'wp-admin',
		'
		.puns-author-image-wrap { margin: 0 0 12px; }
		.puns-author-image-field { margin-top: 8px; }
		.puns-author-image-preview {
			display: block;
			width: 96px;
			height: 96px;
			margin-bottom: 12px;
			border-radius: 50%;
			object-fit: cover;
			background: #f0f0f1;
		}
		.puns-author-image-preview--empty {
			display: flex;
			align-items: center;
			justify-content: center;
			color: #646970;
			font-size: 12px;
			text-align: center;
			padding: 8px;
		}
		.puns-author-image-actions { display: flex; gap: 8px; flex-wrap: wrap; }
		'
	);
}
add_action( 'admin_enqueue_scripts', 'puns_author_image_admin_assets' );

/**
 * Render author image field markup.
 *
 * @param WP_User $user User object.
 */
function puns_author_image_render_field( $user ) {
	if ( ! puns_author_image_can_manage( $user->ID ) ) {
		return;
	}

	$attachment_id = puns_get_author_image_id( $user->ID );
	$preview_url   = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : '';
	$can_upload    = puns_author_image_can_upload();
	?>
	<tr class="puns-author-image-wrap">
		<th scope="row">
			<label for="puns_author_image_id"><?php esc_html_e( 'Author image', 'puns' ); ?></label>
		</th>
		<td>
			<div class="puns-author-image-field" data-puns-author-image>
				<?php wp_nonce_field( 'puns_author_image_save', 'puns_author_image_nonce' ); ?>
				<input
					type="hidden"
					id="puns_author_image_id"
					name="puns_author_image_id"
					value="<?php echo esc_attr( $attachment_id ); ?>"
				>

				<?php if ( $preview_url ) : ?>
					<img
						class="puns-author-image-preview"
						src="<?php echo esc_url( $preview_url ); ?>"
						alt=""
						data-puns-author-image-preview
					>
				<?php else : ?>
					<div class="puns-author-image-preview puns-author-image-preview--empty" data-puns-author-image-preview>
						<?php esc_html_e( 'No custom image', 'puns' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $can_upload ) : ?>
					<div class="puns-author-image-actions">
						<button type="button" class="button button-secondary" data-puns-author-image-upload>
							<?php echo $attachment_id ? esc_html__( 'Replace image', 'puns' ) : esc_html__( 'Upload image', 'puns' ); ?>
						</button>
						<button
							type="button"
							class="button button-link-delete"
							data-puns-author-image-remove
							<?php echo $attachment_id ? '' : 'style="display:none"'; ?>
						>
							<?php esc_html_e( 'Remove custom image', 'puns' ); ?>
						</button>
					</div>
					<p class="description">
						<?php esc_html_e( 'This custom image overwrites Gravatar on posts and author boxes. Use a square image, at least 200x200 px.', 'puns' ); ?>
					</p>
				<?php else : ?>
					<p class="description">
						<?php esc_html_e( 'You do not have permission to upload files. Ask an administrator to set your author image.', 'puns' ); ?>
					</p>
				<?php endif; ?>
			</div>
		</td>
	</tr>
	<?php
}

/**
 * Render author image section on the current user's profile.
 *
 * @param WP_User $user User object.
 */
function puns_author_image_personal_options( $user ) {
	if ( ! puns_author_image_can_manage( $user->ID ) ) {
		return;
	}

	puns_author_image_render_field( $user );
}
add_action( 'personal_options', 'puns_author_image_personal_options' );

/**
 * Render author image section when editing another user.
 *
 * @param WP_User $user User object.
 */
function puns_author_image_edit_user_profile( $user ) {
	if ( ! puns_author_image_can_manage( $user->ID ) ) {
		return;
	}
	?>
	<h2><?php esc_html_e( 'Author Image', 'puns' ); ?></h2>
	<table class="form-table" role="presentation">
		<?php puns_author_image_render_field( $user ); ?>
	</table>
	<?php
}
add_action( 'edit_user_profile', 'puns_author_image_edit_user_profile' );

/**
 * Save author image on profile update.
 *
 * @param int $user_id User ID.
 */
function puns_author_image_save_profile( $user_id ) {
	if ( ! puns_author_image_can_manage( $user_id ) ) {
		return;
	}

	if ( ! isset( $_POST['puns_author_image_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['puns_author_image_nonce'] ) ), 'puns_author_image_save' ) ) {
		return;
	}

	if ( ! isset( $_POST['puns_author_image_id'] ) ) {
		return;
	}

	if ( ! puns_author_image_can_upload() ) {
		return;
	}

	$attachment_id = absint( wp_unslash( $_POST['puns_author_image_id'] ) );

	if ( 0 === $attachment_id ) {
		delete_user_meta( $user_id, PUNS_AUTHOR_IMAGE_META );
		return;
	}

	if ( 'attachment' !== get_post_type( $attachment_id ) ) {
		return;
	}

	update_user_meta( $user_id, PUNS_AUTHOR_IMAGE_META, $attachment_id );
}
add_action( 'personal_options_update', 'puns_author_image_save_profile' );
add_action( 'edit_user_profile_update', 'puns_author_image_save_profile' );
