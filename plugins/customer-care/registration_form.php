<?php

	/* Exit if accessed directly */
	if ( ! defined( 'ABSPATH' ) ) exit; 

	//1. Add a new form element...
	add_action( 'register_form', 'customer_care_register_form' );
	function customer_care_register_form()
	{
		$customer_care_maintenance_date = ( ! empty( $_POST['customer_care_maintenance_date'] ) ) ? sanitize_text_field( $_POST['customer_care_maintenance_date'] ) : '';
		$customer_care_payment_date = ( ! empty( $_POST['customer_care_payment_date'] ) ) ? sanitize_text_field( $_POST['customer_care_payment_date'] ) : '';
		/*
		echo esc_attr( $customer_accpets_news )
		*/
		$sample_date = '2019-12-31';
		wp_nonce_field( 'customer_care_create_user', 'customer_care_subscription');
		?>
		<p>
			<span><?php _e( 'Appliance maintenance date', 'customer_care' ) ?></span>
			<br />
			<input type="date" name="customer_care_maintenance_date" id="customer_care_maintenance_date" class="input" size="25" min="2019-01-01" max="2050-12-31" value="<?php echo $sample_date; ?>" onchange="mydate_e1()" style="font-size:1.1em" />
			<label for="customer_care_maintenance_date" id="customer_care_ndt_1"><?php _e('Undefined date', 'customer_care'); ?></label>
		</p>
		<p>
			<span><?php _e( 'Annual fees payment date', 'customer_care' ) ?></span>
			<br />
			<input type="date" name="customer_care_payment_date" id="customer_care_payment_date" class="input" size="25"  min="2019-01-01" max="2050-12-31" value="<?php echo $sample_date; ?>" onchange="mydate_e2()" style="font-size:1.1em" />
			<label for="customer_care_payment_date" id="customer_care_ndt_2"><?php echo _e('Undefined date', 'customer_care'); ?></label>
		</p>
		<p>
			<label for="customer_accpets_news">
				<i class="icomoon-fontawesome-16x16-check"></i>
				<input type="radio" name="customer_accpets_news" id="customer_accpets_news" value="true" checked style="pointer-events:none;" />
				<?php _e( 'Sign up for reminders and important news', 'customer_care' ) ?>
			</label>
		</p>
		<br />
		<hr />
		<br />
		<?php
	}

	//2. Add validation. In this case, we make sure customer_care_maintenance_date is required.
	add_filter( 'registration_errors', 'customer_care_registration_errors', 10, 3 );
	function customer_care_registration_errors( $errors, $sanitized_user_login, $user_email )
	{        
		if ( empty( $_POST['customer_care_maintenance_date'] ) || ! empty( $_POST['customer_care_maintenance_date'] ) && trim( $_POST['customer_care_maintenance_date'] ) == '' ) 
		{
			$errors->add( 'customer_care_maintenance_date_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'customer_care' ),__( 'You must include a ΚΤΕΟ date.', 'customer_care' ) ) );
		}
		if ( empty( $_POST['customer_care_payment_date'] ) || ! empty( $_POST['customer_care_payment_date'] ) && trim( $_POST['customer_care_payment_date'] ) == '' ) 
		{
			$errors->add( 'customer_care_payment_date', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'customer_care' ),__( 'You must include a name.', 'customer_care' ) ) );
		}
		return $errors;
	}

	//3. Finally, save our extra registration user meta.
	add_action( 'user_register', 'customer_care_user_register' );
	function customer_care_user_register( $user_id )
	{
		if ( ! isset( $_POST['customer_care_subscription 0'] ) || ! wp_verify_nonce( $_POST['customer_care_subscription 0'], 'customer_care_create_user' ) )
		{
			wp_redirect( home_url() );
			exit;
		}
		else
		{
			if ( ! empty( $_POST['customer_care_maintenance_date'] ) )
			{
				update_user_meta( $user_id, 'customer_care_maintenance_date', sanitize_text_field( $_POST['customer_care_maintenance_date'] ) );
			}
			if ( ! empty( $_POST['customer_care_payment_date'] ) )
			{
				update_user_meta( $user_id, 'customer_care_payment_date', sanitize_text_field( $_POST['customer_care_payment_date'] ) );
			}			
			update_user_meta( $user_id, 'customer_accpets_news', 'true');
		}
	}

	/* 4. LOGO */
	function customer_care_login_logo()
	{
		?>
			<style type="text/css">
				.login form {
					padding: 25px!important;
				}
				#login {
					min-width: 400px;
					padding: 2%!important;
				}
				#login h1 a, .login h1 a {
					background-image: url(<?php echo esc_url( plugins_url( 'images/new_logo_roof.png', __FILE__ ) ); ?>);
					background-repeat: no-repeat;
					margin: 5px auto;
				}
				#customer_care_maintenance_date,
				#customer_care_payment_date {
					color: Orange;
				}
				#customer_care_maintenance_date::before,
				#customer_care_payment_date::before {
					font-size: 0.8em;
					color: Black;
				}
			</style>
		<?php
	}
	add_action( 'login_enqueue_scripts', 'customer_care_login_logo' );
?>