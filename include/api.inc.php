<?php

function api_get_news() {
    return db_query_fetch_all('SELECT * FROM news ORDER BY added DESC');
}

function api_get_categories() {
    return db_query_fetch_all('SELECT id, title, description FROM categories ORDER BY title ASC');
}

function api_get_challenges_from_category($category, $for_user) {
    if (is_valid_id($category)) {
        // TODO: If top 3 people submit correct flags at once, no one would have the first blood
        $result = db_query_fetch_all('
            SELECT c.id, c.title, c.description, c.authors, c.points, c.relies_on, c.flaggable,
            (SELECT COUNT(id) FROM submissions WHERE challenge = c.id AND correct = 1
                AND added <= (SELECT added FROM submissions WHERE challenge = c.id AND user_id = :user_id AND correct = 1)) AS solve_position,
                (SELECT max(added) FROM submissions AS ss WHERE ss.challenge = c.id AND ss.user_id = :user_id2) AS latest_submission_added
            FROM challenges AS c
            WHERE category = :category AND exposed = 1
            ORDER BY points ASC, c.id ASC',
            array(
                'category' => $category,
                'user_id' => $for_user,
                'user_id2' => $for_user
            )
        );


        foreach ($result as &$challenge) {
            if (!empty($challenge['relies_on'])) {
                $user_has_solved = db_query_fetch_one('SELECT max(correct) AS solved FROM submissions WHERE challenge=:challenge AND user_id=:user_id', array("challenge" => $challenge['relies_on'], "user_id" => $for_user))['solved'];
                if (!$user_has_solved) {
                    $relies_on_data = db_query_fetch_one('SELECT ch.id, ch.title, ch.category, ca.title AS category_title FROM challenges AS ch LEFT JOIN categories AS ca ON ca.id = ch.category WHERE ch.id=:challenge', array("challenge" => $challenge['relies_on']));
                    
                    $challenge['relies_on_data'] = $relies_on_data;
                    $challenge['dependency_unsatisfied'] = true;    
                }
            }
        }

        return $result;
    } else {
        return array();
    }
}

function api_get_challenge_info($challenge) {
    if (is_valid_id($challenge)) {
        $result = db_query_fetch_one('
            SELECT c.id, c.title, c.description, c.authors, c.points, c.solves, c.release_time, c.relies_on, c.flaggable FROM challenges AS c
            WHERE id = :id AND exposed = 1',
            array(
                'id' => $challenge
            )
        );

        // Make sure to hide info if the user hasn't satisfied the dependency
        return $result;
    } else {
        return array();
    }
}

function get_submissions_for_challenge($challenge) {
    if (is_valid_id($challenge)) {
        return db_query_fetch_all(
            'SELECT
                u.id AS user_id,
                u.team_name,
                MAX(s.added) AS solve_timestamp,
                MAX(s.correct) AS has_solve,
                COUNT(s.id) AS tries
            FROM users AS u
            LEFT JOIN submissions AS s ON s.user_id = u.id
            WHERE
                u.competing = 1 AND
                s.challenge = :id
            GROUP BY u.id
            ORDER BY solve_timestamp ASC',
            array('id' => $_GET['id'])
        );
    } else {
        return array();
    }
}

function get_num_participating_users() {
    return db_query_fetch_one('SELECT COUNT(*) AS num FROM users')['num'];
}

function get_user_info($user) {
    if (is_valid_id($user)) {
        return db_query_fetch_one('
            SELECT
                u.team_name,
                u.email,
                u.competing,
                co.country_name,
                co.country_code,
                COALESCE(SUM(c.points),0) + extra_points AS score
            FROM users AS u
            LEFT JOIN countries AS co ON co.id = u.country_id
            LEFT JOIN submissions AS s ON u.id = s.user_id AND s.correct = 1
            LEFT JOIN challenges AS c ON c.id = s.challenge
            WHERE
            u.id = :user_id',
            array('user_id' => $_GET['id'])
        );
    } else {
        return array();
    }
}

function get_countries() {
    return db_select_all('countries', array('id', 'country_name'), null, 'country_name ASC');
}

// TODO: Forbid access when challenge is hidden
function api_get_targets_for_challenge($challenge) {
    if (is_valid_id($challenge)) {
        return db_query_fetch_all('SELECT id, challenge, url FROM targets WHERE challenge=:challenge', array('challenge' => $challenge));
    } else {
        return array();
    }
}

// TODO: Forbid access when challenge is hidden
function api_get_files_for_challenge($challenge) {
    if (is_valid_id($challenge)) {
        return db_query_fetch_all('SELECT id, challenge, name, url FROM files WHERE challenge=:challenge', array('challenge' => $challenge));
    } else {
        return array();
    }
}

// TODO: Forbid access when challenge is hidden
function api_get_hints_for_challenge($challenge) {
    if (is_valid_id($challenge)) {
        return db_query_fetch_all('SELECT id, challenge, content FROM hints WHERE challenge=:challenge', array('challenge' => $challenge));
    } else {
        return array();
    }
}

/* Functions normally used by admin sections */

function api_admin_get_challenges_from_category($category) {
    if (is_valid_id($category)) {
        return db_query_fetch_all('
            SELECT * FROM challenges
            WHERE category = :category
            ORDER BY points ASC, id ASC',
            array(
                'category' => $category
            )
        );
    } else {
        return array();
    }
}