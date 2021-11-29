<?php

$cache_fname = '';

function cache_start($page, $lifetime, $id = '') {
    global $cache_fname;

    if ($lifetime == 0) {
        return true;
    }
    
    if (!empty($id)) {
        validate_id($id);
    }

    $cache_fname = CONST_PATH_CACHE . '/' . $page . $id;

    if (file_exists($cache_fname) && time() - filemtime($cache_fname) <= $lifetime) {
        $fp = fopen($cache_fname, "r");

        if (flock($fp, LOCK_SH)) {
            echo fread($fp, filesize($cache_fname));
            flock($fp, LOCK_UN);
            fclose($fp);
        } else {
            echo "Cache error.";
        }

        $cache_fname = '';
        return false;
    } else {
        ob_start();
        return true;
    }
}

function cache_end() {
    global $cache_fname;

    if (!empty($cache_fname)) {
        $data = ob_get_contents();
        ob_end_flush();

        $fp = fopen($cache_fname, "w");

        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $data);
            flock($fp, LOCK_UN);
            fclose($fp);
        } else {
            echo 'Cache erorr.';
        }
    }
}
