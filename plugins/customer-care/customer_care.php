<?php

	/*
		Plugin Name: Customer care
		Description: The one and only cute plugin for after sales and pre sales support. Siginificant dates reminder. Customer care notifications.
		Text Domain: customer_care
		Domain Path: /languages/
		Version: 0.8.1
		Author: Nomikos Strigkos
		Author URI: https://mikroweb.eu/wordpress/
		License: GPL2
	*/

	/* 	
		* requires 4.5 
		Σ
	*/

	/* Exit if accessed directly */
	if ( ! defined( 'ABSPATH' ) ) exit; 
	
	if (!defined ( "CUSTOMER_CARE_EMAILS_TEST" )) 
	{
		define("CUSTOMER_CARE_EMAILS_TEST", false);
	}

	if (!defined ( "CUSTOMER_CARE_EMAILS_SLEEP" ))
	{
		define("CUSTOMER_CARE_EMAILS_SLEEP", 1);
	}

	if (!defined ( "CUSTOMER_CARE_EMAILS_PER_HOUR" ))
	{
		define("CUSTOMER_CARE_EMAILS_PER_HOUR", 20);
	}

	if (!defined ( "CUSTOMER_CARE_DEFAULT_START_HOUR" ))
	{
		define("CUSTOMER_CARE_DEFAULT_START_HOUR", '8');
	}

	if (!defined ("CUSTOMER_CARE_DEFAULT_START_HOUR_N" ))
	{
		define("CUSTOMER_CARE_DEFAULT_START_HOUR_N", (int)CUSTOMER_CARE_DEFAULT_START_HOUR);
	}

	global $customer_care_checking_by_button;
	$customer_care_checking_by_button = false;

	/*	______________________________________________________________________________
	
		* This function, used for debugging, writes a messsage to wp-content/debug.log
		______________________________________________________________________________
	*/
	function customer_care_write_log( $log )
	{
		if ( true === WP_DEBUG )
		{
			if ( is_array( $log ) || is_object( $log ) )
			{
				error_log( print_r( $log, true ) );
			}
			else
			{
				error_log( $log );
			}
		}
	}	

	/*	_________________________________________________________________________________________
	
		* This function detects whether an SMTP plugin is present and returns true if it's active
		_________________________________________________________________________________________
	*/
	function customer_care_smtp_plugin_active()
	{
		$active = false;
		if (!$active)
		{
			if (file_exists(dirname(__FILE__) . '/../postman-smtp/postman-smtp.php'))
			{
				if (is_plugin_active('postman-smtp/postman-smtp.php')) 
				{
					$active = true;
				}
			}
		}
		if (!$active)
		{
			if (file_exists(dirname(__FILE__) . '/../wp-mail-smtp/wp_mail_smtp.php'))
			{
				if (is_plugin_active('wp-mail-smtp/wp_mail_smtp.php')) 
				{
					$active = true;
				}
			}
		}
		if (!$active)
		{
			if (file_exists(dirname(__FILE__) . '/../easy-wp-smtp/easy-wp-smtp.php'))
			{
				if (is_plugin_active('easy-wp-smtp/easy-wp-smtp.php')) $active = true;
			}
		}
		if (!$active)
		{
			if (file_exists(dirname(__FILE__) . '/../gmail-smtp/main.php'))
			{
				if (is_plugin_active('gmail-smtp/main.php')) $active = true;
			}
		}

		if (!$active)
		{
			if (file_exists(dirname(__FILE__) . '/../wp-mail-bank/wp-mail-bank.php'))
			{
				if (is_plugin_active('wp-mail-bank/wp-mail-bank.php')) $active = true;
			}
		}
		return $active;
	}	

	/*	______________________________________________________________________________________________
	
		* This function attempts to load translations for the country/language set in Settings/General
		______________________________________________________________________________________________
	*/

	function customer_care_load_plugin_textdomain()
	{
		load_plugin_textdomain('customer_care', FALSE, basename(dirname(__FILE__)) . '/languages/');
	}	
	add_action( 'plugins_loaded', 'customer_care_load_plugin_textdomain' );

	/*	_______________
	
		* Settings link
		_______________
	*/
	function customer_care_plugin_page_settings_link( $links ) {
		$links[] = '<a href="' . admin_url( 'users.php?page=customer_care' ) . '">' . __('Settings') . '</a>';
		return $links;
	}
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'customer_care_plugin_page_settings_link');
	
	/*	______________________________________________________________________________________________
	
		* This function attempts to load translations for the country/language set in Settings/General
		______________________________________________________________________________________________
	*/
	if ( !function_exists( 'customer_care_load_plugin_textdomain' ) )
	{
		function customer_care_load_plugin_textdomain()
		{
			load_plugin_textdomain('customer_care', FALSE, basename(dirname(__FILE__)) . '/languages/');
		}
	}
	add_action( 'plugins_loaded', 'customer_care_load_plugin_textdomain' );
	
	/*
		Load WordPress ??? 
		// require_once (ABSPATH . 'wp-load.php');
		Not required!!!
	*/
	
	/*	__________
	
		* INCLUDEs
		__________
	*/

	/* ----- mike : 24.11.2018 ----- */
	include('activation_settings.php');
	
	/* ----- mike : 24.11.2018 ----- */
	include('admin_page.php');

	/* ----- mike : 25,4,2020 ----- */
	///include('premium_access.php');

	/* ----- mike : 24.11.2018 ----- */
	include('user_profile.php');

	/* ----- mike : 9.12.2018 ----- */
	include('registration_form.php');
	
	/* ----- mike : 21.12.2018 ----- */
	include('shortcode_front.php');
	
	/* ----- mike : 24.11.2018 ----- */
	include('check_date.php');
	
	/* ----- mike : 24.11.2018 ----- */
	include('email_template.php');
	
	/* ----- mike : 4.1.2019 ----- */
	include('holy_tagline.php');
