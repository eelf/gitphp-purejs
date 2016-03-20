<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace Gitphp;

class Controller_Login
{
    public function run() {
        $Session = Session::startFromCookie();
        $user_id = $Session['user_id'];
        return ['page' => 'login', 'log' => "user_id=$user_id\npost:" . file_get_contents('php://input')];
    }
}