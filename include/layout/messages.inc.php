<?php

function message_generic_error($head = true, $foot = true, $exit = true) {
    message_error(lang_get('generic_error'), $head, $foot, $exit);
}

function message_error ($message, $head = true, $foot = true, $exit = true) {
    global $head_sent;

    if ($head && !$head_sent) {
        head(lang_get('error'));
    }

    echo '<h2 class="typewriter" style="margin-bottom:5px">', lang_get('error'), '</h2>';

    message_inline ($message, "red");

    if ($foot) {
        foot();
    }

    if ($exit) {
        exit;
    }
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

function message_inline ($message, $color = "blue", $strip_html = true, $extra_style = "") {
    switch ($color) {
        case "green": $textcolor = "#CFFF42"; break;
        case "red": $textcolor = "#FF4242"; break;
        default: $textcolor = "";
    }

    echo '<div class="alert" style="', isset ($textcolor)?'color:' . $textcolor . ';':'',
    $extra_style, '">', title_decorator ($color, "270deg"), ($strip_html ? htmlspecialchars($message) : $message), '</div>';
}

function message_dialog ($message, $title, $closeText, $class, $buttonType = "1") {
    echo '
    <div class="modal fade ',$class,'">
        <div class="modal-dialog light-theme">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">',$title,'</h4>
                </div>
                <div class="modal-body">
                    <p>',$message,'</p>
                    <button type="button" class="btn btn-lg btn-',$buttonType,'" data-dismiss="modal">',$closeText,'</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    ';
}