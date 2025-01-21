<?php
	/* Exit if accessed directly */
	if ( !defined( 'ABSPATH' ) ) exit; 
	
	global $action_tab;
	if ( empty($_GET['action']) )
	{
		$action_tab = 'premium_content';
	}
	else
	{
		$action_tab = filter_var($_GET['action'], FILTER_SANITIZE_STRING);
	}
	/* 
		===============================================================================
		* This section builds the admin page and manages the settings contained therein
		===============================================================================
		_______________________________________________________________________________
	
		* This function adds an option to the User menu. It is run on admin_menu action
		* The options page is called customer_care_options_page
		_______________________________________________________________________________
	*/
	function customer_care_custom_admin_menu()
	{
		add_users_page( __('Significant dates, reminders, notifications','customer_care'), __('Customer care settings','customer_care'), 'manage_options', 'customer_care', 'customer_care_options_page' );
	}	
	add_action( 'admin_menu', 'customer_care_custom_admin_menu' );

	/*	_______________________________________________________________________________________

		* This function adds the Settings choice underneath the Plugin Name on the Plugins page
		_______________________________________________________________________________________
	*/
	function customer_care_plugin_action_links( $links, $file )
	{
		if ( ! is_network_admin() )
		{
			if (current_user_can('manage_options'))
			{
				static $this_plugin;
				if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

				if ($file == $this_plugin)
				{
					$settings_link = '<a href="admin.php?page=customer_care.php">' . __('Settings', 'customer_care') . '</a>';
					array_unshift($links, $settings_link);
				}
			}
		}
		return $links;
	}
	add_filter( 'plugin_action_links', 'customer_care_plugin_action_links', 10, 2 );

	/*	______________________________________________________________________________________________
	
		* This function adds the Settings choice underneath the Plugin Description on the Plugins page		
		______________________________________________________________________________________________
	*/
	function customer_care_register_plugin_links( $links, $file )
	{
		$base = plugin_basename( __FILE__ );
		if ( $file == $base )
		{
			if ( ! is_network_admin() )
			{
				if (current_user_can('manage_options'))
				{
					$links[] = '<a href="admin.php?page=customer_care.php">' . __('Settings', 'customer_care') . '</a>';
				}
			}
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'customer_care_register_plugin_links', 10, 2 );

	/*	___________________________________________________________________________________________________________________________________
		
		* This function is called by WordPress when it is time to render the options page
		* It is registered with the page itself, above
		* Included here are additional submit buttons, in their own forms, to perform their own separate actions
		* These actions are 'customer_care_test' and 'customer_care_edit' and 'customer_care_dohourly' which are handled by functions below
		___________________________________________________________________________________________________________________________________
	*/
	function customer_care_options_page()
	{
		global $action_tab;
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e('Customer care settings','customer_care'); ?></h1>
			<ul class="subsubsub" style="float:right"><li><a href="<?php echo admin_url('admin.php?page=customer_care&amp;action=help'); ?>">Documentation</a></li></ul>
			<div class="clear"></div>
			<br>
			<h2 class="nav-tab-wrapper" style="border-bottom:none; border-bottom: 1px solid #ccc;">
				<a href="<?php echo admin_url('admin.php?page=customer_care&amp;action=premium_content'); ?>" class="nav-tab <?php if ($action_tab == 'premium_content') echo 'nav-tab-active'; ?> ">
					<?php _e(' 📰 Premium content', 'customer_care'); ?>
				</a>
				<a href="<?php echo admin_url('admin.php?page=customer_care&amp;action=reminder'); ?>" class="nav-tab <?php if ($action_tab == 'reminder') echo 'nav-tab-active'; ?> ">
					<?php _e('Reminder', 'customer_care'); ?>
				</a> 
				<a href="<?php echo admin_url('admin.php?page=customer_care&amp;action=tagline'); ?>" class="nav-tab <?php if ($action_tab == 'tagline') echo 'nav-tab-active'; ?> ">
					<?php _e('Tagline', 'customer_care'); ?> 
				</a> 
				<a href="<?php echo admin_url('admin.php?page=customer_care&amp;action=data'); ?>" class="nav-tab <?php if ($action_tab == 'data') echo 'nav-tab-active'; ?> ">
					<?php _e('️Data', 'customer_care'); ?> 
				</a>
			</h2>
			<?php
			
			if ( empty($_GET['action']) ||  $_GET['action'] == 'premium_content')
			{
				?>
				<p style="width:50%; margin: 20px auto">
					<em>
					By activating this plugin you lock the posts ( but not pages & archives ), so only your customers can read them.
					<br />
					<br />
					It means that only your customers that have bought something from you WooCommerce shop the last year ( 365 days )
					can read articles you have in your WP blog.
					<br />
					<br />
					This can be also used to create a web newspaper to sell you content (articles).
					<br />
					<br />
					</em>
				</p>
				<?php
			}
			else if ( empty($_GET['action']) || $_GET['action'] == 'reminder')
			{
			?>
			<!-- Begin 1 -->
			<div class="card" style="max-width:720px">
				<h2 class="title"><?php _e('Help','customer_care'); ?></h2>
				<ul>
					<li>&bull;
						<?php 
						if ( get_option('users_can_register') ) 
						{
							echo '<span style="color:#0a0">';
							_e('OK! Your website is open to new user subscription. ', 'customer_care'); 
							echo '</span>';
							echo '<span class="">';
							_e('So your visitors will be able to use the special registration form. ', 'customer_care'); 
							echo '</a>';
						}
						else
						{
							echo '<span style="color:#a00">';
							_e('Your visitors cannot register! ', 'customer_care'); 
							echo '</span>';
							echo '<a style="" href="' . admin_url('options-general.php') . '">';
							_e('Please open the Settings (Section:General > Label:Membership) to check the [Anyone can register] option', 'customer_care'); 
							echo '</a>';
						}
					?>
					</li>
					<li> &bull; <?php _e('Use the [Edit template] button to design the letter will be sent.', 'customer_care'); ?></li>
					<li> &bull; <?php _e('You can [Send a test email] to yourself to see what your customers will receive.', 'customer_care'); ?></li>
					<li> &bull; <?php _e('Apart from the scheduled process you can always use the [Check and Send IMMEDIATELY] button.','customer_care'); ?></li>
				</ul>
			</div>
			<div class="clear"></div>
			<form action="options.php" method="post" xmlns="http://www.w3.org/1999/html">
				<?php
					settings_fields('customer_care_pluginPage');
					do_settings_sections('customer_care_pluginPage');
					submit_button();
				?>
			</form>
			<hr />
			<div style="display: table;">
				<div style="display: table-row">
					<div style="padding: 10px 20px; display: table-cell;">
						<form method="POST" action="<?php echo admin_url('admin.php'); ?>">
							<?php wp_nonce_field('customer_care_edit'); ?>
							<input type="hidden" name="action" value="customer_care_edit"/>
							<input class="button button-secondary" type="submit" value="<?php _e('Edit email template','customer_care'); ?>" />
						</form>
						<br/>
						<br/>
					</div>
					<div style="padding: 10px 20px; display: table-cell;">
						<form method="POST" action="<?php echo admin_url('admin.php'); ?>">
							<?php wp_nonce_field('customer_care_test'); ?>
							<input type="hidden" name="action" value="customer_care_test"/>
							<input class="button button-secondary" type="submit" value="<?php _e('Send a test email','customer_care'); ?>" />
						</form>
					</div>
				<?php
					$adminURL = admin_url('admin.php');
				?>
					<div style="padding: 10px 20px; display: table-cell;">
						<form method="POST" action="<?php echo $adminURL ?>">
							<?php wp_nonce_field('customer_care_dohourly'); ?>
							<input type="hidden" name="action" value="customer_care_dohourly" />
							<input class="button button-primary" type="submit" value="<?php _e('Check and Send IMMEDIATELY!','customer_care'); ?>" />
						</form>
					</div>
				</div>
			</div>
			<!-- End of 1 -->
			<?php
			}
			else if ( $_GET['action'] == 'tagline' )
			{
			?>
				<div class="wrap">
					<form method="POST" action="options.php">
						<fieldset style="cursor:text!important" title="Hardcoded parameters" >
							<input type="hidden" name="action" value="tagline_alteration_mode" />								
							<?php
								wp_nonce_field('customer_care_tagline_settings');
								settings_fields('customer_care_tagline_admin_page');
								do_settings_sections('customer_care_tagline_admin_page');
								submit_button( __('Save mode','customer_care') );
							?>								
						</fieldset>
					</form>
					<hr />
					<h3>Available messages</h3>
					<ul>
						<?php
							global $lyrics_array;
							foreach ($lyrics_array as $ui_message)
							{
								echo '<li>' .  $ui_message . '</li>';
							}
						?>
					</ul>
					<!-- <input class="button button-primary" type="submit" value="<?php _e('Switch now','customer_care'); ?>" style="visibility:hidden" disabled /> -->
				</div>
				<h3>Next visitor will see</h3>
					<p class="updated notice is-dismissible" style="border-left-color: #46b450; padding: 10px 12px;">
						<?php echo get_option( 'blogdescription' ); ?>
					</p>
			<?php
			}
			else if ( $_GET['action'] == 'data' )
			{
			?>
				<!-- Begin of 2 -->
				<div class="wrap">
					<h3 class="title">&nbsp;All users except admin</h3>
					<table class="wp-list-table widefat fixed striped posts">
					<?php 
						/* Export data */
						$my_customers = get_users();
						foreach ( $my_customers as $subscriber )
						{
							if ( !$subscriber->caps['administrator'] )
							{
								echo '<tr>';
								
								echo '<td>';
								echo $subscriber->user_email;
								echo '</td>';
								
								echo '<td>';
								echo $subscriber->display_name;
								echo '</td>';
								
								echo '</tr>';
							}
						}
					?>
					</table>
					<?php 
						/*
						echo '<pre>';
						print_r ($my_customers);
						echo '</pre>';
						*/
					?>
					<form method="POST" action="<?php echo $adminURL ?>" style="text-align:center">
						<?php wp_nonce_field('data_export'); ?>
						<input type="hidden" name="action" value="data_export" />
						<input class="button button-primary" type="submit" value="<?php _e('Export data','customer_care'); ?>" style="visibility:hidden" />
					</form>
				</div>
				<!-- End of 2 -->
			<?php
			}
			else if ( $_GET['action'] == 'help' ) 
			{
				include ( 'readme_first.php' );
			?>
				
				<!-- Begin of 3 -->
				<div class="wrap" style="text-align:center">
					<?php 
						// About						
					?>
					<b>Customer care</b> Wordpress plugin
					<br />
					<i>by Nomikos Strigkos</i>
				</div>
				<!-- End of 3 -->
			<?php
			}
			else
			{
			?>
				<!-- Begin of 3 -->
				<div class="wrap" style="text-align:center">
					<?php 
						// About						
					?>
					<b>Customer care</b> Wordpress plugin
					<br />
					<i>by Nomikos Strigkos</i>
				</div>
				<!-- End of 3 -->
			<?php
			}
			?>
		</div>
		<?php
	}


	/*	_________________________________________________________________
		
		* This function sanitizes user input from the admin options page
		* It is run on by WordPress whenever the options page is saved
		* It is registered as part of the settings defined below
		_________________________________________________________________
	*/
	function customer_care_sanitize($input)
	{
		$new_input = array();
		if (isset($input['customer_care_text_field_Hour']))
		{
			$new_val = sanitize_text_field($input['customer_care_text_field_Hour']);
			if ($new_val != '0')
			{
				if (!$new_val) $new_val = CUSTOMER_CARE_DEFAULT_START_HOUR;
				$new_num = (int)$new_val;
				if (!$new_num)
				{
					$new_val = CUSTOMER_CARE_DEFAULT_START_HOUR;
					$new_num = CUSTOMER_CARE_DEFAULT_START_HOUR_N;
				}
				$new_val = (string)$new_num;
				if (!$new_val)
				{
					$new_val = CUSTOMER_CARE_DEFAULT_START_HOUR;
				}
				if ($new_num < 0)
				{
					$new_val = CUSTOMER_CARE_DEFAULT_START_HOUR;
					$new_num = CUSTOMER_CARE_DEFAULT_START_HOUR_N;
				}
				if ($new_num > 23)
				{
					$new_val = CUSTOMER_CARE_DEFAULT_START_HOUR;
					$new_num = CUSTOMER_CARE_DEFAULT_START_HOUR_N;
				}
			}
			$new_input['customer_care_text_field_Hour'] = $new_val;
		}
		if (isset($input['customer_care_text_field_From_Name']))
		{
			$new_input['customer_care_text_field_From_Name'] = sanitize_text_field($input['customer_care_text_field_From_Name']);
		}
		if (isset($input['customer_care_text_field_From_Email']))
		{
			$new_input['customer_care_text_field_From_Email'] = sanitize_text_field($input['customer_care_text_field_From_Email']);
		}
		if (isset($input['customer_care_checkbox_Notify_YesNo']))
		{
			$new_input['customer_care_checkbox_Notify_YesNo'] = sanitize_text_field($input['customer_care_checkbox_Notify_YesNo']);
		}
		if (isset($input['customer_care_text_field_Notification_Email']))
		{
			$new_input['customer_care_text_field_Notification_Email'] = sanitize_text_field($input['customer_care_text_field_Notification_Email']);
		}
		if (isset($input['customer_care_text_field_test_email']))
		{
			$new_input['customer_care_text_field_test_email'] = sanitize_text_field($input['customer_care_text_field_test_email']);
		}
		if (isset($input['customer_care_text_field_test_name']))
		{
			$new_input['customer_care_text_field_test_name'] = sanitize_text_field($input['customer_care_text_field_test_name']);
		}
		return $new_input;
	}

	function customer_care_tagline_sanitize($input)
	{
		$sanitas = array();
		if (isset($input['alteration_mode']))
		{
			$sanitas['alteration_mode'] = sanitize_text_field($input['alteration_mode']);
		}
		return $sanitas;
	}	
	/*	________________________________________________________________________________
		
		* This function defines/registers a set of automatically managed admin settings
		* It is run on admin_init action. The settings are called customer_care_settings
		________________________________________________________________________________
	*/
	function customer_care_settings_init()
	{
		register_setting('customer_care_pluginPage', 'customer_care_settings', 'customer_care_sanitize');
		add_settings_section('customer_care_pluginPage_section_from', __('Operator info / sending details', 'customer_care'), 'customer_care_settings_section_from_callback', 'customer_care_pluginPage' );
		add_settings_field('customer_care_text_field_Hour', __('Start time', 'customer_care'), 'customer_care_text_field_Hour_render', 'customer_care_pluginPage', 'customer_care_pluginPage_section_from' ); 
		add_settings_field('customer_care_text_field_From_Name', __('Name for From', 'customer_care'), 'customer_care_text_field_From_Name_render', 'customer_care_pluginPage', 'customer_care_pluginPage_section_from' );
		add_settings_field('customer_care_text_field_From_Email', __('Email Address for From', 'customer_care'), 'customer_care_text_field_From_Email_render', 'customer_care_pluginPage', 'customer_care_pluginPage_section_from' );
		add_settings_field('customer_care_checkbox_Notify_YesNo', __('send BCC', 'customer_care'), 'customer_care_checkbox_Notify_YesNo_render', 'customer_care_pluginPage', 'customer_care_pluginPage_section_from');
		add_settings_field('customer_care_text_field_Notification_Email', __('BCC email', 'customer_care'), 'customer_care_text_field_Notify_Email_render', 'customer_care_pluginPage', 'customer_care_pluginPage_section_from' );
		
		add_settings_section( 'customer_care_pluginPage_section', __('Testing settings', 'customer_care'), 'customer_care_settings_section_callback', 'customer_care_pluginPage' );
		add_settings_field( 'customer_care_text_field_test_name', __('Customer fullname', 'customer_care'), 'customer_care_text_field_test_name_render', 'customer_care_pluginPage', 'customer_care_pluginPage_section' );
		add_settings_field( 'customer_care_text_field_test_email', __('Customer email ', 'customer_care'), 'customer_care_text_field_test_email_render', 'customer_care_pluginPage', 'customer_care_pluginPage_section' );
	}	
	add_action( 'admin_init', 'customer_care_settings_init' );
	
	function customer_care_tagline_settings_init()
	{
		/* $san_args = array('sanitize_callback' => 'customer_care_tagline_sanitize'); */
		register_setting('customer_care_tagline_admin_page', 'customer_care_tagline_settings', 'customer_care_tagline_sanitize');
		add_settings_section( 'customer_care_tagline_admin_page_section', __('Tagline settings', 'customer_care'), 'customer_care_tagline_settings_section_callback', 'customer_care_tagline_admin_page' );
		add_settings_field( 'alteration_mode', __('Tagline alteration mode', 'customer_care'), 'customer_care_tagline_alteration_mode_render', 'customer_care_tagline_admin_page', 'customer_care_tagline_admin_page_section' );
	}	
	add_action( 'admin_init', 'customer_care_tagline_settings_init' );

	/*
		=========
		RENDERING
		=========
		_______________________________________________________________________________________
		
		* These functions are called by WordPress when it is time to render the settings fields
		* These functions are registered along with the settings, above.
		_______________________________________________________________________________________
	*/

	function customer_care_text_field_Hour_render()
	{
		$options = get_option('customer_care_settings');
		printf(
			' @ <input type="number" min="0" max="23" id="title" name="customer_care_settings[customer_care_text_field_Hour]" value="%s" size="2" maxlength="2" style="width:46px" /> : 00 ',
			isset($options['customer_care_text_field_Hour']) ? esc_attr($options['customer_care_text_field_Hour']) : ''
		);
		echo '<p class="description">' . __('Pick a start time by inserting an hour in the day (integer 0-23)', 'customer_care') . '</p>';
	}
	
	function customer_care_text_field_From_Name_render()
	{
		$options = get_option('customer_care_settings');
		printf(
			'<input type="text" id="title" name="customer_care_settings[customer_care_text_field_From_Name]" size="35" value="%s" />',
			isset($options['customer_care_text_field_From_Name']) ? esc_attr($options['customer_care_text_field_From_Name']) : ''
		);
	}

	function customer_care_text_field_From_Email_render()
	{
		$options = get_option('customer_care_settings');
		printf(
			'<input type="text" id="title" name="customer_care_settings[customer_care_text_field_From_Email]" size="35" value="%s" />',
			isset($options['customer_care_text_field_From_Email']) ? esc_attr($options['customer_care_text_field_From_Email']) : ''
		);
	}

	function customer_care_checkbox_Notify_YesNo_render()
	{
		$options = get_option('customer_care_settings');
		$checked = " ";
		if (array_key_exists('customer_care_checkbox_Notify_YesNo', $options))
		{
			if ($options['customer_care_checkbox_Notify_YesNo'])
			{
				$checked = " checked='checked' ";
			}
		}
		echo '<input type="checkbox" name="customer_care_settings[customer_care_checkbox_Notify_YesNo]" value="true" ' . $checked . ' " />';
	}

	function customer_care_text_field_Notify_Email_render()
	{
		$options = get_option('customer_care_settings');
		printf(
			'<input type="text" id="title" name="customer_care_settings[customer_care_text_field_Notification_Email]" size="35" value="%s" />',
			isset($options['customer_care_text_field_Notification_Email']) ? esc_attr($options['customer_care_text_field_Notification_Email']) : ''
		);
	}

	function customer_care_text_field_test_email_render()
	{
		$options = get_option('customer_care_settings');
		printf(
			'<input type="text" id="title" name="customer_care_settings[customer_care_text_field_test_email]" size="35" value="%s" />',
			isset($options['customer_care_text_field_test_email']) ? esc_attr($options['customer_care_text_field_test_email']) : ''
		);
	}
	
	function customer_care_text_field_test_name_render()
	{
		$options = get_option('customer_care_settings');
		printf(
			'<input type="text" id="title" name="customer_care_settings[customer_care_text_field_test_name]" size="35" value="%s" />',
			isset($options['customer_care_text_field_test_name']) ? esc_attr($options['customer_care_text_field_test_name']) : ''
		);
	}	
	
	/* NEW */
	function customer_care_tagline_alteration_mode_render()
	{
		$customer_care_tagline_settings = get_option('customer_care_tagline_settings');
		$alteration_mode = esc_attr($customer_care_tagline_settings['alteration_mode']);
		// $alteration_mode = 'r';
		print '<label>';
		print '<input type="radio" name="customer_care_tagline_settings[alteration_mode]" value="p" style="border: 1px solid green;"' . ($alteration_mode == 'p' ? 'checked' : '') . ' />';			
		print __('Periodically', 'customer_care') . '&emsp;</label>';
		print '<label>';
		print '<input type="radio" name="customer_care_tagline_settings[alteration_mode]" value="r" style="border: 1px solid orange;"' . ($alteration_mode == 'r' ? 'checked' : '') . ' />';
		print __('Randomly', 'customer_care') . '&emsp;</label>';
		print '<label>';
		print '<input type="radio" name="customer_care_tagline_settings[alteration_mode]" value="i" style="border: 1px solid red;"' . ($alteration_mode == 'i' ? 'checked' : '') . ' />';
		print __('Inactive', 'customer_care') . '&emsp;</label>';
		echo '<p class="description">' . __('Stored in DataBase [ table : wp_options ]', 'customer_care') . '</p>';
	}	

	/*	______________________________________________________________________________________________________________	
		
		* These functions are called by WordPress when it is time to render the instructions for a section of options
		* These functions are registered along with the settings, above
		______________________________________________________________________________________________________________
	*/

	function customer_care_settings_section_callback()
	{
		_e('Please define details for testing', 'customer_care');
	}

	function customer_care_settings_section_from_callback()
	{
		_e('Define an account manager fullname and email', 'customer_care');
	}

	function customer_care_settings_section_notify_callback()
	{
		_e('Manager notifications', 'customer_care');
	}

	/* Customer care tagline section */

	function customer_care_tagline_settings_section_callback()
	{
		_e('Dynamic tagline ', 'customer_care');
	}

	/*	__________________________________________________________________________________________________
		
		* This function is called when the form with 'customer_care_edit' for a hidden 'action' is clicked
		* It opens the post edit page for the custom post type that contains the email template.
		__________________________________________________________________________________________________
	*/
	function customer_care_edit_admin_action()
	{
		check_admin_referer('customer_care_edit');
		if ( current_user_can('manage_options') || current_user_can('administrator') )
		{
			$editorURL = $_SERVER['HTTP_REFERER'];
			$editorURL = substr($editorURL, 0, strripos($editorURL, '/'));
			$customer_care_CPT_ID = get_option('customer_care_CPT_ID');
			$editorURL .= '/post.php?post=' . $customer_care_CPT_ID . '&action=edit';
			wp_redirect($editorURL);
			exit();
		}
	}
	
	/* Sumit an admin page with customer care edit */
	add_action( 'admin_action_customer_care_edit', 'customer_care_edit_admin_action' );
	
	/*	__________________________________________________________________________________________

		* This function is called when the form with 'customer_care_test' for a hidden 'action' is clicked
		* It sends a test email to the name and address on the adm form.
		__________________________________________________________________________________________
	*/
	function customer_care_test_admin_action()
	{
		if (current_user_can('manage_options')||current_user_can('administrator'))
		{
			check_admin_referer('customer_care_test');
			$options = get_option('customer_care_settings');
			customer_care_processEmail(-999999, $options['customer_care_text_field_test_email'], $options['customer_care_text_field_test_name'], $options['customer_care_text_field_test_name'], $options['customer_care_text_field_test_name']);
			wp_redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
	}
	
	add_action( 'admin_action_customer_care_test', 'customer_care_test_admin_action' );
	/* BUTTON ACTIONS NEEDED */

	/*	__________________________________________________________________________________________________________________
	
		* This function is called when the form with 'customer_care_dohourly' for a hidden 'action' is clicked
		* It immediately runs the function that normally fires once an hour to process the Users and send reminding emails
		* This was used for testing
		__________________________________________________________________________________________________________________
	*/
	function customer_care_dohourly_admin_action()
	{
		if (current_user_can('manage_options') || current_user_can('administrator'))
		{
			check_admin_referer('customer_care_dohourly');
			global $customer_care_checking_by_button;
			$customer_care_checking_by_button = true;
			customer_care_hourly();
			wp_redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
	}	
	/* admin_action : cusomer_care_dohourly */
	add_action( 'admin_action_customer_care_dohourly', 'customer_care_dohourly_admin_action' );
	
	
	/* 	_____________________________
		
		Customer care tagline Actions
		_____________________________
	*/
	function customer_care_tagline_settings_admin_action()
	{
		/*
			Future extensibility : Switch message now
		*/
	}
	/* BUTTON ACTIONS : customer_care_tagline_edit */
	add_action( 'admin_action_customer_care_tagline_settings', 'customer_care_tagline_settings_admin_action' );

	/* 	___________________
		
		Data export Actions
		___________________
	*/
	function customer_care_export_edit_admin_action()
	{
		check_admin_referer('data_export_edit');
		if (current_user_can('manage_options') || current_user_can('administrator'))
		{
			$editorURL = $_SERVER['HTTP_REFERER'];
			$editorURL = substr($editorURL, 0, strripos($editorURL, '/'));
			$data_export_ID = get_option('data_export_ID');
			$editorURL .= '/post.php?post=' . $data_export_Id . '&action=edit';
			wp_redirect($editorURL);
			exit();
		}
	}	
	add_action( 'admin_action_customer_care_export_edit', 'customer_care_export_edit_admin_action' );
	/* BUTTON ACTIONS NEEDED */