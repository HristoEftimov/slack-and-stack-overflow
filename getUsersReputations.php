<?php

	// Include the external files
	include 'functions.php';

	// Check the request type argument
	$request_type = getRequestType();

	// Get the API filter property
	$filter = getAPIFilterProperty($request_type);

	// Build the URL to stack overflow API
	$stack_url = 'https://api.stackexchange.com/2.2/users/'. $users_ids_string .'/?site=stackoverflow&filter='. $filter;

	// Execute cURL: get the users reputations
	$result = cURLExecute($stack_url);

	// Loop the reputations
	if (sizeof($result->items) > 0) {

		$message = ':star: Reputation Standing :star:';
		$team_reputation = 0;

		// Loop all users and their reputation
		foreach ($result->items as $value) {

			// Build the message for a user
			$message .= getUserReputationMsgByRequestType($value, $request_type);

			// Count the team reputation
			$team_reputation += $value->reputation;
		}

		// Add the team reputation to the message
		$message .= "\n\n". "*Team Reputation:* " . $team_reputation;

		// Post the slack message
		postSlackMessage($message);
	}


	/**
	 * Returns the request type
	 * It can be: weekly, monthly or none
	 * @return string
	 */
	function getRequestType() {

		$type = 'none';

		// Parse the arguments from the cron job request
		if (isset($_SERVER['argv']) )
		{
			parse_str($_SERVER['argv'][0], $args);
			$type = $args['type'];
		}

		// Parse the arguments from the slack slash command
		if (isset($_POST['text']) )
		{
			$type = $_POST['text'];
		}

		return $type;
	}


	/**
	 * Returns the filter property for the API call
	 * @param request type
	 * @return string
	 */
	function getAPIFilterProperty($request_type) {
		switch ($request_type) {
			case 'weekly':
				$filter = '!75UlFOtr1xxIs93ToJi4jwA';
				break;
			case 'monthly':
				$filter = '!75U7pa*xrjkt0Qjet3ghrRA';
				break;
			case 'none':
				$filter = '!qGQfps)LArTmA5VyL1jE';
				break;
		}

		return $filter;
	}

	/**
	 * Returns the filter property for the API call
	 * @param request type
	 * @return string
	 */
	function getUserReputationMsgByRequestType($value, $request_type) {

		// Basic message
		$message = "\n". '*' .$value->reputation. ' reputation* - '. getUserDisplayName($value->user_id). ' ';

		switch ($request_type) {
			case 'weekly':
				$message .= '_('. $value->reputation_change_week .' this week )_';
				break;
			case 'monthly':
				$message .= '_('. $value->reputation_change_month .' this month )_';
				break;
			case 'none':
				break;
		}

		return $message;
	}