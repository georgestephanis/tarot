( function( wp, deck, _ ) {
	var el = wp.element.createElement,
		__ = wp.i18n.__;

	function pickCards( qty ) {
		var thisDeck = _.toArray( deck );
		thisDeck = _.shuffle( thisDeck );

		_.each( thisDeck, function( c, index ) {
			thisDeck[ index ].inverted = Math.random() > 0.5;
		});

		console.log( thisDeck );
		return _.first( thisDeck, qty );
	}

	class TarotCard extends wp.element.Component {
		render() {
			var card = this.props.card,
				classes = this.props.classes || '';

			return el(
				'figure',
				{
					key : 'tarot/card/' + this.props.id
				},
				[
					el(
						'div',
						{
							key       : 'tarot/card/' + this.props.id + '/div',
							className : 'tarot-card ' + classes + ( !! this.props.card ? '' : ' empty' ),
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
								( -1 !== classes.indexOf( 'inverted' ) ? ' ' + __( '(Inverted)', 'tarot' ) : '' )
							),
							! this.props.card && __( 'Choose a card…', 'tarot' )
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
			var cards, cardsEl,
				buildEl = function( c ) {
					return el(
						TarotCard,
						{
							key : 'tarot/spread/' + c.id,
							classes : c.inverted ? 'inverted' : '',
							id  : c.id,
							card : c
						}
					);
				};

			// If we have cards stored for this block, use those.  Otherwise, get some new ones and store them.
			if ( props.attributes.cards ) {
				cards = props.attributes.cards;
			} else {
				cards = pickCards( 3 );
				props.setAttributes({
					cards : cards
				});
			}

			cardsEl = _.map( cards, buildEl );

			return [
				el(
					'div',
					{
						key: 'tarot-spread',
						className: props.className + ' tarot-spread three-card',
					},
					cardsEl
				)
			];
		},

		save: function() {
			return null;
		}
	} );
} )( window.wp, tarotData.deck, _ );
