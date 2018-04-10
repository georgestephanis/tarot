( function( wp ) {
	var el = wp.element.createElement,
		__ = wp.i18n.__;

	class TarotCard extends wp.element.Component {
		render() {
			var card = this.props.card;

			return el(
				'figure',
				{},
				[
					el(
						'div',
						{
							key       : 'tarot/card/' + this.props.id,
							className : 'tarot-card ' + this.props.classes + ( !! this.props.card ? '' : ' empty' ),
						},
						[
							!! this.props.card && el(
								'img',
								{
									key       : 'tarot/card/' + this.props.id + '/image',
									className : 'card-art',
									src       : card.image_url,
									width     : 150,
									alt       : card.unicode
								}
							)
						]
					),
					el(
						'figcaption',
						{
							key : 'tarot/card/' + this.props.id + '/caption',
						},
						[
							!! this.props.card && (
								card.label +
								( -1 !== this.props.classes.indexOf( 'inverted' ) ? ' ' + __( '(Inverted)', 'tarot' ) : '' )
							),
							! this.props.card && __( 'Choose a card…', 'tarot' )
						]
					)
				]
			);
		}
	}

	wp.blocks.registerBlockType( 'tarot/spread', {
		title: __( 'Tarot Spread', 'tarot' ),
		icon: 'admin-page',
		category: 'widgets',
		supportHTML: false,

		edit: function( props ) {
			return [
				el(
					'div',
					{
						key: 'tarot-spread',
						className: props.className + ' tarot-spread three-card',
					},
					[
						el(
							TarotCard,
							{
								key : 'tarot/spread/card-1',
								id : 'ACE-WANDS',
								classes : 'inverted',
								card : {
									unicode   : '🃑',
									image     : 'images/w-01.svg',
									suit      : 'Wands',
									order     : 1,
									label     : 'Ace of Wands',
									image_url : 'https://src.wordpress-develop.dev/wp-content/plugins/tarot/images/w-01.svg'
								}
							}
						),
						el(
							TarotCard,
							{
								key : 'tarot/spread/card-2',
								id  : '',
								card : null,
							}
						)
					]
				)
			];
		},

		save: function() {
			return null;
		}
	} );
} )( window.wp );
