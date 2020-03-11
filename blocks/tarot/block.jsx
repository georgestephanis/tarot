
const {
	Component,
	Fragment,
	createElement
} = wp.element;

const {
	InspectorControls
} = wp.blockEditor;

const {
	Placeholder,
	Button,
	ButtonGroup,
	PanelBody,
	ToggleControl
} = wp.components;

const {
	registerBlockType,
	getBlockType,
	createBlock
} = wp.blocks;

const {
	__
} = wp.i18n;

( function( wp, deck, _ ) {
	const star = <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm7.48 7.16l-5.01-.43-2-4.71c3.21.19 5.91 2.27 7.01 5.14zm-5.07 6.26L12 13.98l-2.39 1.44.63-2.72-2.11-1.83 2.78-.24L12 8.06l1.09 2.56 2.78.24-2.11 1.83.64 2.73zm-2.86-11.4l-2 4.72-5.02.43c1.1-2.88 3.8-4.97 7.02-5.15zM4 12c0-.64.08-1.26.23-1.86l3.79 3.28-1.11 4.75C5.13 16.7 4 14.48 4 12zm3.84 6.82L12 16.31l4.16 2.5c-1.22.75-2.64 1.19-4.17 1.19-1.52 0-2.94-.44-4.15-1.18zm9.25-.65l-1.11-4.75 3.79-3.28c.14.59.23 1.22.23 1.86 0 2.48-1.14 4.7-2.91 6.17z"/></svg>;

	function pickCards( qty ) {
		const thisDeck = _.shuffle( _.toArray( deck ) ),
			selection = _.first( thisDeck, qty );

		_.each( selection, function( c, index ) {
			selection[ index ].inverted = Math.random() > 0.5;
		});

		return selection;
	}

	function getCardIdFromProperties( suit, order ) {
		let fullDeck = Object.entries( deck ),
			matches = fullDeck.filter( function( card ) {
				return card[1].suit === suit && card[1].order === order;
			});

		if ( 1 === matches.length ) {
			return matches[0][0];
		}

		return null;
	}

	class TarotCardPicker extends Component {
		constructor( props ) {
			super( props );
			this.state = {
				suit: 0,
				order: 0,
				inverted: false,
			};
		}

		setSuit( value ) {
			this.setState( { suit: value } );
		}

		setOrder( value ) {
			this.setState( { order: value} );
		}

		toggleInverted() {
			this.setState( { inverted: ! this.state.inverted } );
		}

		render() {
			const suit = this.props.suit ? this.props.suit : null,
				cardinality = this.props.cardinality ? this.props.cardinality : null,
				suits = [
					__( 'Major Arcana', 'tarot' ),
					__( 'Wands', 'tarot' ),
					__( 'Pentacles', 'tarot' ),
					__( 'Cups', 'tarot' ),
					__( 'Swords', 'tarot' ),
				],
				major_arcana = [
					__( 'The Fool', 'tarot' ),
					__( 'The Magician', 'tarot' ),
					__( 'The High Priestess', 'tarot' ),
					__( 'The Empress', 'tarot' ),
					__( 'The Emperor', 'tarot' ),
					__( 'The Hierophant', 'tarot' ),
					__( 'The Lovers', 'tarot' ),
					__( 'The Chariot', 'tarot' ),
					__( 'Strength', 'tarot' ),
					__( 'The Hermit', 'tarot' ),
					__( 'Wheel of Fortune', 'tarot' ),
					__( 'Justice', 'tarot' ),
					__( 'The Hanged Man', 'tarot' ),
					__( 'Death', 'tarot' ),
					__( 'Temperance', 'tarot' ),
					__( 'The Devil', 'tarot' ),
					__( 'The Tower', 'tarot' ),
					__( 'The Star', 'tarot' ),
					__( 'The Moon', 'tarot' ),
					__( 'The Sun', 'tarot' ),
					__( 'Judgement', 'tarot' ),
					__( 'The World', 'tarot' )
				],
				minor_cardinalities = [
					__( 'Ace', 'tarot' ),
					__( '2', 'tarot' ),
					__( '3', 'tarot' ),
					__( '4', 'tarot' ),
					__( '5', 'tarot' ),
					__( '6', 'tarot' ),
					__( '7', 'tarot' ),
					__( '8', 'tarot' ),
					__( '9', 'tarot' ),
					__( '10', 'tarot' ),
					__( 'Page', 'tarot' ),
					__( 'Knight', 'tarot' ),
					__( 'Queen', 'tarot' ),
					__( 'King', 'tarot' )
				];

			return (
				<div className="picker">
					<div className="tarot-card">
						<ButtonGroup className="suit">
							{ suits.map( ( item, index ) => (
								<Button isSecondary key={ 'tarot/picker/suit/' + index } isPrimary={ index === this.state.suit } onClick={ () => this.setSuit( index ) }>
									{item}
								</Button>
							) ) }
						</ButtonGroup>

						{ 0 === this.state.suit &&
							<ButtonGroup className="cardinality">
								{ major_arcana.map( ( item, index ) => (
									<Button isSecondary key={ 'tarot/picker/majorindex/' + index } isPrimary={ index === this.state.order } onClick={ () => this.setOrder( index ) }>
										{item}
									</Button>
								) ) }
							</ButtonGroup>
						}
						{ 0 !== this.state.suit &&
							<ButtonGroup className="cardinality">
								{ minor_cardinalities.map( ( item, index ) => (
									<Button isSecondary key={ 'tarot/picker/index/' + index } isPrimary={ index === this.state.order } onClick={ () => this.setOrder( index ) }>
										{item}
									</Button>
								) ) }
							</ButtonGroup>
						}

						<ToggleControl className="is-inverted" label={ __( 'Inverted', 'tarot' ) } checked={ this.state.inverted } onChange={ () => this.toggleInverted() } />
						<Button className="save-card" isPrimary onClick={ () => this.props.pickerFn( this.props.id, {
										suit: suits[ this.state.suit ], // pass back the translated suit string to index by
										order: ( 0 === this.state.suit ) ? this.state.order : this.state.order + 1, // major arcana index starts with zero
										inverted: this.state.inverted
									} ) }>
							{ __( 'Save Card', 'tarot' ) }
						</Button>
					</div>
				</div>
			);
		}
	}

	class TarotCard extends Component {
		render() {
			const id = this.props.id ? this.props.id : null,
				card = deck[ id ] ? deck[ id ] : null,
				classes = this.props.classes || '';

			if ( ! card ) {
				return ( <TarotCardPicker id={this.props.id} pickerFn={ this.props.pickerFn } /> );
			}

			return (
				<figure>
					<Fragment>
						<div className={ 'tarot-card ' + classes + ( !! id ? '' : ' empty' ) }>
							{ !! card && <img
									className='card-art'
									src={card.image_url}
									width="150"
									alt={card.unicode}
								/>
							}
						</div>
						<figcaption>
							<Fragment>
								{ !! id && ( card.label + ( -1 !== classes.indexOf( 'inverted' ) ? ' ' + __( '(Inverted)', 'tarot' ) : '' ) ) }
								{ ! id && __( 'Choose a card…', 'tarot' ) }
							</Fragment>
						</figcaption>
					</Fragment>
				</figure>
			);
		}
	}

	registerBlockType( 'tarot/spread', {
		title       : __( 'Tarot Spread', 'tarot' ),
		icon        : star,
		category    : 'widgets',
        supports    : {
            html    : false,
        },

		attributes : {
			cards : {
				type    : 'object',
				default : null,
			},
		},

		edit: function( props ) {
			let cards = null;
			const generateSpread = function() {
					cards = pickCards( 3 );
					let newCards = {};
					_.each( cards, function( c ) {
						newCards[ c.id ] = {
							inverted : c.inverted
						};
					});
					props.setAttributes({
						cards : newCards
					});
				},
				clearSpread = function() {
					props.setAttributes({
						cards : null
					});
				},
				manualSpread = function() {
					props.setAttributes({
						cards : {
							pickNo1 : false,
							pickNo2 : false,
							pickNo3 : false,
						}
					});
				},
				setCard = function( oldId, newId, inverted ) {
					let cards = Object.entries( props.attributes.cards ),
						newCards = {};

					cards.forEach( function( card ) {
						// Gotta bump up index to account for zero indexing in arrays
						if ( oldId === card[0] ) {
							newCards[ newId ] = { inverted };
						} else {
							newCards[ card[0] ] = card[1];
						}
					});

					props.setAttributes({
						cards: newCards
					});
				},
				handlePickerResponse = function( position, response ) {
					const card = getCardIdFromProperties( response.suit, response.order );

					if ( card && ! props.attributes.cards[ card ] ) {
						setCard( position, card, response.inverted );
					}
				};

			// If we have cards stored for this block, use those.  Otherwise, get some new ones and store them.
			if ( props.attributes.cards ) {
				cards = props.attributes.cards;
			}

			return (
				<Fragment>
					{ cards ? (
						<div key="tarot-spread" className={props.className + ' tarot-spread three-card'}>
							{ _.map( cards, function( c, id ) {
								return (
									<TarotCard
										key={'tarot/spread/' + id}
										classes={ c.inverted ? 'inverted' : '' }
										id={id}
										pickerFn={handlePickerResponse}
									/>
								);
							} ) }
						</div>
					) : (
						<Placeholder key="tarot/spread/generate" label={ __( 'Generate a Tarot Spread…', 'tarot' ) } icon={ star }>
							<Button key="tarot/spread/generate/button" isPrimary={true} isLarge={true} onClick={generateSpread}>
								{ __( 'Three-Card Spread', 'tarot' ) }
							</Button>
							<Button key="tarot/spread/manual/button" isSecondary={true} isLarge={true} onClick={manualSpread}>
								{ __( 'Manual Card Select', 'tarot' ) }
							</Button>
						</Placeholder>
					) }
					<InspectorControls>
						<PanelBody title={ __( 'Tarot Settings', 'tarot' ) }>
							<Button isPrimary={ !! props.attributes.cards } disabled={ ! props.attributes.cards } isLarge={true} onClick={clearSpread}>
								{ __( 'Clear Spread', 'tarot' ) }
							</Button>
						</PanelBody>
					</InspectorControls>
                </Fragment>
			);
		},

		save: function( props ) {
			let cards = null;

			// If we have cards stored for this block, use those.
			if ( props.attributes.cards ) {
				cards = props.attributes.cards;
			}

			return (
				<ol className='tarot-spread'>
					{
						!! cards && _.map( cards, function( c, id ) {
							return (
								<li key={'tarot/card/' + id}>
									{deck[ id ].label + ( c.inverted ? ' ' + __( '(Inverted)', 'tarot' ) : '' )}
								</li>
							);
						} )
					}
				</ol>
			);
		}
	} );
} )( window.wp, tarotData.deck, _ );
