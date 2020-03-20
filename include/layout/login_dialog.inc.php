<?php

function login_dialog() {
    echo '
    <div class="modal fade" id="login-dialog" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="login-dialog-form" method="post" class="form-signin light-theme" action="/actions/login">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        ',section_head ("Login:", "", "blue"),'
                    </div>
                    <div class="modal-body">
                            <input name="',md5(Config::get('MELLIVORA_CONFIG_SITE_NAME').'USR'),'" type="email" class="form-control form-group" placeholder="',lang_get('email_address'),'" id="login-email-input" required autofocus />
                            <input name="',md5(Config::get('MELLIVORA_CONFIG_SITE_NAME').'PWD'), '" type="password" class="form-control form-group" placeholder="',lang_get('password'),'" id="login-password-input" required />
                            <input type="hidden" name="action" value="login" />
                            <input type="hidden" name="redirect" value="',htmlspecialchars($_SERVER['REQUEST_URI']), '" />

                            <div class="form-group">
                                <div class="checkbox-blue">
                                    <input type="checkbox" class="form-control" name="remember_me" value="1" checked="checked"/>
                                </div>
                                <label class="control-label" for="remember_me">Remember Me</label>
                            </div>
                            <a href="reset_password">',lang_get('forgotten_password'),'</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-lg btn-4" data-dismiss="modal">',lang_get('close'),'</button>
                        <button type="submit" class="btn btn-lg btn-1" id="login-button">',lang_get('log_in'),'</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    ';
}