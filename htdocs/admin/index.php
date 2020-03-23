<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Site management');

menu_management();

check_server_configuration();

$categories = db_query_fetch_all('SELECT * FROM categories ORDER BY title');

section_title ('Dashboard', button_link ('Add category','/admin/category'));

// Print categories + challenges

echo '<div class="dashboard-panel">';
foreach($categories as $category) {
    echo '<h4>';

    edit_link ('category.php?id='.htmlspecialchars($category['id']),
      '✎ <b>'.htmlspecialchars($category['title']).'</b>',
      $category['exposed']?'':'glyphicon-eye-close',
      $category['exposed']?'':'Invisible');

    echo ' <a href="challenge.php?category=',htmlspecialchars($category['id']),'" class="btn btn-xs btn-1">Add challenge</a>
    </h4>';

    $challenges = db_select_all(
        'challenges',
        array(
            'id',
            'title',
            'description',
            'exposed',
            'available_from',
            'available_until',
            'points'
        ),
        array('category' => $category['id']),
        'points ASC'
    );

    if (empty($challenges)) {
      echo '<div style="height:5px"></div>';
    } else {
      echo '<table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th class="center">Points</th>
        </tr>
      </thead>
      <tbody>';

      foreach ($challenges as $challenge) {
        echo '<tr>
        <td>';

        $url = 'challenge.php?id='.htmlspecialchars($challenge['id']);
        $contents = '<b>✎ '.htmlspecialchars($challenge['title']).'</b>';

        if (!$challenge['exposed']) {
          edit_link ($url, $contents, "glyphicon-eye-close", "Invisible");
        } else if (!$category['exposed']) {
          edit_link ($url, $contents, "glyphicon-eye-close", "Invisible due to category");
        } else if (!is_item_available ($challenge['available_from'], $challenge['available_until'])) {
          edit_link ($url, $contents, "glyphicon-eye-open", "Visible, but unflaggable");
        } else {
          edit_link ($url, $contents);
        }

      echo '</td>
          <td>', htmlspecialchars(short_description($challenge['description'], 50)), '</td>
          <td class="center">', number_format($challenge['points']), '</td>
        </tr>';
    }

    echo '</tbody></table>';
  }
}

// Print 2 columns underneath the challenges

echo '</div>
<div class="row">
<div class="col-sm-6">';

// Print left column

echo '<div class="row">';

$activeUsers = db_query_fetch_one ('
    SELECT
      COUNT(*) AS num
    FROM users AS u
    WHERE
      u.last_active > :date_bottom_limit',
    array('date_bottom_limit' => time () - 3600 * 2))['num'];

$totalUsers = db_count_num ("users");
card_simple ($activeUsers . "/" . $totalUsers, "Active Users", "/img/ui/user.png");

$load = sys_getloadavg ();
card_simple (($load[2] * 100) . "%", "CPU Usage", "/img/ui/cpu.png");

$memusage = get_system_memory_usage ();
if ($memusage != 0)
  card_simple (bytes_to_pretty_size ($memusage), "Memory Usage", "/img/ui/ram.png");

$correctSubmissions = db_count_num ("submissions", array ("correct" => 1));
$totalSubmissions = db_count_num ("submissions");
card_simple ($correctSubmissions . "/" . $totalSubmissions, "Correct Submissions", "/img/ui/cloud.png");

echo '</div>';

// Print right column

echo '</div>
<div class="col-sm-6">';

section_head ("News", button_link ('Add news','/admin/news'));

$news = db_query_fetch_all('SELECT * FROM news ORDER BY added DESC');

if (empty ($news)) {
  message_inline ("No news");
}

foreach($news as $item) {
    echo '<div class="ctfx-card">
        <div class="ctfx-card-head">
          <h4>',edit_link ('/admin/news.php?id=' . htmlspecialchars($item['id']), '✎ ' . htmlspecialchars($item['title'])),'</h4>
        </div>
        <div class="ctfx-card-body">
            ',get_bbcode()->parse($item['body']),'
        </div>
    </div>';
}

echo '</div></div>';

foot();