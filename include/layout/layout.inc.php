<?php
require(CONST_PATH_LAYOUT . '/login_dialog.inc.php');
require(CONST_PATH_LAYOUT . '/messages.inc.php');
require(CONST_PATH_LAYOUT . '/scores.inc.php');
require(CONST_PATH_LAYOUT . '/user.inc.php');
require(CONST_PATH_LAYOUT . '/forms.inc.php');

$head_sent = false;
$collapsible_cards_sent = 0;
$parsedown = null;
$staticVersion = "1.3.0a5";

function head($title = '') {
    global $head_sent;
    global $staticVersion;

    header('Content-Type: text/html; charset=utf-8');
    header('Content-Security-Policy: script-src \'self\'');

    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>',($title ? htmlspecialchars($title) . ' : ' : '') , Config::get('SITE_NAME'), ' - ', Config::get('SITE_SLOGAN'),'</title>
    <meta name="description" content="',Config::get('SITE_DESCRIPTION'),'">
    <meta name="author" content="">
    <meta property="og:image" content="'.Config::get('URL_STATIC_RESOURCES').'/img/favicon.png"/>
    <link rel="icon" href="'.Config::get('URL_STATIC_RESOURCES').'/img/favicon.png" type="image/png" />

    <!-- CSS -->
    <link href="/static/ctfx.css?v=' . $staticVersion . '" rel="stylesheet">';

    echo '
    </head>
    <body>';

    if (!user_is_logged_in()) {
        login_dialog();
    }

    echo '<div id="navbar">
        <a href="' . Config::get('URL_BASE_PATH') . '">
            <img id="navbar-logo" src="' . Config::get('URL_STATIC_RESOURCES') . '/img/logo_navbar.png">
        </a>

        <div id="navbar-buttons">';

        $path = strtok(strtok($_SERVER['REQUEST_URI'], '?'), '/');

            if (user_is_logged_in()) {

                foreach (array('Admin', 'Home', 'Challenges', 'Scoreboard', 'Profile') as $entry) {
                    if ($entry === 'Admin' && !user_is_staff()) {
                        continue;
                    }
                    
                    echo '<a ' . ((stripos($path, $entry)!==false)?'class="active" ':'') . 'href="'
                        . Config::get('URL_BASE_PATH') . strtolower($entry) . '">' . $entry . '</a>';
                }
                
                echo '<form action="/actions/logout" method="post">' . form_xsrf_token() . '
                    <button type="submit" id="logout-button">Logout</button>
                </form>';

            } else {
                foreach (array('Home', 'Scoreboard', 'Login') as $entry) {
                    echo '<a ' . ((stripos($path, $entry)!==false)?'class="active" ':'') . 'href="'
                        . Config::get('URL_BASE_PATH') . strtolower($entry) . '">' . $entry . '</a>';
                }
            }

            echo '
        </div>
    </div>

    <div id="body-content">';

    $head_sent = true;
}

function foot () {
    global $staticVersion;
    
    echo '</div>

    <div id="footer">
        <b><a href="https://github.com/Milkdrop/CTFx">CTFx</a></b> v'.$staticVersion.'<br>
        Made with &#x1f499; by <a href="https://gitlab.com/Milkdrop">Milkdrop</a>, Based on <a href="https://github.com/Nakiami/mellivora">mellivora</a>
    </div>

    <!-- JS -->
    <audio id="audio-typewriter" src="/static/audio/typewriter.mp3"></audio>
    <audio id="audio-nav-mouseover" src="/static/audio/nav_mouseover.mp3"></audio>
    <audio id="audio-nav-click" src="/static/audio/nav_click.mp3"></audio>
    <audio id="audio-btn-dynamic-mouseover" src="/static/audio/btn_dynamic_mouseover.mp3"></audio>
    <audio id="audio-btn-dynamic-click" src="/static/audio/btn_dynamic_click.mp3"></audio>
    <audio id="audio-btn-solid-mouseover" src="/static/audio/btn_solid_mouseover.mp3"></audio>
    <audio id="audio-btn-solid-click" src="/static/audio/btn_solid_click.mp3"></audio>
    <audio id="audio-checkbox-click" src="/static/audio/checkbox_click.mp3"></audio>
    <script type="text/javascript" src="/static/ctfx.js?v=' . $staticVersion . '"></script>
    </body>
    </html>';
}

