<?php

require('../include/mellivora.inc.php');

enforce_authentication();

head('Challenges');

if (isset($_GET['status'])) {
    if ($_GET['status']=='correct') {
        message_dialog('Congratulations! You got the flag!', 'Correct flag', 'Lovely', 'challenge-attempt correct on-page-load form-group');
    } else if ($_GET['status']=='incorrect') {
        message_dialog('Sorry! That wasn\'t correct.', 'Incorrect flag', 'Ok', 'challenge-attempt incorrect on-page-load form-group', '4');
    }
}

$categories = sql_get_categories();

// Determine which category to display
if (isset($_GET['category'])) {
    if (is_valid_id($_GET['category'])) {
        $current_category = array_search_matching_key($_GET['category'], $categories, 'id');
    } else {
        $current_category = array_search_matching_key($_GET['category'], $categories, 'title', 'to_permalink');
    }

    if (!$current_category) {
        redirect('challenges');
    }

} else {
    $current_category = array_search_matching_key(Config::get('DEFAULT_CATEGORY_ON_CHALLENGES_PAGE'), $categories, 'id');

    if (!$current_category) {
        $current_category = $categories[0];
    }
}

// Determine challenge visibility
if (!ctf_started()) {
    if (user_is_staff()) {
        echo message_inline("CTF has not started yet, so only admins can see the challenges.");
    } else {
        die_with_message("No challenges yet", "CTF will start in&nbsp;" . timestamp(Config::get('CTF_START_TIME')), false);
    }
}

if (!$current_category) {
    die_with_message("No challenges yet");
}

// Write category title
echo '<div class="pre-category-name">Challenge category:</div>
<div class="category-name typewriter">' . $current_category['title'] . '</div>';

// Write categories selector
echo '<div style="display:flex; flex-wrap:wrap">' . decorator_square("arrow.png", "270deg", "#FCDC4D", true);

foreach ($categories as $cat) {
    echo '<a class="btn-solid ' . ($current_category['id'] == $cat['id'] ? 'active' : '')
        . '" href="challenges?category=' . htmlspecialchars(to_permalink($cat['title'])) . '">'
        . htmlspecialchars($cat['title'])
    . '</a>';
}

echo '</div>';

// Write category description
if (!empty($current_category['description']))
    echo '<div class="category-description"><img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/info.png"><div>' . get_bbcode()->parse($current_category['description']) . '</div></div>';
else
    echo '<div style="margin-bottom: 8px"></div>';

// Write challenges
$challenges = sql_get_challenges_for_category($current_category['id'], $_SESSION['id']);

foreach($challenges as $challenge) {

    $title = '<div style="display:flex"><a href="challenge?id=' . $challenge['id'] . '">' . htmlspecialchars($challenge['title']) . '</a>
        <div class="challenge-points">
            <img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/flag.png">
            ' . $challenge['points'] . ' Points
        </div>
    </div>';

    $side_header = timestamp($item['added'], 'left') . '<img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/clock.png">';
    
    // if this challenge relies on another being solved, get the related information
    if (!empty($challenge['relies_on'])) {
        $relies_on = sql_get_challenge_data($challenge['relies_on'], $_SESSION['id']);
        
        $relies_decorator = decorator_square("hand.png", "270deg", "#E06552", true, true, 24);

        if (!empty($relies_on)) {
            $relies_on_category_name = "";
            foreach ($categories as $category) {
                if ($category['id'] == $relies_on['category']) {
                    $relies_on_category_name = $category['title'];
                }
            }

            $content = '<div class="section-header" style="margin-bottom: 0px">'
                . $relies_decorator
                . '<div>To see this challenge, you must first solve&nbsp;<a href="challenge?id=' . $relies_on['id'] . '">'
                . htmlspecialchars($relies_on['title']) . '</a>&nbsp;from&nbsp;<a href="challenges?category=' . $relies_on['category'] . '">' . htmlspecialchars($relies_on_category_name) . '</a></div></div>';
        } else {
            $content = '<div class="section-header" style="margin-bottom: 0px">'
                . $relies_decorator
                . 'This challenge relies on an inexistent challenge.
                This should never happen.
                Message an admin!
            </div>';
        }
    } else {
        $content = get_bbcode()->parse($challenge['description']);

        if ($challenge['solve_position'] == 0) {
            $content .= '<form class="form-one-line" style="margin-top: 8px" method="post" action="actions/challenges">
                <input name="flag" type="text" placeholder="Input flag" required></input>
                <input type="hidden" name="challenge" value="' . $challenge['id'] . '" />
                <input type="hidden" name="action" value="submit_flag" />';
    
            form_xsrf_token();
    
            if (Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PRIVATE')) {
                display_captcha();
            }
    
            $content .= '<button class="btn-" type="submit">Submit</button>';
            $content .= '</form>';
    
            if (user_is_staff() && ($challenge['exposed'] == 0)) {
                message_inline("This challenge is hidden from normal users");
            }
        }
    }

    echo card($title, '', $content, '');
}

foot();