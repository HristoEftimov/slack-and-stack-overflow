<?php

	// Include the external files
	include 'functions.php';

	// Get the users reputations
	$stack_url = 'https://api.stackexchange.com/2.2/users/'. $users_ids_string .'/?site=stackoverflow&filter=!40D.p(1f74CtdIRr7';
	$string  = curl_init($stack_url);
	curl_setopt($string, CURLOPT_ENCODING, 'gzip');  // Required by API
	curl_setopt($string, CURLOPT_RETURNTRANSFER, 1 );

	// Result object
	$json = curl_exec($string );
	$result = json_decode($json);

	// Close the cURL
	curl_close($string);

	// Loop the reputations
	if (sizeof($result->items) > 0) {

		$message = ':star: Reputation Standing :star:';

		// List all users and their reputation
		foreach ($result->items as $value) {
			$message .= "\n". '*'. $value->reputation .' reputation* - ' . getUserDisplayName($value->user_id);
		}

		// Post the slack message
		postSlackMessage($message);
	}