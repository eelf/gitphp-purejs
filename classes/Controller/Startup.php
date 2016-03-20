<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Startup {
    public function run(Request $Req, Response $Resp) {
        $Session = Session::startFromCookie();
        if (!$Session['user_id']) return ['page' => 'login'];
        return ['page' => 'dashboard'];
    }
}