function decorator_square($icon = "arrow.png", $rotation = "0deg", $color = "#35AAFD", $invert_icon = false, $reset_icon_rotation = false, $icon_size = 16) {
    $icon = htmlspecialchars($icon);
    $rotation = htmlspecialchars($rotation);
    $color = htmlspecialchars($color);
    $icon_size = htmlspecialchars($icon_size);

    return '<div class="decorator-square-container" style="transform: rotate('.$rotation.')">
        <div class="decorator-square-component" style="background-color:'.$color.'"></div>
        <div class="decorator-square-component title-decorator-gray"></div>
        <div class="decorator-square-component decorator-square-icon"
            style="background-image: url(\''.Config::get('URL_STATIC_RESOURCES').'/img/icons/'.$icon.'\');'
                . 'background-size: ' . $icon_size . 'px;'
                .($invert_icon?'filter:invert(1);':'')
                .($reset_icon_rotation?'transform: rotate(-'.$rotation.');':'')
            .'">
        </div>
    </div>';
}

function section_header($title) {
    $title = htmlspecialchars($title);
    return '<div class="section-header">' . decorator_square() . $title . '</div>';
}

function card($html_title, $html_header_right_side, $html_content, $extra_class = '') {
    $extra_class = htmlspecialchars($extra_class);

    return '<div class="card ' . $extra_class . '">
        <div class="card-header">' . $html_title . '<small>' . $html_header_right_side . '</small></div>
        <div class="card-content">' . $html_content . '</div>
    </div>';
}

function collapsible_card($html_title, $html_header_right_side, $html_content) {
    global $collapsible_cards_sent;
    
    $collapsible_cards_sent += 1;
    $id = "collapsible-card-" . $collapsible_cards_sent;

    return '<div class="card">
        <label for="' . $id . '"><div class="card-header">' . $html_title . '<small>' . $html_header_right_side . '</small></div></label>
        <input id="' . $id . '" class="collapser" type="checkbox">
        <div class="card-content collapsible">' . $html_content . '</div>
    </div>';
}

function tag($html_content, $icon = '', $inline_tag = false, $extra_style = '', $extra_classes = '') {
    $icon = htmlspecialchars($icon);
    $extra_style = htmlspecialchars($extra_style);
    $extra_classes = htmlspecialchars($extra_classes);
    
    return '<div class="tag' . ($inline_tag?' tag-inline':'') . ' ' . $extra_classes . '"' . (!empty($extra_style)?('style="' . $extra_style . '"'):'') . '>'
        . (!empty($icon)?('<img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/' . $icon . '" style="width:20px; height:20px; margin-right:8px"/>'):'')
        . $html_content . '</div>';
}

function timestamp($time, $extra_text = '', $substract_with = false) {
    $extra_text = htmlspecialchars($extra_text);
    
    $full_timestamp = formatted_date($time);

    $time_difference = $time - time();
    if ($substract_with !== false) {
        $time_difference = $time - $substract_with;
    }
    
    $seconds = $time_difference % 60;

    if ($time_difference > 0) {
        $minutes = floor($time_difference / 60) % 60;
        $hours = floor($time_difference / (60 * 60)) % 24;
        $days = floor($time_difference / (60 * 60 * 24));
    } else {
        $minutes = ceil($time_difference / 60) % 60;
        $hours = ceil($time_difference / (60 * 60)) % 24;
        $days = ceil($time_difference / (60 * 60 * 24));
    }
    
    $seconds = abs($seconds);
    $minutes = abs($minutes);
    $hours = abs($hours);
    $days = abs($days);
    
    if ($days) $content = $days . " Day" . ($days==1?"":"s") . ", " . $hours . " Hour" . ($hours==1?"":"s");
    else if ($hours) $content = $hours . " Hour" . ($hours==1?"":"s") . ", " . $minutes . " Minute" . ($minutes==1?"":"s");
    else if ($minutes) $content = $minutes . " Minute" . ($minutes==1?"":"s") . ", " . $seconds . " Second" . ($seconds==1?"":"s");
    else $content = $seconds . " Second" . ($seconds==1?"":"s");

    return tooltip('<span class="countdown" time-difference="' . $time_difference . '">' . $content . '</span>&nbsp;' . $extra_text, $full_timestamp);
}

