<?php

require('../include/mellivora.inc.php');

enforce_authentication();

head('Challenges');

$categories = api_get_categories();

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
    echo '<a style="margin:0px 8px 8px 0px" class="btn-solid ' . ($current_category['id'] == $cat['id'] ? 'active' : '')
        . '" href="challenges?category=' . htmlspecialchars(to_permalink($cat['title'])) . '">'
        . htmlspecialchars($cat['title'])
    . '</a>';
}

echo '</div>';

// Write category description
if (!empty($current_category['description']))
    echo '<div class="category-description"><img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/info.png"><div>' . parse_markdown($current_category['description']) . '</div></div>';
else
    echo '<div style="margin-bottom: 8px"></div>';

// Write challenges
$challenges = api_get_challenges_from_category($current_category['id'], $_SESSION['id']);

foreach ($challenges as $challenge) {

    $title = '<div style="display:flex"><a href="challenge?id=' . $challenge['id'] . '">' . htmlspecialchars($challenge['title']) . '</a>
        <div class="challenge-points">
            <img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/flag.png">
            ' . $challenge['points'] . ' Points
        </div>
    </div>';

    $content = parse_markdown($challenge['description']);

    if (!empty($challenge['relies_on']) && !$challenge['flaggable']) {
        $content = '<div style="display:flex; align-items:center">' . decorator_square("hand.png", "270deg", "#E06552", true, true, 24) . $content . '</div>';
    }

    if ($challenge['solve_position'] == 0 && $challenge['flaggable']) {
        $content .= '<form class="form-one-line" style="margin-top: 8px" method="post" action="api">
            <input type="hidden" name="action" value="submit_flag" />
            <input type="hidden" name="challenge" value="' . $challenge['id'] . '" />
            <input type="text" name="flag" placeholder="Input flag" required></input>'
            . form_xsrf_token();

        if (Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PRIVATE')) {
            display_captcha();
        }

        $content .= '<button class="btn-dynamic" type="submit">Submit</button>';
        $content .= '</form>';
    }

    if ($challenge['solve_position'] != 0) {
        $extra_class = 'card-challenge-solved';
        
        if ($challenge['solve_position'] === 1 && $challenge['id'] == 2) {
            $extra_class .= ' card-challenge-scrolling-background card-challenge-first-blood';
            $solved_message = 'FIRST BLOOD';
            $solved_image = '/img/icons/first.png';
        } else if ($challenge['solve_position'] === 2 || $challenge['id'] == 4) {
            $extra_class .= ' card-challenge-scrolling-background card-challenge-second-blood';
            $solved_message = 'SECOND BLOOD';
            $solved_image = '/img/icons/second.png'; 
        } else if ($challenge['solve_position'] === 3 || $challenge['id'] == 5) {
            $extra_class .= ' card-challenge-scrolling-background card-challenge-third-blood';
            $solved_message = 'THIRD BLOOD';
            $solved_image = '/img/icons/third.png'; 
        } else {
            $solved_message = 'SOLVED';
            $solved_image = '/img/icons/check.png';
        }
        
        $side_header = $solved_message . '<img src="' . Config::get('URL_STATIC_RESOURCES') . $solved_image . '">';
    } else {
        $extra_class = '';
        $side_header = '';
    }

    echo card($title, $side_header, $content, $extra_class);
}

foot();