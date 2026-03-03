# Fooz — TwentyTwentyFive Child Theme

A structured WordPress child theme built on top of TwentyTwentyFive. It uses a service-class PHP architecture (PSR-4 autoloading, no Composer), a three-pipeline asset build system (Sass, Vite, `@wordpress/scripts`), and metadata-driven Gutenberg block registration.

---

## Requirements

| Tool | Minimum version |
|---|---|
| PHP | 8.1+ |
| WordPress | 6.5+ |
| Node.js | 20+ |
| npm | 10+ |
| Parent theme | TwentyTwentyFive (active) |

---

## Getting Started

### 1. Install the parent theme

Make sure **TwentyTwentyFive** is installed and present in `wp-content/themes/twentytwentyfive`. It does not need to be the active theme — it only needs to exist as the template.

### 2. Install Node dependencies

```bash
cd wp-content/themes/twentytwentyfive-child
npm install
```

### 3. Build assets

For a one-off production build:

```bash
npm run build
```

For active development with watchers:

```bash
npm run watch
```

### 4. Activate the theme

Activate **Twenty Child** in **WordPress Admin → Appearance → Themes**.

---

## Build System

The theme uses three separate but coordinated build pipelines, all orchestrated from `package.json`.

### Available scripts

| Script | Description |
|---|---|
| `npm run build` | Full production build (CSS + JS + Blocks) |
| `npm run build:css` | Sass → PostCSS (autoprefixer) → `build/assets/css/styles.css` |
| `npm run build:js` | Vite IIFE bundle → `build/assets/js/scripts.js` |
| `npm run build:blocks` | `@wordpress/scripts` webpack → `build/blocks/` |
| `npm run watch` | Concurrent watchers for all three pipelines |
| `npm run watch:css` | Sass + PostCSS watch |
| `npm run watch:js` | Vite watch |
| `npm run watch:blocks` | `wp-scripts start` |
| `npm run lint` | ESLint on `src/assets/js/**/*.js` |
| `npm run lint:fix` | ESLint with auto-fix |
| `npm run format` | Prettier on `src/assets/js/**/*.js` |
| `npm run format:check` | Prettier check (no writes) |

### Pipeline 1 — Theme CSS (Sass → PostCSS)

- **Source**: `src/assets/scss/styles.scss`
- **Output**: `build/assets/css/styles.css`
- Sass compiles all `@use`/`@forward` partials (e.g. `components/_latest-books.scss`), then PostCSS runs `autoprefixer` for browser compatibility.
- Enqueued on the frontend via `wp_enqueue_scripts`.

### Pipeline 2 — Theme JS (Vite)

- **Source**: `src/assets/js/scripts.js`
- **Output**: `build/assets/js/scripts.js`
- Vite outputs an **IIFE bundle** (no ES modules, safe in all browsers).
- `inlineDynamicImports: true` — all modules are bundled into a single file.
- Enqueued in the **footer** via `wp_enqueue_scripts`.
- `wp_localize_script` exposes `window.foozThemeVars = { homeUrl, postId }` to the bundle.

### Pipeline 3 — Gutenberg Blocks (`@wordpress/scripts`)

- **Source**: `src/blocks/<block-name>/`
- **Output**: `build/blocks/<block-name>/`
- Uses the standard `@wordpress/scripts` webpack config.
- Each block's `block.json` is the single source of truth for metadata, scripts, and styles.
- Blocks are registered server-side using `register_block_type($path_to_build_folder)` — WordPress reads `block.json` automatically.

---

## Directory Structure

