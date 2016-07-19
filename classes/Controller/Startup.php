<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Startup {
    public function run(Request $Req, Response $Resp) {
        $url = parse_url($Req->get('url'));

        $Resp->setBodyItem('url_parsed', $url);

        $page = ltrim($url['path'], '/');
        if (!$page) $page = 'dashboard';

        $Resp->setBodyItem('page', $page);
    }
}
