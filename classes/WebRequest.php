<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class WebRequest {
    /**
     * @var Log_Logger
     */
    private static $logger;

    /**
     * @return Log_Logger
     */
    public static function logger() {
        return self::$logger;
    }

    public static function generic() {
//        ini_set('display_errors', 1);
        self::$logger = new Log_Logger();

        set_error_handler([self::$logger, 'error']);

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

        $Resp->setBodyItem('log', self::$logger->getLines());

        $Resp->out();
    }
}
