<?php


function frcaptcha_verify_captcha_solution($solution, $sitekey, $api_key) {
        $endpoint = 'https://friendlycaptcha.com/api/v1/siteverify';

        $response_body = array(
            'secret' => $api_key,
            'sitekey' => $sitekey,
            'solution' => $solution,
        );

        $body = json_encode($response_body);
		$request = array(
			'body' => $response_body,
        );

        
        $response = wp_remote_post( esc_url_raw( $endpoint ), $request );
        $status = wp_remote_retrieve_response_code( $response );

        // Useful for debugging
        // trigger_error($body);

        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );

		if ( 200 != $status ) {
			if ( WP_DEBUG ) {
                frcaptcha_log_remote_request( $endpoint, $request, $response );
                // error_log("The body was " . $body);
			}
            // Better safe than sorry, if the request is non-200 we can not verify the response
            // Either the user's credentials are wrong (e.g. wrong sitekey, api key) or the friendly
			// captcha servers are unresponsive.
			
			// TODO notify site admin somehow
			return array(
                "success" => true,
                "status" => $status,
                "errors" => array(),
                "response_body" => $response_body
            );
        }

		$success = isset( $response_body['success'] )
			? $response_body['success']
            : false;

        $errorCodes = isset( $response_body['errors'] )
			? reset($response_body['errors'])
			: array();


		return array(
            "success" => $success,
            "status" => $status,
            "error_codes" => $errorCodes,
            "response_body" => $response_body
        );
}