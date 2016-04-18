<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Login implements IWantSession {
    public $Session;

    public function run(Request $Req, Response $Resp) {
        $user_id = $this->Session['user_id'];

        if ($user_id) {
            $Resp->setBodyItem('page', 'dashboard');
        } else if ($Req->body_err()) {
            $Resp->setBodyItem('error', $Req->body_err());
        } else {
            $body = $Req->body();

            WebRequest::logger()->info('req->body:' . var_export($body, 1));

            if ($body['name'] == 'ezh' && $body['password'] == '123') {
                $this->Session['user_id'] = 'ezh';
                $Resp->setBodyItem('page', 'dashboard');
            } else {
                $Resp->setBodyItem('page', 'login');
            }
        }
    }
}
