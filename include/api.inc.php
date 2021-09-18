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
        return db_query_fetch_all('
            SELECT c.id, c.title, c.description, c.points, c.relies_on,
            (SELECT COUNT(id) FROM submissions WHERE challenge = c.id AND correct = 1
                AND added <= (SELECT added FROM submissions WHERE challenge = c.id AND user_id = :user_id AND correct = 1)) AS solve_position,
                (SELECT max(added) FROM submissions AS ss WHERE ss.challenge = c.id AND ss.user_id = :user_id2) AS latest_submission_added
            FROM challenges AS c
            WHERE category = :category AND exposed = 1
            ORDER BY points ASC, c.id ASC',
            array(
                'category'=>$category,
                'user_id'=>$for_user,
                'user_id2'=>$for_user
            )
        );
    } else {
        return array();
    }
}

function sql_get_challenge_data($challenge_id, $check_solved_user_id = 0) {
    if (is_valid_id($challenge_id)) {
        if ($from_user === 0) {
            return db_query_fetch_one('SELECT * FROM challenges WHERE id = :challenge', array('challenge'=>$challenge_id));
        } else {
            return db_query_fetch_one('
                SELECT *,(SELECT added FROM submissions WHERE challenge = c.id AND user_id = :user_id AND correct = 1) AS correct_submission FROM challenges AS c WHERE id = :challenge AND exposed = 1', array('challenge'=>$challenge_id, 'user_id' => $check_solved_user_id));
        }
    } else {
        return array();
    }
}