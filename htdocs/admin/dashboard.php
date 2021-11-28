<?php

require('../../include/ctfx.inc.php');

enforce_authentication(true);

head('Dashboard');
admin_menu();

check_server_configuration();

foot();