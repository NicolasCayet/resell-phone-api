<?php

namespace App\RestBundle\Tests;

use App\TestsBundle\KernelAwareTest;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test case class helpful with unit tests on REST API's actions
 */
abstract class RestTest extends KernelAwareTest
{
    /**
     * @var Client $jsonClient
     */
    protected $jsonClient;

    /**
     * @return null
     */
    public function setUp()
    {
        parent::setUp();

        $this->jsonClient = $this->createClient(array(
            'ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ));
    }

    /**
     * @param Response $response
     * @return mixed Result of the JSON decode (as objects)
     */
    protected function assertAndParseJSONResponse(Response $response)
    {
        // Test if Content-Type is valid application/json
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        // Test that response is not empty
        $this->assertNotEmpty($response->getContent());
        // Parse the content as JSON
        $content = json_decode($response->getContent());
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE, 'JSON parsing failed.');

        return $content;
    }

    /**
     * Creates a HTTP Client.
     *
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    protected function createClient(array $server = array())
    {
        $client = $this->container->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }
}