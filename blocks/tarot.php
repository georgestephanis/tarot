<?php

function register_tarot_block() {
	wp_register_style( 'tarot-block', plugins_url( '../tarot.css', __FILE__ ), array( 'wp-blocks' ), filemtime( dirname( __FILE__ ) . '/../tarot.css' ) );
	register_block_type( 'tarot/spread', array(
		'style' => 'tarot-block',
		'render_callback' => array( 'Tarot', 'print_spread' ),
	) );
}
add_action( 'init', 'register_tarot_block' );

function tarot_enqueue_block_editor_assets() {
	$dir = dirname( __FILE__ );
	$block_js = 'tarot/block.js';
	wp_enqueue_script( 'tarot-block', plugins_url( $block_js, __FILE__ ), array(
		'wp-blocks',
		'wp-components',
		'wp-i18n',
		'wp-element',
		'underscore',
	), filemtime( "$dir/$block_js" ) );
	wp_localize_script( 'tarot-block', 'tarotData', array(
		'deck' => Tarot::get_deck(),
	) );
	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'tarot-block', 'tarot' );
	}
	wp_enqueue_style( 'tarot-block' );
}
add_action( 'enqueue_block_editor_assets', 'tarot_enqueue_block_editor_assets' );
