<?php

require('../include/ctfx.inc.php');

validate_id($_GET['id']);

head('Challenge');

if (cache_start('challenge', Config::get('CACHE_TIME_CHALLENGE'), $_GET['id'])) {

    $challenge = api_get_challenge_info($_GET['id']);
    
    if (empty($challenge) || (!ctf_started())) {
        die_with_message("No such challenge");
    }

    $submissions = get_submissions_for_challenge($challenge['id']);

    $correct_submissions = [];
    foreach ($submissions as $submission) {
        if ($submission['has_solve'] == 1) {
            array_push($correct_submissions, $submission);
        }
    }

    $solve_percentage = number_format(((count($correct_submissions) / get_num_participating_users()) * 100), 1);

    echo '<div class="pre-category-name">Challenge:</div>
    <div style="font-size:48px" class="category-name">' . htmlspecialchars($challenge['title']) . '</div>
    <div style="display:flex; margin-top:8px">'
    . tag('<b>' . $challenge['points'] . ' Points</b>', "flag.png", true, 'margin-right:8px')
    . tag('<b>' . $challenge['solves'] . ' Solves (' . $solve_percentage . '% of users)</b>', "check.png", true, 'margin-right:8px')
    . '</div>
    ';

    if (count($correct_submissions) == 0) {
        die_with_message('No solves.');
    } else {
        echo section_header('Solvers');
        echo '<table>
        <thead>
            <tr>
                <th style="flex-basis: 10%;">Position</th>
                <th style="flex-basis: 55%;">Team</th>
                <th style="flex-basis: 35%;">Solved</th>
            </tr>
        </thead>
        <tbody>';

        $i = 1;
        foreach ($correct_submissions as $submission) {
            echo '<tr>
                <td style="flex-basis: 10%;">' . $i . '</td>
                <td style="flex-basis: 55%; display: inline; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;"><a href="user.php?id=', htmlspecialchars($submission['user_id']), '">' . htmlspecialchars($submission['team_name']) . '</a></td>
                <td style="flex-basis: 35%;">' . timestamp($submission['solve_timestamp'], 'after release (' . htmlspecialchars($submission['tries']) . ' tries)', $challenge['release_time'], true) . '</td>
              </tr>
              ';
            $i++;
        }

        echo '</tbody>
       </table>
         ';
    }

    cache_end();
}

foot();