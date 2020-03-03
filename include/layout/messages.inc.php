<?php

function message_generic_error($head = true, $foot = true, $exit = true) {
    message_error(lang_get('generic_error'), $head, $foot, $exit);
}

function message_error ($message, $head = true, $foot = true, $exit = true) {
    global $head_sent;

    if ($head && !$head_sent) {
        head(lang_get('error'));
    }

    echo '<h2 class="typewriter">', lang_get('error'), '</h2>';

    message_inline_red($message);

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

    echo '<h2 class="typewriter">', htmlspecialchars($title), '</h2>';

    message_inline_blue($message);

    if ($foot) {
        foot();
    }

    if ($exit) {
        exit;
    }
}

function message_inline_bland ($message) {
    echo '<p>',htmlspecialchars($message),'</p>';
}

function message_inline_blue ($message, $strip_html = true) {
    echo '<div class="alert alert-info">', dropdown_element ('blue'), ($strip_html ? htmlspecialchars($message) : $message),'</div>';
}

function message_inline_red ($message, $strip_html = true) {
    echo '<div class="alert alert-danger">', dropdown_element ('red'), ($strip_html ? htmlspecialchars($message) : $message),'</div>';
}

function message_inline_yellow ($message, $strip_html = true) {
    echo '<div class="alert alert-warning">', dropdown_element ('yellow'), ($strip_html ? htmlspecialchars($message) : $message),'</div>';
}

function message_inline_green ($message, $strip_html = true) {
    echo '<div class="alert alert-success">', dropdown_element ('green'), ($strip_html ? htmlspecialchars($message) : $message),'</div>';
}

function message_dialog ($message, $title, $closeText, $class, $buttonType = "primary") {
    echo '
    <div class="modal fade ',$class,'">
        <div class="modal-dialog form-black">
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

function message_correct_flag () {
    echo '<div id="correct-flag"></div>';
}