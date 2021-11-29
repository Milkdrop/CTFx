<?php

$cache_fname = '';
$cache_it = -1;
$max_cache_it = 3;

function cache_start($page, $lifetime, $id = '') {
    global $cache_fname;
    global $cache_it;
    global $max_cache_it;

    if ($lifetime == 0) {
        return true;
    }
    
    if (!empty($id)) {
        validate_id($id);
    }

    $cache_fname = CONST_PATH_CACHE . '/' . $page . $id;

    $usable_cache_fname = '';
    for ($i = 0; $i < $max_cache_it; $i++) {
        if (file_exists($cache_fname . $i)) {
            $usable_cache_fname = $cache_fname . $i;
            $cache_it = $i;
        }
    }

    if (!empty($usable_cache_fname) && time() - filemtime($usable_cache_fname) <= $lifetime) {
        echo file_get_contents($usable_cache_fname);
        $cache_fname = '';
        return false;
    } else {
        ob_start();
        return true;
    }
}

function cache_end() {
    global $cache_fname;
    global $cache_it;
    global $max_cache_it;

    if (!empty($cache_fname)) {
        $data = ob_get_contents();
        ob_end_flush();

        if ($cache_it == -1) {
            file_put_contents($cache_fname . '0', $data);
        } else {
            $new_cache_it = ($cache_it + 1) % $max_cache_it;
            $delete_cache_it = ($max_cache_it + $cache_it - 1) % $max_cache_it;

            file_put_contents($cache_fname . $new_cache_it, $data);
            unlink($cache_fname . $delete_cache_it);
        }
    }
}
