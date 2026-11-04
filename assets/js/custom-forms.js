/**
 * Custom Contact / Write form AJAX submission.
 */
(function () {
	'use strict';

	if (typeof punsCustomForms === 'undefined') {
		return;
	}

	var cfg = punsCustomForms;

	function qs(root, sel) {
		return root.querySelector(sel);
	}

	function qsa(root, sel) {
		return Array.prototype.slice.call(root.querySelectorAll(sel));
	}

	function setNotice(wrap, message, type) {
		var notice = qs(wrap, '.puns-cform__notice');
		if (!notice) {
			return;
		}

		if (!message) {
			notice.hidden = true;
			notice.textContent = '';
			notice.className = 'puns-cform__notice';
			return;
		}

		notice.hidden = false;
		notice.textContent = message;
		notice.className = 'puns-cform__notice puns-cform__notice--' + (type || 'error');
	}

	function clearFieldErrors(form) {
		qsa(form, '.puns-cform__field').forEach(function (field) {
			field.classList.remove('puns-cform__field--error');
			var err = qs(field, '.puns-cform__error');
			if (err) {
				err.hidden = true;
				err.textContent = '';
			}
		});
	}

	function showFieldErrors(form, fields) {
		if (!fields || typeof fields !== 'object') {
			return;
		}

		Object.keys(fields).forEach(function (key) {
			var field = qs(form, '.puns-cform__field[data-field="' + key + '"]');
			if (!field) {
				return;
			}
			field.classList.add('puns-cform__field--error');
			var err = qs(field, '.puns-cform__error');
			if (err) {
				err.hidden = false;
				err.textContent = fields[key];
			}
		});
	}

	function scrollToFirstError(form) {
		var first = qs(form, '.puns-cform__field--error .puns-cform__control');
		if (first) {
			first.focus({ preventScroll: true });
			first.scrollIntoView({ behavior: 'smooth', block: 'center' });
		}
	}

	function validateFormClient(form) {
		var errors = {};

		qsa(form, '.puns-cform__field').forEach(function (fieldWrap) {
			var input = qs(fieldWrap, '.puns-cform__control');
			var key = fieldWrap.getAttribute('data-field');

			if (!input || !key) {
				return;
			}

			var value = input.value.trim();
			var required = input.hasAttribute('required');
			var type = (input.getAttribute('type') || 'text').toLowerCase();

			if (required && '' === value) {
				errors[key] = cfg.i18n.requiredField || 'This field is required.';
				return;
			}

			if ('' === value) {
				return;
			}

			if ('email' === type && !input.validity.valid) {
				errors[key] = cfg.i18n.invalidEmail || 'Please enter a valid email address.';
				return;
			}

			if ('url' === type && !input.validity.valid) {
				errors[key] = cfg.i18n.invalidUrl || 'Please enter a valid URL.';
			}
		});

		return errors;
	}

	function showValidationErrors(form, wrap, fields) {
		showFieldErrors(form, fields);
		setNotice(
			wrap,
			cfg.i18n.validationSummary ||
				'There was a problem with your submission. Please review the fields below.',
			'validation'
		);
		scrollToFirstError(form);
	}

	function getRecaptchaToken(formKey) {
		if (!cfg.recaptchaEnabled) {
			return Promise.resolve('');
		}

		if (cfg.recaptchaVersion === 'v3' && window.grecaptcha && cfg.recaptchaSiteKey) {
			return new Promise(function (resolve, reject) {
				window.grecaptcha.ready(function () {
					window.grecaptcha
						.execute(cfg.recaptchaSiteKey, { action: 'puns_form_' + formKey })
						.then(resolve)
						.catch(reject);
				});
			});
		}

		var widget = document.querySelector('.g-recaptcha textarea[name="g-recaptcha-response"]');
		if (widget && widget.value) {
			return Promise.resolve(widget.value);
		}

		return Promise.reject(new Error('recaptcha'));
	}

	function resetRecaptcha() {
		if (window.grecaptcha && typeof window.grecaptcha.reset === 'function') {
			try {
				window.grecaptcha.reset();
			} catch (e) {
				/* widget may not exist for v3 */
			}
		}
	}

	function buildFormData(form, token) {
		var data = new FormData();
		data.append('action', cfg.action);
		data.append('nonce', cfg.nonce);
		data.append('form_key', form.getAttribute('data-form-key') || '');

		qsa(form, 'input[name^="fields"], textarea[name^="fields"]').forEach(function (input) {
			data.append(input.name, input.value);
		});

		var hp = qs(form, 'input[name="puns_hp"]');
		if (hp) {
			data.append('puns_hp', hp.value);
		}

		if (token) {
			data.append('g-recaptcha-response', token);
		}

		return data;
	}

	function bindFieldClear(form, wrap) {
		qsa(form, '.puns-cform__control').forEach(function (input) {
			input.addEventListener('input', function () {
				var fieldWrap = input.closest('.puns-cform__field');
				if (!fieldWrap || !fieldWrap.classList.contains('puns-cform__field--error')) {
					return;
				}

				var value = input.value.trim();
				var required = input.hasAttribute('required');
				var type = (input.getAttribute('type') || 'text').toLowerCase();
				var valid = true;

				if (required && '' === value) {
					valid = false;
				} else if ('email' === type && '' !== value && !input.validity.valid) {
					valid = false;
				} else if ('url' === type && '' !== value && !input.validity.valid) {
					valid = false;
				}

				if (valid) {
					fieldWrap.classList.remove('puns-cform__field--error');
					var err = qs(fieldWrap, '.puns-cform__error');
					if (err) {
						err.hidden = true;
						err.textContent = '';
					}

					if (!qs(form, '.puns-cform__field--error')) {
						setNotice(wrap, '', '');
					}
				}
			});
		});
	}

	function handleSubmit(event) {
		event.preventDefault();

		var form = event.currentTarget;
		var wrap = form.closest('.puns-cform-wrap');
		var submitBtn = qs(form, '.puns-cform__submit');
		var formKey = form.getAttribute('data-form-key') || 'contact';
		var defaultLabel =
			formKey === 'write' ? cfg.i18n.submitWrite || cfg.i18n.submit : cfg.i18n.submit;

		clearFieldErrors(form);
		setNotice(wrap, '', '');

		var clientErrors = validateFormClient(form);
		if (Object.keys(clientErrors).length) {
			showValidationErrors(form, wrap, clientErrors);
			return;
		}

		if (submitBtn) {
			submitBtn.disabled = true;
			submitBtn.textContent = cfg.i18n.sending;
		}

		getRecaptchaToken(formKey)
			.then(function (token) {
				return fetch(cfg.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: buildFormData(form, token),
				});
			})
			.then(function (response) {
				return response.json().then(function (json) {
					return { ok: response.ok, status: response.status, json: json };
				});
			})
			.then(function (result) {
				if (result.json && result.json.success) {
					form.reset();
					resetRecaptcha();
					setNotice(
						wrap,
						(result.json.data && result.json.data.message) ||
							'Thank you! Your message was sent.',
						'success'
					);
					return;
				}

				var payload = result.json && result.json.data ? result.json.data : {};
				if (payload.fields) {
					showValidationErrors(form, wrap, payload.fields);
				} else {
					setNotice(wrap, payload.message || cfg.i18n.error, 'error');
				}
				resetRecaptcha();
			})
			.catch(function (err) {
				if (err && err.message === 'recaptcha') {
					setNotice(wrap, cfg.i18n.recaptcha, 'validation');
				} else {
					setNotice(wrap, cfg.i18n.error, 'error');
				}
				resetRecaptcha();
			})
			.finally(function () {
				if (submitBtn) {
					submitBtn.disabled = false;
					submitBtn.textContent = defaultLabel;
				}
			});
	}

	document.addEventListener('DOMContentLoaded', function () {
		qsa(document, '.puns-cform').forEach(function (form) {
			var wrap = form.closest('.puns-cform-wrap');
			form.addEventListener('submit', handleSubmit);
			bindFieldClear(form, wrap);
		});
	});
})();
