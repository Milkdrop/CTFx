<?php
require(CONST_PATH_LAYOUT . 'login_dialog.inc.php');
require(CONST_PATH_LAYOUT . 'messages.inc.php');
require(CONST_PATH_LAYOUT . 'scores.inc.php');
require(CONST_PATH_LAYOUT . 'user.inc.php');
require(CONST_PATH_LAYOUT . 'forms.inc.php');
require(CONST_PATH_LAYOUT . 'challenges.inc.php');
require(CONST_PATH_LAYOUT . 'dynamic.inc.php');

// set global head_sent variable
$head_sent = false;
// singleton bbcode instance
$bbc = null;

function head($title = '') {
    global $head_sent;

    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>',($title ? htmlspecialchars($title) . ' : ' : '') , Config::get('MELLIVORA_CONFIG_SITE_NAME'), ' - ', Config::get('MELLIVORA_CONFIG_SITE_SLOGAN'),'</title>
    <meta name="description" content="',Config::get('MELLIVORA_CONFIG_SITE_DESCRIPTION'),'">
    <meta name="author" content="">
    <link rel="icon" href="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'img/favicon.png" type="image/png" />

    <!-- CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'css/mellivora.css" rel="stylesheet">';

    js_global_dict();

    if (Config::get('MELLIVORA_CONFIG_SEGMENT_IO_KEY')) {
        echo '
        <script type="text/javascript">
        window.analytics=window.analytics||[],window.analytics.methods=["identify","group","track","page","pageview","alias","ready","on","once","off","trackLink","trackForm","trackClick","trackSubmit"],window.analytics.factory=function(t){return function(){var a=Array.prototype.slice.call(arguments);return a.unshift(t),window.analytics.push(a),window.analytics}};for(var i=0;i<window.analytics.methods.length;i++){var key=window.analytics.methods[i];window.analytics[key]=window.analytics.factory(key)}window.analytics.load=function(t){if(!document.getElementById("analytics-js")){var a=document.createElement("script");a.type="text/javascript",a.id="analytics-js",a.async=!0,a.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.io/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)}},window.analytics.SNIPPET_VERSION="2.0.9",
        window.analytics.load("',Config::get('MELLIVORA_CONFIG_SEGMENT_IO_KEY'),'");
        window.analytics.page();
        </script>
        ';
    }

    echo '
    </head>
    <body>';

    if (!user_is_logged_in()) {
        login_dialog();
    }

    echo '
    <div class="page">
    <nav class="header" id="header">
        <div id="header-inner">
            <div id="header-logo">
                <a href="',Config::get('MELLIVORA_CONFIG_SITE_URL'),'">
                    <h3 id="site-logo-text">',Config::get('MELLIVORA_CONFIG_SITE_NAME'),'</h3>
                </a>
            </div>
            <div id="header-menu">
                <ul class="nav nav-pills pull-right" id="menu-main">';

                    if (user_is_logged_in()) {

                        if (user_is_staff()) {
                            echo '<li><a class="shuffle-text" href="',Config::get('MELLIVORA_CONFIG_SITE_ADMIN_URL'),'">',lang_get('manage'),'</a></li>';
                        }

                        echo '
                            <li><a class="shuffle-text" style="margin-right: -20px" href="',Config::get('MELLIVORA_CONFIG_SITE_URL'),'home">',lang_get('home'),'</a></li>
                            <li><a class="shuffle-text" style="margin-right: 30px" href="',Config::get('MELLIVORA_CONFIG_SITE_URL'),'challenges">',lang_get('challenges'),'</a></li>
                            <li><a class="shuffle-text" href="',Config::get('MELLIVORA_CONFIG_SITE_URL'),'scores">',lang_get('scores'),'</a></li>
                            <li><a class="shuffle-text" href="',Config::get('MELLIVORA_CONFIG_SITE_URL'),'profile">',lang_get('profile'),'</a></li>
                            ',dynamic_menu_content(),'
                            <li>',form_logout(),'</li>
                            ';

                    } else {
                        echo '
                            <li><a class="shuffle-text" style="margin-right: -20px" href="',Config::get('MELLIVORA_CONFIG_SITE_URL'),'home">',lang_get('home'),'</a></li>
                            <li><a class="shuffle-text" style="margin-right: 30px" href="',Config::get('MELLIVORA_CONFIG_SITE_URL'),'scores">',lang_get('scoreboard'),'</a></li>
                            ',dynamic_menu_content(),'
                            <li><a class="shuffle-text" href="',Config::get('MELLIVORA_CONFIG_SITE_URL'),'register">',lang_get('register'),'</a></li>
                            <li><a class="shuffle-text" href="" data-toggle="modal" data-target="#login-dialog">',lang_get('log_in'),'</a></li>
                        ';
                    }
                    echo '
                </ul>
            </div>
        </div>
    </nav><!-- navbar -->

    <div id="background-dots"></div>
    <div class="container" id="body-container">

        <div id="content-container">
        ';

    if (isset($_GET['generic_success'])) {
        message_inline_green("Action Successful", false);
    } else if (isset($_GET['generic_failure'])) {
        message_inline_red("Action Failed", false);
    } else if (isset($_GET['generic_warning'])) {
        message_inline_red("Something Went Wrong", false);
    }

    $head_sent = true;
}

