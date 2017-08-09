<?php

	function getBadgeDetailsMessage($badge_id, $userDisplayName) {

		$get_badge_url = 'https://api.stackexchange.com/2.2/badges/'. $badge_id .'?site=stackoverflow';
		
		// Execute cURL: get the badge details
		$result = cURLExecute($get_badge_url);
		
		if (sizeof($result->items) > 0) {

			foreach ($result->items as $badge) {
				return $userDisplayName . ' earned a new '. $badge->rank.' badge: <'. $badge->link .'|'. $badge->name .'>. Congrats buddy :slightly_smiling_face:';
			}
		}
	}
