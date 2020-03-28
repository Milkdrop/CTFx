<?php

require('../include/mellivora.inc.php');

validate_id(array_get($_GET, 'id'));

head(lang_get('user_details'));

if (cache_start(CONST_CACHE_NAME_USER . $_GET['id'], Config::get('MELLIVORA_CONFIG_CACHE_TIME_USER'))) {

    $user = db_query_fetch_one('
        SELECT
            u.team_name,
            u.email,
            u.competing,
            co.country_name,
            co.country_code,
            SUM(c.points) AS score
        FROM users AS u
        LEFT JOIN countries AS co ON co.id = u.country_id
        LEFT JOIN submissions AS s ON u.id = s.user_id AND s.correct = 1
        LEFT JOIN challenges AS c ON c.id = s.challenge
        WHERE
          u.id = :user_id',
        array('user_id' => $_GET['id'])
    );

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
        SELECT SUM(c.points) AS points
        FROM challenges AS c
        WHERE c.exposed = 1')["points"];

    if (empty ($totalPoints) || !ctfStarted ())
        $totalPoints = 0;
    
    $avatarURL = "https://www.gravatar.com/avatar/" . md5 ($user["email"]) . "?s=128&d=mp";

    echo '<div class="user-profile">
        <div class="user-image" style="background-image:url(\'', htmlspecialchars ($avatarURL), '\')"></div>',
        '<div class="user-description">
            <h2>', htmlspecialchars ($user["team_name"]), country_flag_link($user['country_name'], $user['country_code'], true), '</h2>
            <h4><b>', $user["score"], '</b><small>/', $totalPoints, ' Points</small></h4>';

    if (!$user['competing']) {
        spacer ();
        message_inline(lang_get('non_competing_user'));
    }

    echo '</div>
    </div>';

    if (ctfStarted ()) {
        print_solved_graph($_GET['id']);
        print_solved_challenges($_GET['id']);
    }
    
    cache_end(CONST_CACHE_NAME_USER . $_GET['id']);
}

foot();