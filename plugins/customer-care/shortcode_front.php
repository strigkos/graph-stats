<?php

	/* Exit if accessed directly */
	if ( ! defined( 'ABSPATH' ) ) exit; 
	
	/* Shortcodes to display register or login form */
	
	/* Register */
	function customer_care_register_customize( $atts ) 
	{
		if (is_user_logged_in())
		{
			$user_link = '<a href="'. admin_url() . 'profile.php">' . __( 'Go to user profile!', 'customer_care' ) . '</a>';
			return $user_link;	
		}
		echo '<form action="' . site_url('wp-login.php?action=register', 'login_post') . '" method="post">';
		echo '<input type="text" name="user_login" placeholder ="' . __( 'Username', 'customer_care' ) . '" id="user_login" class="input" />';
		echo '<br />';
		echo '<input type="text" name="user_email" placeholder ="' .  __( 'Email account', 'customer_care' ) . '" id="user_email" class="input" />';
		customer_care_register_form();
		// wp_nonce_field();
		echo '<input type="submit" value="' .  __( 'Subscribe', 'customer_care' ) . '" id="register" />';
		echo '</form>';
		return;
	}
	add_shortcode( 'custom_register', 'customer_care_register_customize' );
	
	/* Login */
	function customer_care_login_customize( $atts ) 
	{	
		if ( is_user_logged_in() )
		{
			wp_redirect('/admin');
			$user_link = '<a href="' . admin_url() . 'profile.php">' . __( 'Go to user profile!', 'customer_care' ) . '</a>';
			return $user_link;	
		}
		else
		{
			$args = array (
			'echo'           => false,
			'remember'       => true,
			'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
			'form_id'        => 'loginform',
			'id_username'    => 'user_login',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'label_username' => __( 'Username or Email Address' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in'   => __( 'Log In' ),
			'value_username' => '',
			'value_remember' => false
		);
			return  wp_login_form( $args );			
		}
	}
	add_shortcode( 'custom_login', 'customer_care_login_customize' );
	
