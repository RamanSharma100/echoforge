<?php

namespace Forge\core;

class Request
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

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getBody()
    {
        $body = [];
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
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

    public function getPort()
    {
        return $this->getServer('REMOTE_PORT');
    }

    public function getProtocol()
    {
        return $this->getServer('SERVER_PROTOCOL');
    }

    public function getHost()
    {
        return $this->getServer('HTTP_HOST');
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
}
