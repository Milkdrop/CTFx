<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] === 'change_times') {

        require_fields(array('ctf_start_time'), $_POST);
        require_fields(array('ctf_end_time'), $_POST);

        db_update_all (
            'challenges',
            array(
                'available_from'=>strtotime($_POST['ctf_start_time']),
                'available_until'=>strtotime($_POST['ctf_end_time'])
            )
        );

        redirect('/admin/edit_ctf.php?generic_success=1');
    }
}