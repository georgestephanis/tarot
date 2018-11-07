<?php

/**
 * 8-Bit Tarot, by Indigo Kelleigh
 *
 * https://www.lunarbistro.com/games/8-bit-tarot/
 *
 * License: CC BY-NC-SA https://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 * https://twitter.com/indigo_k/status/984282919026880512
 */

class Tarot_8bit {

    public static function is_installed() {
        $deck = self::get_deck_array();
        $upload_dir = wp_get_upload_dir();

        return file_exists( $upload_dir['basedir'] . '/tarot/8bit/' . $deck['ARCANA-00-FOOL'] );
    }

    public static function get_credit() {
        return sprintf(
            __( '<span class="credit"><a href="%s" target="_blank">8-Bit Tarot</a>, by <a href="%s" target="_blank">Indigo Kelleigh</a>. Licensed under <a href="%s" target="_blank">Creative Commons BY-NC-SA</a></span>' ),
            'https://www.lunarbistro.com/games/8-bit-tarot/',
            'http://www.indigokelleigh.com/',
            'https://creativecommons.org/licenses/by-nc-sa/4.0/'
        );
    }

    public static function get_deck_array() {
		return array(
			'ARCANA-00-FOOL'             => '00-The-Fool.gif',
			'ARCANA-01-MAGICIAN'         => '01-The-Magician.gif',
			'ARCANA-02-HIGH-PRIESTESS'   => '02-High-Priestess.gif',
			'ARCANA-03-EMPRESS'          => '03-The-Empress.gif',
			'ARCANA-04-EMPEROR'          => '04-The-Emperor.gif',
			'ARCANA-05-HEIROPHANT'       => '05-The-Hierophant.gif',
			'ARCANA-06-LOVERS'           => '06-The-Lovers.gif',
			'ARCANA-07-CHARIOT'          => '07-The-Chariot.gif',
			'ARCANA-08-STRENGTH'         => '08-Strength.gif',
			'ARCANA-09-HERMIT'           => '09-The-Hermit.gif',
			'ARCANA-10-WHEEL-OF-FORTUNE' => '10-Wheel-of-Fortune.gif',
			'ARCANA-11-JUSTICE'          => '11-Justice.gif',
			'ARCANA-12-HANGED-MAN'       => '12-Hanged_Man.gif',
			'ARCANA-13-DEATH'            => '13-Death.gif',
			'ARCANA-14-TEMPERANCE'       => '14-Temperance.gif',
			'ARCANA-15-DEVIL'            => '15-Devil.gif',
			'ARCANA-16-TOWER'            => '16-The-Tower.gif',
			'ARCANA-17-STAR'             => '17-The-Star.gif',
			'ARCANA-18-MOON'             => '18-The-Moon.gif',
			'ARCANA-19-SUN'              => '19-The-Sun.gif',
			'ARCANA-20-JUDGEMENT'        => '20-Judgement.gif',
			'ARCANA-21-WORLD'            => '21-The-World.gif',
			// wands = clubs
			'ACE-WANDS'    => 'Wands-01.gif',
			'TWO-WANDS'    => 'Wands-02.gif',
			'THREE-WANDS'  => 'Wands-03.gif',
			'FOUR-WANDS'   => 'Wands-04.gif',
			'FIVE-WANDS'   => 'Wands-05.gif',
			'SIX-WANDS'    => 'Wands-06.gif',
			'SEVEN-WANDS'  => 'Wands-07.gif',
			'EIGHT-WANDS'  => 'Wands-08.gif',
			'NINE-WANDS'   => 'Wands-09.gif',
			'TEN-WANDS'    => 'Wands-10.gif',
			'PAGE-WANDS'   => 'Wands-Page.gif',
			'KNIGHT-WANDS' => 'Wands-Knight.gif',
			'QUEEN-WANDS'  => 'Wands-Queen.gif',
			'KING-WANDS'   => 'Wands-King.gif',
			// pentacles = diamonds
			'ACE-PENTACLES'    => 'Coins-01.gif',
			'TWO-PENTACLES'    => 'Coins-02.gif',
			'THREE-PENTACLES'  => 'Coins-03.gif',
			'FOUR-PENTACLES'   => 'Coins-04.gif',
			'FIVE-PENTACLES'   => 'Coins-05.gif',
			'SIX-PENTACLES'    => 'Coins-06.gif',
			'SEVEN-PENTACLES'  => 'Coins-07.gif',
			'EIGHT-PENTACLES'  => 'Coins-08.gif',
			'NINE-PENTACLES'   => 'Coins-09.gif',
			'TEN-PENTACLES'    => 'Coins-10.gif',
			'PAGE-PENTACLES'   => 'Coins-Page.gif',
			'KNIGHT-PENTACLES' => 'Coins-Knight.gif',
			'QUEEN-PENTACLES'  => 'Coins-Queen.gif',
			'KING-PENTACLES'   => 'Coins-King.gif',
			// cups = heartss
			'ACE-CUPS'    => 'Cups-01.gif',
			'TWO-CUPS'    => 'Cups-02.gif',
			'THREE-CUPS'  => 'Cups-03.gif',
			'FOUR-CUPS'   => 'Cups-04.gif',
			'FIVE-CUPS'   => 'Cups-05.gif',
			'SIX-CUPS'    => 'Cups-06.gif',
			'SEVEN-CUPS'  => 'Cups-07.gif',
			'EIGHT-CUPS'  => 'Cups-08.gif',
			'NINE-CUPS'   => 'Cups-09.gif',
			'TEN-CUPS'    => 'Cups-10.gif',
			'PAGE-CUPS'   => 'Cups-Page.gif',
			'KNIGHT-CUPS' => 'Cups-Knight.gif',
			'QUEEN-CUPS'  => 'Cups-Queen.gif',
			'KING-CUPS'   => 'Cups-King.gif',
			// swords = spades
			'ACE-SWORDS'    => 'Swords-01.gif',
			'TWO-SWORDS'    => 'Swords-02.gif',
			'THREE-SWORDS'  => 'Swords-03.gif',
			'FOUR-SWORDS'   => 'Swords-04.gif',
			'FIVE-SWORDS'   => 'Swords-05.gif',
			'SIX-SWORDS'    => 'Swords-06.gif',
			'SEVEN-SWORDS'  => 'Swords-07.gif',
			'EIGHT-SWORDS'  => 'Swords-08.gif',
			'NINE-SWORDS'   => 'Swords-09.gif',
			'TEN-SWORDS'    => 'Swords-10.gif',
			'PAGE-SWORDS'   => 'Swords-Page.gif',
			'KNIGHT-SWORDS' => 'Swords-Knight.gif',
			'QUEEN-SWORDS'  => 'Swords-Queen.gif',
			'KING-SWORDS'   => 'Swords-King.gif',
		);
	}

