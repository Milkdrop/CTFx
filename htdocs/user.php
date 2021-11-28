<?php

require('../include/ctfx.inc.php');

validate_id(array_get($_GET, 'id'));

head(lang_get('user_details'));

if (cache_start(CONST_CACHE_NAME_USER . $_GET['id'], Config::get('MELLIVORA_CONFIG_CACHE_TIME_USER'))) {


    if (empty($user)) {
        message_generic(
            lang_get('sorry'),
            lang_get('no_user_found'),
            false
        );
    }

    if (!isset ($user['score']))
        $user['score'] = 0;

    $totalPoints = db_query_fetch_one ('
        SELECT COALESCE(SUM(c.points),0) AS points
        FROM challenges AS c
        WHERE c.exposed = 1')["points"];

    if (empty ($totalPoints) || !ctf_started ())
        $totalPoints = 0;
    
    $avatarURL = "https://www.gravatar.com/avatar/" . md5 ($user["email"]) . "?s=128&d=mp";

    echo '<div class="user-profile">
        <div class="user-image" style="background-image:url(\'', htmlspecialchars ($avatarURL), '\')"></div>',
        '<div class="user-description">
            <h2>', htmlspecialchars ($user["team_name"]), country_flag_link($user['country_name'], $user['country_code'], true), '</h2>
            <h4><b>', $user["score"], '</b><small>/', $totalPoints, ' Points</small></h4>';
    
    $userAchievements = $user["achievements"];
    if (Config::get("MELLIVORA_CONFIG_SHOW_ACHIEVEMENTS") && $userAchievements != 0) {
        echo '<b>Achievements:</b><br>';

        for ($i = 0; $i < count(CONST_ACHIEVEMENTS); $i++) {
            if ($userAchievements & (1 << $i)) {
                $achievement = CONST_ACHIEVEMENTS[$i];
                echo '<img class="achievement has-tooltip" data-toggle="tooltip" data-html="true" data-placement="top" title="<b>' . $achievement["title"] . '</b><br>' .
                $achievement["description"] . '" src="'.Config::get('URL_STATIC_RESOURCES').'/img/achievements/' . $achievement["icon"] . '">';
            }
        }
    }

    echo '</div>
    </div>';

    if (!$user['competing']) {
        spacer ();
        message_inline(lang_get('non_competing_user'));
    }

    if (ctf_started ()) {
        print_solved_graph($_GET['id']);
        print_solved_challenges($_GET['id']);
    }
    
    cache_end(CONST_CACHE_NAME_USER . $_GET['id']);
}

foot();