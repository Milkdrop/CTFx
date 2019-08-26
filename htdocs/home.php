<?php

require('../include/mellivora.inc.php');

login_session_refresh();

send_cache_headers('home', Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'));

head(lang_get('home'));

if (cache_start(CONST_CACHE_NAME_HOME, Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'))) {

    echo '<img id="ctfx-main-logo" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'img/logo.png"/>
    <div class="main-intro-text">
        Welcome to CTF<div class="blue">x</div>. This is a fork of <a href="https://github.com/Nakiami/mellivora">mellivora</a> that sports an overhaul of the UI, extra functionality (such as dynamic scoring) and various other quality-of-life tweaks. The goal of CTF<div class="blue">x</div> is to bring together the speed of mellivora and the appearance of modern and future web in order to create a fast, lightweight and enjoyable CTF Platform.
    </div>';

    section_title_no_underline ("Latest News");

    $news = db_query_fetch_all('SELECT * FROM news ORDER BY added DESC');
    foreach ($news as $item) {
        echo '
        <div class="news-container">';
            section_head($item['title'], "(" . date_time ($item['added']) . ")");
            echo '
            <div class="news-body">
                ',get_bbcode()->parse($item['body']),'
            </div>
        </div>
        ';
    }

    cache_end(CONST_CACHE_NAME_HOME);
}

foot();