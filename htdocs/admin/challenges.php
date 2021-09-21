<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Challenges');
admin_menu();

$categories = api_get_categories();

foreach ($categories as $category) {
    echo '<div style="display:flex; align-items:top; margin-bottom: 16px; max-height: 32px;">'
        . '<form style="display:flex; align-items:top; flex-grow:1" method="post" action="api">'
            . form_xsrf_token()
            . '<input type="hidden" name="action" value="edit"/>'
            . '<input type="hidden" name="what" value="category"/>'
            . '<input type="hidden" name="id" value="' . $category['id'] . '"/>'
            . decorator_square()
            . '<input type="text" name="title" style="padding:2px; width:256px; font-size:24px; height:24px" class="input-silent" value="' . htmlspecialchars($category['title']) . '"></input>'
            . '<div style="display:flex; align-items:center; margin: 0px 8px"><img style="width:24px; height:24px; opacity: 0.55" src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/info.png"></img></div>'
            . '<textarea name="description" style="padding:2px; flex-grow:1; margin-right:8px" class="input-silent">' . htmlspecialchars($category['description']) . '</textarea>'
            . '<button style="margin-right:8px" class="btn-solid" type="submit">Update</button>'
        . '</form>'
        . '<form method="post" action="api">'
            . form_xsrf_token()
            . '<input type="hidden" name="action" value="delete"/>'
            . '<input type="hidden" name="what" value="category"/>'
            . '<input type="hidden" name="id" value="' . $category['id'] . '"/>'
            . '<button class="btn-solid btn-solid-danger" type="submit">Delete</button>'
        . '</form>'
        . '</div>';
}

foot();