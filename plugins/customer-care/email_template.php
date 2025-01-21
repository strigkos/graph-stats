<?php		

	/* Exit if accessed directly */
	if ( ! defined( 'ABSPATH' ) ) exit; 

	/* =====================================================================================
	 * This section manages the custom post type that holds the email template
	 * ===================================================================================== */

	/***************************************************************************************
	* This function defines a custom post type (CPT) to hold the reminding email template
	***************************************************************************************/

	function customer_care_custom_post_type()
	{
		// Set UI labels for Custom Post Type
		$labels = array(
			'name' => _x('Customer Emails', 'Post Type General Name', 'customer_care'),
			'singular_name' => _x('Customer Email', 'Post Type Singular Name', 'customer_care'),
			'menu_name' => __('Customer Emails', 'customer_care'),
			'parent_item_colon' => __('Parent Email', 'customer_care'),
			'all_items' => __('All Customer Emails', 'customer_care'),
			'view_item' => __('View Customer Email', 'customer_care'),
			'add_new_item' => __('Add New Customer Email', 'customer_care'),
			'add_new' => __('Add New', 'customer_care'),
			'edit_item' => __('Edit Customer Email Template', 'customer_care'),
			'update_item' => __('Update Customer Email', 'customer_care'),
			'search_items' => __('Search Customer Emails', 'customer_care'),
			'not_found' => __('Not Found', 'customer_care'),
			'not_found_in_trash' => __('Not found in Trash', 'customer_care'),
		);

		// Set other options for Custom Post Type
		$args = array(
			'label' => 'customer_care',
			'description' => __('Email Templates for Customer Emails', 'customer_care'),
			'labels' => $labels,
			// Features this CPT supports in Post Editor
			'supports' => array('title', 'editor'),
			//'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// You can associate this CPT with a taxonomy or custom taxonomy.
			//        'taxonomies'          => array( 'genres' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/
			'hierarchical' => false,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'menu_position' => 5,
			'can_export' => false,
			'has_archive' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'capability_type' => 'page',
			'capabilities' => array( 'create_posts' => 'do_not_allow', ),  // false < WP 4.5, credit @Ewout
			'map_meta_cap' => true, 
			// Set to `false`, if users are not allowed to edit/delete existing posts
		);
		// Registering your Custom Post Type
		register_post_type('customer_care', $args);
	}
	add_action( 'init', 'customer_care_custom_post_type', 0 );

	/***************************************************************************************
	* Build initial settings, options, and template - builds content if none exists yet.
	* This function checks for the existence of the option that stores the post ID of the email template.
	* If not found, or the email template itself is not found, then the email template is created with default content.
	* It is called on the 'init' action.
	***************************************************************************************/

	function customer_care_buildbdemail()
	{
		$custom_care_Id = get_option('customer_care_CPT_ID'); 
		//see if we already created the Birthday Email Template
		if ($custom_care_Id)
		{
			//if an ID was recorded, be sure we can retrieve the email template, if not say it wasn't recorded.
			if (null === get_post($custom_care_Id)) 
			{
				$custom_care_Id = 0;
			}
		}

		if (!$custom_care_Id)
		{
			//if not, see if it was just not recorded
			$the_query = new WP_Query(array('post_type' => 'customer_care', 'post_status' => 'publish'));
			while ($the_query->have_posts()) 
			{
				$the_query->the_post();
				//found it
				$custom_care_Id = get_the_ID();
				update_option('customer_care_CPT_ID', $custom_care_Id);
				//record it
			}
			$the_query = null;
		}
		if (!$custom_care_Id)
		{
			//we didn't create the email template yet, so do it now.
			$args = array( 
				'post_title' => __('Remind', 'customer_care').' @fullname',
				'post_content' => '<p><h1>'. __('Important notification', 'customer_care').' @fullname'. __('!','customer_care').'</h1></p><p>@defaultcarimage</p><p>'.__('We remind you that a year have past since you purchased our product / service.','customer_care').'</p><p></p><p>'.__('Sincerely','customer_care').',</p><p>'.__('Admin','customer_care').' @ @sitetitle.</p><p style="text-align: center;">@unsubscribe</p>',
				'post_status' => 'publish',
				'post_type' => 'customer_care'
			);
			$custom_care_Id = wp_insert_post($args);
			if ($custom_care_Id) 
			{
				update_option('customer_care_CPT_ID', $custom_care_Id); 
				//record it
			}
		}
		$custom_care_Id = get_option('customer_care_settings');
		//see if we already added admin options
		if (!$custom_care_Id)
		{
			// if we didn't already set admin options:
			$blog_title = get_bloginfo('name');
			$blog_email = get_bloginfo('admin_email');
			$args = [];
			$args['customer_care_text_field_Hour'] = CUSTOMER_CARE_DEFAULT_START_HOUR;
			$args['customer_care_text_field_From_Name'] = __('Account manager','customer_care')." @ $blog_title";
			$args['customer_care_text_field_From_Email'] = $blog_email;
			$args['customer_care_text_field_Notification_Email'] = $blog_email;
			$args['customer_care_text_field_test_email'] = $blog_email;
			$args['customer_care_text_field_test_name'] = __('Test customer','customer_care')." @ $blog_title";
			update_option('customer_care_settings', $args);
			$custom_care_Id = get_option('customer_care_settings');
		}
		else
		{
			//if we did already add them, see if we set the hour to start sending emails (new feature with 1.1)
			if (!isset($custom_care_Id['customer_care_text_field_Hour']))
			{
				$custom_care_Id['customer_care_text_field_Hour'] = CUSTOMER_CARE_DEFAULT_START_HOUR;
				update_option('customer_care_settings', $custom_care_Id);
			}
		}

		/* See if unsubscribe table exists, create it if not */
		global $wpdb;
		$table_name = $wpdb->prefix . 'customer_care_unsubscribe';
		$charset_collate = $wpdb->get_charset_collate();
		$existing = $wpdb->get_var('SHOW TABLES LIKE "'.$table_name.'";');
		if ($existing != $table_name) 
		{
			$sql = "CREATE TABLE $table_name (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					hash varchar(100) NOT NULL,
					userid mediumint(9) NOT NULL,
					created date NOT NULL,
					PRIMARY KEY  (ID)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	add_action( 'init', 'customer_care_buildbdemail' );

	/*	__________________________________________________________________________________________________________________________________________
	
		* This function adds instructions to the bottom of the post edit form, IF the post ID is the one with the email template,
		* It is called on the 'edit_form_after_editor' action, new to WordPress 4.5 - this is the reason the plugin requires at least that version
		__________________________________________________________________________________________________________________________________________
	*/
	function customer_care_edit_form_after_editor()
	{
		global $post;
		if ($post->post_type == 'customer_care')
		{
			echo '<h1>' . __('Note:', 'customer_care').'</h1><br/>' . __('Use the Title, at the top of this page, for the Subject line of the Email.', 'customer_care') . '<br/><br/>' . _x('To insert the user\'s full name type "','ends with opening quote','customer_care').'@fullname' . _x('" (without quotes)','begins with close quote','customer_care') . '<br/><br/>';
			echo _x('"', 'opening quote','opening quote','customer_care') . '@fullname' . _x('" will be replaced with the cusomer Full Name.','begins with close quote','customer_care').'<br/>';
			echo _x('"', 'opening quote','customer_care') . '@firstname' . _x('" will be replaced with the customer First Name.','begins with close quote','customer_care').'<br/>';
			echo _x('"', 'opening quote','customer_care') . '@nickname' . _x('" will be replaced with the customer Nick Name.','begins with close quote','customer_care').'<br/>';
			echo _x('"', 'opening quote','customer_care') . '@sitetitle' . _x('" will be replaced with the Blog Site Title from the Settings/General page.','begins with close quote','customer_care').'<br/>';
			echo _x('"', 'opening quote','customer_care') . '@unsubscribe' . _x('" will be replaced with a link allowing the recipient to unsubscribe from Birthday Emails.','begins with close quote','customer_care').'<br/>';
			echo _x('"', 'opening quote','customer_care') . '@defaultcarimage' . _x('" will be replaced with an image that comes with the Check-day Emails plugin.','begins with close quote','customer_care').'<br/>';
		}
	}	
	add_action( 'edit_form_after_editor', 'customer_care_edit_form_after_editor' );

	/*	______________________________________________________________________________________________
		
		* This function adds css to the head of the admin pages if the post type is the email template
		* The css hides the publish information and also the 'Move to Trash' option.
		* It is called by the 'admin_head-post.php' action.
		______________________________________________________________________________________________
	*/
	function customer_care_hide_publishing_actions()
	{
		global $post;
		if ( $post->post_type == 'customer_care' )
		{
			echo '
				<style type="text/css">
					#delete-action,
					#misc-publishing-actions,
					#minor-publishing-actions{
						display:none;
					}
				</style>
			';
		}
	}
	add_action('admin_head-post.php', 'customer_care_hide_publishing_actions');
