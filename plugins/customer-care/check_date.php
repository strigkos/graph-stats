<?php

	/* Exit if accessed directly */
	if ( ! defined( 'ABSPATH' ) ) exit;

	/*
		======================================================================================
		This section does the actual work of the plugin, checking for dates and sending emails
		====================================================================================== 
		Σ
	*/

	/*	________________________________________________________________
	
		This function runs each time the event is fired. 
		It sends the emails to customers whose signigicant date is today
		and clears the flag saying this was done		
		________________________________________________________________
	*/
	/*
		The function below does something every hour
		a) Checks
		b) Sends
		c) Logs
	*/
	function customer_care_hourly()
	{
		/* To pause for maintenance - Future functionality */
		$customer_care_running = get_option('customer_care_running', true);
		if ( $customer_care_running )
		{
			/* Write the log - Each time keep only 1000 characters from the past */
			$check_date = gmdate('Y-m-d H:i', time()) . "\n" ;
			$check_date .= substr ( file_get_contents ( WP_CONTENT_DIR . '/logs/customer_care_dates_checked.log' ), 0, 1000 );
			file_put_contents ( WP_CONTENT_DIR . '/logs/customer_care_dates_checked.log', $check_date);
		}
		else 
		{
			/* Pause for maintenance - Future functionality */
			return;
		}
		
		$timezoneString = get_option('timezone_string');
		if ($timezoneString)
		{
			date_default_timezone_set($timezoneString);
		}

		/* 1.0.1 - See if we're not yet at the hour to send emails */
		$nowHour = (int)date('G');

		/* get the current hour number */
		$options = get_option('customer_care_settings');
		
		if ( !$options )
		{
			$startHour = CUSTOMER_CARE_DEFAULT_START_HOUR_N;
		}
		else
		{
			/* start_hour saved option */
			$startHour = (int)$options['customer_care_text_field_Hour'];
		}
		
		if ( !$startHour )
		{
			$startHour = CUSTOMER_CARE_DEFAULT_START_HOUR_N;
			if ($options)
			{
				$options['customer_care_text_field_Hour'] = CUSTOMER_CARE_DEFAULT_START_HOUR;
				update_option('customer_care_settings', $options);
			}
		}
		if ($startHour < 0)
		{
			$startHour = CUSTOMER_CARE_DEFAULT_START_HOUR_N;
			if ($options)
			{
				$options['customer_care_text_field_Hour'] = CUSTOMER_CARE_DEFAULT_START_HOUR;
				update_option('customer_care_settings', $options);
			}
		}

		if ($startHour > 23)
		{
			$startHour = CUSTOMER_CARE_DEFAULT_START_HOUR_N;
			if ($options)
			{
				$options['customer_care_text_field_Hour'] = CUSTOMER_CARE_DEFAULT_START_HOUR;
				update_option('customer_care_settings', $options);
			}
		}
		
		global $customer_care_checking_by_button;			
		if ($nowHour < $startHour)
		{
			/*
				if current hour is less than starting hour,
					don't check for significant days,
					unless using the send emails now button
			*/
			if (!$customer_care_checking_by_button)
			{	
				return;
			}
		}

		$customer_care_checking_by_button = false;
		/*
			LOOP through all USERS with today as significant day and not yet done,
			SEND the email,
			MARK as done.
		*/			

		/* */
		$date_today = date('Y-m-d');
		$date_after5 = date('Y-m-d', time() + 5 * 86400);
		$date_after15 = date('Y-m-d', time() + 15 * 86400);

		/* See if buddypress was installed */
		$query = "";
		global $wpdb;
		$field_id = 0;

		/*
			build these query parms
			WP_User_Query arguments
		*/ 
		$customer_care_maintenance_args_0 = array( 'meta_query' => array( array ( 'key' => 'customer_care_maintenance_date', 'value' => $date_today, 'compare' => '=', 'type' => 'DATE', ), ), 'fields' => 'all_with_meta', );
		$customer_care_maintenance_args_5 = array( 'meta_query' => array( array ( 'key' => 'customer_care_maintenance_date', 'value' => $date_after5, 'compare' => '=', 'type' => 'DATE', ), ), 'fields' => 'all_with_meta', );
		$customer_care_maintenance_args_15 = array( 'meta_query' => array( array ( 'key' => 'customer_care_maintenance_date', 'value' => $date_after15, 'compare' => '=', 'type' => 'DATE', ), ), 'fields' => 'all_with_meta', );
		$customer_care_payment_args_0 = array( 'meta_query' => array( array ( 'key' => 'customer_care_payment_date', 'value' => $date_today, 'compare' => '=', 'type' => 'DATE', ), ), 'fields' => 'all_with_meta', );
		$customer_care_payment_args_5 = array( 'meta_query' => array( array ( 'key' => 'customer_care_payment_date', 'value' => $date_after5, 'compare' => '=', 'type' => 'DATE', ), ), 'fields' => 'all_with_meta', );
		$customer_care_payment_args_15 = array( 'meta_query' => array( array ( 'key' => 'customer_care_payment_date', 'value' => $date_after15, 'compare' => '=', 'type' => 'DATE', ), ), 'fields' => 'all_with_meta', );
		
		/*
			The User Query - 
			3 cases : 30 days ago, 10 days ago, same day
		*/
		$customer_care_maintenance_query_0 = new WP_User_Query($customer_care_maintenance_args_0);
		$customer_care_maintenance_query_5 = new WP_User_Query($customer_care_maintenance_args_5);
		$customer_care_maintenance_query_15 = new WP_User_Query($customer_care_maintenance_args_15);
		$customer_care_maintenance_query = array_merge($customer_care_maintenance_query_0->results, $customer_care_maintenance_query_5->results, $customer_care_maintenance_query_15->results);
		
		$customer_care_payment_query_0 = new WP_User_Query($customer_care_payment_args_0);
		$customer_care_payment_query_5 = new WP_User_Query($customer_care_payment_args_5);
		$customer_care_payment_query_15 = new WP_User_Query($customer_care_payment_args_15);
		$customer_care_payment_query = array_merge($customer_care_payment_query_0->results, $customer_care_payment_query_5->results, $customer_care_payment_query_15->results);

		/* No users to remind */
		if ( empty($customer_care_payment_query) && empty($customer_care_maintenance_query) )
		{
			file_put_contents ( WP_CONTENT_DIR . '/logs/customer_care_no_reminds.log', 'No customers notified on ' . gmdate('Y-m-d H:i', time()) );
		}
		
		/*
			The User Loop
		*/
		$emails_per_hour_count = CUSTOMER_CARE_EMAILS_PER_HOUR;

		/*
			Significant day 1
		*/
		if ( !empty($customer_care_maintenance_query) )
		{
			foreach ($customer_care_maintenance_query as $user)
			{
				/*
					For future developemnt
				*/
				if ((!customer_care_smtp_plugin_active()) && ($emails_per_hour_count < 1))
				{
					// break;
				}
				
				if ($user->exists())
				{
					if ($user->has_prop('customer_care_maintenance_date')) 
					{
						$customer_care_maintenance_date = $user->get('customer_care_maintenance_date');
					}
					else
					{
						$customer_care_maintenance_date = '';
					}
					
					if ($user->has_prop('customer_care_accomplished')) 
					{
						$accomplished = ( $user->get('customer_care_accomplished') == 'true' );							
					}
					else
					{
						$accomplished = false;
					}
					
					if ($user->has_prop('customer_care_mobile'))
					{
						$mobile = $user->get('customer_care_mobile');
					}
					else
					{
						$mobile = '';
					}

					if ( $accomplished )
					{
						/*
							Future fucntionality
							break;
						*/
					}
								
					/* Check if the users has already beeing notified */
					if ($user->has_prop('customer_care_maintenance_email_done'))
					{
						$customer_care_maintenance_email_done = $user->get('customer_care_maintenance_email_done');
					}
					else
					{
						$customer_care_maintenance_email_done = '2000-01-01';
					}						

					if (CUSTOMER_CARE_EMAILS_TEST) 
					{
						$customer_care_maintenance_email_done = '2000-01-01';
					}

					if ( $customer_care_maintenance_email_done != $date_today && ( $customer_care_maintenance_date == $date_today || $customer_care_maintenance_date = $date_after5 || $customer_care_maintenance_date = $date_after15) )
					{
						$emailStatus = customer_care_processEmail($user->get('ID'), $user->get('user_email'), $user->get('display_name'), $user->get('first_name'), $user->get('nickname'), 'maintenance');
						// $emails_per_hour_count--;

						if ($emailStatus) 
						{
							update_user_meta($user->get('ID'), 'customer_care_maintenance_email_done', $date_today);
						}

						if (!customer_care_smtp_plugin_active()) 
						{
							sleep(CUSTOMER_CARE_EMAILS_SLEEP);
						} 

						if ($emailStatus)
						{
							customer_care_sendNotification($user->get('display_name'));
							$emails_per_hour_count--;
							if (!customer_care_smtp_plugin_active())
							{
								sleep(CUSTOMER_CARE_EMAILS_SLEEP);
							}
						}
					}
				}
			}
		}
		
		/* Significant day 2 */
		if ( !empty($customer_care_payment_query) )
		{
			foreach ($customer_care_payment_query as $user)
			{
				/*
					Future functionality 
				*/
				if ((!customer_care_smtp_plugin_active()) && ($emails_per_hour_count < 1))
				{
					/* break; */
				}

				if ($user->exists())
				{
					if ($user->has_prop('customer_care_payment_date')) 
					{
						$customer_care_payment_date = $user->get('customer_care_payment_date');
					}
					else
					{
						$customer_care_payment_date = '2000-01-01';
					}
					if ($user->has_prop('customer_care_payment_date')) 
					{
						$customer_care_payment_date = $user->get('customer_care_payment_date');
					}
					else
					{
						$customer_care_payment_date = '';
					}

					if ($user->has_prop('customer_care_accomplished'))
					{
						$accomplished = ($user->get('customer_care_accomplished') == 'true');
					}
					else
					{
						$accomplished = false;
					}
					
					if ($user->has_prop('customer_care_mobile'))
					{
						$mobile = $user->get('customer_care_mobile');
					}
					else
					{
						$mobile = '';
					}

					if ($accomplished)
					{
						/* break; */
					}

					if ($user->has_prop('customer_care_payment_email_done'))
					{
						$customer_care_payment_email_done = $user->get('customer_care_payment_email_done');
					}
					else
					{
						$customer_care_payment_email_done = '2000-01-01';
					}

					if (CUSTOMER_CARE_EMAILS_TEST)
					{
						$customer_care_payment_email_done = '2000-01-01';
					}

					if ( $customer_care_payment_email_done != $date_today && ( $customer_care_payment_date == $date_today || $customer_care_payment_date = $date_after5 || $customer_care_payment_date = $date_after15) )
					{
						$insurance_email_status = customer_care_processEmail($user->get('ID'), $user->get('user_email'), $user->get('display_name'), $user->get('first_name'), $user->get('nickname'), 'payment');
						$emails_per_hour_count--;
						if ($insurance_email_status) 
						{
							update_user_meta($user->get('ID'), 'customer_care_payment_email_done', $date_today);
						}

						if (!customer_care_smtp_plugin_active()) 
						{
							sleep(CUSTOMER_CARE_EMAILS_SLEEP);
						} 

						if ($insurance_email_status)
						{
							customer_care_sendNotification($user->get('display_name'));
							// $emails_per_hour_count--;
							if (!customer_care_smtp_plugin_active())
							{
								sleep(CUSTOMER_CARE_EMAILS_SLEEP);
							}
						}
					}
				}
			}
		}

		/*
			Instead of a flag I write the date that the customer most lately notified 
			and I dont send a duplicate email in the same day
			
			- Loop through all users with done, and not today as significant, TO MARK THEM AS "not done"
		*/

		/* WP_User_Query marketing arguments - Non accomplished users */
		$car_reminder_args = array( 'meta_query' => array( array( 'key' => 'car_marketing', 'value' => 'true', 'compare' => '=', 'type' => 'CHAR', ), ), 'fields' => 'all_with_meta', );
		$car_marketing_args = array( 'meta_query' => array( array( 'key' => 'car_marketing', 'value' => 'true', 'compare' => '=', 'type' => 'CHAR', ), ), 'fields' => 'all_with_meta', );
		/* Reset */
		
		/* The marketing Query */
		$user_query_marketing = new WP_User_Query($car_marketing_args);
		$user_query_reminder = new WP_User_Query($car_reminder_args);

		/* The User Loop for Date_1 */
		if (!empty($user_query_marketing->results))
		{
			foreach ($user_query_marketing->results as $user)
			{
				if ($user->exists())
				{
					if ($user->has_prop('car_marketing'))
					{
						$car_marketing = $user->get('car_marketing');
					}
					else
					{
						$car_marketing = '';
					}
					
					/* Date_1 */
					if ($user->has_prop('customer_care_maintenance_email_done'))
					{
						$customer_care_maintenance_email_done = $user->get('customer_care_maintenance_email_done');
					}
					else
					{
						$customer_care_maintenance_email_done = '2000-01-01';
					}
					
					/* If a long time has past from the last notification  */
					if ( $date_today > $customer_care_maintenance_date ) 
					{
						/* 
							Send email
							* Future functionality
						*/
					}
				}
			}
		}

		/* The User Loop for Date_2 */
		if (!empty($user_query_reminder->results))
		{
			foreach ($user_query_reminder->results as $user)
			{
				if ($user->exists())
				{
					if ($user->has_prop('car_reminder'))
					{
						$car_reminder = $user->get('car_reminder');
					}
					else
					{
						$car_reminder = '';
					}

					/* Date_2 */
					if ($user->has_prop('car_reminder'))
					{
						$car_reminder = ($user->get('car_reminder') == 'true');
					}
					else
					{
						$car_reminder = false;
					}
					
					/* If a long time has past from the latest notification  */
					if ( $date_today && !$car_insurace_date ) 
					{
						/*
							Send email
							* Future functionality
						*/
					}
				}
			}
		}

		/*
			Go through unsubscribe / accomplished table and remove entries over 90 days old				
			Clear unsubscribe keys older than 90 days
		*/
		$table_name = $wpdb->prefix . 'customer_care_unsubscribe';
		$days_ago = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 90, date("Y")));
		$query = 'SELECT id from '.$table_name.' WHERE created < "'. $days_ago .'"';
		$results = $wpdb->get_results($query, ARRAY_A);
		if ($results)
		{
			foreach ($results as $result)
			{
				$wpdb->delete($table_name, $result);
			}
		}
	}
	add_action('customer_care_hourly_event', 'customer_care_hourly');

	/*	_______________________________________________________________________
	
		Manager notification
		- This function sends a Notification email for every customer reminded,
		if the option is selected in the admin options page
		_______________________________________________________________________
	*/
	function customer_care_sendNotification($displayName)
	{
		$options = get_option('customer_care_settings');
		$fromName = $options['customer_care_text_field_From_Name'];
		$fromEmail = $options['customer_care_text_field_From_Email'];
		$headers = [];
		if ($fromEmail) $headers[] = "From: " . '' . get_bloginfo('name') . " <$fromEmail>";
		if ($fromEmail) $headers[] = "Reply-to: $fromEmail";
		$headers[] = 'Content-type: text/plain; charset=utf-8';
		$headers = implode("\r\n", $headers);
		$checked = '';
		if (array_key_exists('customer_care_checkbox_Notify_YesNo', $options))
		{
			if ($options['customer_care_checkbox_Notify_YesNo'])
			{
				$checked = isset($options['customer_care_text_field_Notification_Email']) ? esc_attr($options['customer_care_text_field_Notification_Email']) : '';
			}
		}
		if ($checked)
		{
			wp_mail($checked, __('Acount manager alert for Date 1 ', 'customer_care'), __('A reminding email was sent to ', 'customer_care') . $displayName . '.', $headers);
		}
	}
	
	/*
		*****************************************************************
		Compose the email & Send
		This function does the work of assembling and sending an email.
		- It returns true if the email was successfully sent, 
		indicating that the User should be marked with customer_care_Done
		*****************************************************************
	*/
	function customer_care_processEmail($recipientID, $recipientAddr, $fullname, $firstname, $nickname, $email_purpose)
	{
		if ($email_purpose == 'maintenance' ) 
		{
			$email_body = 'Dear sir / madam';
			$email_body .= "\n";
			$email_body .= __('We would like to remind you that you have to schedule an [ appliance maintenance ]'); 
			$email_body .= "\n";
			$email_body .= "Best regards";
		}
		else if ($email_purpose == 'payment' )
		{
			$email_body = 'Dear sir / madam';
			$email_body .= "\n";
			$email_body .= __('We would like to remind you that you have to schedule an [ annual fees payoff ]');
			$email_body .= "\n";
			$email_body .= "Best regards";
		}
		$template = null;
		$email_status = false;
		$custom_ID = get_option('customer_care_CPT_ID');
		//see if we already created the Birthday Email Template
		if ($custom_ID)
		{ 
			//if an ID was recorded, be sure we can retrieve the email template, if not say it wasn't recorded.
			$template = get_post($custom_ID);
			if (null === $template)
			{
				$custom_ID = 0;
			}
			else
			{
				$postTitle = $template->post_title;
				$template = $template->post_content;
				$template = $email_body . $template;
				/* mike - 9.12.2018 */
			}
		}
		if ($custom_ID)
		{
			$options = get_option('customer_care_settings');
			$fromName = $options['customer_care_text_field_From_Name'];
			$fromEmail = $options['customer_care_text_field_From_Email'];
			$template = str_replace('@fullname', $fullname, $template);
			$postTitle = str_replace('@fullname', $fullname, $postTitle);
			$template = str_replace('@firstname', $firstname, $template);
			$postTitle = str_replace('@firstname', $firstname, $postTitle);
			$template = str_replace('@nickname', $nickname, $template);
			$postTitle = str_replace('@nickname', $nickname, $postTitle);
			$blog_title = get_bloginfo('name');
			$template = str_replace('@sitetitle', $blog_title, $template);
			$postTitle = str_replace('@sitetitle', $blog_title, $postTitle);
			$url = plugins_url('images/checkdaycar.jpg', __FILE__);
			$img = "<img class=\"alignnone size-full wp-image-10\" src=\"@urlhere\" />";
			$img = str_replace('@urlhere', $url, $img);
			$template = str_replace('@defaultcarimage', $img, $template);
			$template = str_replace('@unsubscribe', customer_care_getUnsubscribe($recipientID), $template);
			$template = wpautop($template);
			$headers = [];
			if ($fromEmail) { 
				$headers[] = "From: $fromName <$fromEmail>";
			}
			if ($fromEmail) {
				$headers[] = "Reply-to: $fromEmail";
			}
			$headers[] = 'Content-type: text/html; charset=utf-8';
			$headers = implode("\r\n", $headers);
			$toaddr = $fullname . ' <' . $recipientAddr . '>';
			$_POST['customer_care'] = 'present';
			$email_status = wp_mail($toaddr, $postTitle, $template, $headers);
		}
		return $email_status;
	}


	/*	_______________________________________________________________________________________________________

		Create unsubscribe hyperlink
		Function : To create and return a link containing a unique URL to use for unsubscribing
		- The unique identifier is placed in a database table along with the userID of the person unsubscribing
		_______________________________________________________________________________________________________
	*/
	function customer_care_getUnsubscribe($recipientID)
	{
		/* first, generate and store a hash of the $recipientID */
		if ( !$recipientID )
		{
			return '';
		}
		
		/* generate hash of recipientID */
		$random = openssl_random_pseudo_bytes(18);
		
		$salt = sprintf('$2y$%02d$%s',	13, substr(strtr(base64_encode($random), '+', '.'), 0, 22) );
		/* 2^n cost factor */
		
		$hash = crypt($recipientID, $salt);
		$hashenc = urlencode($hash);
		
		/* store the hash in the unsubscribe table */
		global $wpdb;
		$table_name = $wpdb->prefix.'customer_care_unsubscribe';
		$wpdb->insert( $table_name, array( 'created' => current_time( 'mysql' ), 'hash' => $hash, 'userid' => $recipientID ) );
		
		/*
			Build the URL and the LINK for user account, edit and aunsubscribe 
			$unsubscribe_url = get_edit_user_link((int)$recipientID);  								DOESNT WORK FOR ME!!!
			$unsubscribe_url = plugin_dir_url ( __FILE__ ) . 'unsubscribe_me.php?uid=' . $hashenc;	OLD and FUTURE USE!
		*/
		$unsubscribe_url = admin_url() . 'profile.php';			
		$unsubscribe_link = '<a href="' . $unsubscribe_url .'">' . __('To stop receiveing reminding messages and terminate your acount please click here', 'customer_care') . '</a>';
		return $unsubscribe_link;
	}	
