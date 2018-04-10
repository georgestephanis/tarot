<?php

function tarot_enqueue_block_editor_assets() {
	$dir = dirname( __FILE__ );
	$block_js = 'tarot/block.js';
	$editor_css = '../tarot.css';
	wp_enqueue_script( 'tarot-block', plugins_url( $block_js, __FILE__ ), array(
		'wp-blocks',
		'wp-i18n',
		'wp-element',
		'underscore',
	), filemtime( "$dir/$block_js" ) );
	wp_localize_script( 'tarot-block', 'tarotData', array(
		'deck' => Tarot::get_deck(),
	) );
	wp_enqueue_style( 'tarot-block', plugins_url( $editor_css, __FILE__ ), array(
		'wp-blocks',
	), filemtime( "$dir/$editor_css" ) );
}
add_action( 'enqueue_block_editor_assets', 'tarot_enqueue_block_editor_assets' );
