<?php
/**
 * This file helps to generate the json object to register icon collection from svg files.
 *
 * Put all svg files in svg directory and run file with `php generate-svg-json.php | pbcopy` and paste the json in a new file and reformat.
 *
 * For example: See icons.js
 *
 * @package Eutopia_Theme
 */

/*
 * Note this is standalone script.
 */
$object = [];

$svg_files = glob( __DIR__ . '/svg/*.svg' );
foreach ( $svg_files as $file ) {
	$svg_file = basename( $file );
	if ( preg_match( '/\.svg$/', $svg_file ) ) {
		$filename = str_replace( '.svg', '', $svg_file );
		$label    = implode( ' ', array_map( 'ucfirst', explode( '-', $filename ) ) );
		$object[] = [
			'name'   => $filename,
			'label'  => $label,
			'source' => file_get_contents( $file ), // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
		];
	}
}

echo json_encode( $object ); // phpcs:ignore
exit;
