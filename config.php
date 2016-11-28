<?php

	$config = array(
		slack => array(

			// Webhook
			slack_webhook_url => "https://hooks.slack.com/services/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",

			// Channel
			channel => "stackoverflow"
		),

		// The cron job interval ( in minutes )
		cronJobInterval => 50,

		// Include timeline activities
		timeline => array(
			type => array(
				'accepted' => true,
				'asked' => true,
				'answered' => true,
				'suggested' => true,
				'commented' => false,
				'badge' => true,
				'reviewed' => true,
				'revision' => true
			)
		)
	);

	// Array with all subscribed users
	$users = array(
		'2765346' => array(
			name =>'Hristo Eftimov'
		),
		'1333836' => array(
			name =>'Kaloyan Kosev'
		),
		'4312466' => array(
			name =>'Jordan Enev'
		)
	);