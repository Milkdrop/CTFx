<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Site management');
menu_management();

section_title ('New category');
form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/new_category');
form_input_text('Title');
form_textarea('Description');
form_input_checkbox('Exposed', true);
form_button_submit('Create category');

section_subhead ("Advanced Settings:");
form_input_text('Available from', date_time(Config::get('MELLIVORA_CONFIG_CTF_START_TIME')));
form_input_text('Available until', date_time(Config::get('MELLIVORA_CONFIG_CTF_END_TIME')));
form_hidden('action', 'new');
form_end();

foot();