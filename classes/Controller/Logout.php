<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Logout {
    public function run(Request $Req, Response $Resp) {
        $Session = Context::session();
        $Session->destroy();
        $Resp->setBodyItem('page', 'login');
    }
}
