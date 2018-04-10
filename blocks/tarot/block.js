( function( wp ) {
	var el = wp.element.createElement,
		__ = wp.i18n.__;

	wp.blocks.registerBlockType( 'tarot/spread', {
		title: __( 'Tarot Spread', 'tarot' ),
		icon: 'admin-page',
		category: 'widgets',
		supportHTML: false,

		edit: function( props ) {
			return el(
				'p',
				{ className: props.className },
				__( 'Replace with your content!', 'tarot' )
			);
		},

		save: function() {
			return el(
				'p',
				{},
				__( 'Replace with your content!', 'tarot' )
			);
		}
	} );
} )( window.wp );
