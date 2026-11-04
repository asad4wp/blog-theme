# Blog Theme

A clean, modern WordPress blog theme by [104Digital](https://104digital.com). Built for fast editorial sites with Gutenberg-editable pages, design variants, SEO helpers, forms, PWA support, and a mobile-first header.

| | |
| --- | --- |
| **Theme name** | Blog Theme |
| **Text domain** | `puns` |
| **Requires** | WordPress 6.0+, PHP 8.0+ |
| **License** | GPL-2.0-or-later |
| **Primary brand color** | `#3858E9` |

---

## Table of contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Quick start](#quick-start)
5. [Theme structure](#theme-structure)
6. [Page templates](#page-templates)
7. [Homepage (editable)](#homepage-editable)
8. [About & Contact (block patterns)](#about--contact-block-patterns)
9. [Menus & mobile navigation](#menus--mobile-navigation)
10. [Designs (Appearance → Designs)](#designs-appearance--designs)
11. [Customizer options](#customizer-options)
12. [Shortcodes](#shortcodes)
13. [Forms](#forms)
14. [Cookie banner & consent](#cookie-banner--consent)
15. [SEO & Rank Math](#seo--rank-math)
16. [PWA](#pwa)
17. [AJAX features](#ajax-features)
18. [Author images](#author-images)
19. [Assets & branding](#assets--branding)
20. [Development notes](#development-notes)
21. [Changelog / version](#changelog--version)

---

## Features

- **Editorial homepage** with featured hero (sticky or latest post) and latest posts grid + Load More
- **Gutenberg-friendly** Home, About Us, and Contact Us pages with block patterns
- **20 design variants** each for global colors, home layout, single post, and post cards
- **Responsive header** with logo, primary nav, search modal, subscribe modal, and mobile hamburger drawer
- **Cookie consent banner** (theme-native) plus optional Google CMP / AdSense consent mode
- **Built-in contact & write forms** with submissions CPT, email notifications, reCAPTCHA
- **Gravity Forms** styling / integration helpers
- **SEO meta & schema** helpers, Rank Math breadcrumbs integration
- **PWA** (manifest, service worker, installable)
- **AJAX search** modal and **AJAX Load More** on home / archives / search
- **Custom author images** (profile override)
- **FAQ tabs**, copy-to-clipboard resources, recommended posts
- Brand assets: logo mark, favicon, PWA icon, theme screenshot

---

## Requirements

- WordPress **6.0** or newer
- PHP **8.0** or newer
- A modern browser (Chrome, Firefox, Safari, Edge)
- Optional: [Rank Math](https://rankmath.com/), [Gravity Forms](https://www.gravityforms.com/)

---

## Installation

1. Copy this theme folder into:

   ```text
   wp-content/themes/blog-theme
   ```

2. In WordPress admin go to **Appearance → Themes**.
3. Activate **Blog Theme**.
4. On first load the theme may seed:
   - A **Home** page (static front page)
   - A **Blog** posts page (if missing)
   - Default block content for About / Contact when those pages exist or are created

5. Clear any page / object cache and hard-refresh the front end (`Cmd+Shift+R` / `Ctrl+Shift+R`).

---

## Quick start

1. **Settings → Reading**
   - Your homepage displays: **A static page**
   - Homepage: **Home** (template: **Home**)
   - Posts page: **Blog** (optional but recommended)

2. **Appearance → Customize**
   - Upload **Header Logo** / **Footer Logo** (or keep the built-in SVG brand mark + site title)
   - Set **Homepage → Posts Title** (default: “Latest Posts”)
   - Configure **Cookie Banner**, **PWA**, **Consent & AdSense**, **SEO** as needed

3. **Appearance → Menus**
   - Create a menu and assign it to **Primary Menu** (desktop + mobile)
   - Optionally assign **Footer Menu**

4. **Appearance → Designs**
   - Pick global colors, home layout, single layout, and card style
   - Each option shows a visual preview thumbnail

5. Edit **Pages → Home** to change intro text between the hero and the post grid (Gutenberg).

6. Publish posts. Mark a post as **Sticky** to feature it in the home hero (otherwise the latest post is used).

---

## Theme structure

```text
blog-theme/
├── style.css                 # Theme header (WP reads this)
├── screenshot.png            # Theme thumbnail in admin
├── functions.php             # Bootstraps includes + theme supports
├── header.php / footer.php
├── front-page.php            # Uses Home template when static front page is set
├── index.php                 # Blog index / posts page fallback
├── single.php / page.php / archive.php / search.php / 404.php
├── page-templates/
│   ├── home.php              # Editable homepage shell
│   ├── about.php
│   ├── contact.php
│   └── write-with-us.php
├── template-parts/
│   ├── home-featured.php     # Featured hero markup
│   ├── home-posts.php        # Latest posts grid
│   ├── content-card.php
│   ├── content-single.php
│   ├── breadcrumbs.php
│   ├── search-modal.php
│   ├── subscribe-modal.php
│   └── static/               # Legacy static partials (About / Contact / Write)
├── inc/
│   ├── customizer.php        # Logos, footer, homepage title, favicon
│   ├── design-options.php    # Appearance → Designs (20×4 variants + previews)
│   ├── home-blocks.php       # Home shortcodes + seeding
│   ├── block-patterns.php    # About / Contact / Home patterns
│   ├── cookies.php           # Cookie banner
│   ├── consent.php           # Google CMP / Consent Mode
│   ├── custom-forms.php      # Contact / Write forms + shortcode
│   ├── gravity-forms.php
│   ├── load-more.php
│   ├── ajax-search.php
│   ├── seo.php / rank-math.php / pwa.php
│   ├── author-image.php
│   ├── nav-walkers.php       # Primary / mobile / footer walkers + fallback links
│   └── …
└── assets/
    ├── css/style.css         # Main stylesheet (design tokens)
    ├── css/designs.css       # Design variant overrides
    ├── js/                   # main, cookies, load-more, search, forms, PWA, …
    └── images/               # logo, favicon, pwa-icon
```

Internal PHP package / textdomain remains `puns` for historical compatibility (class prefixes, CSS classes like `.puns-header`, options keys, etc.).

---

## Page templates

| Template | File | Purpose |
| --- | --- | --- |
| **Home** | `page-templates/home.php` | Static front page: featured hero + editable middle + latest posts |
| **About Us** | `page-templates/about.php` | About layout wrapper; content from blocks |
| **Contact Us** | `page-templates/contact.php` | Contact layout wrapper; form + info blocks |
| **Write with Us** | `page-templates/write-with-us.php` | Contributor / pitch page |

Assign a template under **Page → Template** in the editor.

Body classes help styling, for example:

- `puns-page-home`
- `puns-page-article`
- `puns-page-archive`
- `puns-page-about` / `puns-page-contact` / `puns-page-write`
- `puns-design-global-XX`, `puns-design-home-XX`, `puns-design-single-XX`, `puns-design-tile-XX`

---

## Homepage (editable)

When a static **Home** page uses the **Home** template:

1. **Featured hero** — rendered by the theme from the sticky post (or latest post). Fully clickable.
2. **Editable middle** — any Gutenberg blocks you add on the Home page (intros, banners, CTAs).
3. **Latest posts grid** — theme query excluding the featured post, with **Load More** when more pages exist.

### Editor tips

- Prefer editing the **intro paragraph / groups** between hero and grid.
- Avoid pasting raw `[puns_featured_hero]` / `[puns_latest_posts]` as plain paragraphs with curly quotes; WordPress “smart quotes” can break shortcodes.
- Use **Patterns → Blog Theme Pages → Home page** if you need to re-insert the default block scaffold.
- Featured eyebrow / Read More defaults: “Editor’s Choice” / “Read More” (also available via shortcode attributes when used correctly).

### Shortcodes (advanced)

```text
[puns_featured_hero eyebrow="Editor's Choice" button="Read More"]
[puns_latest_posts title="Latest Posts" show_title="1"]
```

On the Home template, hero and posts are also rendered in PHP so the front end stays reliable even if shortcode markup in the editor is corrupted.

### Featured post rules

- If any **sticky** posts exist, the first sticky is featured.
- Otherwise the newest published post is featured.
- That post is excluded from the homepage grid.

---

## About & Contact (block patterns)

Patterns are registered under category **Blog Theme Pages**:

- **About Us page** — hero, mission panel, value cards, CTA
- **Contact Us page** — hero, `[puns_form form="contact"]`, info cards
- **Home page** — shortcode scaffold + editable note

On theme switch / first admin init, About and Contact pages can be seeded with default block content when empty.

Edit freely in the block editor: headings, text, buttons, columns, and form shortcode attributes.

---

## Menus & mobile navigation

### Locations

| Location | Usage |
| --- | --- |
| **Primary Menu** | Desktop header nav + mobile drawer |
| **Footer Menu** | Footer link row |

### Mobile hamburger

- Always visible below the `768px` breakpoint.
- Opens a right-hand drawer with backdrop, close button, and Escape support.
- If **no Primary Menu** is assigned, fallback links are used: **Home**, plus discovered pages such as About, Blog, Contact, Write with Us.

### Desktop

- Primary links show in the centered header nav.
- Search and Subscribe stay on the right.
- On very small screens, Subscribe may hide to free space for the hamburger.

---

## Designs (Appearance → Designs)

Admin page: **Appearance → Designs**.

Four areas, **20 options each**, with **visual previews** next to every choice:

| Area | What it changes |
| --- | --- |
| **Website (global colors)** | Site-wide palette (primary, accent, surfaces, tags) |
| **Home page** | Homepage layout (hero / grid arrangements) |
| **Single post** | Article layout (width, meta, sidebar-style variants) |
| **Post tile / card** | Card / tile appearance in grids |

Saved in option `puns_design_options`. CSS lives in `assets/css/designs.css` and activates via body classes.

Default brand (global design `01` / base tokens) uses primary **`#3858E9`**.

---

## Customizer options

**Appearance → Customize**

### Header

- WordPress **Custom Logo** (Header Logo)
- **Logo Max Height (px)**

If no custom logo is set, the theme SVG brand mark + site title are shown.

### Footer

- Footer logo image (falls back to header logo)
- Footer logo text fallback
- Footer logo max height
- Copyright text — supports `{year}` and `{site_name}`

### Homepage

- **Posts Title** — heading above the homepage post grid (empty → “Latest Posts”)

### Cookie Banner

- Enable / disable
- Custom message
- Privacy Policy URL (falls back to WP Privacy Policy page or `/privacy-policy/`)

Hidden automatically when **Consent & AdSense** CMP is enabled (to avoid double banners).

### Consent & AdSense

- Enable consent management
- Publisher ID (`ca-pub-…`)
- Google CMP vs alternative CMP script
- Optional AdSense script load
- Consent Mode v2 defaults

### PWA

- Enable Progressive Web App
- App name / short name
- Icons fall back to theme `pwa-icon.svg` or Site Icon

### SEO / related

- Default social / Open Graph image and related SEO Customizer fields (see `inc/seo.php`)

### Gravity Forms (if present)

- Theme-specific GF Customizer section for styling / embed helpers

---

## Shortcodes

| Shortcode | Description |
| --- | --- |
| `[puns_featured_hero]` | Featured home hero. Attrs: `eyebrow`, `button` |
| `[puns_latest_posts]` | Latest posts grid. Attrs: `title`, `show_title` (`1`/`0`) |
| `[puns_form]` | Themed form. Attrs: `form` (`contact`\|`write`), `heading`, `intro` |

Examples:

```text
[puns_form form="contact" heading="Send a message" intro="We reply within 1–2 business days."]
[puns_form form="write" heading="Send pitch" intro="Tell us about your idea."]
```

---

## Forms

Implemented in `inc/custom-forms.php`.

- Forms: **contact**, **write**
- AJAX submit (`admin-ajax.php` action `puns_submit_form`)
- Submissions stored as custom post type
- Admin + autoresponder emails with merge tags
- Optional Google reCAPTCHA v2 / v3
- Honeypot + rate limiting
- Settings under the theme forms settings screen in admin

Gravity Forms: see `inc/gravity-forms.php` for theme wrappers and Customizer options.

---

## Cookie banner & consent

### Theme cookie banner (`inc/cookies.php`)

- Fixed bottom bar: message, Privacy link, **Decline** / **Accept**
- Preference stored in `localStorage` key `puns_cookie_consent`
- Scripts: `assets/js/cookies.js`

### Google consent / CMP (`inc/consent.php`)

- Consent Mode defaults (`ad_storage` denied until CMP resolves)
- Funding Choices / alternative CMP script output
- Optional AdSense loader
- Footer “Cookie settings” helper for CMP reopen (when CMP enabled)

Use **either** the simple theme banner **or** CMP — not both at once (theme banner auto-disables when CMP is on).

---

## SEO & Rank Math

### Built-in SEO (`inc/seo.php`)

- Meta description / social tags helpers
- Default share image from Customizer
- Article / page structured data hooks

### Rank Math (`inc/rank-math.php`)

- Uses Rank Math breadcrumbs when the plugin is active and breadcrumbs are enabled
- Otherwise falls back to theme breadcrumbs (`template-parts/breadcrumbs.php`)

---

## PWA

`inc/pwa.php` + `assets/js/pwa.js` + generated service worker from `assets/js/service-worker.tpl.js`.

When enabled in Customizer:

- Web app manifest
- Service worker registration
- Theme / background colors aligned with brand (`#3858E9` / `#F8FAFF`)
- Apple web app meta tags

---

## AJAX features

### Load More (`inc/load-more.php`, `assets/js/load-more.js`)

- Home, archives, and search (when more than one page of results)
- Button: `.puns-load-more`
- Home context excludes the featured post ID

### Search modal (`inc/ajax-search.php`, `assets/js/ajax-search.js`)

- Opened from header search icon (`.puns-search-open`)
- Live results into the search modal template

### Subscribe modal

- Header **Subscribe** button opens `template-parts/subscribe-modal.php`
- Front-end open/close handled in `assets/js/main.js`

---

## Author images

`inc/author-image.php` adds an **Author Image** field on user profiles. Used for:

- Homepage featured hero avatar
- Single post meta avatar
- Related author UI

Falls back to Gravatar / default when unset.

---

## Assets & branding

| File | Role |
| --- | --- |
| `assets/images/logo-puns-mark.svg` | Header / footer brand mark |
| `assets/images/logo-puns.svg` | Full wordmark SVG |
| `assets/images/favicon.svg` | Default favicon (if no Site Icon) |
| `assets/images/pwa-icon.svg` | PWA / apple touch fallback |
| `screenshot.png` | Themes screen thumbnail |
| `assets/css/style.css` | Core design tokens & components |
| `assets/css/designs.css` | Design variant overrides |

### CSS tokens (base)

Primary brand lives in `:root` / `.puns-page-home`, for example:

- `--puns-teal: #3858E9`
- `--puns-teal-hover: #2C46C7`
- `--puns-accent: #60A5FA`
- Surfaces / borders use cool neutrals (`#F8FAFF`, `#E2E8F0`, …)

Component classes are prefixed with `puns-` (e.g. `.puns-header`, `.puns-hero`, `.puns-card`, `.puns-cookie-banner`).

---

## Development notes

- **Version constant:** `PUNS_VERSION` in `functions.php` (bump when shipping asset changes so caches bust).
- **Textdomain:** `puns` — wrap user-facing strings with `__()` / `esc_html_e()` etc.
- **Prefix:** Functions, options, and classes use the `puns_` / `Puns_` prefix.
- **Local / HTTP sites:** Clipboard and some browser APIs behave differently on non-HTTPS `.local` hosts; theme includes fallbacks where practical.
- **Do not commit secrets:** Keep API keys (reCAPTCHA, AdSense) in Customizer / options, not in the repo.
- **Editor smart quotes:** Prefer shortcode blocks or attribute-free shortcodes; the theme normalizes curly quotes where possible.

### Recommended workflow

1. Activate theme on a staging site (Local WP, etc.).
2. Set Reading → static Home + Blog.
3. Assign Primary menu.
4. Choose Designs + Customizer logos/colors.
5. Edit Home / About / Contact in Gutenberg.
6. Test mobile menu, search, subscribe, cookies, Load More, and forms.

---

## Changelog / version

- Theme header `style.css` version: **1.0.0** (WordPress theme metadata)
- Asset / include bootstrap version: **`PUNS_VERSION` `1.4.4`** in `functions.php`

Update both when releasing a public build if you rely on them for support or caching.

---

## Support

Theme by **104Digital** — [https://104digital.com](https://104digital.com)

For site-specific customization (child theme, new patterns, or design variants), keep changes in a child theme when possible so updates to this theme stay merge-friendly.

---

## License

GPL-2.0-or-later. See the license header in `style.css` and the GPL terms that ship with WordPress.
