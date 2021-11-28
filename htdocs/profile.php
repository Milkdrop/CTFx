<?php

require('../include/ctfx.inc.php');

enforce_authentication();

head('Profile');

$user = db_select_one(
    'users',
    array(
        'team_name',
        'email',
        'competing',
        'country_id',
        '2fa_status'
    ),
    array('id' => $_SESSION['id'])
);

echo '
<div class="pre-category-name">Gateway:</div>
<div class="category-name typewriter">Edit profile</div>
<br>
<a style="font-weight:bold; font-size:24px; margin-bottom:8px" href="user?id=' . $_SESSION['id'] . '">View public profile</a>
<form style="margin-top:8px; margin-bottom:8px" method="post" action="api">
<input type="hidden" name="action" value="update_profile"/>
<input type="email" name="email" style="margin-bottom:8px" placeholder="Email" value="' . htmlspecialchars($user['email']) . '" required/><br>
<input type="text" name="team_name" style="margin-bottom:8px" placeholder="Team name" minlength="' . Config::get('MIN_TEAM_NAME_LENGTH') . '" maxlength="',Config::get('MAX_TEAM_NAME_LENGTH'),'" value="' . htmlspecialchars($user['team_name']) . '" required/><br>';

$countries = api_get_countries();

echo '<select name="country" style="width: 270px; margin-bottom:8px" required>';

foreach ($countries as $country) {
    echo '<option ' . (($country['id'] == $user['country_id'])?'selected':'') . ' value="' . htmlspecialchars($country['id']) . '">' . htmlspecialchars($country['country_name']) . '</option>';
}

echo '</select><br>'
. form_xsrf_token()
. '<button class="btn-dynamic" type="submit">Update</button>
</form>';

echo section_header("Profile picture");
echo '<img src="https://www.gravatar.com/avatar/' . md5($user["email"]) . '?s=256&d=mp"/>';
echo tag('<b>You can change your profile picture using <a href="https://gravatar.com">Gravatar</a></b>', 'info.png', true, 'width: max-content');

echo section_header("Two-Factor Autentication (using Google Authenticator)");

echo '<form style="margin-top:8px; margin-bottom:8px" method="post" action="api">'
. form_xsrf_token();

if ($user['2fa_status'] == 'generated') {
    echo '<input type="hidden" name="action" value="enable_2fa"/>';
    echo '<img style="margin-bottom:4px" src="'.get_two_factor_auth_qr_url().'"/><br>';
    echo tag("Scan the QR Code using a 2FA TOTP App such as Google Authenticator to obtain the code", 'info.png', true, 'width: max-content');
    echo '<input style="margin-bottom:8px" type="text" name="code" placeholder="2FA Code" required/><br>';
    echo '<button class="btn-dynamic" type="submit">Enable 2FA</button>';
}

else if ($user['2fa_status'] == 'disabled') {
    echo '<input type="hidden" name="action" value="generate_2fa"/>';
    echo '<button class="btn-dynamic" type="submit">Generate QR</button>';
}

else if ($user['2fa_status'] == 'enabled') {
    echo '<input type="hidden" name="action" value="disable_2fa"/>';
    echo '<img style="margin-bottom:4px" src="'.get_two_factor_auth_qr_url().'"/><br>';
    echo '<button class="btn-dynamic" type="submit">Disable 2FA</button>';
}

echo '</form>';

echo section_header("Change password");

echo '<form style="margin-top:8px; margin-bottom:8px" method="post" action="api">'
. form_xsrf_token();

echo '<input type="hidden" name="action" value="change_password"/>
<input style="margin-bottom:8px" type="text" name="current_password" placeholder="Current password" required/><br>
<input style="margin-bottom:8px" type="text" name="new_password" placeholder="New password" required/><br>
<input style="margin-bottom:8px" type="text" name="new_password_repeat" placeholder="New password (repeat)" required/><br>
<button class="btn-dynamic" type="submit">Update</button>
</form>';

foot();