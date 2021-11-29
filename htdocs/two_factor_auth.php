<?php

require('../include/ctfx.inc.php');

head('2FA Guard');

if (user_is_logged_in() || !isset($_SESSION['id_before_2fa'])) {
    redirect(Config::get('REDIRECT_INDEX_TO'));
}

echo '
<div class="pre-category-name">Gateway:</div>
<div class="category-name typewriter">2FA</div>
<div style="display:flex; align-items:center">' . decorator_square("hand.png", "270deg", "#EF3E36", true, true, 24) . ' Supply your Two-Factor Authentication code.</div>

<form style="margin-top:8px; margin-bottom:8px" method="post" action="api" autocomplete="off">
<input type="hidden" name="action" value="login_2fa"/>'
. form_xsrf_token()
. '<input type="text" name="code" style="margin-bottom:8px" placeholder="2FA Code" required autofocus/><br>
<button class="btn-dynamic" type="submit">Submit</button>
</form>';

foot();