# TH Variation Swatches - Plugin Structure

```
th-variation-swatches/
├── th-variation-swatches.php          # Main plugin file (entry point)
├── readme.txt                         # WordPress plugin readme
├── structure.md                       # This file
│
├── assets/
│   ├── css/
│   │   ├── admin.css                  # Admin panel styles
│   │   └── thvs-front-style.css       # Frontend swatch styles
│   └── js/
│       ├── thvs-front.js              # Frontend swatch behavior
│       ├── thvs-setting.js            # Admin settings JS
│       └── wp-color-picker-alpha.js   # Color picker with alpha support
│
├── images/
│   ├── icon.png
│   ├── placeholder.png
│   ├── th-logo.png
│   ├── th-shop-mania-ad.png
│   ├── th-variation-pro.png
│   ├── pro-coming-soon.png
│   ├── pro-coming-soonb.png
│   ├── pro1.png – pro6.png            # Pro feature preview images
│
├── inc/
│   ├── thvs.php                       # Core plugin class
│   ├── thvs-function.php              # Helper/utility functions
│   ├── thvs-hook.php                  # WordPress action/filter hooks
│   ├── thvs-meta.php                  # Product variation meta handling
│   ├── thvs-settings.php              # Plugin settings page
│   ├── thvs-front-custom-style.php    # Dynamic inline styles for frontend
│   │
│   └── themehunk-menu/
│       ├── admin-menu.php             # Admin menu registration
│       ├── plugins-list.php           # ThemeHunk plugins listing page
│       └── th-option/
│           ├── th-option.php          # Options framework loader
│           ├── header.php             # Admin page header template
│           ├── sidebar.php            # Admin page sidebar template
│           ├── tab-html.php           # Tab content HTML template
│           ├── assets/
│           │   ├── css/
│           │   │   └── started.css    # Getting started page styles
│           │   ├── js/
│           │   │   └── th-options.js  # Options panel JS
│           │   └── images/
│           │       ├── ico.png
│           │       ├── icon.png
│           │       └── th-shop-mania.png
```

## File Responsibilities

| File | Purpose |
|------|---------|
| `th-variation-swatches.php` | Plugin bootstrap: defines constants, loads includes |
| `inc/thvs.php` | Main class: enqueue scripts/styles, init hooks |
| `inc/thvs-hook.php` | WooCommerce hooks for swatch rendering |
| `inc/thvs-meta.php` | Save/retrieve swatch type meta per attribute |
| `inc/thvs-settings.php` | Global plugin settings (WP admin page) |
| `inc/thvs-function.php` | Shared utility functions |
| `inc/thvs-front-custom-style.php` | Outputs `<style>` block based on saved settings |
