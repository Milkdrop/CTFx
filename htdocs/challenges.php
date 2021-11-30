<?php

require('../include/ctfx.inc.php');

enforce_authentication();

head('Challenges');

$categories = api_get_categories();

// Determine which category to display
if (isset($_GET['category'])) {
    $category_to_select = $_GET['category'];
} else {
    $category_to_select = Config::get('DEFAULT_CATEGORY_ON_CHALLENGES_PAGE');
}

foreach ($categories as $category) {
    if ($category['id'] == $category_to_select || $category['title'] == $category_to_select) {
        $current_category = $category;
    }
}

if (!$current_category) {
    if (isset($_GET['category'])) {
        redirect('challenges');
    } else {
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
echo '<div style="display:flex; flex-wrap:wrap">' . decorator_square("arrow.png", "270deg", "#F8C630", true);

foreach ($categories as $cat) {
    echo '<a style="margin:0px 8px 8px 0px" class="btn-solid btn-solid-warning ' . ($current_category['id'] == $cat['id'] ? 'active' : '')
        . '" href="challenges?category=' . htmlspecialchars(urlencode($cat['title'])) . '">'
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
if (user_is_staff()) {
    $challenges = api_admin_get_challenges_from_category($current_category['id']);
} else {
    $challenges = api_get_challenges_from_category($current_category['id'], $_SESSION['id']);
}

foreach ($challenges as $challenge) {
    $title = '<div style="display:flex"><a href="challenge?id=' . $challenge['id'] . '">' . htmlspecialchars($challenge['title']) . '</a>
        <div class="challenge-points">
            <img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/flag.png">
            ' . $challenge['points'] . ' Points';
    
    if ($challenge['exposed'] == 0) {
        $title .= '<img style="margin-left:8px" src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/hidden.png"> Hidden';
    }

    $title .= '</div>
    </div>';

    if (isset($challenge['dependency_unsatisfied']) && $challenge['dependency_unsatisfied']) {
        $relies_on_data = $challenge['relies_on_data'];
        $msg = '<b>To see this challenge, you must first solve <a href="challenge?id=' . $relies_on_data['id'] . '">'
        . htmlspecialchars($relies_on_data['title']) . '</a>'
        . ' from <a href="challenges?category=' . $relies_on_data['category'] . '">' . htmlspecialchars($relies_on_data['category_title']) . '</a></b>';

        $content = '<div style="display:flex; align-items:center">' . decorator_square("hand.png", "270deg", "#EF3E36", true, true, 24) . $msg . '</div>';
    } else {
        $content = parse_markdown($challenge['description']);

        $targets = api_get_targets_for_challenge($challenge['id']);
        $content .= '<div style="margin-top:8px; display:flex; flex-wrap: wrap">';

        if (count($targets) > 0) {
            foreach ($targets as $target) {
                if (stripos($target['url'], "http") === 0) {
                    $content .= '<a style="text-decoration:none; margin-right:8px; margin-bottom:8px" href="' . htmlspecialchars($target['url']) . '" target="_blank">'
                    . tag(htmlspecialchars($target['url']), 'link.png', true, "margin-bottom:0px", "btn-solid btn-solid-danger btn-solid-link") . '</a>';
                } else {
                    $content .= '<div style="text-decoration:none; margin-right:8px; margin-bottom:8px" href="' . htmlspecialchars($target['url']) . '" target="_blank">'
                    . tag(htmlspecialchars($target['url']), 'target.png', true, "margin-bottom:0px", "btn-solid btn-solid-danger btn-solid-link btn-solid-link-unclickable") . '</div>';
                }
            }
        }

        $files = api_get_files_for_challenge($challenge['id']);

        if (count($files) > 0) {
            foreach ($files as $file) {
                $content .= '<a style="text-decoration:none; margin-right:8px; margin-bottom:8px" href="' . htmlspecialchars($file['url']) . '" target="_blank">'
                . tag(htmlspecialchars($file['name']), 'package.png', true, "margin-bottom:0px", "btn-solid btn-solid-link") . '</a>';
            }
        }

        $content .= '</div>';

        $hints = api_get_hints_for_challenge($challenge['id']);
        $content .= '<div>';
        foreach ($hints as $hint) {
            $content .= tag('<b style="margin-right:8px">Hint!</b>' . parse_markdown($hint['content']), 'info.png', true);
        }
        $content .= '</div>';
        
        if (!empty($challenge['authors'])) {
            $content .= tag('<b style="margin-right:8px">By:</b>' . htmlspecialchars($challenge['authors']), 'user.png', true, "margin-bottom:0px");
        }

        if ($challenge['solve_position'] == 0 && $challenge['flaggable']) {
            $content .= '<form style="display:flex; margin-top:8px" method="post" action="api">
                <input type="hidden" name="action" value="submit_flag" />
                <input type="hidden" name="challenge" value="' . $challenge['id'] . '" />
                <input type="text" name="flag" style="flex-grow:1; margin-right:8px" placeholder="Input flag" required/>'
                . form_xsrf_token();
    
            $content .= '<button class="btn-dynamic" type="submit">Submit</button>';
            $content .= '</form>';
        }
    
        if ($challenge['solve_position'] != 0) {
            $extra_class = 'card-challenge-solved';
            
            if ($challenge['solve_position'] === 1) {
                $extra_class .= ' card-challenge-scrolling-background card-challenge-first-blood';
                $solved_message = 'FIRST BLOOD';
                $solved_image = '/img/icons/first.png';
            } else if ($challenge['solve_position'] === 2) {
                $extra_class .= ' card-challenge-scrolling-background card-challenge-second-blood';
                $solved_message = 'SECOND BLOOD';
                $solved_image = '/img/icons/second.png'; 
            } else if ($challenge['solve_position'] === 3) {
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
    }

    echo card($title, $side_header, $content, $extra_class);
}

foot();