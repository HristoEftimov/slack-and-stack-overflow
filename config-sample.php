<?php

	$config = array(
		slack => array(

			// Webhook
			slack_webhook_url => "YOUR_SLACK_WEBHOOK",

			// Channel
			channel => "YOUR_SLACK_CHANNEL"
		),

		// The cron job interval ( in minutes )
		cronJobInterval => 30
	);

	// Array with all subscribed users
	$users = array(
		'USER_ID' => array(
			name =>'USER_DISPLAY_NAME'
		),
		'USER_ID' => array(
			name =>'USER_DISPLAY_NAME'
		)
	);