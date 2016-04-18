<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Logout implements IWantSession {
    /** @var Session */
    public $Session;

    public function run(Request $Req, Response $Resp) {
        $this->Session->destroy();
        $Resp->setBodyItem('page', 'login');
    }
}