function tooltip($html_content, $tooltip_text) {
    $tooltip_text = htmlspecialchars($tooltip_text);

    return '<span class="tooltip">' . $html_content . '<div class="tooltip-text">' . $tooltip_text . '</div></span>';
}

function message_inline($message, $strip_html = true, $color = "#35AAFD") {
    if ($strip_html)
        $message = htmlspecialchars($message);
    
    return '<div class="section-header">' . decorator_square("arrow.png", "270deg", $color) . $message . '</div>';
}

function die_with_message($message, $submessage = "", $strip_html = true, $img = "warning.png", $color = "#35AAFD") {
    global $head_sent;

    $message = htmlspecialchars($message);
    $color = htmlspecialchars($color);

    if (!$head_sent)
        head("Message");

    echo '<div class="message-centered">
        <img src="'.Config::get('URL_STATIC_RESOURCES').'/img/icons/' . htmlspecialchars($img) . '">
        <div>
        <div>' . $message . '</div>';

    if (!empty($submessage))
        echo '<div>' . message_inline($submessage, $strip_html, $color) . '</div>';

    echo '</div></div>';

    foot();
    die();
}

function die_with_message_error($error_message) {
    http_response_code(400);
    die_with_message("Fatal Error", $error_message, true, 'cracked.png', '#E06552');
}

function admin_delete_confirmation($explanation = '') {
    $submessage = '<form style="margin-right:8px" method="post" action="api">'
        . form_xsrf_token()
        . form_action_what($_POST['action'], $_POST['what'])
        . form_hidden('id', $_POST['id'])
        . form_hidden('delete_confirmation', 'yes')
        . '<button class="btn-solid btn-solid-danger" type="submit">Yes</button>'
        . '</form>';
    
    if (!empty($explanation))
        $submessage .= tooltip('<img style="width:24px; height:24px" src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/question.png"></img>', $explanation);
        
    die_with_message('Confirm delete?', $submessage, false, 'delete.png', '#E06552');
}

/* Forms */

function form_action_what($action, $what) {
    return form_hidden('action', $action) . form_hidden('what', $what);
}

function form_hidden($name, $value) {
    $name = htmlspecialchars($name);
    $value = htmlspecialchars($value);
    return '<input type="hidden" name="' . $name . '" value="' . $value . '"/>';
}

function form_checkbox($name, $checked = false, $custom_style = '') {
    $input_name = htmlspecialchars(str_replace(array(' ', '-'), '_', strtolower($name)));
    $printed_name = htmlspecialchars($name);
    $custom_style = htmlspecialchars($custom_style);

    return '<div class="section-header" style="font-size:20px; ' . $custom_style . '">
        <label>
            <input type="hidden" name="' . $input_name . '" value="0"/>
            <input type="checkbox" name="' . $input_name . '" value="1" ' . ($checked?'checked':'').'/>
            <div class="checkbox">
            <img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/cross.png"/>
            <img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/check.png"/>
            </div>
        </label>' . $printed_name . '</div>';
}

function admin_menu() {
    $path = basename(strtok($_SERVER['REQUEST_URI'], '?'));
    $sections = array('Dashboard', 'Challenges');
    $active_path = "???";

    foreach ($sections as $entry) {
        if (stripos($path, $entry)!==false) {
            $active_path = $entry;
        }
    }

    echo '<div class="pre-category-name">Admin section:</div>
        <div class="category-name typewriter">' . $active_path . '</div>';

    echo '<div style="display:flex; flex-wrap:wrap">' . decorator_square("arrow.png", "270deg", "#FCDC4D", true);
    foreach ($sections as $entry) {
        echo '<a style="margin:0px 8px 8px 0px" class="btn-solid btn-solid-warning ' . ((stripos($path, $entry)!==false)?'active':'')
            . '" href="' . strtolower($entry) . '">' . $entry . '</a>';
    }

    echo '</div>';
}

