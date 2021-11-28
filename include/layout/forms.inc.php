<?php

function form_start($action='', $class='', $enctype='') {
    echo '
    <form method="post" class="',($class ? $class : 'form-horizontal'),'"',($enctype ? ' enctype="'.$enctype.'"' : ''),'',($action ? ' action="'.$action.'"' : ''),' role="form">
    ';

    echo form_xsrf_token();
}

function form_end() {
    echo '</form>';
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

function form_input_checkbox ($name, $checked = 0, $color = "blue") {
    $colorcode = "#808080";

    switch ($color) {
        case "blue": $colorcode = "#0B90FD"; break;
        case "green": $colorcode = "#C2E812"; break;
        case "red": $colorcode = "#F2542D"; break;
        default: break;
    }

    $name = htmlspecialchars($name);
    $field_name = strtolower(str_replace(' ','_',$name));
    echo '<div class="form-group">
        <input style="border-color: ',$colorcode,'" type="checkbox" id="',$field_name,'" class="form-control checkbox-', htmlspecialchars($color) ,'" name="',$field_name,'" value="1"',($checked ? ' checked="checked"' : ''),' />
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

function country_select() {
}