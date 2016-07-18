<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Startup {
    public function run(Request $Req, Response $Resp) {
        $Session = Context::session();
        if (!$Session['user_id']) $Resp->setBodyItem('page', 'login');
        else $Resp->setBodyItem('page', 'dashboard');
    }
}
