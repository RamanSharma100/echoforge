<?php

namespace Forge\core;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;



function is_date($date)
{
    return (bool) strtotime($date);
}

function is_datetime($datetime)
{
    return (bool) strtotime($datetime);
}

function is_time($time)
{
    return (bool) strtotime($time);
}

class Request extends SymfonyRequest
{

    private array $params = [];

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }


    public function getBody()
    {

        $body = [];
        $method = strtolower($this->method());
        if ($method == 'get') {
            $body = $_GET;
        }

        if ($method == 'post') {
            $body = $this->getPostParams();
            if (empty($body)) {
                $body =
                    json_decode(file_get_contents('php://input'), true, 512, JSON_OBJECT_AS_ARRAY);
            }
        }

        return $body;
    }

    public function getQueryParams()
    {
        $query = [];
        foreach ($_GET as $key => $value) {
            $query[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $query;
    }

    public function getQuery($key)
    {
        return filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getPostParams()
    {
        $post = [];
        foreach ($_POST as $key => $value) {
            $post[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $post;
    }

    public function getPost($key)
    {
        return filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getServerParams()
    {
        $server = [];
        foreach ($_SERVER as $key => $value) {
            $server[$key] = filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $server;
    }

    public function getServer($key)
    {
        return filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getCookieParams()
    {
        $cookie = [];
        foreach ($_COOKIE as $key => $value) {
            $cookie[$key] = filter_input(INPUT_COOKIE, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $cookie;
    }

    public function getCookie($key)
    {
        return filter_input(INPUT_COOKIE, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function setCookie($key, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httponly = false)
    {
        setcookie($key, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function deleteCookie($key)
    {
        setcookie($key, '', time() - 3600);
    }

    public function getHeaderParams()
    {
        $headers = [];
        foreach (getallheaders() as $key => $value) {
            $headers[$key] = filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $headers;
    }

    public function getHeader($key)
    {
        return filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getHeaders()
    {
        return getallheaders();
    }

    public function getIp()
    {
        return $this->getServer('REMOTE_ADDR');
    }


    public function getProtocol()
    {
        return $this->getServer('SERVER_PROTOCOL');
    }


    public function getUserAgent()
    {
        return $this->getServer('HTTP_USER_AGENT');
    }

    public function getAccept()
    {
        return $this->getServer('HTTP_ACCEPT');
    }

    public function getAcceptLanguage()
    {
        return $this->getServer('HTTP_ACCEPT_LANGUAGE');
    }

    public function getAcceptEncoding()
    {
        return $this->getServer('HTTP_ACCEPT_ENCODING');
    }

    public function getAcceptCharset()
    {
        return $this->getServer('HTTP_ACCEPT_CHARSET');
    }

    public function getKeepAlive()
    {
        return $this->getServer('HTTP_KEEP_ALIVE');
    }

    public function getConnection()
    {
        return $this->getServer('HTTP_CONNECTION');
    }

    public function getCacheControl()
    {
        return $this->getServer('HTTP_CACHE_CONTROL');
    }

    public function getPragma()
    {
        return $this->getServer('HTTP_PRAGMA');
    }

    public function getReferer()
    {
        return $this->getServer('HTTP_REFERER');
    }

    public function getOrigin()
    {
        return $this->getServer('HTTP_ORIGIN');
    }

    public function getForwarded()
    {
        return $this->getServer('HTTP_FORWARDED');
    }

    public function getForwardedFor()
    {
        return $this->getServer('HTTP_FORWARDED_FOR');
    }

    public function getForwardedHost()
    {
        return $this->getServer('HTTP_FORWARDED_HOST');
    }

    public function getForwardedProto()
    {
        return $this->getServer('HTTP_FORWARDED_PROTO');
    }



    public function all()
    {


        $body = $this->getBody();
        $query = $this->getQueryParams();
        $params = $this->getParams();
        $intvalues = array_map('intval', $body);

        return array_merge([
            'body' => $body,
            'query' => $query,
            'params' => $params,
            'intvalues' => $intvalues
        ]);
    }

    public function only($keys)
    {
        $body = $this->getBody();
        $query = $this->getQueryParams();
        $params = $this->getParams();
        $intvalues = array_map('intval', $body);

        $all = array([
            'body' => $body,
            'query' => $query,
            'params' => $params,
            'intvalues' => $intvalues
        ]);

        $result = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $all)) {
                $result[$key] = $all[$key];
            }
        }
        return $result;
    }

    public function getParam($key)
    {
        return $this->params[$key] ?? null;
    }

    public function validate(
        Request $request,
        array $rules,
    ) {
        $errors = [];
        $attributes = $request->getBody();
        foreach ($rules as $key => $rule) {
            $rule = explode('|', $rule);

            foreach ($rule as $r) {
                if ($r == 'required') {
                    if (empty($attributes[$key])) {
                        $errors[$key] = 'The ' . $key . ' field is required';
                        break;
                        break;
                    }
                }
                if ($r == 'string') {
                    if (
                        !is_string($attributes[$key])
                    ) {
                        $errors[$key] = 'The ' . $key . ' field must be a string';
                        break;
                    }
                }
                if ($r == 'integer') {
                    $integer = filter_var($attributes[$key], FILTER_VALIDATE_INT);
                    if (
                        !is_numeric($attributes[$key]) ||
                        !$integer
                    ) {
                        $errors[$key] = 'The ' . $key . ' field must be an integer';
                        break;
                    } else {
                        $attributes[$key] = $integer;
                    }
                }
                if ($r == 'boolean') {
                    if (!is_bool($attributes[$key])) {
                        $errors[$key] = 'The ' . $key . ' field must be a boolean';
                        break;
                    }
                }
                if ($r == 'array') {
                    if (!is_array($attributes[$key])) {
                        $errors[$key] = 'The ' . $key . ' field must be an array';
                        break;
                    }
                }
                if ($r == 'date') {
                    if (!is_date($attributes[$key])) {
                        $errors[$key] = 'The ' . $key . ' field must be a date';
                        break;
                    }
                }
                if ($r == 'datetime') {
                    if (!is_datetime($attributes[$key])) {
                        $errors[$key] = 'The ' . $key . ' field must be a datetime';
                        break;
                    }
                }
                if ($r == 'time') {
                    if (!is_time($attributes[$key])) {
                        $errors[$key] = 'The ' . $key . ' field must be a time';
                        break;
                    }
                }
                if ($r == 'url') {
                    if (!filter_var($attributes[$key], FILTER_VALIDATE_URL)) {
                        $errors[$key] = 'The ' . $key . ' field is not a valid url';
                        break;
                    }
                }
                if ($r == 'email') {
                    if (!filter_var($attributes[$key], FILTER_VALIDATE_EMAIL)) {
                        $errors[$key] = 'The ' . $key . ' field is not a valid email';
                        break;
                    }
                }
                if (str_contains($r, 'min')) {
                    $min = explode(':', $r)[1];
                    if (strlen($attributes[$key]) < $min) {
                        $errors[$key] = 'The ' . $key . ' field must be at least ' . $min . ' characters';
                        break;
                    }
                }
                if (str_contains($r, 'max')) {
                    $max = explode(':', $r)[1];
                    if (strlen($attributes[$key]) > $max) {
                        $errors[$key] = 'The ' . $key . ' field must be at most ' . $max . ' characters';
                        break;
                    }
                }
                if (str_contains($r, 'unique')) {
                    $unique = explode(':', $r)[1];
                    $model = new $unique();
                    if ($model->where($key, $attributes[$key])->first()) {
                        $errors[$key] = 'The ' . $key . ' field must be unique';
                        break;
                    }
                }
            }
        }
        return $errors;
    }
}
