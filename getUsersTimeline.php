<?php

	// Include the external files
	include 'functions.php';
	include 'badge.php';

	// Set time zone about
	// Necessary about datetime functions
	date_default_timezone_set('Europe/Sofia');

	// Get the users timeline data
	$stack_url = 'https://api.stackexchange.com/2.2/users/'. $users_ids_string .'/timeline?site=stackoverflow';
	$string  = curl_init($stack_url);
	curl_setopt($string, CURLOPT_ENCODING, 'gzip');  // Required by API
	curl_setopt($string, CURLOPT_RETURNTRANSFER, 1 );

	// Result object
	$json = curl_exec($string );
	$result = json_decode($json);

	// Close the cURL
	curl_close($string);

	// Loop the activities
	if (sizeof($result->items) > 0) {
		foreach ($result->items as $value) {

			$message = '';
			$userDisplayName = getUserDisplayName($value->user_id);

			// Get the current date and time
			$currentDate = new DateTime();

			// Get the activity date and time
			$activityDate = new DateTime( date("Y-m-d H:i:s", $value->creation_date) );

			// Calculate the difference in minutes
			$diffMinutes = round(($currentDate->format('U') - $activityDate->format('U')) / (60));

			// Get the activities after the last cron job
			if ($diffMinutes <= $config['cronJobInterval']) {
			// if (true) {

				// List the timeline type if it is set as true
				if ($config['timeline']['type'][$value->timeline_type]) {

					// Show the correct message by the timeline type
					switch ($value->timeline_type) {
						case 'accepted':
							$message = $userDisplayName . ' accepted an answer <http://stackoverflow.com/questions/'. $value->post_id .'|'. $value->title .'>';
							break;
						case 'asked':
							$message = $userDisplayName . ' asked a question <http://stackoverflow.com/questions/'. $value->post_id .'|'. $value->title .'>';
							break;
						case 'answered':
							$message = $userDisplayName . ' ' . $value->timeline_type . ' on post <http://stackoverflow.com/questions/'. $value->post_id .'|'. $value->title .'>';
							break;
						case 'suggested':
							$message = $userDisplayName . ' ' . $value->timeline_type . ' an edit on <http://stackoverflow.com/questions/'. $value->post_id .'|'. $value->title .'>';
							break;
						case 'commented':
							$message = $userDisplayName . ' ' . $value->timeline_type . ' on <http://stackoverflow.com/questions/'. $value->post_id .'|'. $value->title .'>';
							break;
						case 'badge':
							$message = getBadgeDetailsMessage($value->badge_id, $userDisplayName);
							break;
						case 'reviewed':
							$message = $userDisplayName . ' reviewed a suggested edit <http://stackoverflow.com/questions/'. $value->post_id .'|'. $value->title .'>';
							break;
						case 'revision':
							$message = $userDisplayName . ' edited a post <http://stackoverflow.com/questions/'. $value->post_id .'|'. $value->title .'>';
							break;
					}
				}

				// Post the slack message
				postSlackMessage($message);
			}
		}
	}
