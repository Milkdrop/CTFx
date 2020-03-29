<?php

require('../../include/mellivora.inc.php');

validate_xsrf_token(array_get($_POST, CONST_XSRF_TOKEN_KEY));

logout();