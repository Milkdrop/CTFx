<?php

require('../../include/ctfx.inc.php');

enforce_authentication(true);

redirect(Config::get('URL_BASE_PATH') . 'admin/challenges');