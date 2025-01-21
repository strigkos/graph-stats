<?php
	/*
 	* @package Customer_care
 	* @version 0.7
 	*/	
	
	/* 
		We build our array for some periods in during a day
		0-6, 6-12, 12-18, 18-24
	*/
	
	/* Exit if accessed directly */
	if ( ! defined( 'ABSPATH' ) ) exit; 
	
	global $lyrics_array;
	$lyrics_array = array();
	$lyrics_array[0] = 'Goodnight';
	$lyrics_array[6] = 'Good morning';
	$lyrics_array[12] = 'Welcome';
	$lyrics_array[18] = 'Good evening';
	
	/* IF SUBMITed MODE ~ WRITE */
	global $shuffle_mode;
	/*
		Shuffle method / mode
			i = Inactive
			p = Periodically
			r = Randomly
		* READ THE SETTING from DB
	*/
	$customer_care_tagline_settings = get_option('customer_care_tagline_settings');
	$alteration_mode = esc_attr($customer_care_tagline_settings['alteration_mode']);
	$shuffle_mode = $alteration_mode;
	
	function customer_care_get_lyric()
	{
		global $lyrics_array;
		global $shuffle_mode;
		if ($shuffle_mode == 'i')
		{
			return get_option( 'blogdescription' );
		}
		else if ($shuffle_mode == 'p')
		{
			/* Periodically */
			$this_hour = current_time('H');
			if ($this_hour > 18) 
			{
				$customer_holy_tag = $lyrics_array[18];
			}
			else if ($this_hour > 12) 
			{
				$customer_holy_tag = $lyrics_array[12];
			}
			else if ($this_hour > 6)
			{
				$customer_holy_tag = $lyrics_array[6];
			}
			else if ($this_hour > 0) 
			{
				$customer_holy_tag = $lyrics_array[0];
			}
			else
			{
				$customer_holy_tag = 'Welcome';
			}
		}
		else if ($shuffle_mode == 'r')
		{
			/* RANDOMLY choose a line */
			$random_key = array_rand($lyrics_array, 1);
			$customer_holy_tag = wptexturize( $lyrics_array[$random_key] );
		}		
		else
		{
			return wptexturize( $lyrics_array[ mt_rand( 0, count( $lyrics_array ) - 1 ) ] );
		}
		return $customer_holy_tag;
	}

	///This just echoes the chosen line, we'll position it later
	function customer_care_tagline_alteration() 
	{
		/// If is front end - Save next phrase as Tagline
		if ( $shuffle_mode != 'i' && !is_admin() )
		{
			$chosen = trim( customer_care_get_lyric() );			
			update_option( 'blogdescription' , $chosen );
		}
		else
		{
			/// Echo to Dashboard up right corner
			echo '<p id="dolly">' . $chosen . '</p>';
		}
	}
	add_action( 'wp_enqueue_style', 'customer_care_tagline_alteration' );
	/// add_action( 'wp_loaded', 'customer_care_tagline_alteration' );
	/// This caused a bug !!!

	/* We need some CSS to position the paragraph */
	function customer_care_tagline_styling()
	{
		// This makes sure that the positioning is also good for right-to-left languages
		$x = is_rtl() ? 'left' : 'right';
		echo "
		<style type='text/css'>
			#dolly {
				float: $x;
				padding-$x: 15px;
				padding-top: 5px;		
				margin: 0;
				font-size: 11px;
			}
			.block-editor-page #dolly {
				display: none;
			}
		</style>
		";
	}
	add_action( 'admin_head', 'customer_care_tagline_styling' );

?>
