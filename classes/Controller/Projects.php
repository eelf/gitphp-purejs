<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Projects {
    public function run(Request $Req, Response $Resp) {
        $list = Context::config()->get('projects');
        $Resp->setBodyItem('projects', array_keys($list));
    }
}