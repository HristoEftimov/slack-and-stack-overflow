<?php

	// Include the external files
	include '.config.php';



	/**
	 * Global variables
	 **/

	// Array of all users ids
	$users_ids_arrays = array_keys($users);

	// String of all users ids separated with semicolon
	$users_ids_string = join(';', $users_ids_arrays);



	/**
	 * Global Functions
	 **/

	/**
	 * Returns the user's display name
	 * @param $user_id
	 * @return string
	 */
	function getUserDisplayName($user_id) {
		global $users;

		return $users[$user_id]['name'];
	}

	/**
	 * Send a slack message
	 * @param $message
	 */
	function postSlackMessage($message) {
		global $config;

		// Build the data
		$data = array(
			"channel" => $config['slack']['channel'],
			"text" => $message,
			"mrkdwn" => true,
		);

		// Convert the data to a json
		$json_string = json_encode($data);

		// Send the post request to slack webhook
		$slack_call = curl_init($config['slack']['slack_webhook_url']);
		curl_setopt($slack_call, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($slack_call, CURLOPT_POSTFIELDS, $json_string);
		curl_setopt($slack_call, CURLOPT_CRLF, true);
		curl_setopt($slack_call, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($slack_call, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"Content-Length: " . strlen($json_string))
		);
		$slack_result = curl_exec($slack_call);
		curl_close($slack_call);
	}