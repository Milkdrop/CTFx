<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Site management');
menu_management();

section_header('Edit CTF Settings');

section_subhead ("Challenge Start / End Times:");
form_start('/admin/actions/edit_ctf');
form_input_text('CTF Start Time', date_time(Config::get('CTF_START_TIME')));
form_input_text('CTF End Time', date_time(Config::get('CTF_END_TIME')));
message_inline('This will update all challenges to start / end at the dates above, but it won\'t change the dates in the config');
form_hidden('action', 'change_times');
form_button_submit('Update');
form_end();

foot();