    public static function filter_deck( $deck ) {
		$deck8bit = self::get_deck_array();
		$upload_dir = wp_get_upload_dir();

		foreach ( $deck as $id => $card ) {
			$deck[ $id ]['image_url'] = $upload_dir['baseurl'] . '/tarot/8bit/' . $deck8bit[ $id ];
		};

		return $deck;
	}

	public static function sideload_deck() {
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$upload_dir = wp_get_upload_dir();
		wp_mkdir_p( $upload_dir['basedir'] . '/tarot/8bit' );

		add_filter( 'upload_dir', array( __CLASS__, 'override_upload_dir' ) );

		$return = array();
		$deck = self::get_deck_array();
		foreach ( $deck as $id => $card ) {
			$tmp = download_url( 'https://www.lunarbistro.com/img/game-img/tarot-cards/' . $card );

			if ( is_wp_error( $tmp ) ) {
				unlink( $tmp );
				continue;
			}

			$file_array = array(
				'name' => $card,
				'tmp_name' => $tmp,
			);

			$return[ $id ] = wp_handle_sideload( $file_array, array( 'test_form' => false ) );
		}

		remove_filter( 'upload_dir', 'override_upload_dir_8bit' );

		return $return;
	}

	public static function override_upload_dir( $uploads ) {
		$uploads['path'] = $uploads['basedir'] . '/tarot/8bit';
		return $uploads;
	}
}
