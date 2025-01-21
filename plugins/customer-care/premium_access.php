<?php

	ob_clean();
	ob_start();

///Lock posts
function premium_access()
{

	$product_duration = 365;
	$has_access = false;

	/// If is post
	if ( is_single() )
	{
		/// If is logged in user
		if (  get_current_user_id() )
		{

			$userdata = get_userdata( get_current_user_id() ) ;
			
			/// If user has orders
			if ( $userdata->allcaps['read'] == 1)
			{
				/// Get order details
				$customer_orders = get_posts( array (
					'meta_key'    => '_customer_user',
					'meta_value'  => get_current_user_id(),
					'post_type'   => wc_get_order_types(),
					'post_status' => array_keys( wc_get_order_statuses() ),
				) );

				foreach ( $customer_orders  as $order )
				{
					$datetime1 = new DateTime('now');
					$datetime2 = new DateTime($order->post_date);
					$interval = date_diff($datetime2, $datetime1);
					$date_diff = $interval->format('%R%a');
					echo '<!-- dur : ' . $date_diff ."/n" . '-->';
					if (  $date_diff < $product_duration ) 
					{
						$has_access = true;
					}
					else
					{
						/// $has_access = false;
					}
				}
			}
			else
			{
				/// $has_access = false;
			}
		}
		/// If no registered user
		else if ( empty ( get_current_user_id() ) )
		{
			/// $has_access = false;
		}
	}
	else
	{
		/// Allow : homepage, blogpage, pages, categories, tags, years, months, days, authors, archives
		$has_access = true;
	}

	/// return $has_access; 
	if ( $has_access )
	{
		/// Loads post
	}
	else
	{
		$to_URL = get_permalink( wc_get_page_id( 'shop' ) ) ?: get_home_url();
		wp_redirect ( $to_URL );
	}
}
add_action('wp_head', 'premium_access');