<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Login {
    public function run(Request $Req, Response $Resp) {
        $Session = Context::session();
        $user_id = $Session['user_id'];

        if ($user_id) {
            $Resp->setBodyItem('page', 'dashboard');
        } else if ($Req->body_err()) {
            $Resp->setBodyItem('error', $Req->body_err());
        } else {
            $body = $Req->body();

            WebRequest::logger()->info('req->body:' . var_export($body, 1));

            if (empty($body['name'])) {
                $Resp->setBodyItem('error', 'Empty login');
            } else if (empty($body['password'])) {
                $Resp->setBodyItem('error', 'Empty password');
            } else if ($body['name'] == 'ezh' && $body['password'] == '123') {
                $Session['user_id'] = 'ezh';
                $Resp->setBodyItem('page', 'dashboard');
            } else {
                $Resp->setBodyItem('error', 'Bad username or password');
            }
        }
    }
}
