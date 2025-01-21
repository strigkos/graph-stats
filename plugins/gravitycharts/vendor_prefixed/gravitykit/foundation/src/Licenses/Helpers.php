<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\Foundation\Licenses;

use Exception;

class Helpers {
	/**
	 * Performs remote call to GravityKit's EDD API.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url API URL.
	 * @param array  $args Request arguments.
	 *
	 * @throws Exception
	 *
	 * @return array|null Response body.
	 */
	public static function query_api( $url, array $args = [] ) {
		$request_parameters = [
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $args,
		];

		$response = wp_remote_post(
			$url,
			$request_parameters
		);

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $response );

		$response = json_decode( $body, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			throw new Exception( esc_html__( 'Unable to process remote request. Invalid response body.', 'gk-gravitycharts' ) );
		}

		return $response;
	}
}
