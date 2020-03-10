
const {
	Component,
	Fragment,
	createElement
} = wp.element;

const {
	InspectorControls
} = wp.editor;

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

	class TarotCardPicker extends Component {
		render() {
			const suit = this.props.suit ? this.props.suit : null,
				cardinality = this.props.cardinality ? this.props.cardinality : null;

			return (
				<div className="picker">
					<ButtonGroup className="suit">
						<Button isPrimary>Major Arcana</Button>
						<Button isPrimary>Wands</Button>
						<Button isPrimary>Pentacles</Button>
						<Button isPrimary>Cups</Button>
						<Button isPrimary>Swords</Button>
					</ButtonGroup>
					{ 'Major Arcana' === suit &&
						<ButtonGroup className="cardinality">
							<Button isSecondary>The Fool</Button>
							<Button isSecondary>The Magician</Button>
							<Button isSecondary>The High Priestess</Button>
							<Button isSecondary>The Empress</Button>
							<Button isSecondary>The Emperor</Button>
							<Button isSecondary>The Hierophant</Button>
							<Button isSecondary>The Lovers</Button>
							<Button isSecondary>The Chariot</Button>
							<Button isSecondary>Strength</Button>
							<Button isSecondary>The Hermit</Button>
							<Button isSecondary>Wheel of Fortune</Button>
							<Button isSecondary>Justice</Button>
							<Button isSecondary>The Hanged Man</Button>
							<Button isSecondary>Death</Button>
							<Button isSecondary>Temperance</Button>
							<Button isSecondary>The Devil</Button>
							<Button isSecondary>The Tower</Button>
							<Button isSecondary>The Star</Button>
							<Button isSecondary>The Moon</Button>
							<Button isSecondary>The Sun</Button>
							<Button isSecondary>Judgement</Button>
							<Button isSecondary>The World</Button>
						</ButtonGroup>
					}
					{ 'Major Arcana' !== suit &&
						<ButtonGroup className="cardinality">
							<Button isSecondary>Ace</Button>
							<Button isSecondary>2</Button>
							<Button isSecondary>3</Button>
							<Button isSecondary>4</Button>
							<Button isSecondary>5</Button>
							<Button isSecondary>6</Button>
							<Button isSecondary>7</Button>
							<Button isSecondary>8</Button>
							<Button isSecondary>9</Button>
							<Button isSecondary>10</Button>
							<Button isSecondary>Page</Button>
							<Button isSecondary>Knight</Button>
							<Button isSecondary>Queen</Button>
							<Button isSecondary>King</Button>
						</ButtonGroup>
					}
					<ToggleControl
						label="Inverted"
					/>
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
				return ( <TarotCardPicker /> );
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
						cards : [
							{},
							{},
							{}
						]
					});
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
