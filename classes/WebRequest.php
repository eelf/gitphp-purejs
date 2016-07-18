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

    public static function generic(\Bootstrap $bootstrap) {
        self::$logger = new Log_Logger();

        set_error_handler([self::$logger, 'error']);

        $Config = new Config($bootstrap->getRoot() . '/config.php');
        Context::config($Config);

        $Req = Request::initFromServer();

        $Controller = Routes::action($Req);

        $Resp = new Response();

        $Session = Session::startFromCookie($Req, $Resp);
        Context::session($Session);

        $skip_auth = [
            Controller_Login::class,
        ];
        if (empty($Session['user_id']) && !in_array(get_class($Controller), $skip_auth)) {
            $Resp->setBodyItem('page', 'login');
        } else {
            $Controller->run($Req, $Resp);
        }

        $Session->finish();

        $Resp->setBodyItem('log', self::$logger->getLines());

        $Resp->out();
    }
}