function foot () {
    echo '

    </div> <!-- / content container -->

</div> <!-- /container -->
<div id="footer">
	CTFx v1.0 - Work-In-Progress. Made by <a href="https://github.com/MoonfireSeco">Milkdrop</a>
</div>

<!--<video autoplay="true" loop="true" id="dotCanvas" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'img/dotCanvas.mp4"></video>-->

</div> <!-- /page -->

<!-- JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<audio id="audio-typewriter" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/typewriter.mp3"></audio>
<audio id="audio-navbar" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/navbar.mp3"></audio>
<audio id="audio-navclick" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/navclick.mp3"></audio>
<audio id="audio-footer-mouseover" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/footer_mouseover.mp3"></audio>
<audio id="audio-button-mouseover" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/button_mouseover.mp3"></audio>
<audio id="audio-button-click" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/button_click.mp3"></audio>
<audio id="audio-button-cancel-mouseover" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/button_cancel_mouseover.mp3"></audio>
<audio id="audio-button-cancel-click" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/button_cancel_click.mp3"></audio>
<audio id="audio-button-small-mouseover" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/button_small_mouseover.mp3"></audio>
<audio id="audio-button-small-click" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/button_small_click.mp3"></audio>
<audio id="audio-dropdown-open" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/dropdown_open.mp3"></audio>
<audio id="audio-checkbox-click" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'audio/checkbox_click.mp3"></audio>
<script type="text/javascript" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'js/mellivora.js"></script>
</body>
</html>';
}

function section_title ($title, $tagline = '', $decorator_color = "green") {
    echo '
    <div class="row">
        <div class="col-lg-12 page-header">
            <h2 class="typewriter">', title_decorator ($decorator_color), htmlspecialchars ($title),
            '<small>'.$tagline.'</small>','
            </h2>
        </div>
    </div>
    ';
}

function section_head ($title, $tagline = '', $strip_html = true) {
    echo '
        <h2 class="page-header">',($strip_html ? htmlspecialchars($title) : $title),' ',($tagline ? $strip_html ? '<small>'.htmlspecialchars($tagline).'</small>' : '<small>'.$tagline.'</small>' : ''),'</h2>
    ';
}

function section_subhead ($title, $tagline = '', $strip_html = true) {
    echo '
    <div class="row">
        <div class="col-lg-12">
          <h3 class="page-header">',($strip_html ? htmlspecialchars($title) : $title),' ',($tagline ? $strip_html ? '<small>'.htmlspecialchars($tagline).'</small>' : '<small>'.$tagline.'</small>' : ''),'</h3>
        </div>
    </div>
    ';
}

