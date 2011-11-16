<?php
/**
 * Connection.php
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 * @package Selfish
 * @version $Id$
 * @license The BSD License
 */

namespace Selfish\Service\Redmine;

/**
 * Connection
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
class Connection
{
    const AUTH_BASIC = 'basic';

    protected $url;
    protected $auth;
    protected $api_key;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    public function setBasicAuth($username, $password)
    {
        $this->auth = array(
            'type' => static::AUTH_BASIC,
            'user' => $username,
            'pass' => $password,
        );
    }

    public function get($path, $params = array())
    {
        return $this->request($path, http_build_query($params), 'GET');
    }

    public function post($path, $params = array())
    {
        return $this->request($path, json_encode($params), 'POST');
    }

    public function put($path, $params = array())
    {
        return $this->request($path, json_encode($params), 'PUT');
    }

    public function delete($path)
    {
        return $this->request($path, '', 'DELETE');
    }

    public function request($path, $query = '', $method = 'GET')
    {
        $method = strtoupper($method);

        switch ($method) {
            case 'GET':
                if ($query) {
                    $path .= (strpos($path, '?') === false) ? '?' : '&';
                    $path .= $query;

                    $query = '';
                }

            case 'POST':
            case 'PUT':
                $context = stream_context_create(array(
                    'http' => array(
                        'method' => $method,
                        'header' => $this->getHeader(array(
                            sprintf('Content-Length: %d', strlen($query)),
                        )),
                        'content' => $query,
                    ),
                ));
                break;

            case 'DELETE':
                $context = stream_context_create(array(
                    'http' => array(
                        'method' => $method,
                    ),
                ));
                break;

            default:
        }

        if (isset($context)) {
            return file_get_contents($this->buildUrl($path), false, $context);
        }

        throw new \RuntimeException('Unknown method');
    }

    protected function buildUrl($path = '')
    {
        $base = rtrim($this->url, '/');
        $path = ltrim($path, '/');

        return $base . '/' . $path;
    }

    protected function getHeader($headers = array())
    {
        if ($this->api_key) {
            $headers[] = sprintf('X-Redmine-API-Key: %s', $this->api_key);
        }

        if ($this->auth && $this->auth['type'] == static::AUTH_BASIC) {
            $headers[] = sprintf('Authorization: BASIC '
                . base64_encode($this->auth['user'] . ':' . $this->auth['pass']));
        }

        $headers[] = 'User-Agent: Selfish/0.0.0-dev';

        return implode("\r\n", $headers) . "\r\n";
    }
}

