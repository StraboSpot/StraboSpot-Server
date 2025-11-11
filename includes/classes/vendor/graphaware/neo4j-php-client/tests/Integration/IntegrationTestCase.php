<?php
/**
 * File: IntegrationTestCase.php
 * Description: IntegrationTestCase class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace GraphAware\Neo4j\Client\Tests\Integration;

use GraphAware\Neo4j\Client\ClientBuilder;

class IntegrationTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \GraphAware\Neo4j\Client\Client
     */
    protected $client;


    public function setUp()
    {
        $this->client = ClientBuilder::create()
            ->addConnection('http', 'http://localhost:7474')
            ->addConnection('bolt', 'bolt://localhost')
            ->build();
    }

    /**
     * Empties the graph database
     *
     * @void
     */
    public function emptyDb()
    {
        $this->client->run('MATCH (n) OPTIONAL MATCH (n)-[r]-() DELETE r,n', null, null, 'http');
    }
}