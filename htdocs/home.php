<?php

require('../include/ctfx.inc.php');

send_cache_headers('home', Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'));

head('Home');

echo '<div id="home-logo"><img src="'.Config::get('URL_STATIC_RESOURCES').'/img/logo.png"></div>';

if (!ctf_started()) {
    echo '<div id="home-ctf-start-time">' . decorator_square("star.png", "270deg", "#FCDC4D", true, true, 24) . 'CTF starts in&nbsp;<b>' . timestamp(Config::get('CTF_START_TIME')) . '</b></div>';
}

echo '<div id="home-intro-text">
    Welcome to <a href="https://github.com/Milkdrop/CTFx">CTFx</a>. This is a fork of <a href="https://github.com/Nakiami/mellivora">mellivora</a> that sports an overhaul of the UI,
    extra functionality (such as dynamic scoring) and various other quality-of-life tweaks. The goal of CTFx is to bring together the
    speed of mellivora and the appearance of modern and future web in order to create a fast, lightweight and enjoyable CTF Platform.
    The current CTFx repository is a close clone of the CTFx instance that is running at the official <a href="https://ctftime.org/ctf/277">X-MAS CTF</a>.
</div>';

echo section_header("Proudly sponsored by:") . '<div style="margin-bottom:24px">
    <a target="_blank" href="https://google.com/"><img class="ctfx-sponsor-logo" src="'.Config::get('URL_STATIC_RESOURCES').'/img/sponsors/sponsor_logo.png"></a>
    <a target="_blank" href="https://google.com/"><img class="ctfx-sponsor-logo" src="'.Config::get('URL_STATIC_RESOURCES').'/img/sponsors/sponsor_logo.png"></a>
    <a target="_blank" href="https://google.com/"><img class="ctfx-sponsor-logo" src="'.Config::get('URL_STATIC_RESOURCES').'/img/sponsors/sponsor_logo.png"></a>
</div>';

echo '<div style="display:flex; margin-bottom:16px">
    <div>' . section_header("Rules") .
        '<ul>
            <li>Attacking the platform is strictly prohibited and will get you disqualified.</li>
            <li>The flag format is <b>X-MAS{1337_Str1ng}</b> unless specified otherwise.</li>
            <li>The competition start on Fri, 11 Dec. 2040 19:00 UTC and will be over on Fri, 18 Dec. 2040 at 19:00 UTC. The challenges will be online for the following 2-3 days afterwards.</li>
            <li>Bruteforcing the flag will not get you anywhere except on the naughty list.</li>
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

foot();
