<?php

require('../include/mellivora.inc.php');

enforce_authentication();

$now = time();

head('Challenges');

if (isset($_GET['status'])) {
    if ($_GET['status']=='correct') {
        message_dialog('Congratulations! You got the flag!', 'Correct flag', 'Lovely', 'challenge-attempt correct on-page-load form-group');
    } else if ($_GET['status']=='incorrect') {
        message_dialog('Sorry! That wasn\'t correct.', 'Incorrect flag', 'Ok', 'challenge-attempt incorrect on-page-load form-group', 'default');
    } else if ($_GET['status']=='manual') {
        message_dialog('Your submission is awaiting manual marking.', 'Manual marking', 'Ok', 'challenge-attempt manual on-page-load form-group', 'default');
    }
}

$categories = db_select_all(
    'categories',
    array(
        'id',
        'title',
        'description'
    ),
    array(
        'exposed'=>1
    ),
    'title ASC'
);

// determine which category to display
if (isset($_GET['category'])) {

    if (is_valid_id($_GET['category'])) {
        $current_category = array_search_matching_key(
            $_GET['category'],
            $categories,
            'id'
        );
    } else {
        $current_category = array_search_matching_key(
            $_GET['category'],
            $categories,
            'title',
            'to_permalink'
        );
    }

    if (!$current_category) {
        redirect('challenges');
    }

} else
    $current_category = $categories[0];

if (empty($current_category)) {
    message_generic('Challenges', 'Your CTF is looking a bit empty! Start by adding a category using the management console.');
}

// write category name
echo '<h3 style="font-size: 18px">Category: </h3><div id="category-name" class="typewriter">', $current_category['title'], '</div>';

// write out our categories menu
echo '<div id="categories-menu" class="menu">
', title_decorator ("green", "270deg");

foreach ($categories as $cat) {
    echo '<a class="btn btn-xs btn-2 ',($current_category['id'] == $cat['id'] ? 'active' : ''),'" href="/challenges?category=',htmlspecialchars(to_permalink($cat['title'])),'">',htmlspecialchars($cat['title']),'</a>';
}

echo '</div>';

// write out the category description, if one exists
if ($current_category['description']) {
    echo '<div id="category-description">', get_bbcode()->parse($current_category['description']), '</div>';
}

// get all the challenges for the selected category
$challenges = db_query_fetch_all('
    SELECT
       c.id,
       c.title,
       c.description,
       c.available_from,
       c.available_until,
       c.points,
       c.num_attempts_allowed,
       c.min_seconds_between_submissions,
       c.automark,
       c.relies_on,
       IF(c.automark = 1, 0, (SELECT ss.id FROM submissions AS ss WHERE ss.challenge = c.id AND ss.user_id = :user_id_1 AND ss.marked = 0)) AS unmarked, -- a submission is waiting to be marked
       (SELECT ss.added FROM submissions AS ss WHERE ss.challenge = c.id AND ss.user_id = :user_id_2 AND ss.correct = 1) AS correct_submission_added, -- a correct submission has been made
       (SELECT COUNT(*) FROM submissions AS ss WHERE ss.challenge = c.id AND ss.user_id = :user_id_3) AS num_submissions, -- number of submissions made
       (SELECT max(ss.added) FROM submissions AS ss WHERE ss.challenge = c.id AND ss.user_id = :user_id_4) AS latest_submission_added
    FROM challenges AS c
    WHERE
       c.category = :category AND
       c.exposed = 1
    ORDER BY c.points ASC, c.id ASC',
    array(
        'user_id_1'=>$_SESSION['id'],
        'user_id_2'=>$_SESSION['id'],
        'user_id_3'=>$_SESSION['id'],
        'user_id_4'=>$_SESSION['id'],
        'category'=>$current_category['id']
    )
);

