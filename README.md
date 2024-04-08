# Eutopia Theme

**Status:**
![Lint and Test](
https://github.com/gabriel-detassigny/eutopia-theme/actions/workflows/theme.yml/badge.svg)


## Project Structure

The project uses a monolithic approach where the site functionality, the templates and the custom blocks along with the presentations layer are all stored the theme directory. This makes the
development workflow easier and allows us to use the same tools and processes for the entire project.

Here is the structure of the theme directory:

* `.github/` contains GitHub Actions configuration files.
* `.vscode/` contains Visual Studio Code configuration files.
* `assets` contains static assets like fonts and media.
* `blocks/` contains custom blocks. Note that blocks are automatically detected by the Webpack build process and
  enqueued by WordPress. See the [README](web/wp-content/themes/baseball-america/blocks/README.md) file for more
  details.
* `build/` contains the build output of the Webpack build process.
* `css/` contains the SCSS source files.
* `docs/` contains the technical documentation.
* `js/` contains the JavaScript source files.
* `patterns/` contains the custom block patterns. 
* `php/` contains the PHP source files.
  * `php/components/` contains the PHP classes that are responsible for the site functionality and business logic.
  * `php/helpers/` contains the PHP helpers.
* `template-parts/` contains the PHP templates used to render the site.
* `tests/` contains the PHPUnit tests for the most important parts of the site.
* `tools/` contains scripts that are used in the theme development workflow.
* `vendor` contains composer dependencies.

## Local Environment

* :wrench: [Follow the Setup guide](docs/local_setup.md) for getting a local development environment.

## Environment and Branch Mapping

| Environment | Branch    | URL                                   |
|-------------|-----------|---------------------------------------|
| Local       |           | https://local.example.net             |
| Develop     | `develop` | https://develop.example.net           |
| Test        | `test`    | https://test.example.net              |
| Production  | `main`    | https://example.net                   |

* :rocket: [Deployments process](docs/deployments.md)

[Engineering Guide for Git Workflows - Branching :arrow_upper_right:](#)

## Development

Use the :clipboard: [Developer Checklist](docs/dev_checklist.md) to get going quickly.

Development guidelines:

* [Working with PHP](docs/working_with_php.md)
* [Working with CSS](docs/working_with_css.md)
* [Working with JS](docs/working_with_js.md)
* [Working with PHP](docs/working_with_php.md)
* [Working with Media - SVGs & Images](docs/working_with_media.md)
* [Working with Blocks](docs/working_with_blocks.md)
* [Working with Block Patterns](docs/working_with_block_patterns.md)
* [Working with Theme Options](docs/working_with_theme_options.md)
* [Working with Git](docs/working_with_git.md)

### Working with Curated Content Block

This block allows for the curation of posts to appear in various locations on the page, and it contains several customizable options, providing great versatility in its use.

* [Curated Content Block Documentation](blocks/curated-content/README.md)

### Scripts :toolbox:

Included scripts (see the `scripts` section `package.json` for the full list):

* `npm run start`, `npm run stop` and `npm run env` to work with the included development environment. See [`@wordpress/env` :arrow_upper_right:](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) for instructions.
* `npm run dev` to watch and rebuild JS and CSS.
* `npm run lint` to check CSS, JS and PHP formatting errors.
* `npm run format` to fix CSS, JS and PHP formatting errors. Automatically fixable CSS & JS formatting errors are handled transparently in the pre-commit hook.
* `npm run build` to build all assets.
* `npm run test` to run the PHP tests inside the development environment along with JS unit tests.
* `npm run wp -- wp user create dev local@example.net --role=administrator --user_pass=dev` to create a new local administrator account.
* `npm run push:develop` to push changes from the current branch into develop.
* `npm run pull:main` to pull changes from main branch into the current branch.

Notes:

* The [`PHPCompatibility/PHPCompatibilityWP` package :arrow_upper_right:](https://github.com/PHPCompatibility/PHPCompatibilityWP) [is locked :arrow_upper_right:](https://github.com/PHPCompatibility/PHPCompatibilityWP/blob/262f9d81273932315d15d704f69b9d678b939cb3/composer.json#L26) to an old version of [PHPCompatibility/PHPCompatibility :arrow_upper_right:](https://github.com/PHPCompatibility/PHPCompatibility) which [doesn't support PHP 8 compatability checks :arrow_upper_right:](https://github.com/PHPCompatibility/PHPCompatibilityWP/issues/40) so we manually require `PHPCompatibility/PHPCompatibility` at `dev-develop as 9.99.99`.

* If you try to build scripts from Windows environment, you will encounter an error due to executing linux based scripts from Windows. The solution is to build scripts via Linux subsystem.

### Hot Reload

The "Hot Reload" feature is now seamlessly integrated when executing the `npm run dev` command. This dynamic reloading capability is applicable to blocks within the editor, enhancing real-time editing experiences. However, please note that the refreshing of global styles and scripts is exclusively operational on the frontend.

## 404 Page

A custom 404 page can be selected in the WordPress settings under "Reading". If no page is selected, a default template will be used from the `404.php` file. When a page is selected, its status will be set to "draft" to prevent it from being publicly accessible and indexed. It is recommended that the 404 page remains a draft.
