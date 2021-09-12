<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] === 'new') {
        if (!is_valid_id(array_get($_POST, 'category'))) {
            message_error('You must select a category to create a challenge!');
        }

        require_fields(array('title'), $_POST);

        $initialPts = Config::get ("MELLIVORA_CONFIG_CHALL_INITIAL_POINTS");
        $minPts = Config::get ("MELLIVORA_CONFIG_CHALL_MINIMUM_POINTS");
        $decay = Config::get ("MELLIVORA_CONFIG_CHALL_SOLVE_DECAY");

        $id = db_insert(
            'challenges',
            array(
                'added' => time(),
                'added_by' => $_SESSION['id'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'available_from'=>Config::get ("CTF_START_TIME"),
                'available_until'=>Config::get ("CTF_END_TIME"),
                'points'=>dynamicScoringFormula ($initialPts, $minPts, $decay, 0),
                'initial_points' => $initialPts,
                'minimum_points' => $minPts,
                'solve_decay' => $decay,
                'flag' => $_POST['flag'],
                'category' => $_POST['category'],
                'exposed' => $_POST['exposed']
            )
        );

        if ($id) {
            redirect('/admin/challenge.php?id=' . $id);
        } else {
            message_error('Could not insert new challenge.');
        }

    } else {

        validate_id($_POST['id']);

        if ($_POST['action'] === 'edit') {
            $challenge = db_select_one(
                'challenges',
                array(
                    'solves'
                ),
                array(
                    'id' => $_POST['id']
                )
            );

           db_update(
                'challenges',
                array(
                    'title'=>$_POST['title'],
                    'description'=>$_POST['description'],
                    'flag'=>$_POST['flag'],
                    'automark'=>$_POST['automark'],
                    'case_insensitive'=>$_POST['case_insensitive'],
                    'points' => dynamicScoringFormula ($_POST['initial_points'], $_POST['minimum_points'], $_POST['solve_decay'], $challenge['solves']),
                    'initial_points' => empty_to_zero($_POST['initial_points']),
                    'minimum_points' => empty_to_zero($_POST['minimum_points']),
                    'solve_decay' => empty_to_zero($_POST['solve_decay']),
                    'category'=>$_POST['category'],
                    'exposed'=>$_POST['exposed'],
                    'available_from'=>strtotime($_POST['available_from']),
                    'available_until'=>strtotime($_POST['available_until']),
                    'num_attempts_allowed'=>$_POST['num_attempts_allowed'],
                    'min_seconds_between_submissions'=>$_POST['min_seconds_between_submissions'],
                    'relies_on'=>$_POST['relies_on']
                ),
                array('id'=>$_POST['id'])
            );

            redirect('/admin/challenge.php?id='.$_POST['id'].'&generic_success=1');
        }

        else if ($_POST['action'] === 'delete') {

            if (!$_POST['delete_confirmation']) {
                message_error('Please confirm delete');
            }

            delete_challenge_cascading($_POST['id']);

            invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['id']);
            invalidate_cache(CONST_CACHE_NAME_CHALLENGE_HINTS . $_POST['id']);

            redirect('/admin/index.php?generic_success=1');
        }
    }
}