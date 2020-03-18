<?php

require('../include/mellivora.inc.php');

login_session_refresh();

send_cache_headers('home', Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'));

head(lang_get('home'));

if (cache_start(CONST_CACHE_NAME_HOME, Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'))) {

    echo '<div id="ctfx-main-logo"></div>
        <div class="main-intro-text">
        Welcome to CTFx. This is a fork of <a href="https://github.com/Nakiami/mellivora">mellivora</a> that sports an overhaul of the UI, extra functionality (such as dynamic scoring) and various other quality-of-life tweaks. The goal of CTFx is to bring together the speed of mellivora and the appearance of modern and future web in order to create a fast, lightweight and enjoyable CTF Platform. The current CTFx repository is a close clone of the CTFx instance that is running at the official X-MAS CTF.
    </div>
    <br>';

    echo '<div class="row">
    <div class="col-md-6">';

    echo '<iframe src="https://discordapp.com/widget?id=519974854485737483&theme=dark" width="100%" height="240" allowtransparency="true" frameborder="0"></iframe>';

    section_head ("Rules");
    
    echo '<ul>
            <li>Attacking the web server is strictly prohibited and will get you disqualified.</li>
            <li>The flag format is <b>X-MAS{1337_Str1ng}</b>, unless specified otherwise.</li>
            <li>The competition will be over on Fri, 20 Dec. 2019 at 19:00 UTC but the challenges will be online for the following 2-3 days afterwards.</li>
            <li>Bruteforcing the flag will not get you anywhere except on the naughty list.</li>
            <li>Any questions regarding challenges or the platform should be sent to xmasctf.contact@gmail.com.</li>
            <li>Teams may have an <b>unlimited</b> number of members, but only a maximum of 4 people per team can receive the prizes.</li>
        </ul>';

    echo '</div>
    <div class="col-md-6">';

    section_head ("Latest News");

    $news = db_query_fetch_all('SELECT * FROM news ORDER BY added DESC');

    if (count ($news) == 0) {
    	message_inline ("No news");
    }

    foreach ($news as $item) {
        echo '<div class="news-container">
            <div class="news-head"><h4 class="news-title">',
                htmlspecialchars($item['title']),
                '</h4> <small>',
                date_time ($item['added']),
                '</small></div>
            <div class="news-body">
                ',get_bbcode()->parse($item['body']),'
            </div>
        </div>';
    }

    echo '</div></div>';
    cache_end(CONST_CACHE_NAME_HOME);
}

foot();
