<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Heads {
    public function run(Request $Req, Response $Resp) {
        $project = $Req->get('project');

        $config = Context::config();

        $projects = $config->get('projects');

        if (!isset($projects[$project])) {
            $Resp->setBodyItem('error', 'bad project:' . $project);
            return;
        }
        $dir = $projects[$project];

        $Git = new Git($config->get('git_bin'), $dir);
        $Git->logger = WebRequest::logger();

        list ($heads, $err) = $Git->getBranchHeads();

        if ($err) {
            $Resp->setBodyItem('error', $err);
            return;
        }
        foreach ($heads as $idx => $head) {
            $heads[$idx] = ['name' => $head];
        }
        $Resp->setBodyItem('heads', $heads);
    }
}
