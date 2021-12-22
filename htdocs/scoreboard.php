<?php

require('../include/ctfx.inc.php');

head('Scoreboard');

if (cache_start('scoreboard', Config::get('CACHE_TIME_CHALLENGE'))) {
    $now = time();

    $scores = api_get_scores();
    
    if (empty($scores)) {
        die_with_message("No teams");
    }

    $top3 = [1, 0, 2];
    $widths = [128, 196, 96];
  
    echo '<div style="display:flex; justify-content:center; align-items:end">';
  
    for ($i = 0; $i < 3; $i++) {
        $team = $scores[$top3[$i]];
  
        if (!isset($team)) {
            continue;
        }

        $avatar = "https://www.gravatar.com/avatar/" . md5($team["email"]) . "?s=256&d=mp";
        
        echo '<div>
            <a href="/user?id=' . $team['user_id'] . '">
            <img style="width:' . $widths[$i] . 'px; margin:0px 4px" src="' . $avatar . '">
            ' . tooltip('<div class="scoreboard-team-name" style=" max-width:'. $widths[$i] .'px">' . ($top3[$i] + 1) . '. <span>' . htmlspecialchars($team['team_name']) . '</span></div>', $team['team_name']) . '
            </a>
        </div>';
    }

    echo '</div><br>
    <div class="scoreboard">';
  
    $maxScore = $scores[0]['score'];
    if ($maxScore == 0) {
        $maxScore = 1;
    }

    $i = 1;
    foreach ($scores as $team) {
        echo '<div class="scoreboard-entry">
        <div style="margin-right:4px">' . $i . '.</div>
        <a class="scoreboard-team-name" href="user?id=' . $team['user_id'] . '">' . htmlspecialchars($team['team_name']) . '</a>
        ' . tooltip('<img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/flags/' . htmlspecialchars($team['country_code']) . '.png">', $team['country_name']) . '
        <div class="scoreboard-score"><div class="scoreboard-fill ' . (($i <= 3)?('scoreboard-fill-position-' . $i):'') . '" style="width:' . max(($team['score'] * 100) / $maxScore, 0) . '%"><div style="margin-left:8px">' . $team['score'] . ' Points</div></div></div>
        </div>';

        $i++;
    }

    echo '</div>';

    cache_end();
}

foot();
