<?php
/**
 * Issues.php
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 * @package Selfish
 * @version $Id$
 * @license The BSD License
 */

namespace Selfish\Service\Redmine;

/**
 * Issues
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
class Issues
{
    const PATH = '/issues.json';

    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listing(array $filter = array())
    {
        $response = $this->connection->get(static::PATH, $filter);

        return \json_decode($response, true);
    }

    public function show($id)
    {
        $response = $this->connection->get(sprintf('/issues/%d.json', $id));
        $response = \json_decode($response, true);

        if (count($response) == 1) {
            return array_shift($response);
        }

        return $response;
    }

    public function create(array $params)
    {
        $response = $this->connection->post(static::PATH, $params);

        return \json_decode($response, true);
    }

    public function update($id, array $params)
    {
        $response = $this->connection->put(sprintf('/issues/%d.json', $id), $params);

        return \json_decode($response, true);
    }

    public function delete($id)
    {
        $response = $this->connection->delete(sprintf('/issues/%d.xml', $id));

        return $response;
    }
}

