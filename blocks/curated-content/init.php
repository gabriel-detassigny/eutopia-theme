<?php
/**
 * Eutopia_Theme\Curated_Content
 * Curated content initializer. For initializing the Curated Content Block.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme;

require_once __DIR__ . '/php/class-curated-content.php';

$inst = new Helpers\Autoload();

$inst->add( 'Eutopia_Theme\Curated_Content', __DIR__ . '/php' );

Curated_Content\Curated_Content::get_instance();
