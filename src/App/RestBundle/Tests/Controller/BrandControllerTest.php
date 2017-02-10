<?php

namespace App\RestBundle\Tests\Controller;

use App\RestBundle\Tests\RestTest;

/**
 * Test actions of Brand REST controller
 */
class BrandControllerTest extends RestTest
{
    public function testBrandsListing()
    {
        $this->jsonClient->request('GET', '/api/brands.json');
        $response = $this->jsonClient->getResponse();

        // Test if response is OK
        $this->assertSame(200, $response->getStatusCode());

        // Assert the content is JSON and parse it
        $content = $this->assertAndParseJSONResponse($response);

        // For now, the list should be an empty array
        $this->assertInternalType('array', $content);
        $this->assertCount(0, $content);
    }

    public function testBrandCrud()
    {
        // Creation
        $request = $this->jsonClient->request(
            'POST',
            '/api/brands.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'BrandName'))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $content = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $content);
        $this->assertGreaterThanOrEqual(1, $content->id);
        $this->assertObjectHasAttribute('name', $content);
        $this->assertEquals('BrandName', $content->name);

        // Read the previous created

        $this->jsonClient->request(
            'GET',
            '/api/brands/'.$content->id.'.json'
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $contentGet = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $contentGet);
        $this->assertEquals($content->id, $contentGet->id);
        $this->assertObjectHasAttribute('name', $contentGet);
        $this->assertEquals('BrandName', $contentGet->name);

        // Update

        $this->jsonClient->request(
            'PUT',
            '/api/brands/'.$content->id.'.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'BrandNameModified'))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $content = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $content);
        $this->assertEquals($contentGet->id, $content->id);
        $this->assertObjectHasAttribute('name', $content);
        $this->assertEquals('BrandNameModified', $content->name);

        // Read the previous updated

        $this->jsonClient->request(
            'GET',
            '/api/brands/'.$content->id.'.json'
        );
        $response = $this->jsonClient->getResponse();

        $contentGet = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $contentGet);
        $this->assertEquals($content->id, $contentGet->id);
        $this->assertObjectHasAttribute('name', $contentGet);
        $this->assertEquals('BrandNameModified', $contentGet->name);

        // Get all brands returns more than one row

        $this->jsonClient->request('GET', '/api/brands.json');
        $response = $this->jsonClient->getResponse();

        $contentRows = $this->assertAndParseJSONResponse($response);

        $this->assertInternalType('array', $contentRows);
        $this->assertGreaterThanOrEqual(1, count($contentRows));

        foreach ($contentRows as $row) {
            if ($row->id == $content->id) {
                $this->assertObjectHasAttribute('name', $row);
                $this->assertEquals('BrandNameModified', $row->name);
            }
        }

        // Delete

        $this->jsonClient->request(
            'DELETE',
            '/api/brands/'.$content->id.'.json'
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $contentGet = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $contentGet);
        $this->assertEquals($content->id, $contentGet->id);

        // Try to read the previous deleted

        $this->jsonClient->request(
            'GET',
            '/api/brands/'.$content->id.'.json'
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(404, $response->getStatusCode());
        $this->assertAndParseJSONResponse($response);
    }
}