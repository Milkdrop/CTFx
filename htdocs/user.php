<?php

require('../include/ctfx.inc.php');

validate_id($_GET['id']);

head('User');

if (cache_start('user', Config::get('CACHE_TIME_USER'), $_GET['id'])) {
    $user = db_select_one(
        'users',
        array(
            'id',
            'email',
            'team_name',
            'competing',
            'country_id',
            'extra_points'
        ),
        array('id' => $_GET['id'])
    );

    if (empty($user)) {
        die_with_message_error('No such user');
    }

    if (ctf_started()) {
        $challenges = db_query_fetch_all('
            SELECT c.id, c.title, c.category, c.points, c.release_time, ca.title AS category_title, s.added AS solve_timestamp, s.solve_position
            FROM challenges AS c
            LEFT JOIN categories as ca ON ca.id = c.category
            LEFT JOIN submissions AS s ON s.challenge = c.id AND s.user_id = :user_id
            WHERE c.exposed = 1 AND s.correct = 1
            ORDER BY c.category ASC, c.id ASC',
            array(
                'user_id' => $user['id']
            )
        );
    } else {
        $challenges = array();
    }

    $country = db_select_one(
        'countries',
        array('country_name','country_code'),
        array('id' => $user['country_id'])
    );

    $points = $user['extra_points'];

    foreach ($challenges as $challenge) {
        $points += $challenge['points'];
    }
    
    $avatar_url = "https://www.gravatar.com/avatar/" . md5($user["email"]) . "?s=256&d=mp";
    
    echo '
    <div style="display:flex; margin-bottom: 16px">
    <img src="' . $avatar_url . '"/>
    <div style="margin-left:16px">
        <div class="pre-category-name">Team:</div>
        <div class="category-name">' . htmlspecialchars($user['team_name']) . '</div>
        <div style="display:flex">
        '
        . tag('<b>' . htmlspecialchars($country['country_name']) . '</b>', '../flags/' . htmlspecialchars($country['country_code']) . '.png', true, 'margin-right:8px')
        . tag('<b>' . $points . ' Points</b>', '', true)
        . '
        </div>
        ' . (($user['competing'] == 0)?message_inline('This user is not competing'):'') . '
    </div>
    </div>';

    if (count($challenges) > 0) {
        echo section_header('Solved challenges');
        echo '<table>
        <thead>
            <tr>
                <th style="flex-basis: 15%;">Category</th>
                <th style="flex-basis: 50%;">Challenge</th>
                <th style="flex-basis: 35%;">Solved</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($challenges as $challenge) {
            echo '<tr>
                <td style="flex-basis: 15%;"><a href="/challenges?category=' . $challenge['category'] . '">' . htmlspecialchars($challenge['category_title']) . '</a></td>

                <td style="flex-basis: 50%;"><a href="/challenge?id=' . $challenge['id'] . '">' . htmlspecialchars($challenge['title']) . '</a> (' . $challenge['points'] . ' Points)</td>
                <td style="flex-basis: 35%;">' . timestamp($challenge['solve_timestamp'], 'after release (solver #' . $challenge['solve_position'] . ')', $challenge['release_time'], true) . '</td>
            </tr>';
        }

        echo '</tbody>
        </table>';
    } else {
        echo message_inline('User hasn\'t solved any challenges');
    }
    
    cache_end();
}

foot();