```
twentytwentyfive-child/
├── style.css                   # Theme header only — no actual styles here
├── functions.php               # PSR-4 autoloader + bootstraps Main
├── package.json
├── vite.config.js              # Vite config (IIFE, theme JS only)
├── postcss.config.js           # PostCSS (autoprefixer)
├── eslint.config.js
│
├── src/
│   ├── assets/
│   │   ├── scss/
│   │   │   ├── styles.scss             # SCSS entry point
│   │   │   └── components/
│   │   │       └── _latest-books.scss
│   │   └── js/
│   │       ├── scripts.js              # JS entry point
│   │       └── modules/
│   │           └── latest-books.js     # REST API book widget
│   └── blocks/
│       ├── faq-accordion/              # Parent block
│       │   ├── block.json
│       │   ├── index.js                # registerBlockType
│       │   ├── edit.js                 # Editor component (JSX)
│       │   ├── save.js                 # Save component (JSX)
│       │   ├── view.js                 # Frontend JS (accordion toggle)
│       │   └── style.scss              # Block styles
│       └── faq-item/                   # Child block (locked inside faq-accordion)
│           ├── block.json
│           ├── index.js
│           ├── edit.js
│           └── save.js
│
├── build/                      # Compiled output — do not edit manually
│   ├── assets/
│   │   ├── css/styles.css
│   │   └── js/scripts.js
│   └── blocks/
│       ├── faq-accordion/
│       └── faq-item/
│
└── inc/                        # PHP service classes (PSR-4: FoozTheme\)
    ├── Main/Main.php
    ├── Constants/Constants.php
    ├── Enqueue/Enqueue.php
    ├── Blocks/Blocks.php
    ├── CustomPostTypes/
    │   ├── CustomPostTypes.php
    │   └── BooksPostType.php
    ├── CustomTaxonomies/
    │   ├── CustomTaxonomies.php
    │   └── BookGenreTaxonomy.php
    ├── Rest/
    │   ├── RestRoutes.php
    │   └── Routes/LatestBooksRoute.php
    └── Query/
        └── QueryModifier.php
```

---

## PHP Architecture

The theme uses a **service-class architecture** with a hand-rolled PSR-4 autoloader — no Composer required.

### Autoloading

`functions.php` registers an `spl_autoload_register` callback that maps the `FoozTheme\` namespace to the `inc/` directory:

```
FoozTheme\Enqueue\Enqueue  →  inc/Enqueue/Enqueue.php
FoozTheme\Blocks\Blocks    →  inc/Blocks/Blocks.php
```

### Boot sequence

```
functions.php
  └── new Main()->init()
        ├── new Enqueue()->init()         # wp_enqueue_scripts
        ├── new Blocks()->init()          # init (register_block_type)
        ├── new CustomPostTypes()->init() # init (register_post_type)
        ├── new CustomTaxonomies()->init()# init (register_taxonomy)
        ├── new RestRoutes()->init()      # rest_api_init
        └── new QueryModifier()->init()   # pre_get_posts
```

Every service class follows the same contract: a constructor and a public `init()` method that attaches WordPress hooks.

### Adding a new service class

1. Create `inc/YourFeature/YourFeature.php` with namespace `FoozTheme\YourFeature`.
2. Implement `__construct()` and `public function init(): void`.
3. Instantiate it in `inc/Main/Main.php` and call `->init()`.

No additional autoloader configuration is needed.

---

## Gutenberg Blocks

### Existing blocks

| Block name | Title | Description |
|---|---|---|
| `fooz-theme/faq-accordion` | FAQ Accordion | Wrapper block with an editable heading and an ordered list of FAQ items |
| `fooz-theme/faq-item` | FAQ Item | Question/answer pair — only usable inside `faq-accordion` |

### How block registration works

`inc/Blocks/Blocks.php` hooks into `init` and calls:

```php
register_block_type( $blocks_dir . '/faq-accordion' );
register_block_type( $blocks_dir . '/faq-item' );
```

WordPress reads each block's `build/blocks/<name>/block.json` automatically and registers all associated scripts and styles declared there. You do not need to call `wp_register_script` or `wp_register_style` for blocks.

### Adding a new block

1. Create a source folder: `src/blocks/my-block/`
2. Add the required files:
   - `block.json` — block metadata (name, title, category, attributes, script/style handles)
   - `index.js` — calls `registerBlockType`
   - `edit.js` — React/JSX editor component
   - `save.js` — React/JSX save component (or `null` for dynamic/server-rendered blocks)
   - `style.scss` — (optional) frontend + editor styles
   - `view.js` — (optional) frontend-only vanilla JS
3. Run `npm run build:blocks` (or `npm run watch:blocks` during development).
4. Register the block in `inc/Blocks/Blocks.php`:
   ```php
   register_block_type( $blocks_dir . '/my-block' );
   ```

### Block source file conventions

| File | Purpose |
|---|---|
| `block.json` | Single source of truth: name, version, attributes, script/style references |
| `index.js` | `registerBlockType` entry point — imports `edit` and `save` |
| `edit.js` | Editor UI — uses `@wordpress/block-editor` hooks and components |
| `save.js` | Static HTML output — must be pure (no hooks, no side effects) |
| `view.js` | Loaded on the **frontend only** — vanilla JS for interactivity |
| `style.scss` | Styles applied on both **frontend and editor** |
| `editor.scss` | (optional) Editor-only styles |

### Frontend interactivity

Blocks use the `viewScript` field in `block.json` to ship lightweight vanilla JS to the frontend. The `faq-accordion` block demonstrates this pattern: `view.js` attaches click handlers on `DOMContentLoaded` to toggle `aria-expanded` and the `hidden` attribute on answer panels.

---

## Custom CSS

### Theme styles

- **Source**: `src/assets/scss/styles.scss`
- **Output**: `build/assets/css/styles.css`

Add new partials inside `src/assets/scss/` and `@use` or `@forward` them from `styles.scss`.

```
src/assets/scss/
├── styles.scss           ← entry point
└── components/
    └── _latest-books.scss
