<?php

require('../include/mellivora.inc.php');

login_session_refresh();

header('Content-type: application/json');

if (!isset($_GET['view'])) {
    echo json_error(lang_get('please_request_view'));
    exit;
}

if ($_GET['view'] == 'scoreboard') {
    if (cache_start(CONST_CACHE_NAME_SCORES_JSON, Config::get('MELLIVORA_CONFIG_CACHE_TIME_SCORES'))) {
        json_scoreboard(array_get($_GET, 'user_type'));
        // To make the scoreboard fully CTFTime compatible you can run this python3 one-liner to encode unicode chars to \uXXXX:
        // open("ctftime.json","wb").write(open("ctfx.json","r",encoding='utf-8').read().encode("ascii","backslashreplace").replace(b"\\x",b"\\u00"))
        cache_end(CONST_CACHE_NAME_SCORES_JSON);
    }
}

else {
    echo json_error(lang_get('please_request_view'));
    exit;
}
