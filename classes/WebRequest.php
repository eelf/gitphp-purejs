<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class WebRequest {
    public static function generic() {
        $Logger = new Log_Logger();

        $Req = Request::initFromServer();

        $Controller = Routes::action($Req);

        $Resp = new Response();

        if ($Controller instanceof IWantSession) {
            $Controller->Session = Session::startFromCookie($Req, $Resp);
        }
        /** @var $Controller Controller_Startup */
        $Controller->run($Req, $Resp);

        if ($Controller instanceof IWantSession) {
            $Controller->Session->finish();
        }

        $Resp->setBodyItem('log', $Logger->getLines());

        $Resp->out();
    }
}
