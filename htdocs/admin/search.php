<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Site management');
menu_management();

echo section_header('Search');

form_start('/admin/actions/search');
form_input_text('Search for');

$opts[] = array('id'=>'users','name'=>'Users');
$opts[] = array('id'=>'ip_log','name'=>'IP log');

form_select($opts, 'Search in', 'id', 'users', 'name');
form_button_submit('Search');
echo form_xsrf_token();
form_end();

foot();