<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Challenges');
admin_menu();

$categories = api_get_categories();

foreach ($categories as $category) {
    echo '<div style="display:flex; align-items:top; margin-bottom: 8px; max-height: 32px;">'
        . '<form style="display:flex; align-items:top; flex-grow:1" method="post" action="api">'
            . form_xsrf_token()
            . form_action_what('update', 'category')
            . form_hidden('id', $category['id'])
            . decorator_square()
            . '<input type="text" name="title" style="font-size:24px; width:256px; margin-right:8px" class="input-silent" placeholder="Category title" value="' . htmlspecialchars($category['title']) . '" required=""></input>'
            . '<textarea name="description" style="font-size:20px; flex-grow:1; margin-right:8px" class="input-silent" placeholder="Category description">' . htmlspecialchars($category['description']) . '</textarea>'
            . '<button style="margin-right:8px" class="btn-solid" type="submit">Update</button>'
        . '</form>'
        . '<form method="post" action="api">'
            . form_xsrf_token()
            . form_action_what('delete', 'category')
            . form_hidden('id', $category['id'])
            . '<button class="btn-solid btn-solid-danger" type="submit">Delete</button>'
        . '</form>'
    . '</div>';

    $challenges = api_admin_get_challenges_from_category($category['id']);

    foreach ($challenges as $challenge) {
        echo '<form method="post" style="margin-left:40px" action="api">'
            . form_xsrf_token()
            . form_action_what('update', 'challenge')
            . form_hidden('id', $challenge['id']);
        
        $title = '<div style="display:flex"><input type="text" name="title" class="input-silent" placeholder="Challenge title" value="' . htmlspecialchars($challenge['title']) . '" required=""/>'
            . '<div class="challenge-points">'
            . '<img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/flag.png">'
            . $challenge['points'] . ' Points'
            . '<img style="margin-left:8px" src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/check.png">'
            . $challenge['solves'] . ' Solves'
        . '</div></div>';
        
        $content = '<textarea name="description" style="width:100%; height:92px; resize:vertical" class="input-silent" placeholder="Challenge description">' . htmlspecialchars($challenge['description']) . '</textarea>'
            . '<select name="category">';

        foreach ($categories as $category_option) {
            $content .= '<option value="' . $category_option['id'] . '"' . (($challenge['category']==$category_option['id'])?' selected':'')
                . '>' . htmlspecialchars($category_option['title']) . '</option>';
        }

        $content .= '</select>'
            . '<input type="text" name="flag" style="width:100%" placeholder="Flag" value="' . htmlspecialchars($challenge['flag']) . '"/>'
            . '<div style="margin-top:8px">'
            . '<div style="display:flex">'
            . '<button style="margin-right:8px" class="btn-dynamic" type="submit">Update</button>'
        . '</form>'
        . '<form method="post" action="api">'
            . form_xsrf_token()
            . form_action_what('delete', 'challenge')
            . form_hidden('id', $challenge['id'])
            . '<button class="btn-dynamic btn-dynamic-danger" type="submit">Delete</button>'
        . '</form>'
        . '</div></div>';

        echo collapsible_card($title, '', $content);
    }

    echo '<form style="display:flex; align-items:top; max-height: 32px; margin:0px 0px 16px 40px" method="post" action="api">'
        . form_xsrf_token()
        . form_action_what('create', 'challenge')
        . form_hidden('category', $category['id'])
        . '<input type="text" name="title" style="font-size:24px; width:256px; margin-right:8px" class="input-silent" placeholder="Challenge title" required=""></input>'
        . '<textarea name="description" style="font-size:20px; flex-grow:1; margin-right:8px" class="input-silent" placeholder="Challenge description"></textarea>'
        . '<input type="text" name="flag" style="font-size:24px; width:256px; margin-right:8px" class="input-silent" placeholder="Challenge flag" required=""></input>'
        . '<button class="btn-solid" type="submit">Create</button>'
    . '</form>';
}

echo '<form style="display:flex; align-items:top; max-height: 32px; flex-shrink:1" method="post" action="api">'
    . form_xsrf_token()
    . form_action_what('create', 'category')
    . '<input type="text" name="title" style="font-size:24px; width:256px; margin-right:8px" class="input-silent" placeholder="Category title" required=""></input>'
    . '<textarea name="description" style="font-size:20px; flex-grow:1; margin-right:8px" class="input-silent" placeholder="Category description"></textarea>'
    . '<button class="btn-solid" type="submit">Create</button>'
. '</form>';

foot();