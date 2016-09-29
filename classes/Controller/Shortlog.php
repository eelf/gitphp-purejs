<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Shortlog {
    public function run(Request $Req, Response $Resp) {
        $project = $Req->get('project');

        $config = Context::config();

        $projects = $config->get('projects');

        if (!isset($projects[$project])) {
            return $Resp->setBodyItem('error', 'bad project:' . $project);
        }
        $dir = $projects[$project];

        $Git = new Git('git', $dir);

        $ref = $Req->get('head');

        list ($revs, $err) = $Git->revList($ref);

        if ($err) {
            return $Resp->setBodyItem('error', $err);
        }
        $Resp->setBodyItem('revs', $revs);
    }
}
