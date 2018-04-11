( function( wp, deck, _ ) {
	var el = wp.element.createElement,
		__ = wp.i18n.__;

	function pickCards( qty ) {
		var thisDeck = _.shuffle( _.toArray( deck ) ),
			selection = _.first( thisDeck, qty );

		_.each( selection, function( c, index ) {
			selection[ index ].inverted = Math.random() > 0.5;
		});

		return selection;
	}

	class TarotCard extends wp.element.Component {
		render() {
			var id = this.props.id ? this.props.id : null,
				card = deck[ id ] ? deck[ id ] : null,
				classes = this.props.classes || '';

			return el(
				'figure',
				{
					key : 'tarot/card/' + id
				},
				[
					el(
						'div',
						{
							key       : 'tarot/card/' + id + '/div',
							className : 'tarot-card ' + classes + ( !! id ? '' : ' empty' ),
						},
						[
							!! card && el(
								'img',
								{
									key       : 'tarot/card/' + id + '/image',
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
							key : 'tarot/card/' + id + '/caption',
						},
						[
							!! id && (
								card.label +
								( -1 !== classes.indexOf( 'inverted' ) ? ' ' + __( '(Inverted)', 'tarot' ) : '' )
							),
							! id && __( 'Choose a card…', 'tarot' )
						]
					)
				]
			);
		}
	}

	wp.blocks.registerBlockType( 'tarot/spread', {
		title       : __( 'Tarot Spread', 'tarot' ),
		icon        : 'admin-page',
		category    : 'widgets',
		supportHTML : false,

		attributes : {
			cards : {
				type    : 'array',
				default : ''
			},
		},

		edit: function( props ) {
			var cards = null,
				generateSpread = function() {
					cards = pickCards( 3 );
					var newCards = {};
					_.each( cards, function( c ) {
						newCards[ c.id ] = {
							inverted : c.inverted
						};
					});
					props.setAttributes({
						cards : newCards
					});
				};

			// If we have cards stored for this block, use those.  Otherwise, get some new ones and store them.
			if ( props.attributes.cards ) {
				cards = props.attributes.cards;
			}

			return [
				!! cards && el(
					'div',
					{
						key: 'tarot-spread',
						className: props.className + ' tarot-spread three-card',
					},
					_.map( cards, function( c, id ) {
						return el(
							TarotCard,
							{
								key     : 'tarot/spread/' + id,
								classes : c.inverted ? 'inverted' : '',
								id      : id
							}
						);
					} )
				),
				! cards && el(
						wp.components.Placeholder,
						{
							key : 'tarot/spread/generate',
							label : __( 'Generate a Tarot Spread…', 'tarot' ),
							icon : 'admin-page'
						},
						[
							el(
								wp.components.Button,
								{
									key : 'tarot/spread/generate/button',
									isPrimary : true,
									isLarge : true,
									onClick : generateSpread
								},
								__( 'Three-Card Spread', 'tarot' )
							),
						]
					),
			];
		},

		save: function( props ) {
			var cards = null;

			// If we have cards stored for this block, use those.  Otherwise, get some new ones and store them.
			if ( props.attributes.cards ) {
				cards = props.attributes.cards;
			}

			return el(
				'ol',
				{
					className : 'tarot-spread'
				},
				!! cards && _.map( cards, function( c, id ) {
					return el(
						'li',
						{
							key : 'tarot/card/' + id
						},
						deck[ id ].label + ( c.inverted ? __( ' (Inverted)', 'tarot' ) : '' )
					)
				} )
			);
		}
	} );
} )( window.wp, tarotData.deck, _ );
