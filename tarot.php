<?php

/**
 * Plugin Name: Tarot
 */

class Tarot {

	public static function go() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		require_once( dirname( __FILE__ ) . '/blocks/tarot.php' );
	}

	public static function admin_menu() {
		add_management_page( 'Tarot Test', 'tarot', 'manage_options', 'tarot', array( __CLASS__, 'print_deck' ) );
	}

	public static function print_deck() {
		wp_enqueue_style( 'tarot', plugins_url( 'tarot.css', __FILE__ ) );
		$deck = self::get_deck();

		self::print_card( $deck['ARCANA-13-DEATH'] );
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
					<?php echo esc_html( $card['label'] ) . ( false !== strpos( $classes, 'inverted' ) ? ' ' . esc_html__( '(Inverted)' ) : '' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'Choose a card&hellip;' ); ?>
				<?php endif; ?>
			</figcaption>
		</figure>
		<?php

	}

	public static function get_deck() {
		$deck = array (
			'ARCANA-00-FOOL' => array(
				'unicode' => '🃠',
				'image'   => 'images/a-00.svg',
				'label'   => __( 'The Fool' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 0,
			),
			'ARCANA-01-MAGICIAN' => array(
				'unicode' => '🃡',
				'image'   => 'images/a-01.svg',
				'label'   => __( 'The Magician' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 1,
			),
			'ARCANA-02-HIGH-PRIESTESS' => array(
				'unicode' => '🃢',
				'image'   => 'images/a-02.svg',
				'label'   => __( 'The High Priestess' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 2,
			),
			'ARCANA-03-EMPRESS' => array(
				'unicode' => '🃣',
				'image'   => 'images/a-03.svg',
				'label'   => __( 'The Empress' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 3,
			),
			'ARCANA-04-EMPEROR' => array(
				'unicode' => '🃤',
				'image'   => 'images/a-04.svg',
				'label'   => __( 'The Emperor' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 4,
			),
			'ARCANA-05-HEIROPHANT' => array(
				'unicode' => '🃥',
				'image'   => 'images/a-05.svg',
				'label'   => __( 'The Heirophant' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 5,
			),
			'ARCANA-06-LOVERS' => array(
				'unicode' => '🃦',
				'image'   => 'images/a-06.svg',
				'label'   => __( 'The Lovers' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 6,
			),
			'ARCANA-07-CHARIOT' => array(
				'unicode' => '🃧',
				'image'   => 'images/a-07.svg',
				'label'   => __( 'The Chariot' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 7,
			),
			'ARCANA-08-STRENGTH' => array(
				'unicode' => '🃨',
				'image'   => 'images/a-08.svg',
				'label'   => __( 'Strength' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 8,
			),
			'ARCANA-09-HERMIT' => array(
				'unicode' => '🃩',
				'image'   => 'images/a-09.svg',
				'label'   => __( 'The Hermit' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 9,
			),
			'ARCANA-10-WHEEL-OF-FORTUNE' => array(
				'unicode' => '🃪',
				'image'   => 'images/a-10.svg',
				'label'   => __( 'The Wheel of Fortune' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 10,
			),
			'ARCANA-11-JUSTICE' => array(
				'unicode' => '🃫',
				'image'   => 'images/a-11.svg',
				'label'   => __( 'Justice' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 11,
			),
			'ARCANA-12-HANGED-MAN' => array(
				'unicode' => '🃬',
				'image'   => 'images/a-12.svg',
				'label'   => __( 'The Hanged Man' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 12,
			),
			'ARCANA-13-DEATH' => array(
				'unicode' => '🃭',
				'image'   => 'images/a-13.svg',
				'label'   => __( 'Death' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 13,
			),
			'ARCANA-14-TEMPERANCE' => array(
				'unicode' => '🃮',
				'image'   => 'images/a-14.svg',
				'label'   => __( 'Temperance' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 14,
			),
			'ARCANA-15-DEVIL' => array(
				'unicode' => '🃯',
				'image'   => 'images/a-15.svg',
				'label'   => __( 'The Devil' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 15,
			),
			'ARCANA-16-TOWER' => array(
				'unicode' => '🃰',
				'image'   => 'images/a-16.svg',
				'label'   => __( 'The Tower' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 16,
			),
			'ARCANA-17-STAR' => array(
				'unicode' => '🃱',
				'image'   => 'images/a-17.svg',
				'label'   => __( 'The Star' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 17,
			),
			'ARCANA-18-MOON' => array(
				'unicode' => '🃲',
				'image'   => 'images/a-18.svg',
				'label'   => __( 'The Moon' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 18,
			),
			'ARCANA-19-SUN' => array(
				'unicode' => '🃳',
				'image'   => 'images/a-19.svg',
				'label'   => __( 'The Sun' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 19,
			),
			'ARCANA-20-JUDGEMENT' => array(
				'unicode' => '🃴',
				'image'   => 'images/a-20.svg',
				'label'   => __( 'Judgement' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 20,
			),
			'ARCANA-21-WORLD' => array(
				'unicode' => '🃵',
				'image'   => 'images/a-21.svg',
				'label'   => __( 'The World' ),
				'suit'    => __( 'Major Arcana' ),
				'order'   => 21,
			),
			// wands = clubs
			'ACE-WANDS' => array(
				'unicode' => '🃑',
				'image'   => 'images/w-01.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 1,
			),
			'TWO-WANDS' => array(
				'unicode' => '🃒',
				'image'   => 'images/w-02.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 2,
			),
			'THREE-WANDS' => array(
				'unicode' => '🃓',
				'image'   => 'images/w-03.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 3,
			),
			'FOUR-WANDS' => array(
				'unicode' => '🃔',
				'image'   => 'images/w-04.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 4,
			),
			'FIVE-WANDS' => array(
				'unicode' => '🃕',
				'image'   => 'images/w-05.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 5,
			),
			'SIX-WANDS' => array(
				'unicode' => '🃖',
				'image'   => 'images/w-06.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 6,
			),
			'SEVEN-WANDS' => array(
				'unicode' => '🃗',
				'image'   => 'images/w-07.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 7,
			),
			'EIGHT-WANDS' => array(
				'unicode' => '🃘',
				'image'   => 'images/w-08.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 8,
			),
			'NINE-WANDS' => array(
				'unicode' => '🃙',
				'image'   => 'images/w-09.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 9,
			),
			'TEN-WANDS' => array(
				'unicode' => '🃚',
				'image'   => 'images/w-10.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 10,
			),
			'PAGE-WANDS' => array(
				'unicode' => '🃛',
				'image'   => 'images/w-11.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 11,
			),
			'KNIGHT-WANDS' => array(
				'unicode' => '🃜',
				'image'   => 'images/w-12.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 12,
			),
			'QUEEN-WANDS' => array(
				'unicode' => '🃝',
				'image'   => 'images/w-13.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 13,
			),
			'KING-WANDS' => array(
				'unicode' => '🃞',
				'image'   => 'images/w-14.svg',
				'suit'    => __( 'Wands' ),
				'order'   => 14,
			),
			// pentacles = diamonds
			'ACE-PENTACLES' => array(
				'unicode' => '🃁',
				'image'   => 'images/p-01.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 1,
			),
			'TWO-PENTACLES' => array(
				'unicode' => '🃂',
				'image'   => 'images/p-02.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 2,
			),
			'THREE-PENTACLES' => array(
				'unicode' => '🃃',
				'image'   => 'images/p-03.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 3,
			),
			'FOUR-PENTACLES' => array(
				'unicode' => '🃄',
				'image'   => 'images/p-04.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 4,
			),
			'FIVE-PENTACLES' => array(
				'unicode' => '🃅',
				'image'   => 'images/p-05.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 5,
			),
			'SIX-PENTACLES' => array(
				'unicode' => '🃆',
				'image'   => 'images/p-06.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 6,
			),
			'SEVEN-PENTACLES' => array(
				'unicode' => '🃇',
				'image'   => 'images/p-07.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 7,
			),
			'EIGHT-PENTACLES' => array(
				'unicode' => '🃈',
				'image'   => 'images/p-08.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 8,
			),
			'NINE-PENTACLES' => array(
				'unicode' => '🃉',
				'image'   => 'images/p-09.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 9,
			),
			'TEN-PENTACLES' => array(
				'unicode' => '🃊',
				'image'   => 'images/p-10.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 10,
			),
			'PAGE-PENTACLES' => array(
				'unicode' => '🃋',
				'image'   => 'images/p-11.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 11,
			),
			'KNIGHT-PENTACLES' => array(
				'unicode' => '🃌',
				'image'   => 'images/p-12.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 12,
			),
			'QUEEN-PENTACLES' => array(
				'unicode' => '🃍',
				'image'   => 'images/p-13.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 13,
			),
			'KING-PENTACLES' => array(
				'unicode' => '🃎',
				'image'   => 'images/p-14.svg',
				'suit'    => __( 'Pentacles' ),
				'order'   => 14,
			),
			// cups = hearts
			'ACE-CUPS' => array(
				'unicode' => '🂱',
				'image'   => 'images/c-01.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 1,
			),
			'TWO-CUPS' => array(
				'unicode' => '🂲',
				'image'   => 'images/c-02.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 2,
			),
			'THREE-CUPS' => array(
				'unicode' => '🂳',
				'image'   => 'images/c-03.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 3,
			),
			'FOUR-CUPS' => array(
				'unicode' => '🂴',
				'image'   => 'images/c-04.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 4,
			),
			'FIVE-CUPS' => array(
				'unicode' => '🂵',
				'image'   => 'images/c-05.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 5,
			),
			'SIX-CUPS' => array(
				'unicode' => '🂶',
				'image'   => 'images/c-06.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 6,
			),
			'SEVEN-CUPS' => array(
				'unicode' => '🂷',
				'image'   => 'images/c-07.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 7,
			),
			'EIGHT-CUPS' => array(
				'unicode' => '🂸',
				'image'   => 'images/c-08.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 8,
			),
			'NINE-CUPS' => array(
				'unicode' => '🂹	',
				'image'   => 'images/c-09.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 9,
			),
			'TEN-CUPS' => array(
				'unicode' => '🂺',
				'image'   => 'images/c-10.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 10,
			),
			'PAGE-CUPS' => array(
				'unicode' => '🂻',
				'image'   => 'images/c-11.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 11,
			),
			'KNIGHT-CUPS' => array(
				'unicode' => '🂼',
				'image'   => 'images/c-12.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 12,
			),
			'QUEEN-CUPS' => array(
				'unicode' => '🂽',
				'image'   => 'images/c-13.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 13,
			),
			'KING-CUPS' => array(
				'unicode' => '🂾',
				'image'   => 'images/c-14.svg',
				'suit'    => __( 'Cups' ),
				'order'   => 14,
			),
			// swords = spades
			'ACE-SWORDS' => array(
				'unicode' => '🂡',
				'image'   => 'images/s-01.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 1,
			),
			'TWO-SWORDS' => array(
				'unicode' => '🂢',
				'image'   => 'images/s-02.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 2,
			),
			'THREE-SWORDS' => array(
				'unicode' => '🂣',
				'image'   => 'images/s-03.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 3,
			),
			'FOUR-SWORDS' => array(
				'unicode' => '🂤',
				'image'   => 'images/s-04.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 4,
			),
			'FIVE-SWORDS' => array(
				'unicode' => '🂥',
				'image'   => 'images/s-05.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 5,
			),
			'SIX-SWORDS' => array(
				'unicode' => '🂦',
				'image'   => 'images/s-06.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 6,
			),
			'SEVEN-SWORDS' => array(
				'unicode' => '🂧',
				'image'   => 'images/s-07.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 7,
			),
			'EIGHT-SWORDS' => array(
				'unicode' => '🂨',
				'image'   => 'images/s-08.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 8,
			),
			'NINE-SWORDS' => array(
				'unicode' => '🂩',
				'image'   => 'images/s-09.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 9,
			),
			'TEN-SWORDS' => array(
				'unicode' => '🂪',
				'image'   => 'images/s-10.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 10,
			),
			'PAGE-SWORDS' => array(
				'unicode' => '🂫',
				'image'   => 'images/s-11.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 11,
			),
			'KNIGHT-SWORDS' => array(
				'unicode' => '🂬',
				'image'   => 'images/s-12.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 12,
				),
			'QUEEN-SWORDS' => array(
				'unicode' => '🂭',
				'image'   => 'images/s-13.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 13,
				),
			'KING-SWORDS' => array(
				'unicode' => '🂮',
				'image'   => 'images/s-14.svg',
				'suit'    => __( 'Swords' ),
				'order'   => 14,
				),
		);

		$label_templates = array(
			1 => __( 'Ace of %s' ),
			2 => __( 'Two of %s' ),
			3 => __( 'Three of %s' ),
			4 => __( 'Four of %s' ),
			5 => __( 'Five of %s' ),
			6 => __( 'Six of %s' ),
			7 => __( 'Seven of %s' ),
			8 => __( 'Eight of %s' ),
			9 => __( 'Nine of %s' ),
			10 => __( 'Ten of %s' ),
			11 => __( 'Page of %s' ),
			12 => __( 'Knight of %s' ),
			13 => __( 'Queen of %s' ),
			14 => __( 'King of %s' ),
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