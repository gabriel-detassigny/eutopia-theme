/**
 * Import all stylesheets that are not already imported
 * via any of the JS files to enable hot-reload for those
 * CSS files.
 *
 * See webpack.config.js for how these are added as entrypoints
 * to Webpack build and php/components/class-styles.php for
 * how these are enqueued.
 */

/**
 * Internal dependencies
 */
import '../css/style.scss';
import '../css/lazy.scss';
