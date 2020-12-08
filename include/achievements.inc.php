<?php

// Removable code that's for achievements is marked with an ACHIEVEMENT-CODE comment

const CONST_ACHIEVEMENTS = [
	[
		"icon" => "webmaster.png",
		"title" => "Webmaster",
		"description" => "Solved all Web challenges"
	],

	[
		"icon" => "godofsecrets.png",
		"title" => "God of secrets",
		"description" => "Solved all Cryptography challenges"
	],

	[
		"icon" => "krtwconnoisseur.png",
		"title" => ".kr and .tw connoisseur",
		"description" => "Solved all Binary Exploitation challenges"
	],

	[
		"icon" => "practicalproblems.png",
		"title" => "Practical problems",
		"description" => "Solved all Reverse Engineering challenges"
	],

	[
		"icon" => "jackofalltrades.png",
		"title" => "Jack of all trades",
		"description" => "Solved all Misc challenges"
	],

	[
		"icon" => "finderskeepers.png",
		"title" => "Finders Keepers",
		"description" => "Solved all Forensics challenges"
	],

	[
		"icon" => "ditto.png",
		"title" => "Ditto",
		"description" => "Solved all Emulation challenges"
	],

	[
		"icon" => "breakithammer.png",
		"title" => "If you can break it with a hammer...",
		"description" => "Solved all Hardware challenges"
	],

	[
		"icon" => "programming.png",
		"title" => "Oh, you know programming? Name every algorithm.",
		"description" => "Solved all PPC challenges"
	],

	[
		"icon" => "earlybird.png",
		"title" => "Early Bird",
		"description" => "Created the team before the competition started"
	],

	[
		"icon" => "hoarder.png",
		"title" => "Hoarder",
		"description" => "Solved 5 challenges in the span of 5 minutes"
	],

	[
		"icon" => "cheeser.png",
		"title" => "I swear it must be one of these",
		"description" => "Submitted 10 wrong flags for the same challenge"
	],

	[
		"icon" => "everypoint.png",
		"title" => "Every point counts",
		"description" => "Solved a challenge that's already at its minimum score"
	],

	[
		"icon" => "goodsamaritan.png",
		"title" => "Good Samaritan",
		"description" => "Helped the organizers fix an issue with the competition"
	]
];

function add_achievement($achievementID) {
	$userAchievements = db_select_one('users', array('achievements'), array('id' => $_SESSION['id']))['achievements'];
	db_update('users',array('achievements' => $userAchievements | (1 << $achievementID)),array('id'=>$_SESSION['id']));
}