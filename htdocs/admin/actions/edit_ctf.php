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
    } else if ($_POST['action'] === 'import_challenges') {
        move_uploaded_file($_FILES['file']['tmp_name'], CONST_PATH_FILE_UPLOAD . 'challenge_csv');
        $fp = fopen (CONST_PATH_FILE_UPLOAD . 'challenge_csv', 'r');

        $categories = db_select_all(
           'categories',
            array('id')
        );

        /*db_delete('categories',
            array(
                'id'=>$_POST['id']
            )
        );*/

        foreach ($categories as $category) {
            echo 'Delete Cat: ' . $category['id'] . '\n';

            $challenges = db_select_all(
               'challenges',
                array('id'),
                array('category' => $category['id'])
            );

            foreach ($challenges as $challenge) {
                echo 'Delete chall: ' . $challenge['id'] . '\n';
                //delete_challenge_cascading($challenge['id']);
            }
        }

        $cats = [];
        $categories = [["added", "added_by", "title", "exposed"]];
        $challenges = [["added", "added_by", "title", "description", "flag", "category", "exposed"]];

        while ($data = fgetcsv ($fp)) {
            if (!empty ($data[0])) {
                if (!in_array ($data[1], $cats)) {
                    $id = db_insert(
                      'categories',
                      array(
                         'added'=>time(),
                         'added_by'=>$_SESSION['id'],
                         'title'=>$data[1],
                         'exposed'=>1
                      )
                   );

                    $cats[$data[1]] = $id;
                }

                $id = db_insert(
                    'challenges',
                    array(
                        'added' => time(),
                        'added_by' => $_SESSION['id'],
                        'title' => $data[0],
                        'description' => $data[2],
                        'category' => $_POST['category'],
                        'exposed' => $_POST['exposed']
                    )
                );
            }
        }

        var_dump ($cats);

        unlink (CONST_PATH_FILE_UPLOAD . 'challenge_csv');
        redirect('/admin/edit_ctf.php?generic_success=1');
    }
}