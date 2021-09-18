<?php

function message_error($message) {
    global $head_sent;

    if (!$head_sent) {
        head(lang_get('error'));
    }

    http_response_code(400);
    echo message_center('ERROR: ' . $message, true, "#E06552");
    foot();
}

function message_generic ($title, $message, $head = true, $foot = true, $exit = true) {
    global $head_sent;

    if ($head && !$head_sent) {
        head($title);
    }

    echo '<h2 class="typewriter" style="margin-bottom:5px">', htmlspecialchars($title), '</h2>';

    message_inline ($message);

    if ($foot) {
        foot();
    }

    if ($exit) {
        exit;
    }
}