function progress_bar ($percent, $type = false, $striped = true) {

    if (!$type) {
        $type = ($percent >= 100 ? 'success' : 'info');
    }

    echo '
    <div class="progress',$striped ? ' progress-striped' : '','">
        <div class="progress-bar progress-bar-',$type,'" role="progressbar" aria-valuenow="',$percent,'" aria-valuemin="0" aria-valuemax="100" style="width: ',$percent,'%">
            <span class="sr-only">',$percent,'% complete</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10" preserveAspectRatio="none">
                <polygon fill="#0a0a0a" points="0,0 10,10 10,0"/>
            </svg>
        </div>
    </div>
    ';
}

function country_flag_link($country_name, $country_code, $return = false) {
    $country_name = htmlspecialchars($country_name);
    $country_code = htmlspecialchars($country_code);

    $flag_link = '<a class="country-flag" href="country?code='.htmlspecialchars($country_code).'">' .
        '<img src="'.Config::get('URL_STATIC_RESOURCES').'/img/flags/'.$country_code.'.png" class="has-tooltip" data-toggle="tooltip" data-placement="right" alt="'.$country_code.'" title="'.$country_name.'"/>'.
    '</a>';

    if ($return) {
        return $flag_link;
    }

    echo $flag_link;
}

function pager_filter_from_get($get) {
    if (array_get($get, 'from') != null) {
        unset($get['from']);
    }
    return http_build_query($get);
}

function pager($base_url, $max, $per_page, $current) {
    if (isset($current)){
        if (!is_integer_value($current)) {
            die_with_message_error('Invalid starting page');
        }
    }

    // by default, we add on any get parameter to the pager link
    $get_argument_string = pager_filter_from_get($_GET);
    if (!empty($get_argument_string)) {
        $base_url .= pager_url_param_joining_char($base_url) . $get_argument_string;
    }

    $base_url .= pager_url_param_joining_char($base_url);

    $first_start = 0;
    $first_end = $first_start + $per_page*4;

    if ($current >= $first_end) {
        $first_end -= $per_page;
        $middle_start = $current - $per_page;
        $middle_end = $middle_start + $per_page*2;
    } else {
        $middle_start = 0;
        $middle_end = 0;
    }

    $last_start = $max - $per_page*2;
    $last_end = $max;

    echo '<div class="ctfx-pager">',
        '<a class="pager-arrow" style="margin-right:5px" href="'.htmlspecialchars($base_url).'from='.max(0, ($current-$per_page)).'">◀</a>',
        '<a class="btn btn-xs btn-2 ',(!$current ? 'active' : ''), '" href="',htmlspecialchars($base_url),'">',min(1, $max),'-',min($max, $per_page),'</a>';

    $i = $per_page;
    while ($i < $max) {

        // are we in valid range to display buttons?
        if (
            !($i >= $first_start && $i <= $first_end)
            &&
            !($i >= $middle_start && $i <= $middle_end)
            &&
            !($i >= $last_start && $i <= $last_end)
        ) {
            $i+=$per_page;
            continue;
        }

        echo '<a class="btn btn-xs btn-2 ',($current == $i ? 'active' : ''),'" href="',htmlspecialchars($base_url),'from=',$i,'">', $i+1, ' - ', min($max, ($i+$per_page)), '</a>';

        $i+=$per_page;

        if ((
                (
                    ($i > $first_end) // if we've passed the first end
                    && // and
                    ($i - $per_page <= $first_end) // we've just crossed over the line
                    && // and
                    ($i - $per_page != $middle_start) // we're not adjacent to our middle start
                )
                || // or
                (
                    ($i > $middle_end) // if we've passed the current end
                    && // and
                    ($i - $per_page <= $middle_end) // we've just crossed over the line
                )
            ) && ($i + $per_page*3 < $max) // and we're more than three steps over from the last one
        ) {
        echo '<a class="btn btn-xs">...</a>';
        }
    }

    echo '<a class="pager-arrow" href="'.htmlspecialchars($base_url).'from='.min($max-($max%$per_page), ($current+$per_page)).'">▶</a>

    </div>';
}

function pager_url_param_joining_char($base_url) {
    $last_char = substr($base_url, -1);
    if (strpos($base_url, '?') && $last_char != '?' && $last_char != '&') {
        return '&';
    } else {
        return '?';
    }
}

function get_pager_from($val) {
    if (is_valid_id(array_get($val, 'from'))) {
        return $val['from'];
    }

    return 0;
}

function parse_markdown($text) {
    global $parsedown;

    if ($parsedown === null) {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
    }

    return $parsedown->text($text);
}
