<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

validate_id($_GET['id']);

$challenge = db_select_one(
    'challenges',
    array('*'),
    array('id' => $_GET['id'])
);

if (empty($challenge)) {
    message_error('No challenge found with this ID');
}

head('Site management');
menu_management();

section_title ('Edit challenge: ' . $challenge['title']);
form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_challenge');
$opts = db_query_fetch_all('SELECT * FROM categories ORDER BY title');

form_input_text('Title', $challenge['title']);
form_textarea('Description', $challenge['description']);
form_input_text('Flag', $challenge['flag']);
form_select($opts, 'Category', 'id', $challenge['category'], 'title');
form_input_checkbox('Exposed', $challenge['exposed']);

form_button_submit('Save changes');

section_subhead ("Advanced Settings:");
form_input_text('Initial Points', $challenge['initial_points'], null, "Initial Points");
form_input_text('Minimum Points', $challenge['minimum_points'], null, "Minimum Points");
form_input_text('Solve Decay', $challenge['solve_decay'], null, "Solve Decay");

$opts = db_query_fetch_all('
    SELECT
       ch.id,
       ch.title,
       ca.title AS category
    FROM challenges AS ch
    LEFT JOIN categories AS ca ON ca.id = ch.category
    ORDER BY ca.title, ch.title'
);

array_unshift($opts, array('id'=>0, 'title'=> '-- Depend on another challenge? --'));
form_select($opts, 'Relies on', 'id', $challenge['relies_on'], 'title', 'category');

form_input_text('Available from', date_time($challenge['available_from']), null, "Available from");
form_input_text('Available until', date_time($challenge['available_until']), null, "Available until");

form_input_checkbox('Automark', $challenge['automark']);
form_input_checkbox('Case insensitive', $challenge['case_insensitive']);
form_input_text('Num attempts allowed', $challenge['num_attempts_allowed'], null, "Max attempts allowed (0 for unlimited)");
form_input_text('Min seconds between submissions', $challenge['min_seconds_between_submissions'], null, "Submission cooldown");

form_hidden('action', 'edit');
form_hidden('id', $_GET['id']);

form_button_submit('Save changes');
form_end();

section_subhead ('Hints');

$hints = db_select_all(
    'hints',
    array(
        'id',
        'body',
        'visible'
    ),
    array(
        'challenge' => $_GET['id']
    )
);

foreach ($hints as $hint) {
  $msg = '<a style="margin: 0px; margin-right: 5px" href="edit_hint.php?id=' . htmlspecialchars($hint['id']) . '" class="btn btn-xs btn-warning">✎</a>';
  $msg .= '<strong>Hint!</strong> ' . get_bbcode()->parse($hint['body']);

  if ($hint["visible"] === 0) {
    $msg .= '<div class="inline-tag">(invisible)</div>';
  }

  message_inline_yellow ($msg, false);
}

echo '<div class="form-group">
    <a href="new_hint.php?id=',htmlspecialchars($_GET['id']),'" class="btn btn-lg btn-warning">
      Add hint
    </a>
</div>';

section_subhead ('Files');

$files = db_select_all(
    'files',
    array(
        'id',
        'title',
        'size',
        'added',
        'download_key'
    ),
    array(
        'challenge' => $_GET['id']
    )
);

foreach ($files as $file) {
  echo '<div class="challenge-file">';
  title_decorator ("blue", "0deg", "package.png");
  echo '<a style="margin: 0px; margin-right: 5px" href="edit_hint.php?id=' . htmlspecialchars($hint['id']) . '" class="btn btn-xs btn-primary">✎</a>';
  echo '<a target="_blank" href="../download?file_key=', htmlspecialchars($file['download_key']), '&team_key=', get_user_download_key(), '">', htmlspecialchars($file['title']), '</a>';

  if ($file['size']) {
    tag ('Size: ' . bytes_to_pretty_size($file['size']));
  }
  echo '</div>';

  form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_file', 'no-padding-or-margin');
  form_hidden('action', 'delete_file');
  form_hidden('id', $file['id']);
  form_hidden('challenge_id', $_GET['id']);
  form_button_submit_small ('Delete', 'btn-danger');
  form_end();
}

form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_file','','multipart/form-data');
form_file('file');
echo '<br>';
form_hidden('action', 'upload_file');
form_hidden('challenge_id', $_GET['id']);
form_button_submit('Upload file');
echo '(Max file size: ',bytes_to_pretty_size(max_file_upload_size()), ')';
form_end();

section_subhead('Delete challenge: ' . $challenge['title']);
form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_challenge');
message_inline_red('Warning! This will also delete all submissions, all hints and all files associated with challenge!');
form_input_checkbox('Delete confirmation', false, 'red');
form_hidden('action', 'delete');
form_hidden('id', $_GET['id']);
form_button_submit('Delete challenge', 'danger');
form_end();

foot();
