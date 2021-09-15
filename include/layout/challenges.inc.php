<?php

function get_challenge_files($challenge) {
    $files = cache_array_get(CONST_CACHE_NAME_FILES . $challenge['id'], Config::get('MELLIVORA_CONFIG_CACHE_TIME_FILES'));
    if (!is_array($files)) {
        $files = db_select_all(
            'files',
            array(
                'id',
                'title',
                'size',
                'url',
                'md5',
                'download_key'
            ),
            array('challenge' => $challenge['id'])
        );

        cache_array_save(
            $files,
            CONST_CACHE_NAME_FILES . $challenge['id']
        );
    }

    return $files;
}

function print_challenge_files($files) {
    if (count($files)) {
        echo '<div>';
        foreach ($files as $file) {
            echo '<div class="challenge-file">';

            if (empty ($file['url'])) {
                $url = 'download?file_key=' . htmlspecialchars($file['download_key']) . '&team_key=' . get_user_download_key();
            } else {
                $url = htmlspecialchars($file['url']);
            }
            
            echo '<a target="_blank" href="', $url, '">' . decorator_square("package.png", "0deg"), '</a>';
            echo '<a target="_blank" class="challenge-filename" href="', $url, '">', htmlspecialchars($file['title']), '</a>';

            if (empty ($file['url'])) {
                if ($file['size']) {
                    tag ('Size: ' . bytes_to_pretty_size($file['size']));
                }

                if ($file['md5']) {
                    tag ('MD5: ' . $file['md5']);
                }
            }
        
            echo '</div>';
        }

        echo '</div> <!-- / challenge-files -->';
    }
}

function print_hints($challenge) {
    if (cache_start(CONST_CACHE_NAME_CHALLENGE_HINTS . $challenge['id'], Config::get('MELLIVORA_CONFIG_CACHE_TIME_HINTS'))) {
        $hints = db_select_all(
            'hints',
            array('body'),
            array(
                'visible' => 1,
                'challenge' => $challenge['id']
            )
        );

        foreach ($hints as $hint) {
            message_inline('<strong>Hint!</strong> ' . get_bbcode()->parse($hint['body']), "green", false);
        }

        cache_end(CONST_CACHE_NAME_CHALLENGE_HINTS . $challenge['id']);
    }
}