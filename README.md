# Eutopia Theme

![Lint and Test](https://github.com/gabriel-detassigny/eutopia-theme/actions/workflows/theme.yml/badge.svg)

A classic (PHP template) WordPress theme with block editor support, built for a personal site.

Highlights:

- Theme features are split into small PHP components under `php/components/` (scripts, styles, images, SVGs, menus, block patterns, allowed blocks…), wired together in `functions.php`.
- Global styles and design tokens are defined in `theme.json`, with SCSS for everything else.
- Inline, sanitized SVG icons loaded from `assets/svg/`.
- A custom 404 page can be picked under **Settings → Reading**. The selected page is set to draft so it isn't publicly accessible or indexed.
- JS and CSS are built with [`@wordpress/scripts`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/), with hot reload in development.

## Requirements

- Node.js 24 (see `.nvmrc`)
- PHP 8.2+ and [Composer](https://getcomposer.org)
- Docker, for the local [`wp-env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) environment

## Getting started

```sh
nvm use
npm install
composer install
npm run build
npm run start
```

WordPress is then available at http://localhost:8888 (login `admin` / `password`). To use other ports, create a `.wp-env.override.json` (git-ignored):

```json
{ "port": 8895, "testsPort": 8896 }
```

## Scripts

| Command | Description |
|---|---|
| `npm run start` / `npm run stop` | Start / stop the local environment |
| `npm run dev` | Watch and rebuild assets, with hot reload |
| `npm run build` | Build production assets into `build/` and generate the `.pot` file |
| `npm run lint` | Lint CSS, JS, PHP (PHPCS + PHPStan) and `theme.json` |
| `npm run format` | Auto-fix CSS, JS and PHP formatting |
| `npm run test` | Run the PHPUnit tests inside the local environment |
| `npm run wp -- wp <command>` | Run WP-CLI commands in the local environment |
| `npm run scaffold` | Generate boilerplate for a new block |

A pre-commit hook runs `lint-staged`, and a pre-push hook runs the full lint.

## Project structure

```
assets/          Static assets (SVG icons, logos, fonts)
blocks/          Custom blocks, if any (registered automatically; created by `npm run scaffold`)
build/           Compiled assets (git-ignored)
css/             SCSS sources
js/              JS sources (`main.js` for the front end, `editor.js` for the block editor)
patterns/        Block patterns
php/             Theme classes (`components/` and `helpers/`)
template-parts/  Template partials
tests/           PHPUnit tests
tools/           Dev tooling (scaffolding, theme.json linter, git hooks)
```

## Deployment

Run `npm run build`, then upload the theme folder to `wp-content/themes/eutopia-theme`. `node_modules/`, `tests/`, `tools/` and the dev-only files aren't needed on the server. `vendor/` is: install it with `composer install --no-dev`.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
