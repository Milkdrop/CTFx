<?php

require('../include/mellivora.inc.php');

login_session_refresh();

send_cache_headers('home', Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'));

head(lang_get('home'));

if (cache_start(CONST_CACHE_NAME_HOME, Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'))) {

    echo '<img id="ctfx-main-logo" src="',Config::get('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES'),'img/logo.png"/>
    <div class="main-intro-text">
    Welcome to CTF<div class="blue">x</div>. This is a fork of <a href="https://github.com/Nakiami/mellivora">mellivora</a> that sports an overhaul of the UI, extra functionality (such as dynamic scoring) and various other quality-of-life tweaks. The goal of CTF<div class="blue">x</div> is to bring together the speed of mellivora and the appearance of modern and future web in order to create a fast, lightweight and enjoyable CTF Platform. The current CTFx repository is a close clone of the CTFx instance that is running at the official X-MAS CTF.
</div>';

    section_title_no_underline ("Latest News");

    $news = db_query_fetch_all('SELECT * FROM news ORDER BY added DESC');
    echo '
        <div class="news-container">';
            section_head("Join the official X-MAS CTF Discord Server");
            echo '
            <div class="news-body">
                <iframe src="https://discordapp.com/widget?id=519974854485737483&theme=dark" width="100%" height="240" allowtransparency="true" frameborder="0"></iframe>
            </div>
        </div>
        ';

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

    section_title_no_underline ("Rules");
    
    echo '
        <br>
        <div class="news-container">
            <div class="news-body">
                <ul>
                    <li>Attacking the web server is strictly prohibited and will get you disqualified.</li>
                    <li>The flag format is <b>X-MAS{1337_Str1ng}</b>, unless specified otherwise.</li>
                    <li>The competition will be over on Fri, 20 Dec. 2019 at 19:00 UTC but the challenges will be online for the following 2-3 days afterwards.</li>
                    <li>Bruteforcing the flag will not get you anywhere except on the naughty list.</li>
                    <li>Any questions regarding challenges or the platform should be sent to xmasctf.contact@gmail.com.</li>
                    <li>Teams may have an <b>unlimited</b> number of members, but only a maximum of 4 people per team can receive the prizes.</li>
                </ul>
            </div>
        </div>';

    /*section_title_no_underline ("Sponsors");

    echo '<br>
        <div style="text-align:center;margin-bottom:-40px">
            <a href="https://www.hackthebox.eu/" style="padding-right:40px"><img style="display:inline-block;width:256px" src="/img/logos/htb.png"></a>
            <a href="https://tryhackme.com/"><img style="padding-left:40px;display:inline-block;width:340px" src="/img/logos/thm.png"></a>
            <br><br>
            <a href="https://rough.ro/"><img style="width:340px" src="/img/logos/rough.png"></a>
        </div>
        ';*/

    cache_end(CONST_CACHE_NAME_HOME);
}

foot();
