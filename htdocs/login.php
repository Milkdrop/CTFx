<?php

require('../include/ctfx.inc.php');

if (user_is_logged_in()) {
    redirect(Config::get('REDIRECT_INDEX_TO'));
}

head('Login');

echo '<div>
<div class="pre-category-name">Gateway:</div>
<div class="category-name typewriter">Login / Register</div>
<form style="margin-top:8px; width:256px; margin-bottom:8px" method="post" action="api">
<input type="hidden" name="action" value="login" />
<input type="email" name="email" style="width:256px; margin-bottom:8px" placeholder="Email" required autofocus/><br>
<input type="password" name="password" style="width:256px; margin-bottom:8px" placeholder="Password" required/><br>'
. form_xsrf_token()
. '<button class="btn-dynamic" type="submit">Login</button>
</form>';

echo section_header("Don't have an account? Register:");
echo tag("Your team shares one account", "info.png", true, 'width: max-content');

echo '<form style="margin-top:8px;" method="post" action="api">
<input type="hidden" name="action" value="register" />
<input type="email" name="email" style="width:256px; margin-bottom:8px" placeholder="Email" required/><br>
<input type="password" name="password" style="width:256px; margin-bottom:8px" placeholder="Password" required/><br>
<input type="text" name="team_name" style="width:256px; margin-bottom:8px" placeholder="Team name" minlength="' 
. Config::get('MIN_TEAM_NAME_LENGTH') . '" maxlength="',Config::get('MAX_TEAM_NAME_LENGTH'),'" required/><br>';

$countries = api_get_countries();

echo '<select name="country" style="width:256px; margin-bottom:8px" required>
    <option disabled selected>-- Select a country --</option>';

foreach ($countries as $country) {
    echo '<option value="' . htmlspecialchars($country['id']) . '">' . htmlspecialchars($country['country_name']) . '</option>';
}

echo '</select><br>';

if (Config::get('ENABLE_CAPTCHA')) {
    echo display_captcha();
}

echo form_xsrf_token() . '<button class="btn-dynamic" type="submit">Register</button>
</form>
</div>
<img style="width:35%; position: absolute; top: 35%; right: 12%;" src="'.Config::get('URL_STATIC_RESOURCES').'/img/logo.png">';

foot();