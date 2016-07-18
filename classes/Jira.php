<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace GitPHP;

class Jira
{
    const COOKIE_NAME = 'crowd.token_key';

    protected static $instance;

    public static function instance()
    {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    public function authenticatePrincipalByCookie($crowd_token_key)
    {
        $Response = $this->request('GET', 'usermanagement/latest/session/' . urlencode($crowd_token_key));

        $err = null;
        if ($Response->status_code != 200) {
            $err = isset($Response->body['message']) ? $Response->body['message'] : ($Response->status_code . $Response->status_text);
        }
        if (!isset($Response->body['user']['name']) || !isset($Response->body['user']['display-name']) || !isset($Response->body['user']['email'])) {
            $err = 'Bad response:' . print_r($Response->body, true);
        }

        if ($err) return [null, $err];

        $result = [
            'user_id' => $Response->body['user']['name'],
            'user_name' => $Response->body['user']['display-name'],
            'user_email' => $Response->body['user']['email'],
            'user_token' => $crowd_token_key,
        ];

        return [$result, null];
    }

    public function authenticatePrincipal($login, $password)
    {
        $data = json_encode(['value' => $password]);
        $Response = $this->request('POST', 'usermanagement/latest/authentication?username=' . urlencode($login), $data);

        $err = null;
        if ($Response->status_code != 200) {
            $err = isset($Response->body['message']) ? $Response->body['message'] : ($Response->status_code . $Response->status_text);
        } else if ($Response->err) {
            $err = $Response->err;
        } else if (!isset($Response->body['name']) || !isset($Response->body['display-name']) || !isset($Response->body['email'])) {
            $err = 'Bad response:' . print_r($Response->body, true);
        }

        if ($err) return [null, $err];

        $result = [
            'user_id' => $Response->body['name'],
            'user_name' => $Response->body['display-name'],
            'user_email' => $Response->body['email'],
        ];

        $data = json_encode(['username' => $login, 'password' => $password]);
        $Response = $this->request('POST', 'usermanagement/latest/session', $data);

        if ($Response->status_code != 201) {
            $err = isset($Response->body['message']) ? $Response->body['message'] : ($Response->status_code . $Response->status_text);
        }
        if (!isset($Response->body['token'])) {
            $err = 'Bad response:' . print_r($Response->body, true);
        }

        if ($err) return [null, $err];

        $result['user_token'] = $Response->body['token'];

        return [$result, null];
    }

    public function isGroupMember($user_id, $group_name)
    {
        $query = [
            'username' => $user_id,
            'groupname' => $group_name,
        ];
        $query_str = http_build_query($query);
        $Response = $this->request('GET', 'usermanagement/latest/user/group/direct?' . $query_str);
        if ($Response->status_code != 200 || empty($Response->body['name'])) {
            return false;
        }
        return true;
    }

    protected function request($http_method, $method, $data = null, $headers = [])
    {
        $Config = Context::config();

        $headers = [
            'Authorization: Basic ' . $Config->get('app_auth'),
            'Accept: application/json',
            'Content-Type: application/json',
        ] + $headers;

        $opts = [
            'http' => [
                'method' => $http_method,
                'header' => implode("\r\n", $headers) . "\r\n",
                'ignore_errors' => true,
            ],
        ];
        if ($data !== null) $opts['http']['content'] = $data;
        $ctx = stream_context_create($opts);

        $url = $Config->get('jira_url') . $method;

        $body = file_get_contents($url, null, $ctx);
        $Response = new Http_Response(isset($http_response_header) ? $http_response_header : [], $body);

        return $Response;
    }
}
