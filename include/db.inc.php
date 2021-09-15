<?php

// set global db variable
$db = null;

function get_global_db_pdo() {
    global $db;

    if ($db === null) {
        try {
            $db = new PDO(
                Config::get('MELLIVORA_CONFIG_DB_ENGINE').':' .
                'host='.Config::get('MELLIVORA_CONFIG_DB_HOST').';' .
                'port='.Config::get('MELLIVORA_CONFIG_DB_PORT').';' .
                'dbname='.Config::get('MELLIVORA_CONFIG_DB_NAME').';' .
                'charset=utf8', Config::get('MELLIVORA_CONFIG_DB_USER'),
                Config::get('MELLIVORA_CONFIG_DB_PASSWORD')
            );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (Exception $e) {
            message_inline('Caught exception connecting to database', "red");
            throw $e;
        }
    }

    return $db;
}

function sql_get_news() {
    return db_query_fetch_all('SELECT * FROM news ORDER BY added DESC');
}

function sql_get_categories() {
    return db_query_fetch_all('SELECT id, title, description FROM categories WHERE exposed = 1 ORDER BY title ASC');
}

function sql_get_challenges_for_category($category, $from_user = 0) {
    if (is_valid_id($category)) {
        if ($from_user === 0) {
            // Bypass exposed to allow admin reads in the interface
            return db_query_fetch_all('
                SELECT id, title, description, points, exposed, relies_on
                FROM challenges
                WHERE category = :category
                ORDER BY points ASC, id ASC',
                array('category'=>$category)
            );
        } else {
            // TODO: If top 3 people submit correct flags at once, no one would have the first blood
            return db_query_fetch_all('
                SELECT id, title, description, points, exposed, relies_on,
                    (SELECT COUNT(*) FROM submissions WHERE challenge = c.id AND correct = 1
                        AND added <= (SELECT added FROM submissions WHERE challenge = c.id AND user_id = :user_id AND correct = 1)) AS solve_position,
                    (SELECT max(added) FROM submissions AS ss WHERE ss.challenge = c.id AND ss.user_id = :user_id2) AS latest_submission_added
                FROM challenges AS c
                WHERE category = :category AND exposed = 1
                ORDER BY points ASC, id ASC',
                array(
                    'user_id'=>$from_user,
                    'user_id2'=>$from_user, // Needed for now because PDO needs to have the same amount of values as params
                    'category'=>$category
                )
            );
        }
        
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

function db_insert ($table, array $fields) {
    $db = get_global_db_pdo();

    try {
        $sql = 'INSERT INTO '.$table.' (';
        $sql .= implode(', ', array_keys($fields));
        $sql .= ') VALUES (';
        $sql .= implode(', ', array_fill(0, count($fields), '?'));
        $sql .= ')';

        $stmt = $db->prepare($sql);

        // fix null values
        array_walk($fields, 'null_to_bool');

        // get the field values
        $values = array_values($fields);

        $stmt->execute($values);

        return $db->lastInsertId();

    } catch (PDOException $e) {
        sql_exception($e);
    }
}

function db_insert_multiple ($table, array $rows) {
    $db = get_global_db_pdo();

    try {
        $sql = 'INSERT INTO '.$table.' (';
        $sql .= implode(', ', array_keys($rows[0]));
        $sql .= ') VALUES ';

        $values = array ();

        foreach ($rows as $row) {
            $sql .= '(' . implode(', ', array_fill(0, count($row), '?')) . '),';
            array_walk ($row, 'null_to_bool');
            $values = array_merge ($values, array_values ($row));
        }

        $sql = substr($sql, 0, -1);

        $stmt = $db->prepare($sql);

        var_dump ($values);
        $stmt->execute($values);

        return $db->lastInsertId();

    } catch (PDOException $e) {
        sql_exception($e);
    }
}

function db_update_all ($table, array $fields) {
    $db = get_global_db_pdo();

    try {
        $sql = 'UPDATE '.$table.' SET ';
        $sql .= implode('=?, ', array_keys($fields)).'=? ';

        $stmt = $db->prepare($sql);

        // fix null values
        array_walk($fields, 'null_to_bool');

        // get the field values and "WHERE" values. merge them into one array.
        $values = array_values($fields);

        // execute the statement
        $stmt->execute($values);

        return $stmt->rowCount();

    } catch (PDOException $e) {
        sql_exception($e);
    }
}

function db_update ($table, array $fields, array $where, $whereGlue = 'AND') {
    $db = get_global_db_pdo();

    try {
        $sql = 'UPDATE '.$table.' SET ';
        $sql .= implode('=?, ', array_keys($fields)).'=? ';
        $sql .= 'WHERE '.implode('=? '.$whereGlue.' ', array_keys($where)).'=?';

        $stmt = $db->prepare($sql);

        // fix null values
        array_walk($fields, 'null_to_bool');

        // get the field values and "WHERE" values. merge them into one array.
        $values = array_merge(array_values($fields), array_values($where));

        // execute the statement
        $stmt->execute($values);

        return $stmt->rowCount();

    } catch (PDOException $e) {
        sql_exception($e);
    }
}

function db_delete ($table, array $where, $whereGlue = 'AND') {
    $db = get_global_db_pdo();

    try {
        $sql = 'DELETE FROM '.$table.' ';
        $sql .= 'WHERE '.implode('=? '.$whereGlue.' ', array_keys($where)).'=?';

        $stmt = $db->prepare($sql);

        // get the field values and "WHERE" values
        $values = array_values($where);

        // execute the statement
        $stmt->execute($values);

        return $stmt->rowCount();

    } catch (PDOException $e) {
        sql_exception($e);
    }
}

function db_count_num ($table, array $where = null, $whereGlue = 'AND') {
    $count = db_select_one ($table, array('COUNT(*) AS num'), $where, null, $whereGlue, false);
    return $count['num'];
}

function db_select_one ($table, array $fields, array $where = null, $orderBy = null, $whereGlue = 'AND') {
    return db_select ($table, $fields, $where, $orderBy, $whereGlue, false);
}

function db_select_all ($table, array $fields, array $where = null, $orderBy = null, $whereGlue = 'AND') {
    return db_select ($table, $fields, $where, $orderBy, $whereGlue, true);
}

function db_select ($table, array $fields, array $where = null, $orderBy = null, $whereGlue = 'AND', $all = true) {
    $db = get_global_db_pdo();

    try {
        $query = 'SELECT '.implode(', ', $fields).' ';
        $query .= 'FROM '.$table.' ';

        if (!empty($where)) {
            $query .= 'WHERE '.implode('=? '.$whereGlue.' ', array_keys($where)).'=?';
        }

        if (!empty($orderBy)) {
            $query .= ' ORDER BY ' . $orderBy;
        }

        if (!empty($where)) {
            $stmt = $db->prepare($query);

            // get the field values and "WHERE" values. merge them into one array.
            $values = array_values($where);

            // execute the statement
            $stmt->execute($values);
        }

        else {
            $stmt = $db->query($query);
        }

        if ($all) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        else {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        sql_exception($e);
    }
}

function db_query_fetch_one ($query, array $values = null) {
    return db_query($query, $values, false);
}

function db_query_fetch_all ($query, array $values = null) {
    return db_query($query, $values, true);
}

function db_query_fetch_none ($query, array $values = null) {
    db_query($query, $values, null);
}

function db_query ($query, array $values = null, $all = true) {
    $db = get_global_db_pdo();

    try {
        if (!empty($values)) {
            $stmt = $db->prepare($query);
            $stmt->execute($values);
        }

        else {
            $stmt = $db->query($query);
        }

        if ($all === true) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        else if ($all === false) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        sql_exception($e);
    }
}

function db_begin_transaction() {
    $db = get_global_db_pdo();
    $db->beginTransaction();
}

function db_end_transaction() {
    $db = get_global_db_pdo();
    $db->commit();
}

function db_rollback_transaction() {
    $db = get_global_db_pdo();
    $db->rollBack();
}

function sql_exception (PDOException $e) {
    log_exception($e);
    message_error('An SQL exception occurred. Please check the exceptions log.');
}

function null_to_bool(&$val) {
    if (!isset($val)) {
        $val = 0;
    }
}