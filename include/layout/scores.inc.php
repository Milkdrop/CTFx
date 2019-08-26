<?php

function scoreboard ($scores) {

    echo '
    <table class="team-table table table-striped table-hover">
      <tbody>
     ';

    //$fd = fopen("/var/www/ctfx/include/layout/custom_scores.json", "r") or die ("unable to open json scoreboard");
    //$scores = json_decode (fread($fd, filesize ("/var/www/ctfx/include/layout/custom_scores.json")), true);

    $i = 1;
    $maxScore = $scores[0]['score'];

    foreach($scores as $score) {

        echo '<tr>
          <td class="team-name">
            <p class="team-number" style="', ($i <= 3) ? 'color: #42a0ff' : '','">',number_format($i++),'.</p>
            <a href="user?id=',htmlspecialchars($score['user_id']),'">
              <span class="team_',htmlspecialchars($score['user_id']),'">
                ',htmlspecialchars($score['team_name']),'
              </span>
            </a>
          </td>
          <td class="team-flag">',country_flag_link($score['country_name'], $score['country_code']),'</td>
          <td class="team-progress-bar">',progress_bar(($score['score'] / $maxScore) * 100, false, false),'</td>
          <td class="team-score">',number_format($score['score']),' Points</td>
        </tr>
        ';
    }

    echo '
      </tbody>
    </table>
    ';
}

function challenges($categories) {
    $now = time();
    $num_participating_users = get_num_participating_users();

    foreach($categories as $category) {

        echo '
        <table class="team-table table table-striped table-hover">
          <thead>
            <tr>
              <th>',htmlspecialchars($category['title']),'</th>
              <th class="center">',lang_get('points'),'</th>
              <th class="center"><span class="has-tooltip" data-toggle="tooltip" data-placement="top" title="% of actively participating users">',lang_get('percentage_solvers'),'</span></th>
              <th>',lang_get('first_solvers'),'</th>
            </tr>
          </thead>
          <tbody>
         ';

        $challenges = db_query_fetch_all('
            SELECT
               id,
               title,
               points,
               available_from
            FROM challenges
            WHERE
              available_from < '.$now.' AND
              category = :category AND
              exposed = 1
            ORDER BY points ASC',
            array(
                'category'=>$category['id']
            )
        );

        foreach($challenges as $challenge) {

            $num_solvers = db_count_num(
                'submissions',
                array(
                    'correct' => 1,
                    'challenge' => $challenge['id']
                )
            );

            echo '
            <tr>
                <td>
                    <a href="challenge?id=',htmlspecialchars($challenge['id']),'">',htmlspecialchars($challenge['title']),'</a>
                </td>

                <td class="center">
                    ',number_format($challenge['points']),'
                </td>

                <td class="center">
                    ',number_format($num_participating_users ? ($num_solvers / $num_participating_users) * 100 : 0),'%
                </td>

                <td class="team-name">';

            $users = db_query_fetch_all('
                SELECT
                   u.id,
                   u.team_name
                FROM users AS u
                JOIN submissions AS s ON s.user_id = u.id
                WHERE
                   u.competing = 1 AND
                   s.correct = 1 AND
                   s.challenge = :challenge
                ORDER BY s.added ASC
                LIMIT 3',
                array(
                    'challenge'=>$challenge['id']
                )
            );

            if (count($users)) {
                $pos = 1;
                foreach($users as $user) {
                    echo get_position_medal($pos++),
                    '<a href="user?id=',htmlspecialchars($user['id']),'">',htmlspecialchars($user['team_name']), '</a><br />';
                }
            }

            else {
                echo '<i>',lang_get('unsolved'),'</i>';
            }

            echo '
                </td>
            </tr>';
        }
        echo '
        </tbody>
        </table>';
    }
}

function get_position_medal ($position, $return_pos = false) {
    switch ($position) {
        case 1:
            return '<img src="'.Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES').'img/award_star_gold_3.png" class="has-tooltip" data-toggle="tooltip" data-placement="right" title="'.lang_get('challenge_solved_first').'" alt="'.lang_get('challenge_solved_first').'" />';
        case 2:
            return '<img src="'.Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES').'img/award_star_silver_3.png" class="has-tooltip" data-toggle="tooltip" data-placement="right" title="'.lang_get('challenge_solved_second').'" alt="'.lang_get('challenge_solved_second').'" />';
        case 3:
            return '<img src="'.Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES').'img/award_star_bronze_3.png" class="has-tooltip" data-toggle="tooltip" data-placement="right" title="'.lang_get('challenge_solved_third').'" alt="'.lang_get('challenge_solved_third').'" />';
    }

    if ($return_pos) {
        return '#'.$position.', ';
    }

    return '';
}