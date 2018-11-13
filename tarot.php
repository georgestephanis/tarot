<?php

/**
 * Plugin Name: Tarot
 * Plugin URI:   https://github.com/georgestephanis/tarot/
 * Description:  A Gutenberg Block allowing Tarot card spreads
 * Version:      1.0
 * Author:       George Stephanis
 * Author URI:   https://stephanis.info
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  tarot
 */

class Tarot {

	public static function go() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'wp_ajax_download-tarot-deck', array( __CLASS__, 'handle_download_tarot_decks' ) );

		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		require_once( dirname( __FILE__ ) . '/blocks/tarot.php' );

		require_once( dirname( __FILE__ ) . '/decks/8bit.php' );

		switch( get_option( 'tarot_deck', 'base' ) ) {
			case '8bit':
				add_filter( 'tarot_get_deck', array( 'Tarot_8bit', 'filter_deck' ) );
				break;
			default:
				break;
		}

		add_action( 'plugins_loaded', array( __CLASS__, 'load_plugin_textdomain' ) );
	}

	public static function load_plugin_textdomain() {
		load_plugin_textdomain( 'tarot', FALSE, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	public static function admin_init() {
		add_settings_section(
			'tarot',
			esc_html__( 'Tarot', 'tarot' ),
			array( __CLASS__, 'writing_settings_section' ),
			'writing'
		);

		add_settings_field(
			'tarot_deck',
			sprintf( '<label for="tarot_deck">%1$s</label>', __( 'Tarot Deck', 'tarot' ) ),
			array( __CLASS__, 'tarot_deck_cb' ),
			'writing',
			'tarot'
		);

		register_setting( 'writing', 'tarot_deck', array( __CLASS__, 'sanitize_tarot_deck' ) );
	}

	public static function writing_settings_section() {
		?>
		<p id="tarot-settings-section">
			<?php _e( 'Settings for Tarot decks and spreads&hellip;', 'tarot' ); ?>
		</p>
		<?php
	}

	public static function tarot_deck_cb() {
		$decks = self::list_decks();
		$current = get_option( 'tarot_deck', 'base' );
		?>
		<ul id="tarot-decks">
			<?php foreach ( $decks as $slug => $deck ) : ?>
				<li>
					<input
						id="tarot_deck-<?php echo esc_attr( $slug ); ?>"
						type="radio"
						name="tarot_deck"
						value="<?php echo esc_attr( $slug ); ?>"
						<?php checked( $slug, $current ); ?>
						<?php if ( ! $deck['installed'] ) echo 'disabled'; ?>
					/>

					<label for="tarot_deck-<?php echo esc_attr( $slug ); ?>">
						<?php echo esc_html( $deck['description'] ); ?>
					</label>

					<?php if ( ! $deck['installed'] ) : ?>
						<button class="button button-secondary small install" data-deck="<?php echo esc_attr( $slug ); ?>" data-dl-nonce="<?php echo esc_attr( wp_create_nonce( 'download-tarot-deck_' . $slug ) ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3zm-5.55-8h-2.9v3H8l4 4 4-4h-2.55z"/></svg>
							<span class="screen-reader-text"><?php esc_html_e( 'Install', 'tarot' ); ?></span>
						</button>
					<?php endif; ?>

					<br />

					<?php
					if ( '8bit' === $slug ) {
						echo '<small style="padding-left:2em;">' . Tarot_8bit::get_credit() . '</small>';
					}
					?>
				</li>
			<?php endforeach; ?>
		</ul>

		<script>
			(function($){
				var $tarot_decks = $('#tarot-decks');
				$tarot_decks.on( 'click', 'button.install', function(e){
					const $btn = $( e.target ).closest('button');
					e.preventDefault();
					$btn.prop( 'disabled', true ).addClass( 'inactive' );
					alert( '<?php echo esc_js( __( 'Please be patient, this may take a minute.', 'tarot' ) ); ?>' );

					$.post(
						ajaxurl,
						{
							action : 'download-tarot-deck',
							deck : $btn.data( 'deck' ),
							_wpnonce : $btn.data( 'dl-nonce' ),
						},
						function( response ) {
							console.log( response );
							if ( response.success ) {
								$btn.siblings( 'input' ).prop( 'disabled', false );
								$btn.remove();
							} else {
								alert( response.data );
							}
						}
					);

				});
			})(jQuery);
		</script>
		<?php
	}

	public static function handle_download_tarot_decks() {
		$deck = $_REQUEST['deck'];

		$decks = self::list_decks();

		if ( ! isset( $decks[ $deck ] ) ) {
			wp_send_json_error( 'unknown_deck: ' . $deck );
		}

		check_ajax_referer( 'download-tarot-deck_' . $deck );

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error( 'bad_cap' );
		}

		$results = null;

		switch( $deck ) {
			case '8bit':
				$results = Tarot_8bit::sideload_deck();
				break;
			default:
				break;
		}

		wp_send_json_success( $results );
	}

	public static function sanitize_tarot_deck( $deck ) {
		if ( ! in_array( $deck, array( '8bit'  ) ) ) {
			$deck = 'base';
		}
		return $deck;
	}

	public static function admin_menu() {
		add_management_page( __( 'Tarot Test', 'tarot' ), 'tarot', 'manage_options', 'tarot', array( __CLASS__, 'print_deck' ) );
	}

	public static function print_deck() {
		wp_enqueue_style( 'tarot', plugins_url( 'tarot.css', __FILE__ ) );
		$deck = self::get_deck();

		array_map( array( __CLASS__, 'print_card' ), $deck );
		?>
		<div class="tarot-spread three-card">
			<?php
			self::print_card( $deck['ARCANA-02-HIGH-PRIESTESS'], 'inverted' );
			self::print_card();
			self::print_card( $deck['QUEEN-WANDS'] );
			?>
		</div>
		<?php
	}

	public static function print_card( $card = null, $classes = '' ) {
		?>
		<figure>
			<div class="tarot-card <?php echo esc_attr( $classes ); ?> <?php if ( ! $card ) echo 'empty'; ?>">
				<?php if ( $card ) : ?>
					<img class="card-art" src="<?php echo esc_url( $card['image_url'] ); ?>" width="150" alt="<?php echo esc_attr( $card['unicode'] ); ?>" />
				<?php endif; ?>
			</div>
			<figcaption>
				<?php if ( $card ) : ?>
					<?php echo esc_html( $card['label'] ) . ( false !== strpos( $classes, 'inverted' ) ? ' ' . esc_html__( '(Inverted)', 'tarot' ) : '' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'Choose a card&hellip;', 'tarot' ); ?>
				<?php endif; ?>
			</figcaption>
		</figure>
		<?php

	}

	public static function print_spread( $atts ) {
		$deck = self::get_deck();

		$return = '<div class="tarot-spread three-card">';
		foreach ( $atts['cards'] as $card => $props ) {
			ob_start();
			self::print_card( $deck[ $card ], ( $props['inverted'] ? 'inverted' : '' ) );
			$return .= ob_get_clean();
		}
		if ( '8bit' === get_option( 'tarot_deck', 'base' ) ) {
			$return .= '<small class="deck-attribution">' . sprintf( __( 'Card images from %s.', 'tarot' ), Tarot_8bit::get_credit() ) . '</small>';
		}
		$return .= '</div>';


		return $return;

	}

	public static function list_decks() {
		$decks = array(
			'base' => array(
				'installed' => true,
				'description' => __( 'The base deck, a black and white version of the 1910 Rider-Waite deck.', 'tarot' ),
			),
			'8bit' => array(
				'installed' => Tarot_8bit::is_installed(),
				'description' => __( 'An 8-bit, retro-themed deck.', 'tarot' ),
			),
		);

		return $decks;
	}

	public static function get_deck() {
		$deck = array (
			'ARCANA-00-FOOL' => array(
				'unicode' => '🃠',
				'image'   => 'images/a-00.svg',
				'label'   => __( 'The Fool', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 0,
			),
			'ARCANA-01-MAGICIAN' => array(
				'unicode' => '🃡',
				'image'   => 'images/a-01.svg',
				'label'   => __( 'The Magician', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 1,
			),
			'ARCANA-02-HIGH-PRIESTESS' => array(
				'unicode' => '🃢',
				'image'   => 'images/a-02.svg',
				'label'   => __( 'The High Priestess', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 2,
			),
			'ARCANA-03-EMPRESS' => array(
				'unicode' => '🃣',
				'image'   => 'images/a-03.svg',
				'label'   => __( 'The Empress', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 3,
			),
			'ARCANA-04-EMPEROR' => array(
				'unicode' => '🃤',
				'image'   => 'images/a-04.svg',
				'label'   => __( 'The Emperor', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 4,
			),
			'ARCANA-05-HEIROPHANT' => array(
				'unicode' => '🃥',
				'image'   => 'images/a-05.svg',
				'label'   => __( 'The Heirophant', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 5,
			),
			'ARCANA-06-LOVERS' => array(
				'unicode' => '🃦',
				'image'   => 'images/a-06.svg',
				'label'   => __( 'The Lovers', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 6,
			),
			'ARCANA-07-CHARIOT' => array(
				'unicode' => '🃧',
				'image'   => 'images/a-07.svg',
				'label'   => __( 'The Chariot', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 7,
			),
			'ARCANA-08-STRENGTH' => array(
				'unicode' => '🃨',
				'image'   => 'images/a-08.svg',
				'label'   => __( 'Strength', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 8,
			),
			'ARCANA-09-HERMIT' => array(
				'unicode' => '🃩',
				'image'   => 'images/a-09.svg',
				'label'   => __( 'The Hermit', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 9,
			),
			'ARCANA-10-WHEEL-OF-FORTUNE' => array(
				'unicode' => '🃪',
				'image'   => 'images/a-10.svg',
				'label'   => __( 'The Wheel of Fortune', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 10,
			),
			'ARCANA-11-JUSTICE' => array(
				'unicode' => '🃫',
				'image'   => 'images/a-11.svg',
				'label'   => __( 'Justice', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 11,
			),
			'ARCANA-12-HANGED-MAN' => array(
				'unicode' => '🃬',
				'image'   => 'images/a-12.svg',
				'label'   => __( 'The Hanged Man', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 12,
			),
			'ARCANA-13-DEATH' => array(
				'unicode' => '🃭',
				'image'   => 'images/a-13.svg',
				'label'   => __( 'Death', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 13,
			),
			'ARCANA-14-TEMPERANCE' => array(
				'unicode' => '🃮',
				'image'   => 'images/a-14.svg',
				'label'   => __( 'Temperance', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 14,
			),
			'ARCANA-15-DEVIL' => array(
				'unicode' => '🃯',
				'image'   => 'images/a-15.svg',
				'label'   => __( 'The Devil', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 15,
			),
			'ARCANA-16-TOWER' => array(
				'unicode' => '🃰',
				'image'   => 'images/a-16.svg',
				'label'   => __( 'The Tower', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 16,
			),
			'ARCANA-17-STAR' => array(
				'unicode' => '🃱',
				'image'   => 'images/a-17.svg',
				'label'   => __( 'The Star', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 17,
			),
			'ARCANA-18-MOON' => array(
				'unicode' => '🃲',
				'image'   => 'images/a-18.svg',
				'label'   => __( 'The Moon', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 18,
			),
			'ARCANA-19-SUN' => array(
				'unicode' => '🃳',
				'image'   => 'images/a-19.svg',
				'label'   => __( 'The Sun', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 19,
			),
			'ARCANA-20-JUDGEMENT' => array(
				'unicode' => '🃴',
				'image'   => 'images/a-20.svg',
				'label'   => __( 'Judgement', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 20,
			),
			'ARCANA-21-WORLD' => array(
				'unicode' => '🃵',
				'image'   => 'images/a-21.svg',
				'label'   => __( 'The World', 'tarot' ),
				'suit'    => __( 'Major Arcana', 'tarot' ),
				'order'   => 21,
			),
			// wands = clubs
			'ACE-WANDS' => array(
				'unicode' => '🃑',
				'image'   => 'images/w-01.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 1,
			),
			'TWO-WANDS' => array(
				'unicode' => '🃒',
				'image'   => 'images/w-02.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 2,
			),
			'THREE-WANDS' => array(
				'unicode' => '🃓',
				'image'   => 'images/w-03.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 3,
			),
			'FOUR-WANDS' => array(
				'unicode' => '🃔',
				'image'   => 'images/w-04.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 4,
			),
			'FIVE-WANDS' => array(
				'unicode' => '🃕',
				'image'   => 'images/w-05.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 5,
			),
			'SIX-WANDS' => array(
				'unicode' => '🃖',
				'image'   => 'images/w-06.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 6,
			),
			'SEVEN-WANDS' => array(
				'unicode' => '🃗',
				'image'   => 'images/w-07.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 7,
			),
			'EIGHT-WANDS' => array(
				'unicode' => '🃘',
				'image'   => 'images/w-08.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 8,
			),
			'NINE-WANDS' => array(
				'unicode' => '🃙',
				'image'   => 'images/w-09.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 9,
			),
			'TEN-WANDS' => array(
				'unicode' => '🃚',
				'image'   => 'images/w-10.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 10,
			),
			'PAGE-WANDS' => array(
				'unicode' => '🃛',
				'image'   => 'images/w-11.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 11,
			),
			'KNIGHT-WANDS' => array(
				'unicode' => '🃜',
				'image'   => 'images/w-12.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 12,
			),
			'QUEEN-WANDS' => array(
				'unicode' => '🃝',
				'image'   => 'images/w-13.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 13,
			),
			'KING-WANDS' => array(
				'unicode' => '🃞',
				'image'   => 'images/w-14.svg',
				'suit'    => __( 'Wands', 'tarot' ),
				'order'   => 14,
			),
			// pentacles = diamonds
			'ACE-PENTACLES' => array(
				'unicode' => '🃁',
				'image'   => 'images/p-01.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 1,
			),
			'TWO-PENTACLES' => array(
				'unicode' => '🃂',
				'image'   => 'images/p-02.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 2,
			),
			'THREE-PENTACLES' => array(
				'unicode' => '🃃',
				'image'   => 'images/p-03.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 3,
			),
			'FOUR-PENTACLES' => array(
				'unicode' => '🃄',
				'image'   => 'images/p-04.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 4,
			),
			'FIVE-PENTACLES' => array(
				'unicode' => '🃅',
				'image'   => 'images/p-05.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 5,
			),
			'SIX-PENTACLES' => array(
				'unicode' => '🃆',
				'image'   => 'images/p-06.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 6,
			),
			'SEVEN-PENTACLES' => array(
				'unicode' => '🃇',
				'image'   => 'images/p-07.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 7,
			),
			'EIGHT-PENTACLES' => array(
				'unicode' => '🃈',
				'image'   => 'images/p-08.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 8,
			),
			'NINE-PENTACLES' => array(
				'unicode' => '🃉',
				'image'   => 'images/p-09.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 9,
			),
			'TEN-PENTACLES' => array(
				'unicode' => '🃊',
				'image'   => 'images/p-10.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 10,
			),
			'PAGE-PENTACLES' => array(
				'unicode' => '🃋',
				'image'   => 'images/p-11.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 11,
			),
			'KNIGHT-PENTACLES' => array(
				'unicode' => '🃌',
				'image'   => 'images/p-12.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 12,
			),
			'QUEEN-PENTACLES' => array(
				'unicode' => '🃍',
				'image'   => 'images/p-13.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 13,
			),
			'KING-PENTACLES' => array(
				'unicode' => '🃎',
				'image'   => 'images/p-14.svg',
				'suit'    => __( 'Pentacles', 'tarot' ),
				'order'   => 14,
			),
			// cups = hearts
			'ACE-CUPS' => array(
				'unicode' => '🂱',
				'image'   => 'images/c-01.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 1,
			),
			'TWO-CUPS' => array(
				'unicode' => '🂲',
				'image'   => 'images/c-02.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 2,
			),
			'THREE-CUPS' => array(
				'unicode' => '🂳',
				'image'   => 'images/c-03.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 3,
			),
			'FOUR-CUPS' => array(
				'unicode' => '🂴',
				'image'   => 'images/c-04.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 4,
			),
			'FIVE-CUPS' => array(
				'unicode' => '🂵',
				'image'   => 'images/c-05.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 5,
			),
			'SIX-CUPS' => array(
				'unicode' => '🂶',
				'image'   => 'images/c-06.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 6,
			),
			'SEVEN-CUPS' => array(
				'unicode' => '🂷',
				'image'   => 'images/c-07.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 7,
			),
			'EIGHT-CUPS' => array(
				'unicode' => '🂸',
				'image'   => 'images/c-08.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 8,
			),
			'NINE-CUPS' => array(
				'unicode' => '🂹	',
				'image'   => 'images/c-09.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 9,
			),
			'TEN-CUPS' => array(
				'unicode' => '🂺',
				'image'   => 'images/c-10.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 10,
			),
			'PAGE-CUPS' => array(
				'unicode' => '🂻',
				'image'   => 'images/c-11.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 11,
			),
			'KNIGHT-CUPS' => array(
				'unicode' => '🂼',
				'image'   => 'images/c-12.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 12,
			),
			'QUEEN-CUPS' => array(
				'unicode' => '🂽',
				'image'   => 'images/c-13.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 13,
			),
			'KING-CUPS' => array(
				'unicode' => '🂾',
				'image'   => 'images/c-14.svg',
				'suit'    => __( 'Cups', 'tarot' ),
				'order'   => 14,
			),
			// swords = spades
			'ACE-SWORDS' => array(
				'unicode' => '🂡',
				'image'   => 'images/s-01.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 1,
			),
			'TWO-SWORDS' => array(
				'unicode' => '🂢',
				'image'   => 'images/s-02.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 2,
			),
			'THREE-SWORDS' => array(
				'unicode' => '🂣',
				'image'   => 'images/s-03.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 3,
			),
			'FOUR-SWORDS' => array(
				'unicode' => '🂤',
				'image'   => 'images/s-04.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 4,
			),
			'FIVE-SWORDS' => array(
				'unicode' => '🂥',
				'image'   => 'images/s-05.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 5,
			),
			'SIX-SWORDS' => array(
				'unicode' => '🂦',
				'image'   => 'images/s-06.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 6,
			),
			'SEVEN-SWORDS' => array(
				'unicode' => '🂧',
				'image'   => 'images/s-07.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 7,
			),
			'EIGHT-SWORDS' => array(
				'unicode' => '🂨',
				'image'   => 'images/s-08.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 8,
			),
			'NINE-SWORDS' => array(
				'unicode' => '🂩',
				'image'   => 'images/s-09.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 9,
			),
			'TEN-SWORDS' => array(
				'unicode' => '🂪',
				'image'   => 'images/s-10.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 10,
			),
			'PAGE-SWORDS' => array(
				'unicode' => '🂫',
				'image'   => 'images/s-11.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 11,
			),
			'KNIGHT-SWORDS' => array(
				'unicode' => '🂬',
				'image'   => 'images/s-12.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 12,
				),
			'QUEEN-SWORDS' => array(
				'unicode' => '🂭',
				'image'   => 'images/s-13.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 13,
				),
			'KING-SWORDS' => array(
				'unicode' => '🂮',
				'image'   => 'images/s-14.svg',
				'suit'    => __( 'Swords', 'tarot' ),
				'order'   => 14,
				),
		);

		$label_templates = array(
			1 => __( 'Ace of %s', 'tarot' ),
			2 => __( 'Two of %s', 'tarot' ),
			3 => __( 'Three of %s', 'tarot' ),
			4 => __( 'Four of %s', 'tarot' ),
			5 => __( 'Five of %s', 'tarot' ),
			6 => __( 'Six of %s', 'tarot' ),
			7 => __( 'Seven of %s', 'tarot' ),
			8 => __( 'Eight of %s', 'tarot' ),
			9 => __( 'Nine of %s', 'tarot' ),
			10 => __( 'Ten of %s', 'tarot' ),
			11 => __( 'Page of %s', 'tarot' ),
			12 => __( 'Knight of %s', 'tarot' ),
			13 => __( 'Queen of %s', 'tarot' ),
			14 => __( 'King of %s', 'tarot' ),
		);

		foreach ( $deck as $id => &$card ) {
			$card['id'] = $id;
			$card['image_url'] = plugins_url( $card['image'], __FILE__ );
			// This is manually specified for the Major Arcana, but let's populate it automatically for the minors.
			if ( empty( $card['label'] ) ) {
				$card['label'] = sprintf( $label_templates[ $card['order'] ], $card['suit'] );
			}
		}
		unset( $card );

		return apply_filters( 'tarot_get_deck', $deck );
	}
}

Tarot::go();