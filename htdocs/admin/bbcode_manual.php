<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Site management');

menu_management();

section_title ('BBCode Manual');

bbcode_manual();

foot ();