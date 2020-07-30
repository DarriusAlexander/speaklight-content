<?php

// if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
// We use this function to generate the fonts.
bfi_get_googlefonts_from_json(file_get_contents("https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyB7JMGpC3uyoLm6XfFIU3rSndRQnnfr27M"));
function bfi_get_googlefonts_from_json($json) {
	$res = json_decode( $json );
	$allFonts = array();

	foreach ($res->items as $item) {
		// $fontName = urlencode($item->family);
		$fontFamily = $item->family;
		$fontSubsets = $item->subsets;
		$fontVariants = $item->variants;

		foreach ( $fontVariants as $key => $variant ) {
			if ( $variant == 'regular' ) {
				$fontVariants[$key] = '400';
			}
		}

		$allFonts[] = array(
			'name' => $fontFamily,
			'subsets' => $fontSubsets,
			'variants' => $fontVariants
		);
	}

	// print an array so we can paste it below
	foreach ( $allFonts as $font ) {
		echo "array( 'name' => '{$font['name']}', 'subsets' => array(";
		foreach ( $font['subsets'] as $key => $subset ) {
			echo $key == 0 ? "" : ",";
			echo "'$subset'";
		}
		echo "), 'variants' => array(";
		foreach ( $font['variants'] as $key => $variant ) {
			echo $key == 0 ? "" : ",";
			echo "'$variant'";
		}
		echo ") ),\n";
	}
}
*/

// All possible google fonts
// List created on Sept 19, 2015
if ( ! function_exists( 'titan_get_googlefonts' ) ) {
	function titan_get_googlefonts() {
		$fonts = array(
			array( 'name' => 'Yellowtail', 'subsets' => array( 'latin' ), 'variants' => array( '400' ) ),
			array( 'name' => 'Yeseva One', 'subsets' => array( 'latin-ext', 'latin', 'cyrillic' ), 'variants' => array( '400' ) ),
			array( 'name' => 'Yesteryear', 'subsets' => array( 'latin' ), 'variants' => array( '400' ) ),
			array( 'name' => 'Zeyada', 'subsets' => array( 'latin' ), 'variants' => array( '400' ) ),

		);
		return $fonts;
	}
}