function title_decorator ($color, $rotation = "0deg", $img = "arrow.png") {
    echo '<div class="title-decorator-container" style="transform: rotate(',$rotation,')">
        <div class="title-decorator title-decorator-',$color,'"></div>
        <div class="title-decorator title-decorator-gray"></div>
        <div class="title-decorator title-decorator-icon" style="background-image: url(\'/img/ui/',$img,'\')"></div>
    </div>';
}

function tag ($text) {
    echo '<div class="inline-tag">',$text,'</div>';
}

function icon ($img) {
    echo '<span class="icon" style="background-image:url(\'/img/ui/',$img,'\')"></span>';
}

function dropdown ($name, $options = null) {
    echo '<div class="btn-group">
        <button class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown">', $name, ' <span class="caret"></span></button>
        <ul class="dropdown-menu">';

        foreach ($options as $option) {
            echo '<li><a href="', $option[1], '">', $option[0], '</a></li>';
        }
    echo '</ul>
    </div>';
}

function menu_management () {
    echo '<div id="menu-management" class="menu">';
    dropdown ("News", [["Add news item", "/admin/edit_news"], ["List news", "/admin/list/list_news"]]);
    dropdown ("Categories", [["Add category", "/admin/edit_category"], ["List categories", "/admin/"]]);
    dropdown ("Challenges", [["Add challenge", "/admin/edit_challenge"], ["List challenge", "/admin/"]]);
    dropdown ("Submissions", [["List submissions", "/admin/list/list_submissions"],
                            ["List submissions in need of marking", "/admin/list/list_submissions?only_needing_marking=1"]]);
    dropdown ("Users", [["List users", "/admin/list/list_users"]]);
    dropdown ("Email", [["Send Email", "/admin/new_email"], ["Send Email to all users", "/admin/new_email?bcc=all"]]);
    dropdown ("Hints", [["New hint", "/admin/hint"], ["List hints", "/admin/list/list_hints"]]);
    dropdown ("Dynamic navbar", [["New element", "/admin/edit_dynamic_menu_item"], ["List hints", "/admin/list/list_dynamic_menu"]]);
    dropdown ("Dynamic pages", [["New page", "/admin/edit_dynamic_page"], ["List hints", "/admin/list/list_dynamic_pages"]]);
    dropdown ("Exceptions", [["List exceptions", "/admin/list/list_exceptions"], ["Edit exceptions", "/admin/edit_exceptions"]]);
    dropdown ("Search", [["Search", "/admin/search"]]);
    dropdown ("Edit CTF", [["Edit ", "/admin/edit_ctf"]]);
    echo '</div>';
}

function bbcode_manual () {
    echo '
    <table>
        <tr>
        <td>
            <ul>
            <li><b>Text Styles:</b>
                <ul>
                <li>[b]<b> Bold </b>[/b]</li>
                <li>[i]<i> Italics </i>[/i]</li>
                <li>[u]<u> Underline </u>[/u]</li>
                <li>[s]<strike> Strikethrough </strike>[/s]</li>
                <li>[sup]<sup> Superscript </sup>[/sup]</li>
                <li>[sub]<sub> Subscript </sub>[/sub]</li>
                <li>[spoiler] Spoiler [/spoiler]</li>
                <li>[acronym] Acronym [/acronym]</li>
                <li>[size=6] Custom Size [/size]</li>
                <li>[color=red] Custom Color [/color]</li>
                <li>[font=verdana] Custom Font [/font]</li>
                </ul>
            </li>
            <li><b>Links:</b>
                <ul>
                <li>[url] URL [/url]</li>
                <li>[url=url] Text [/url]</li>
                <li>[email] E-Mail [/email]</li>
                <li>[wiki]</li>
                </ul>
            </li>
            </ul>
        </td>
        <td>
            <ul>
            <li><b>Replaced Items:</b>
                <ul>
                <li>[img] Image [/img]</li>
                <li>[rule]</li>
                <li>[br]</li>
                </ul>
            </li>
            <li><b>Alignment:</b>
                <ul>
                <li>[center]...[/center]</li>
                <li>[left]...[/left]</li>
                <li>[right]...[/right]</li>
                <li>[indent]...[/indent]</li>
                </ul>
            </li>
            <li><b>Columns:</b>
                <ul>
                <li>[columns]...[/columns]</li>
                <li>[nextcol]</li>
                </ul>
            </li>
            <li><b>Containers:</b>
                <ul>
                <li>[code]...[/code]</li>
                <li>[quote]...[/quote]</li>
                </ul>
            </li>
            </ul>
        </td>

        <td>
            <ul>
            <li><b>Lists:</b>
                <ul>
                <li>[list]...[/list]</li>
                <li>[*]...</li>
                </ul>
            </li>
            </ul>
        </td>
        </tr>
    </table>
    ';
}

