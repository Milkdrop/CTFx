<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Site management');
menu_management();
section_title ('News ', button_link ('Add News Item','../news'), "green");

$news = db_query_fetch_all('SELECT * FROM news ORDER BY added DESC');
foreach($news as $item) {
    echo '<div class="news-container">
        <div class="news-head"><h4 class="news-title">',
            htmlspecialchars($item['title']),
            '</h4>',
            '<a href="/admin/news.php?id='.htmlspecialchars($item['id']).'" class="btn btn-xs btn-warning">✎</a>',
           '</div>
        <div class="news-body">
            ',get_bbcode()->parse($item['body']),'
        </div>
    </div>';
}

foot();