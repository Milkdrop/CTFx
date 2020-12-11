<?php

require('../include/mellivora.inc.php');

login_session_refresh();

send_cache_headers('home', Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'));

head(lang_get('home'));

if (cache_start(CONST_CACHE_NAME_HOME, Config::get('MELLIVORA_CONFIG_CACHE_TIME_HOME'))) {

    echo '<div id="home-logo"></div>
        <div class="home-intro-text">',
    (!ctfStarted ()) ? (title_decorator ("blue", "0deg", "asterisk.png") . 'CTF will start in <b>' . seconds_to_pretty_time(Config::get('MELLIVORA_CONFIG_CTF_START_TIME') - time ()) . '</b><br><br>') : '',
    '<b>X-MAS CTF</b> is a <a href="https://ctftime.org/ctf-wtf/">Capture The Flag competition</a> organized by <a href="https://ctftime.org/team/58218">HTsP</a>. This year we have prepared challenges from a diverse range of categories such as cryptography, web exploitation, forensics, reverse engineering, binary exploitation, hardware, algorithmics and more! We made sure that each category has challenges for every skill level, so that there is always something for everyone to enjoy and work on. This competition is using a dynamic scoring system, meaning that the more solves a challenge has, the less points it will bring to each of the solving teams. This system is put in place in order to keep the challenge score updated to its real difficulty level.
    </div>';

    echo '<div class="row" style="text-align:center; font-size: 20px; margin-bottom:-5px">
    <div style="margin-bottom:-10px">', title_decorator("blue", "0deg", "asterisk.png"), 'Proudly sponsored by:<br></div>

    <div style="display:block ruby; transform: translate(-8%, 0%);">
    <img style="height: 150px; margin-right: -16px"src="/img/sponsors/armedsynapse.png">
    <a target="_blank" href="https://vector35.com/"><img style="height: 110px" src="/img/sponsors/vector35.png"></a>
    <a target="_blank" href="https://www.offensive-security.com/"><img style="height: 60px" src="/img/sponsors/offensivesecurity.png"></a>
    <a target="_blank" href=" https://www.hackthebox.eu/"><img style="height: 43px; margin-left: -19px" src="/img/sponsors/hackthebox.png"></a>
    <a target="_blank" href="https://www.pentesteracademy.com/"><img style="height: 85px;margin-right: 22px;" src="/img/sponsors/pentesteracademy.png"></a>
    <a target="_blank" href=" https://pentesterlab.com/"><img style="height: 43px; margin-right: 12px" src="/img/sponsors/pentesterlab.png"></a>
    <a target="_blank" href="https://bluuk.io/"><img style="height: 54px" src="/img/sponsors/bluuk.png"></a>
    <br></div></div>';

    echo '<div class="row">
    <div class="col-md-6">';

    echo '<iframe src="https://discordapp.com/widget?id=519974854485737483&theme=dark" width="100%" height="240" allowtransparency="true" frameborder="0"></iframe>';

    section_head ("Rules");
    
    echo '<ul>
        <li>Attacking the platform is strictly prohibited and will get you disqualified.</li>
        <li>The flag format is X-MAS{1337_Str1ng} unless specified otherwise.</li>
        <li>The competition start on Fri, 11 Dec. 2020 19:00 UTC and will be over on Fri, 18 Dec. 2020 at 19:00 UTC. The challenges will be online for the following 2-3 days afterwards.</li>
        <li>Bruteforcing the flag will not get you anywhere except on the naughty list.</li>
        <li>Any questions regarding challenges or the platform should be asked on our discord server. (Make sure you aren\'t sharing anything important related to a challenge!)</li>
    </ul>';

    echo '</div>
    <div class="col-md-6">';

    section_head ("Latest News");

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

    $bgchoice = rand(0, 2);

    echo '<style>
        .background-left {
            background-image:url("/img/theme/human' . $bgchoice . 'left.png");
        }
        .background-right {
            background-image:url("/img/theme/human' . $bgchoice . 'right.png");
        }
    </style>';

    cache_end (CONST_CACHE_NAME_HOME);
}

foot();
