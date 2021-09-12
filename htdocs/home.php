<?php

require('../include/mellivora.inc.php');

login_session_refresh();

send_cache_headers('home', Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'));

head(lang_get('home'));

if (cache_start(CONST_CACHE_NAME_HOME, Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'))) {

    echo '<div id="home-logo"><img src="'.Config::get('URL_STATIC_RESOURCES').'/img/logo.png"></div>';
    
    if (!ctf_started() || true) {
        echo '<div id="home-ctf-start-time">' . decorator_square("asterisk.png", "270deg", "#FCDC4D") . 'CTF starts in&nbsp;<b>' . time_remaining(Config::get('CTF_START_TIME')) . '</b></div>';
    }

    echo '<div id="home-intro-text">
        Welcome to <a href="https://github.com/Milkdrop/CTFx">CTFx</a>. This is a fork of <a href="https://github.com/Nakiami/mellivora">mellivora</a> that sports an overhaul of the UI,
        extra functionality (such as dynamic scoring) and various other quality-of-life tweaks. The goal of CTFx is to bring together the
        speed of mellivora and the appearance of modern and future web in order to create a fast, lightweight and enjoyable CTF Platform.
        The current CTFx repository is a close clone of the CTFx instance that is running at the official <a href="https://ctftime.org/ctf/277">X-MAS CTF</a>.
    </div>';

    section_header("Proudly sponsored by:");
    echo '<div class="ctfx-sponsor-list">
        <a target="_blank" href="https://google.com/"><img class="ctfx-sponsor-logo" src="'.Config::get('URL_STATIC_RESOURCES').'/img/sponsors/sponsor_logo.png"></a>
        <a target="_blank" href="https://google.com/"><img class="ctfx-sponsor-logo" src="'.Config::get('URL_STATIC_RESOURCES').'/img/sponsors/sponsor_logo.png"></a>
        <a target="_blank" href="https://google.com/"><img class="ctfx-sponsor-logo" src="'.Config::get('URL_STATIC_RESOURCES').'/img/sponsors/sponsor_logo.png"></a>
    </div>';

    echo '<div class="row">
    <div class="col-md-6">';

    echo '<iframe src="https://discordapp.com/widget?id=519974854485737483&theme=dark" width="100%" height="240" allowtransparency="true" frameborder="0"></iframe>';

    section_header("Rules");
    
    echo '<ul>
        <li>Attacking the platform is strictly prohibited and will get you disqualified.</li>
        <li>The flag format is X-MAS{1337_Str1ng} unless specified otherwise.</li>
        <li>The competition start on Fri, 11 Dec. 2020 19:00 UTC and will be over on Fri, 18 Dec. 2020 at 19:00 UTC. The challenges will be online for the following 2-3 days afterwards.</li>
        <li>Bruteforcing the flag will not get you anywhere except on the naughty list.</li>
        <li>Any questions regarding challenges or the platform should be asked on our discord server. (Make sure you aren\'t sharing anything important related to a challenge!)</li>
    </ul>';

    echo '</div>
    <div class="col-md-6">';

    section_header("Latest News");

    $news = db_query_fetch_all('SELECT * FROM news ORDER BY added DESC');

    if (count ($news) == 0) {
    	message_inline ("No news");
    }

    foreach ($news as $item) {
        echo '<div class="ctfx-card">
            <div class="ctfx-card-head"><h4>',
                htmlspecialchars($item['title']),
                '</h4> <small>',
                date_time ($item['added']),
                '</small></div>
            <div class="ctfx-card-body">
                ',get_bbcode()->parse($item['body']),'
            </div>
        </div>';
    }

    echo '</div></div>';

    cache_end (CONST_CACHE_NAME_HOME);
}

foot();