function js_global_dict () {

    $dict = array();
    if (user_is_logged_in()) {
        $dict['user_id'] = $_SESSION['id'];
    }

    echo '<script type="text/javascript">
        var global_dict = {};
        ';

    foreach ($dict as $key => $val) {
        echo 'global_dict["',htmlspecialchars($key),'"] = "',htmlspecialchars($val),'"';
    }

    echo '
    </script>';
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

function print_ri($val){
    echo '<pre>',print_r($val),'</pre>';
}

function country_flag_link($country_name, $country_code, $return = false) {
    $country_name = htmlspecialchars($country_name);
    $country_code = htmlspecialchars($country_code);

    $flag_link = '
    <a href="country?code='.htmlspecialchars($country_code).'">
        <img src="'.Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES').'img/flags/'.$country_code.'.png" class="has-tooltip" data-toggle="tooltip" data-placement="right" alt="'.$country_code.'" title="'.$country_name.'" />
    </a>';

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
        validate_integer($current);
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

    echo '
    <div class="text-center">
        <ul class="pagination no-padding-or-margin">

        <li><a href="'.htmlspecialchars($base_url).'from='.max(0, ($current-$per_page)).'">Prev</a></li>

        <li',(!$current ? ' class="active"' : ''),'><a href="',htmlspecialchars($base_url),'">',min(1, $max),'-',min($max, $per_page),'</a></li>';

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

        echo '<li',($current == $i ? ' class="active"' : ''),'><a href="',htmlspecialchars($base_url),'from=',$i,'">', $i+1, ' - ', min($max, ($i+$per_page)), '</a></li>';

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
            echo '<li><a>...</a></li>';
        }
    }

    echo '

        <li><a href="'.htmlspecialchars($base_url).'from='.min($max-($max%$per_page), ($current+$per_page)).'">Next</a></li>

        </ul>
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

function get_availability_icons($exposed, $available_from, $available_until, $item_name) {
    $icons = "";

    if (!$exposed) {
        $icons .= '<span class="glyphicon glyphicon-ban-circle has-tooltip" data-toggle="tooltip" data-placement="top" title="'. htmlspecialchars($item_name) .' not exposed"></span> ';
    }

    if (!is_item_available($available_from, $available_until)) {
        $icons .= '<span class="glyphicon glyphicon-eye-close has-tooltip" data-toggle="tooltip" data-placement="top" title="'. htmlspecialchars($item_name) .' not available"></span> ';
    }

    if ($exposed && is_item_available($available_from, $available_until)) {
        $icons .= '<span class="glyphicon glyphicon-eye-open has-tooltip" data-toggle="tooltip" data-placement="top" title="'. htmlspecialchars($item_name) .' exposed and available"></span> ';
    }

    return $icons;
}

function get_bbcode() {
    global $bbc;

    if ($bbc === null) {
        $bbc = new BBCode();
        $bbc->SetEnableSmileys(false);
    }

    return $bbc;
}