```

### Block styles

Each block has its own `style.scss` in its source folder. These are compiled by `@wordpress/scripts` into `build/blocks/<name>/style-index.css` and loaded automatically by WordPress on pages that render the block.

Do **not** put block styles in the theme's main `styles.scss` — keep them co-located with the block source.

---

## Custom JavaScript

### Theme JS

- **Source**: `src/assets/js/scripts.js` → imports modules from `src/assets/js/modules/`
- **Output**: `build/assets/js/scripts.js` (IIFE bundle, loaded in footer)
- `window.foozThemeVars` is available in this bundle (injected via `wp_localize_script`):
  - `foozThemeVars.homeUrl` — site home URL
  - `foozThemeVars.postId` — current post ID

Add new modules inside `src/assets/js/modules/` and import them in `scripts.js`.

### Block JS

Use `view.js` inside a block's source folder for frontend JavaScript. It is compiled separately by `@wordpress/scripts` and only loaded when the block is present on the page.

---

## REST API

The theme registers custom REST endpoints under the `fooz-theme/v1` namespace.

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/wp-json/fooz-theme/v1/latest-books` | Returns up to 20 latest books ordered by date. Accepts `?exclude=<post_id>`. |

Each item in the response includes: `title`, `date`, `genre`, `excerpt`, `url`.

To add a new endpoint, create a route class in `inc/Rest/Routes/` and register it in `inc/Rest/RestRoutes.php`.

---

## Custom Post Types & Taxonomies

| Type | Slug | Description |
|---|---|---|
| Post Type | `library` | Books CPT — public, has archive, supports REST |
| Taxonomy | `book-genre` | Hierarchical taxonomy on `library` — supports REST |

The `QueryModifier` class limits `book-genre` taxonomy archive pages to **5 posts per page** via `pre_get_posts`.

---

## Asset Versioning

All theme assets use `filemtime()` for cache-busting — no hardcoded version strings. Whenever a file changes and a new build is produced, WordPress will serve the updated asset immediately.

---

## Linting & Formatting

```bash
# Lint theme JS
npm run lint

# Auto-fix lint errors
npm run lint:fix

# Format with Prettier
npm run format

# Check formatting without writing
npm run format:check
```

ESLint is configured with two separate rule sets inside `eslint.config.js`:
- `src/assets/js/**/*.js` — ES2021 browser/Node globals, single quotes, 2-space indent
- `src/blocks/**/*.js` — Same but with `eslint-plugin-react` for JSX, tab indent

---

## Key Design Decisions

- **No Composer** — PSR-4 autoloading is handled by a single `spl_autoload_register` in `functions.php`. Sufficient for a theme; avoids requiring Composer on the server.
- **No `style.css` styles** — `style.css` contains only the WordPress theme header comment. All styles live in `src/`.
- **Three build pipelines** — Theme CSS, theme JS, and block JS are intentionally kept separate. This lets `@wordpress/scripts` handle block-specific tooling (webpack, asset manifests, `block.json` copying) while Vite handles the lightweight theme JS bundle.
- **Metadata-driven blocks** — `register_block_type($dir)` reads `block.json` automatically. Block scripts and styles never need to be manually registered with `wp_register_*`.
- **`filemtime()` versioning** — Cache-busting is automatic; no manual version bumps needed.
