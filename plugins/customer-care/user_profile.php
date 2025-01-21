<?php

	/* Exit if accessed directly */
	if ( !defined( 'ABSPATH' ) ) exit; 

	/* =====================================================================================
	* This section manages the User Profile page, adding extra fields to the page
	* ===================================================================================== */

	/***************************************************************************************
	* This function adds ot the list of contact fields, two new ones, for customer info
	* It is applied as a filter on 'user_contactmethods'
	***************************************************************************************/

	function customer_care_modify_contact_methods ( $profile_fields )
	{	
		/// Remove obsolete fields		
		unset( $profile_fields['aim'] );
		unset( $profile_fields['yim'] );
		unset( $profile_fields['jabber'] );
		
		/// Remove user contact methods
		unset( $profile_fields['googleplus'] );
		unset( $profile_fields['twitter'] );
		unset( $profile_fields['facebook'] );

		return $profile_fields;
	}
	add_filter( 'user_contactmethods', 'customer_care_modify_contact_methods' );
	
	/* ---------- */
	/* Save selected data */
	add_action( 'personal_options_update', 'customer_care_save_user_fields' );
	add_action( 'edit_user_profile_update', 'customer_care_save_user_fields' );
	function customer_care_save_user_fields( $user_id ) 
	{
		if ( !current_user_can( 'edit_user', $user_id ) )
		{
			return false;
		}
		else
		{
			//
		}
		
		/* User inputs to DB */
		if ( $_POST['customer_care_maintenance_date'] )
		{
			$customer_care_date_for_maintenance = sanitize_text_field ( $_POST['customer_care_maintenance_date'] ) ;
			$year_1 = substr ( $customer_care_date_for_maintenance, 0, 4 );
			$month_1 = substr ( $customer_care_date_for_maintenance, 5, 2 );
			$day_1 = substr ( $customer_care_date_for_maintenance, 8, 2 );
			
			if ( checkdate ($month_1, $day_1, $year_1) && $year_1 . '-' . $month_1 . '-' . $day_1 > date("Y-m-d") )
			{				
				$customer_care_date_1 = $year_1 . '-' . $month_1 . '-' . $day_1;
				update_usermeta( $user_id, 'customer_care_maintenance_date', $customer_care_date_1 );
			}
			else
			{
				$customer_care_date_1 = date("Y-m-d");
				/* DO NOT UPDATE */
			}
		}

		if ( $_POST['customer_care_payment_date'] )
		{			
			$customer_care_date_to_payoff = sanitize_text_field ( $_POST['customer_care_payment_date'] ) ;
			$year_2 = substr ( $customer_care_date_to_payoff, 0, 4 ); 
			$month_2 = substr ( $customer_care_date_to_payoff, 5, 2 );
			$day_2 = substr ( $customer_care_date_to_payoff, 8, 2 );
			
			if ( checkdate ($month_2, $day_2, $year_2) && $year_2 . '-' . $month_2 . '-' . $day_2 > date("Y-m-d") )
			{
				$customer_care_date_2 = $year_2 . '-' . $month_2 . '-' . $day_2;
				update_usermeta( $user_id, 'customer_care_payment_date', $customer_care_date_2 );
			}
			else
			{
				$customer_care_date_2 = date("Y-m-d");
				/* DO NOT UPDATE */
			}
		}
		
		/* customer_care_accomplished */
		if ( $_POST['customer_care_accomplished'] && $_POST['customer_care_accomplished'] == 'on' )
		{
			$customer_care_accomplished = 'on';
			update_usermeta( $user_id, 'customer_care_accomplished', $customer_care_accomplished );
		}
		else 
		{
			/* $customer_care_accomplished = 'off'; */
			delete_user_meta( $user_id, 'customer_care_accomplished' );
		}
		
		if ( $_POST['customer_care_mobile'] ) 
		{
			$customer_mobile_phone = substr ( sanitize_text_field($_POST['customer_care_mobile']), 0, 15 );
			if ( is_numeric($customer_mobile_phone) )
			{
				update_usermeta( $user_id, 'customer_care_mobile', $customer_mobile_phone );
			}
			else
			{
				/* DO NOT UPDATE */
			}			
		}
		
		/* Future use */
		if ( $_POST['customer_accpet_news'] )
		{
			$customer_accpet_news = sanitize_text_field ( $_POST['customer_accpet_news'] );
			if ( $customer_accpet_news == 'on' )
			{
				update_usermeta( $user_id, 'customer_accpet_news', 'on' );
			}
			else
			{
				delete_user_meta( $user_id, 'customer_accpet_news' );
			}
		}
	}

	add_action( 'personal_options', 'customer_care_add_user_fields' );
	function customer_care_add_user_fields( $user )
	{
		?>
			<tr id="customer_care_maintenance_caption">
				<th colspan="2">
					<h3><?php _e( 'Your siginificant dates', 'customer_care' ); ?></h3>
				</th>
			</tr>
			<tbody id="customer_care_maintenance_rowset">
				<tr>
					<th><label><?php _e( 'Appliance maintenance date', 'customer_care' ); ?></label></th>
					<td>
						<?php 
							/* get saved values */
							$customer_care_maintenance_date = get_the_author_meta( 'customer_care_maintenance_date', $user->ID );
							$customer_care_payment_date = get_the_author_meta( 'customer_care_payment_date', $user->ID );

							/* */
							$customer_care_accomplished = get_the_author_meta( 'customer_care_accomplished', $user->ID );
							if ( $customer_care_accomplished && $customer_care_accomplished == 'on')
							{
								$customer_care_accomplished = 'checked';
							}
							else
							{
								$customer_care_accomplished = 'unchecked';
							}
							
							/* Futur extensibility */
							$customer_accpets_news = get_the_author_meta( 'customer_accpets_news', $user->ID );
							if ( $customer_accpets_news )
							{
								$car_news_ckeck = ' checked' ;
							}
							else
							{
								$car_news_ckeck = ' unchecked';
							}
							
							/* */
							$email_unsubscribe = get_the_author_meta( 'customer_care_Unsubscribed', $user->ID );
							if ( $email_unsubscribe )
							{
								$stop_sending = 'checked'; 
							}
							else
							{
								/* */
							}
							$alert_mobile = get_the_author_meta( 'customer_care_mobile', $user->ID );
						?>
						<input type="date" name="customer_care_maintenance_date" id="customer_care_maintenance_date" min="2018-11-01" max="2050-12-31" value="<?php echo $customer_care_maintenance_date; ?>" onchange="mydate_e1()" />
						&nbsp;<label for="customer_care_maintenance_date" id="customer_care_ndt_1"></label>
						<br />
						<p class="description">&nbsp;<?php _e( 'Pick a future date', 'customer_care' ); ?></p>
						<script>
							var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
							/* Future functionality */
							var months_EN = ['Γενάρη', 'Φλεβάρη', 'Μάρτη', 'Απρίλη', 'Μάη', 'Ιούνη', 'Ιούλη', 'Αυγούστου', 'Σεπτέμβρη', 'Οκτώβρη', 'Νοέμβρη', 'Δεκέμβρη'];
							var months_EL = ['January', 'February', 'March', 'April', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
							function mydate_e1()
							{
								d1 = new Date(document.getElementById("customer_care_maintenance_date").value);
								dt = d1.getDate();
								mn = months[d1.getMonth()];
								yy = d1.getFullYear();
								if (dt)
								{
									document.getElementById("customer_care_ndt_1").innerHTML = dt + " " + mn + " " + yy;
								}
								else
								{
									document.getElementById("customer_care_ndt_1").innerHTML = '<?php _e( 'Undefined', 'customer_care' ); ?>';
								}
							}
							mydate_e1();
						</script>
					</td>
				</tr>
				<tr>
					<th><label><?php _e( 'Annual fees payment date', 'customer_care' ); ?></label></th>
					<td>
						<input type="date" name="customer_care_payment_date" id="customer_care_payment_date" min="2018-11-01" max="2050-12-31" value="<?php echo $customer_care_payment_date; ?>" onchange="mydate_e2()" />
						&nbsp;<label for="customer_care_payment_date" id="customer_care_ndt_2"></label>
						<br />
						<p class="description">&nbsp;<?php _e( 'Pick a future date', 'customer_care' ); ?></p>
						<script>
							function mydate_e2()
							{
								d2 = new Date(document.getElementById("customer_care_payment_date").value);
								dt = d2.getDate();
								mn = months[d2.getMonth()];
								yy = d2.getFullYear();
								if (dt) {
									document.getElementById("customer_care_ndt_2").innerHTML = dt + " " + mn + " " + yy;
								}
								else {
									document.getElementById("customer_care_ndt_2").innerHTML = '<?php _e( 'Undefined', 'customer_care' ); ?>';
								}
							}
							mydate_e2();
						</script>
					</td>
				</tr>
				
				<tr>
					<th><label><?php _e( 'Mission accomplished', 'customer_care' ); ?></label></th>
					<td>
						&nbsp;
						<input name="customer_care_accomplished" id="customer_care_accomplished" style="margin-bottom:5px" <?php echo $customer_care_accomplished; ?> type="checkbox" />
						&nbsp;
						<span class=""><?php _e( 'If you have already taken care of this, we wont remind you again', 'customer_care' ); ?></span>
					</td>
				</tr>
				
				<tr style="display:none">
					<fieldset disabled>
						<th>
							<label for="customer_account_termination"><?php _e( 'Account termination', 'customer_care' ); ?></label>
						</th>
						<td>
							&nbsp;<input name="customer_account_termination" id="customer_account_termination" type="checkbox" <?php echo $stop_sending; ?> disabled />
							<br />
							&nbsp;<span class="description"><?php _e( 'If you check the box we will schedule you account termination', 'customer_care' ); ?></span>
						</td>
					</fieldset>
				</tr>
				<tr>
					<th><label for="customer_care_mobile"><?php _e( 'Mobile phone', 'customer_care' ); ?></label></th>
					<td>&nbsp;<input name="customer_care_mobile" id="customer_care_mobile" value="<?php echo $alert_mobile; ?>"></td>
				</tr>
			</tbody>
		<?php 
	}
