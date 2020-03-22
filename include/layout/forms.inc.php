<?php

function form_start($action='', $class='', $enctype='') {
    echo '
    <form method="post" class="',($class ? $class : 'form-horizontal'),'"',($enctype ? ' enctype="'.$enctype.'"' : ''),'',($action ? ' action="'.$action.'"' : ''),' role="form">
    ';

    form_xsrf_token();
}

function form_end() {
    echo '</form>';
}

function form_hidden ($name, $value) {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '<input type="hidden" name="',$field_name,'" value="',htmlspecialchars($value),'" />';
}

function form_file ($name) {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '<div class="form-group">
        <input class="form-control" type="file" name="',$field_name,'" id="',$field_name,'" />
    </div>';
}

function form_input_text($name, $prefill = false, array $options = null, $tip = null) {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '
    <div class="form-group">
          <input
            type="text"
            id="',$field_name,'"
            name="',$field_name,'"
            class="form-control"
            placeholder="',$name,'"
            ',($prefill !== false ? ' value="'.htmlspecialchars($prefill).'"' : ''),'
            ',(array_get($options, 'disabled') ? ' disabled' : ''),'
            ',(array_get($options, 'autocomplete') ? ' autocomplete="'.$options['autocomplete'].'"' : ''),'
            ',(array_get($options, 'autofocus') ? ' autofocus' : ''),'
          />';
    if (isset ($tip)) {
        echo '<div class="inline-tag form-tip">', htmlspecialchars($tip), "</div>";
    }

    echo '</div>';
}

function form_input_password($name, $prefill = false, array $options = null) {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '
    <div class="form-group">
        <input type="password" id="',$field_name,'" name="',$field_name,'" class="form-control" placeholder="',$name,'"',($prefill !== false ? ' value="'.htmlspecialchars($prefill).'"' : ''),'',($options['disabled'] ? ' disabled' : ''),' required />
    </div>
    ';
}

function form_input_captcha($position = 'private') {
    if (($position == 'private' && Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PRIVATE')) || ($position == 'public' && Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PUBLIC'))) {
        echo '
        <div class="form-group">
          <label class="col-sm-2 control-label" for="captcha"></label>
          <div class="col-sm-10">';

        display_captcha();

        echo '</div>
        </div>
        ';
    }
}

function form_input_checkbox ($name, $checked = 0, $color = "blue", $check_dark = 0) {
    switch ($color) {
        case "blue": $color = "#42A0FF"; break;
        case "green": $color = "#CFFF42"; $check_dark = 1; break;
        case "red": $color = "#FF4242"; break;
        default: break;
    }

    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '<div class="form-group">
        <input style="border-color: ',$color,'" type="checkbox" id="',$field_name,'" class="form-control ',$check_dark ? 'check-dark':'','" name="',$field_name,'" value="1"',($checked ? ' checked="checked"' : ''),' />
      <label class="control-label" for="',$field_name,'">',$name,'</label>
    </div>';
}

function form_generic ($name, $generic) {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '<div class="form-group">',$generic,'</div>';
}

function form_textarea($name, $prefill = false) {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '
    <div class="form-group">
          <textarea
            id="',$field_name,'"
            name="',$field_name,'"
            placeholder="',$name,'"
            class="form-control"
            rows="5">',
            ($prefill !== false ? htmlspecialchars($prefill) : ''),'</textarea>
    </div>
    ';
}

function form_button_submit ($name, $type = '1') {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '
    <div class="form-group">
        <button type="submit" id="',$field_name,'" class="btn btn-lg btn-',htmlspecialchars($type),'">',$name,'</button>
    </div>
    ';
}

function form_button_submit_bbcode ($name) {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '<div class="form-group">
        <button type="submit" id="',$field_name,'" class="btn btn-lg btn-1">',$name,'</button>
        <a target="_blank" href="/admin/bbcode_manual" style="height:45px;margin-top:0px;padding-top:10px" class="btn btn-xs btn-2">BBCode Manual</a>
    </div>';
}

function form_select ($opts, $name, $value, $selected, $option, $optgroup='') {
    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '
    <div class="form-group">
        <select class="form-control" id="',$field_name,'" name="',$field_name,'">';

    $group = '';
    foreach ($opts as $opt) {

        if ($optgroup && $group != array_get($opt, $optgroup)) {
            if ($group) {
                echo '</optgroup>';
            }
            echo '<optgroup label="',htmlspecialchars(array_get($opt, $optgroup)),'">';
        }

        echo '<option value="',htmlspecialchars($opt[$value]),'"',($opt[$value] == $selected ? ' selected="selected"' : ''),'>', htmlspecialchars($opt[$option]), '</option>';

        if ($optgroup) {
            $group = array_get($opt, $optgroup);
        }
    }

    if ($optgroup) {
        echo '</optgroup>';
    }

    echo '
        </select>
    </div>
    ';
}

function form_logout() {
    echo '
    <form action="/actions/logout" method="post">
        ',form_xsrf_token(),'
        <button class="shuffle-text" type="submit" id="logout-button">',lang_get('log_out'),'</button>
    </form>
    ';
}

function country_select() {
    $countries = db_select_all(
        'countries',
        array(
            'id',
            'country_name'
        ),
        null,
        'country_name ASC'
    );

    echo '<select name="country" class="form-control form-group" required="required">
            <option disabled selected>-- ',lang_get('please_select_country'),' --</option>';

    foreach ($countries as $country) {
        echo '<option value="',htmlspecialchars($country['id']),'">',htmlspecialchars($country['country_name']),'</option>';
    }

    echo '</select>';
}

function dynamic_visibility_select($selected = null) {
    $options = array(
        array(
            'val'=>CONST_DYNAMIC_VISIBILITY_BOTH,
            'opt'=>visibility_enum_to_name(CONST_DYNAMIC_VISIBILITY_BOTH)
        ),
        array(
            'val'=>CONST_DYNAMIC_VISIBILITY_PRIVATE,
            'opt'=>visibility_enum_to_name(CONST_DYNAMIC_VISIBILITY_PRIVATE)
        ),
        array(
            'val'=>CONST_DYNAMIC_VISIBILITY_PUBLIC,
            'opt'=>visibility_enum_to_name(CONST_DYNAMIC_VISIBILITY_PUBLIC)
        )
    );

    form_select($options, 'Visibility', 'val', $selected, 'opt');
}

function user_class_select($selected = null) {
    $options = array(
        array(
            'val'=>CONST_USER_CLASS_USER,
            'opt'=>user_class_name(CONST_USER_CLASS_USER)
        ),
        array(
            'val'=>CONST_USER_CLASS_MODERATOR,
            'opt'=>user_class_name(CONST_USER_CLASS_MODERATOR)
        )
    );

    form_select($options, 'Min user class', 'val', $selected, 'opt');
}

function require_fields($required, $form_data) {
    $empties = array();
    foreach ($form_data as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $empties[] = $key;
        }
    }

    if (!empty($empties)) {
        message_error('Missing required field data for: ' . implode(', ', $empties));
    }
}
