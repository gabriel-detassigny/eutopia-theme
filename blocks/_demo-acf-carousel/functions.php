<?php
/**
 * ACF Block Definition
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme;

use Eutopia_Theme\Components\ACF_Block_Registry;

ACF_Block_Registry::get_instance()?->register_block(
	'gabriel-detassigny/demo-acf-carousel',
	[
		'fields' => [
			[
				'label' => __( 'Title', 'eutopia-theme' ),
				'name'  => 'title',
				'type'  => 'text',
			],
			[
				'label'         => __( 'Carousel Images', 'eutopia-theme' ),
				'name'          => 'carousel_images',
				'type'          => 'repeater',
				'layout'        => 'table',
				'button_label'  => __( 'Add image', 'eutopia-theme' ),
				'rows_per_page' => 20,
				'sub_fields'    => [
					[
						'label'           => __( 'Image', 'eutopia-theme' ),
						'name'            => 'carousel_image',
						'type'            => 'image',
						'return_format'   => 'ID',
						'library'         => 'all',
						'preview_size'    => 'medium',
						'parent_repeater' => 'carousel_images',
					],
				],
			],
			[
				'label'         => __( 'Auto Height', 'eutopia-theme' ),
				'name'          => 'auto_height',
				'type'          => 'true_false',
				'default_value' => true,
				'message'       => __( 'Resize the container height to the active image height', 'eutopia-theme' ),
				'instructions'  => __( 'Causes layout shift for the content below the carousel.', 'eutopia-theme' ),
			],
			[
				'label'         => __( 'Loop Slides', 'eutopia-theme' ),
				'name'          => 'loop_slides',
				'type'          => 'true_false',
				'default_value' => false,
				'message'       => __( 'Display the first slide again after reaching the end of carousel', 'eutopia-theme' ),
			],
		],
	]
);
