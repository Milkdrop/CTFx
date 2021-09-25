<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Challenges');
admin_menu();

$categories = api_get_categories();
$challenges = array();

// Precalculate here for the "relies_on" select
foreach ($categories as $category) {
    $challenges[$category['id']] = api_admin_get_challenges_from_category($category['id']);
}

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

    foreach ($challenges[$category['id']] as $challenge) {
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
        
        $content = '<textarea name="description" style="width:100%; height:92px; resize:vertical; margin-bottom:8px" class="input-silent" placeholder="Challenge description">' . htmlspecialchars($challenge['description']) . '</textarea>'
            . '<div style="display:flex; margin-bottom:8px"><select name="category" style="max-width:200px; margin-right:8px">';

        foreach ($categories as $category_option) {
            $content .= '<option value="' . $category_option['id'] . '"' . (($challenge['category']==$category_option['id'])?' selected':'')
                . '>' . htmlspecialchars($category_option['title']) . '</option>';
        }

        $content .= '</select><select name="relies_on" style="max-width:256px">
            <option value="" disabled ' . (empty($challenge['relies_on'])?'selected':'') . ' hidden>Challenge depends on</option>'
            . '<option value="0">No challenge</option>';

        foreach ($categories as $category_option_header) {
            $content .= '<option value="" disabled>' . htmlspecialchars($category_option_header['title']) . '</option>';

            foreach ($challenges[$category_option_header['id']] as $challenge_option) {
                $content .= '<option value="' . $challenge_option['id'] . '"' . (($challenge['relies_on']==$challenge_option['id'])?' selected':'')
                    . '>' . htmlspecialchars($challenge_option['title']) . '</option>';
            }
        }

        $content .= '</select></div>'
            . '<div style="display:flex">'
            . form_checkbox('Exposed', $challenge['exposed'] == '1', 'margin-right:8px')
            . form_checkbox('Flaggable', $challenge['flaggable'] == '1')
            . '</div><input type="text" name="flag" style="width:100%; margin-bottom:8px" placeholder="Flag" value="' . htmlspecialchars($challenge['flag']) . '"/>'
            . '<div style="display:flex">'
            . '<button style="margin-right:8px" class="btn-dynamic" type="submit">Update</button>'
        . '</form>'
        . '<form method="post" action="api">'
            . form_xsrf_token()
            . form_action_what('delete', 'challenge')
            . form_hidden('id', $challenge['id'])
            . '<button class="btn-dynamic btn-dynamic-danger" type="submit">Delete</button>'
        . '</form>'
        . '</div>';

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