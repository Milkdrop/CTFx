<?php
require('../include/ctfx.inc.php');

head('Home');

if (cache_start('home', Config::get('CACHE_TIME_HOME'))) {
    echo '<div id="home-logo"><img src="'.Config::get('URL_STATIC_RESOURCES').'/img/logo.png"></div>';

    if (!ctf_started()) {
        echo '<div id="home-ctf-start-time">' . decorator_square("star.png", "270deg", "#F8C630", true, true, 24) . 'CTF starts in&nbsp;<b>' . timestamp(Config::get('CTF_START_TIME')) . '</b></div>';
    }

    echo '<div id="home-intro-text">
    <b>X-MAS CTF</b> is a <a href="https://ctftime.org/ctf-wtf/">Capture The Flag competition</a> organized by <a href="https://ctftime.org/team/58218">HTsP</a>. This year we have prepared challenges from a diverse range of categories such as web exploitation, forensics, reverse engineering, binary exploitation, game hacking and more! We made sure that each category has challenges for every skill level, so that there is always something for everyone to enjoy and work on. This competition is using a dynamic scoring system, meaning that the more solves a challenge has, the less points it will bring to each of the solving teams. This system is put in place in order to keep the challenge score updated to its real difficulty level.</div>';
    
    echo section_header("Proudly sponsored by:") . '<div style="margin-bottom:24px">
        <a target="_blank" href=""><img class="ctfx-sponsor-logo" src="'.Config::get('URL_STATIC_RESOURCES').'/img/sponsors/sponsor_logo.png"></a>
        <a target="_blank" href=""><img class="ctfx-sponsor-logo" src="'.Config::get('URL_STATIC_RESOURCES').'/img/sponsors/sponsor_logo.png"></a>
    </div>';

    echo '<div style="display:flex; margin-bottom:16px">
        <div>' . section_header("Rules") .
            '<ul>
                <li>Attacking the platform is strictly prohibited and will get you disqualified.</li>
                <li>Bruteforcing the flag will not get you anywhere except on the naughty list.</li>
                <li>The flag format is <b>X-MAS{1337_Str1ng}</b> unless specified otherwise.</li>
                <li>The competition will be divided in 2 different weekends:
                    <ul>
                        <li>First weekend will be between <b>10 Dec. 2021, 19:00 UTC and 12 Dec. 2021, 19:00 UTC</b></li>
                        <li>Second weekend will be between <b>17 Dec. 2021, 19:00 UTC and 19 Dec. 2021, 19:00 UTC</b></li>
                    </ul>
                    <li>Between these two weekends, there will be a few <b>long challenges</b> made available, which are available for the entire CTF (9 days). Most of the other challenges, however, will only be available in the weekend they were released in.
                </li>
                <li>Any questions regarding challenges or the platform should be asked on our discord server. (Make sure you aren\'t sharing anything important related to a challenge!)</li>
            </ul>
        </div>

        <iframe src="https://discordapp.com/widget?id=519974854485737483&theme=dark" width="100%" height="240" allowtransparency="true" frameborder="0"></iframe>
    </div>';

    $news = api_get_news();

    if (count($news) > 0) {
        echo section_header("Latest news");

        foreach ($news as $item) {
            echo card(htmlspecialchars($item['title']), timestamp($item['added'], 'ago') . '<img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/clock.png">', parse_markdown($item['body']));
        }
    }

    cache_end();
}

foot();
