<?php

// Removable code that's for achievements is marked with an ACHIEVEMENT-CODE comment

const CONST_ACHIEVEMENTS = [
];

function add_achievement($achievementID) {
	$userAchievements = db_select_one('users', array('achievements'), array('id' => $_SESSION['id']))['achievements'];
	db_update('users',array('achievements' => $userAchievements | (1 << $achievementID)),array('id'=>$_SESSION['id']));
}