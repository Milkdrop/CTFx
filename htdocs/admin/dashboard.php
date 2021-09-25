<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Dashboard');
admin_menu();

check_server_configuration();

foot();