<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Exceptions');
menu_management();

if (array_get($_GET, 'user_id')) {
    echo section_header('User exceptions', button_link('Show all exceptions', '/admin/exceptions'));
} else if (array_get($_GET, 'delete')) {
    echo section_header('Clear exceptions');
  form_start('/admin/actions/exceptions');
  form_input_checkbox('Delete confirmation', false, 'red');
  form_hidden('action', 'delete');
  message_inline('Warning! This will delete ALL exception logs!!', "red");
  form_button_submit('Clear exceptions', '3');
  form_end();
  die(foot());
} else {
    echo section_header('Exceptions', button_link('Clear exceptions', '/admin/exceptions?delete=1'));
}

echo '
    <table id="hints" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Message</th>
          <th>Added</th>
          <th>User</th>
          <th>IP</th>
        </tr>
      </thead>
      <tbody>
    ';

$where = array();
if (is_valid_id(array_get($_GET, 'user_id'))) {
    $where['added_by'] = $_GET['user_id'];
}

$from = get_pager_from($_GET);
$num_exceptions = db_count_num('exceptions', $where);

pager(
    '/admin/exceptions',
    $num_exceptions,
    CONST_NUM_EXCEPTIONS_PER_PAGE,
    $from
);

$query = 'SELECT
       e.id,
       e.message,
       e.added,
       e.added_by,
       e.trace,
       INET_NTOA(e.user_ip) AS user_ip,
       u.team_name
    FROM exceptions AS e
    LEFT JOIN users AS u ON u.id = e.added_by
    ';

if (!empty($where)) {
    $query .= 'WHERE '.implode('=? AND ', array_keys($where)).'=? ';
}

$query .= 'ORDER BY e.id DESC
           LIMIT '.$from.', '.CONST_NUM_EXCEPTIONS_PER_PAGE;

$exceptions = db_query_fetch_all($query, array_values($where));

foreach($exceptions as $exception) {
    echo '
    <tr>
        <td>',htmlspecialchars($exception['message']),'</td>
        <td>',formatted_date($exception['added']),'</td>
        <td>',($exception['added_by'] ?
         '<a href="/admin/user.php?id='.htmlspecialchars($exception['added_by']).'">'.htmlspecialchars($exception['team_name']).'</a>'
         :
         '<i>N/A</i>'),'
        </td>
        <td><a href="/admin/ip_log.php?ip=',htmlspecialchars($exception['user_ip']),'">',htmlspecialchars($exception['user_ip']),'</a></td>
    </tr>
    <tr>
        <td colspan="4">
            <pre>',nl2br(htmlspecialchars($exception['trace'])),' </pre>
        </td>
    </tr>
    ';
}

echo '</tbody>
    </table>';

foot();