foreach($challenges as $challenge) {

    $has_remaining_submissions = has_remaining_submissions($challenge);

    // if the challenge isn't available yet, display a message and continue to next challenge
    if ($challenge['available_from'] > $now) {
        echo '
        <div class="ctfx-card">
            <div class="ctfx-card-head">
                <h4>Hidden challenge worth ', number_format($challenge['points']), 'pts</h4>
            </div>
            <div class="ctfx-card-body">
                Available in ',time_remaining($challenge['available_from']),' (from ', date_time($challenge['available_from']), ' until ', date_time($challenge['available_until']), ')
            </div>
        </div>';

        continue;
    }

    echo '
    <div class="', get_submission_box_class($challenge, $has_remaining_submissions), ' ctfx-card">
        <div class="ctfx-card-head">
            <h4><a href="challenge?id=',htmlspecialchars($challenge['id']),'">',htmlspecialchars($challenge['title']), '</a> <small>', number_format($challenge['points']), ' Points</small>';

            if ($challenge['correct_submission_added']) {
                $solve_position = db_query_fetch_one('
                    SELECT
                      COUNT(*)+1 AS pos
                    FROM
                      submissions AS s
                    WHERE
                      s.correct = 1 AND
                      s.added < :correct_submission_added AND
                      s.challenge = :challenge_id',
                    array(
                        'correct_submission_added'=>$challenge['correct_submission_added'],
                        'challenge_id'=>$challenge['id']
                    )
                );

                echo ' ', get_position_medal($solve_position['pos']);
            }

    echo '</h4>';

    if ($challenge['correct_submission_added']) {
        echo '<div class="challenge-solved-icon"></div><div class="challenge-solved-text">SOLVED</div>';
    }

    if (should_print_metadata($challenge)) {
        print_time_left_tooltip($challenge);
    }

    echo '</div>';

    unset($relies_on);

    // if this challenge relies on another being solved, get the related information
    if ($challenge['relies_on']) {
        $relies_on = db_query_fetch_one('
            SELECT
              c.id,
              c.title,
              cat.id AS category_id,
              cat.title AS category_title,
              s.correct AS has_solved_requirement
            FROM
              challenges AS c
            LEFT JOIN categories AS cat ON cat.id = c.category
            LEFT JOIN submissions AS s ON s.challenge = c.id AND s.correct = 1 AND s.user_id = :user_id
            WHERE
              c.id = :relies_on',
            array(
                'user_id'=>$_SESSION['id'],
                'relies_on'=>$challenge['relies_on']
            )
        );
    }

    echo '<div class="ctfx-card-body">
    <div class="challenge-description">';

    // if this challenge relies on another, and the user hasn't solved that requirement
    if (isset($relies_on) && !$relies_on['has_solved_requirement']) {
        echo '<span class="glyphicon glyphicon-lock"></span> ',
                lang_get(
                    'challenge_relies_on',
                    array(
                        'relies_on_link' => '<a href="challenge?id='.htmlspecialchars($relies_on['id']).'">'.htmlspecialchars($relies_on['title']).'</a>',
                        'relies_on_category_link' => '<a href="challenges?category='.htmlspecialchars($relies_on['category_id']).'">'.htmlspecialchars($relies_on['category_title']).'</a>'
                    )
                )
            ,'</div>';
    }

    // this challenge either does not have a requirement, or has a requirement that has already been solved
    else {
        // write out challenge description
        if ($challenge['description']) {
            echo get_bbcode()->parse($challenge['description']);
        }

        echo "</div>";

        print_challenge_files(get_challenge_files($challenge));
        print_hints($challenge);

        // only show the hints and flag submission form if we're not already correct and if the challenge hasn't expired
        if (!$challenge['correct_submission_added'] && $challenge['available_until'] > $now) {

            // if we have already made a submission to a manually marked challenge
            if ($challenge['num_submissions'] && !$challenge['automark'] && $challenge['unmarked']) {
                message_inline('Your submission is awaiting manual marking.');
            }

            // if we have remaining submissions, print the submission form
            else if ($has_remaining_submissions) {
                echo '<div class="challenge-submit">
                    <form method="post" class="form-flag" action="actions/challenges">
                        <input name="flag" id="flag-input-'.htmlspecialchars($challenge['id']).'" type="text" class="flag-input form-control form-group" placeholder="Please enter flag for challenge: ',htmlspecialchars($challenge['title']),'"></input>
                        <input type="hidden" name="challenge" value="',htmlspecialchars($challenge['id']),'" />
                        <input type="hidden" name="action" value="submit_flag" />';

                form_xsrf_token();

                if (Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PRIVATE')) {
                    display_captcha();
                }

                echo '<button id="flag-submit-',htmlspecialchars($challenge['id']),'" class="btn btn-lg btn-1 flag-submit-button" type="submit" data-countdown="',max($challenge['latest_submission_added']+$challenge['min_seconds_between_submissions'], 0),'" data-countdown-done="Submit flag">Submit flag</button>';

                if (should_print_metadata($challenge)) {
                    echo '<div class="challenge-submit-metadata">';
                    print_submit_metadata($challenge);
                    echo '</div>';
                }

                echo '</form>';
                echo '</div>';

            }
            // no remaining submission attempts
            else {
                message_inline("You have no remaining submission attempts. If you've made an erroneous submission, please contact the organizers.");
            }
        }
    }

    echo '</div>
    </div> <!-- / challenge-container -->';
}

foot();