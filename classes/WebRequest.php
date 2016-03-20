<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class WebRequest
{
    public static function generic()
    {
        $Controller = Routes::action();

        $Req = new Request();
        $Resp = new Response();
        /** @var $Controller Controller_Startup */
        $resp = $Controller->run($Req, $Resp);
        echo json_encode($resp);

    }
}