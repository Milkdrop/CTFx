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
    echo '<div style="display:flex; margin-bottom: 8px; max-height: 32px;">'
        . '<form style="display:flex; flex-grow:1" method="post" action="api">'
            . form_xsrf_token()
            . form_action_what('update', 'category')
            . form_hidden('id', $category['id'])
            . decorator_square()
            . '<input type="text" name="title" style="font-size:24px; width:256px; margin-right:8px" class="input-silent" placeholder="Category title" value="' . htmlspecialchars($category['title']) . '" required=""/>'
            . '<textarea name="description" style="font-size:20px; flex-grow:1; margin-right:8px" class="input-silent" placeholder="Category description">' . htmlspecialchars($category['description']) . '</textarea>'
            . '<button style="margin-right:8px" class="btn-solid" type="submit">Update</button>'
            . '<button class="btn-solid btn-solid-danger" type="submit" name="action" value="delete">Delete</button>'
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
        
        $content = '<textarea name="description" style="width:100%; height:92px; resize:vertical; margin-bottom:8px" class="input-silent" placeholder="Challenge description">' . htmlspecialchars($challenge['description']) . '</textarea>';

        $files = api_get_files_for_challenge($challenge['id']);
        
        foreach ($files as $file) {
            $content .= '<div style="display:flex; flex-grow:1">'
            . tag('<input style="max-width: 88px" type="text" name="file_name_' . $file['id'] . '" class="input-silent" value="' . htmlspecialchars($file['name']) . '"/>', 'package.png', true, "width: 128px; margin-right:8px")
            . tag('<input style="margin-left:-4px; flex-grow:1" type="text" name="file_url_' . $file['id'] . '" class="input-silent" value="' . htmlspecialchars($file['url']) . '"/>', '', true, 'flex-grow:1')
            . '
            <button style="margin-left: 8px" class="btn-solid btn-solid-danger" name="delete_file_' . $file['id'] . '" value="1" type="submit">Delete</button>
            </div>';
        }

        $hints = api_get_hints_for_challenge($challenge['id']);
        
        foreach ($hints as $hint) {
            $content .= '<div style="display:flex; flex-grow:1">' 
            . tag('<b>Hint!</b><input style="flex-grow:1; font-weight:normal; margin-left:6px; height:26px" type="text" name="hint_content_' . $hint['id'] . '" class="input-silent" value="' . htmlspecialchars($hint['content']) . '"/>', 'info.png', true, 'flex-grow:1')
            . '
            <button style="margin-left: 8px" class="btn-solid btn-solid-danger" name="delete_hint_' . $hint['id'] . '" value="1" type="submit">Delete</button>
            </div>';
        }
        
        $content .= tag('<b>By:</b><input style="flex-grow:1; font-weight:normal; margin-left:6px; height:26px" type="text" name="authors" class="input-silent" value="' . htmlspecialchars($challenge['authors']) . '"/>', 'user.png', true);
        
        $content .= '
        <div style="display:flex; flex-grow:1; margin-bottom:8px">
            <input style="flex-grow:1; margin-right:8px" class="input-silent" name="new_hint_content" placeholder="New Hint"/>
            <input style="flex-grow:1" class="input-silent" name="new_file_url" placeholder="New File (URL)"/>
        </div>';

        $content .= '
            <input type="text" name="flag" style="width:100%; margin-bottom:8px" placeholder="Flag" value="' . htmlspecialchars($challenge['flag']) . '"/>'
            . form_checkbox('Exposed', $challenge['exposed'] == '1', 'display:inline-flex; margin-right:8px')
            . form_checkbox('Flaggable', $challenge['flaggable'] == '1', 'display:inline-flex; margin-right:8px')
            . '<input id="collapsible-challenge-opt-' . $challenge['id'] . '" class="collapser" type="checkbox"/>'
            . '<label style="display:inline-flex" class="checkbox" for="collapsible-challenge-opt-' . $challenge['id'] . '">
                    <img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/cross.png"/>
                    <img src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/check.png"/>
                </label>'
            . '<div style="display:inline-flex; font-size:20px; font-weight:bold; align-items:center; vertical-align:top; height:32px">Show advanced options</div>'
            . '<div class="collapsible">'
            . form_checkbox('Case-insensitive flag', $challenge['case_insensitive_flag'] == '1', 'display:inline-flex; margin-right:8px')
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
            . '<div style="display:flex; align-items:center; margin-bottom:8px">'
            . '<input type="number" name="initial_points" placeholder="Initial points" style="margin-right:8px; width:96px" value="' . $challenge['initial_points'] . '"/>'
            . '<input type="number" name="minimum_points" placeholder="Minimum points" style="margin-right:8px; width:96px" value="' . $challenge['minimum_points'] . '"/>'
            . '<input type="number" name="solves_until_minimum" placeholder="Solves until minimum" style="margin-right:8px; width:96px" value="' . $challenge['solves_until_minimum'] . '"/>'
            . tooltip('<img style="width:32px; height:32px" src="' . Config::get('URL_STATIC_RESOURCES') . '/img/icons/question.png"/>', 'Points are calculated using the formula: init_pts - (init_pts - min_pts) * min((solves ** 2) / (solves_until_min ** 2), 1)')
            . '</div>'
            . '</div>'
            . '<div><button style="margin-right:8px" class="btn-dynamic" type="submit">Update</button>'
            . '<button class="btn-dynamic btn-dynamic-danger" type="submit" name="action" value="delete">Delete</button></div>'
        . '</form>';

        echo collapsible_card($title, '', $content);
    }

    echo '<form style="display:flex; align-items:top; max-height: 32px; margin:0px 0px 16px 40px" method="post" action="api">'
        . form_xsrf_token()
        . form_action_what('create', 'challenge')
        . form_hidden('category', $category['id'])
        . '<input type="text" name="title" style="font-size:24px; width:256px; margin-right:8px" class="input-silent" placeholder="Challenge title" required/>'
        . '<textarea name="description" style="font-size:20px; flex-grow:1; margin-right:8px" class="input-silent" placeholder="Challenge description"></textarea>'
        . '<input type="text" name="flag" style="font-size:24px; width:256px; margin-right:8px" class="input-silent" placeholder="Challenge flag" required/>'
        . '<button class="btn-solid" type="submit">Create</button>'
    . '</form>';
}

echo '<form style="display:flex; align-items:top; max-height: 32px; flex-shrink:1" method="post" action="api">'
    . form_xsrf_token()
    . form_action_what('create', 'category')
    . '<input type="text" name="title" style="font-size:24px; width:256px; margin-right:8px" class="input-silent" placeholder="Category title" required/>'
    . '<textarea name="description" style="font-size:20px; flex-grow:1; margin-right:8px" class="input-silent" placeholder="Category description"></textarea>'
    . '<button class="btn-solid" type="submit">Create</button>'
. '</form>';

foot();