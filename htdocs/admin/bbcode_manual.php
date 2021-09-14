<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Site management');

menu_management();

echo section_header('BBCode Manual');

bbcode_manual();

foot ();