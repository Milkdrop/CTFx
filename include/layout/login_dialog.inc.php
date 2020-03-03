<?php

function login_dialog() {
    echo '
    <div class="modal fade" id="login-dialog" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="login-dialog-form" method="post" class="form-signin light-theme" action="/actions/login">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        ',section_title ("Login:", "", "blue"),'
                    </div>
                    <div class="modal-body">
                            <input name="',md5(Config::get('MELLIVORA_CONFIG_SITE_NAME').'USR'),'" type="email" class="form-control form-group" placeholder="',lang_get('email_address'),'" id="login-email-input" required autofocus />
                            <input name="',md5(Config::get('MELLIVORA_CONFIG_SITE_NAME').'PWD'), '" type="password" class="form-control form-group" placeholder="',lang_get('password'),'" id="login-password-input" required />
                            <input type="hidden" name="action" value="login" />
                            <input type="hidden" name="redirect" value="',htmlspecialchars($_SERVER['REQUEST_URI']), '" />
                            <input type="checkbox" class="form-group" name="remember_me" value="1" checked> ',lang_get('remember_me'),'
                            <br>
                            <a href="reset_password">',lang_get('forgotten_password'),'</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">',lang_get('close'),'</button>
                        <button type="submit" class="btn btn-lg btn-primary" id="login-button">',lang_get('log_in'),'</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    ';
}