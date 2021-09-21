<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Submissions');
admin_menu();

$where = array();
if (array_get($_GET, 'only_needing_marking')) {
    $only_needing_marking = true;
} else {
    $only_needing_marking = false;
}

if (is_valid_id(array_get($_GET, 'user_id'))) {
    $where['user_id'] = $_GET['user_id'];
}

$query = '
    FROM submissions AS s
    LEFT JOIN users AS u on s.user_id = u.id
    LEFT JOIN challenges AS c ON c.id = s.challenge
';

if (!empty($where)) {
    $query .= 'WHERE '.implode('=? AND ', array_keys($where)).'=? ';
}

if (array_get($_GET, 'user_id')) {
    echo section_header('User submissions', button_link('List all submissions', 'submissions?only_needing_marking=0'));
} else if ($only_needing_marking) {
    echo section_header('Submissions in need of marking', button_link('List all submissions', 'submissions?only_needing_marking=0'));
} else {
    echo section_header('All submissions', button_link('Show only in need of marking', 'submissions?only_needing_marking=1'));
}

$num_subs = db_query_fetch_one('
    SELECT
       COUNT(*) AS num
    '. $query,
    array_values($where)
);

$from = get_pager_from($_GET);
$results_per_page = 70;

pager('/admin/submissions', $num_subs['num'], $results_per_page, $from);

echo '<table id="files" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Challenge</th>
          <th>Team name</th>
          <th>Added</th>
          <th>Flag</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>';

$submissions = db_query_fetch_all('
    SELECT
       s.id,
       u.id AS user_id,
       u.team_name,
       s.added,
       s.correct,
       s.flag,
       c.id AS challenge_id,
       c.title AS challenge_title
    '.$query.'
    ORDER BY s.added DESC
    LIMIT '.$from.', '.$results_per_page,
    array_values($where)
);

foreach($submissions as $submission) {
    echo '<tr>
      <td><a href="/challenge.php?id=',htmlspecialchars($submission['challenge_id']),'">',htmlspecialchars($submission['challenge_title']),'</a></td>
      <td><a href="/admin/user.php?id=',htmlspecialchars($submission['user_id']),'">',htmlspecialchars($submission['team_name']),'</a></td>
      <td>' . timestamp($submission['added'], ' ago') . '</td>
      <td>
      <form method="post" action="/admin/actions/submissions" class="discreet-inline">
        <input type="hidden" name="action" value="',($submission['correct'] ? 'mark_incorrect' : 'mark_correct'),'" />
        <input type="hidden" name="id" value="',htmlspecialchars($submission['id']),'" />
        <input type="hidden" name="from" value="',htmlspecialchars($_GET['from']),'" />';
        echo form_xsrf_token();

    if ($submission['correct']) {
      echo '<button type="submit" style="color: #C2E812" title="Click to mark incorrect"
        class="has-tooltip" data-toggle="tooltip" data-placement="top">
        ',htmlspecialchars($submission['flag']),' <img src="/img/ui/correct.png">
        </button>';
    } else {
      echo '<button type="submit" style="color: #F2542D" title="Click to mark correct"
        class="has-tooltip" data-toggle="tooltip" data-placement="top">
        ',htmlspecialchars($submission['flag']),' <img src="/img/ui/wrong.png">
        </button>';
    }
    
    echo '</form></td>

    <td>
    <form method="post" action="/admin/actions/submissions">';
    echo form_xsrf_token();
    echo '
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="',htmlspecialchars($submission['id']),'" />
              <input type="hidden" name="from" value="',htmlspecialchars($_GET['from']),'" />
              <button type="submit" class="btn btn-xs btn-3">Delete</button>
          </form>
      </td>
    </tr>';
}

echo '</tbody>
    </table>';

foot();
