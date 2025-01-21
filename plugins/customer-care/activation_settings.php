<?php

	/* Exit if accessed directly */
	if ( ! defined( 'ABSPATH' ) ) exit; 

	/* 	________________________________________________________________________________

		* This section handles the activation, deactivation, and uninstall of the plugin
		________________________________________________________________________________
	*/
	
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'customer_care_plugin_links' );
	function customer_care_plugin_links ( $links )
	{
		$mylinks = array( '<a href="' . admin_url( 'users.php?page=customer_care.php' ) . '">Settings</a>', );
		return array_merge( $links, $mylinks );
	}

	/*	__________________________________________________________________________________________________

		* This function will run once, when the plugin is being activated. It installs the scheduled event
		__________________________________________________________________________________________________
	 */

	function customer_care_install()
	{
		/* Write timestamp for next execution */
		if (!wp_next_scheduled('customer_care_hourly_event'))
		{
			wp_schedule_event(time(), 'twicedaily', 'customer_care_hourly_event');
			/*
				Writes data to the table wp_options ~ option name : cron
			*/
		}
	}
	// register_activation_hook( __FILE__, 'customer_care_install');
	register_activation_hook( __DIR__ . DIRECTORY_SEPARATOR  . 'customer_care.php', 'customer_care_install');
	/* The register_activation_hook function registers a plugin function to be run when the plugin is activated */

	/*	_________________________________________________________________________________________________

		* This function runs at the time the owner DEactivates the plugin. It removes the scheduled event
		_________________________________________________________________________________________________
	*/
	function customer_care_deactivate()
	{
		/* Do not generate any output here */
		wp_clear_scheduled_hook('customer_care_hourly_event');
	}
	//register_deactivation_hook( __FILE__, 'customer_care_deactivate');
	register_deactivation_hook( __DIR__ . DIRECTORY_SEPARATOR . 'customer_care.php', 'customer_care_deactivate');

	/*	______________________________________________________________________________________________

		* This function runs at the time the owner deletes the plugin. It removes options and settings
		______________________________________________________________________________________________
	*/
	function customer_care_uninstall()
	{
		/* Do not generate any output here */
		$customer_care_CPT_ID = get_option('customer_care_CPT_ID');
		wp_delete_post($customer_care_CPT_ID, true);
		delete_option('customer_care_CPT_ID');
		delete_option('customer_care_settings');
		delete_option('tagline_alteration_mode');
	}
	register_uninstall_hook( __FILE__, 'customer_care_uninstall');

	/* mike : remove yoast extra user fields - 25,11,2018 */
	function customer_care_contact_methods( $methods )
	{
		unset( $methods['googleplus'] );
		unset( $methods['twitter'] );
		unset( $methods['facebook'] );
		return $methods;
	}
	add_filter( 'user_contactmethods', 'customer_care_contact_methods', 11 );
	wp_enqueue_style( 'customer_care_style', plugin_dir_url(__FILE__) . 'custom_style.css');
	wp_enqueue_script( 'customer_care_dates', plugin_dir_url(__FILE__) . 'dates_camouflage.js');
	