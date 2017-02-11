<?php

namespace App\RestBundle\Tests\Controller;

use App\RestBundle\Tests\RestTest;

/**
 * Test actions of Phone REST controller and PhoneByBrand Controller
 */
class PhoneControllerTest extends RestTest
{
    public function testPhoneCrud()
    {
        // Create a brand

        $this->jsonClient->request(
            'POST',
            '/api/brands.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'BrandName'))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $brand = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $brand);

        // Create a first phone POST /api/brands/{id}/phones.json

        $this->jsonClient->request(
            'POST',
            '/api/brands/'.$brand->id.'/phones.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'Phone #1', 'price' => 100.99))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $phone1 = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $phone1);
        $this->assertGreaterThanOrEqual(1, $phone1->id);
        $this->assertObjectHasAttribute('name', $phone1);
        $this->assertEquals('Phone #1', $phone1->name);
        $this->assertObjectHasAttribute('price', $phone1);
        $this->assertEquals(100.99, $phone1->price);

        // Read the previous created GET /api/phones/{id}.json

        $this->jsonClient->request(
            'GET',
            '/api/phones/'.$phone1->id.'.json'
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $content = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $content);
        $this->assertEquals($phone1->id, $content->id);

        // Update PUT /api/phones/{id}.json

        $this->jsonClient->request(
            'PUT',
            '/api/phones/'.$phone1->id.'.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'Phone #1 modified'))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $content = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $content);
        $this->assertEquals($phone1->id, $content->id);
        $this->assertObjectHasAttribute('name', $content);
        $this->assertEquals('Phone #1 modified', $content->name);

        // Read the previous updated GET /api/phones/{id}.json

        $this->jsonClient->request(
            'GET',
            '/api/phones/'.$phone1->id.'.json'
        );
        $response = $this->jsonClient->getResponse();

        $content = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $content);
        $this->assertEquals($phone1->id, $content->id);
        $this->assertObjectHasAttribute('name', $content);
        $this->assertEquals('Phone #1 modified', $content->name);

        // Create a 2nd phone POST /api/brands/{id}/phones.json

        $this->jsonClient->request(
            'POST',
            '/api/brands/'.$brand->id.'/phones.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'Phone #2', 'price' => 200))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $phone2 = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $phone2);

        // Get all phones from the brand GET /api/brands/{id}/phones.json

        $this->jsonClient->request('GET', '/api/brands/'.$brand->id.'/phones.json');
        $response = $this->jsonClient->getResponse();

        $contentRows = $this->assertAndParseJSONResponse($response);

        $this->assertInternalType('array', $contentRows);
        $this->assertEquals(2, count($contentRows));

        foreach ($contentRows as $row) {
            $this->assertObjectHasAttribute('id', $row);
            $this->assertContains($row->id, [$phone1->id, $phone2->id], 'One of the two created phone\'s id');
        }

        // Delete 1st phone DELETE /api/phones/{id}.json

        $this->jsonClient->request(
            'DELETE',
            '/api/phones/'.$phone1->id.'.json'
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $content = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $content);
        $this->assertEquals($phone1->id, $content->id);

        // Try to read the previous deleted GET /api/phones/{id}.json

        $this->jsonClient->request(
            'GET',
            '/api/phones/'.$phone1->id.'.json'
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(404, $response->getStatusCode());
        $this->assertAndParseJSONResponse($response);

        // The brand has now only one attached phone GET /api/brands/{id}/phones.json

        $this->jsonClient->request('GET', '/api/brands/'.$brand->id.'/phones.json');
        $response = $this->jsonClient->getResponse();

        $contentRows = $this->assertAndParseJSONResponse($response);

        $this->assertInternalType('array', $contentRows);
        $this->assertEquals(1, count($contentRows));

        // Create a 2nd brand and attach 2 new phones

        $this->jsonClient->request(
            'POST',
            '/api/brands.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'BrandName #2'))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $brand2 = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $brand2);

        $this->jsonClient->request(
            'POST',
            '/api/brands/'.$brand2->id.'/phones.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'Phone #3', 'price' => 100.99))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $phone3 = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $phone3);

        $this->jsonClient->request(
            'POST',
            '/api/brands/'.$brand2->id.'/phones.json',
            array(),
            array(),
            array(),
            json_encode(array('name' => 'Phone #4', 'price' => 100.99))
        );
        $response = $this->jsonClient->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $phone4 = $this->assertAndParseJSONResponse($response);

        $this->assertObjectHasAttribute('id', $phone4);

        // Search all phones belonging to the new brand and check ids GET /api/brands/{id}/phones.json

        $this->jsonClient->request('GET', '/api/brands/'.$brand2->id.'/phones.json');
        $response = $this->jsonClient->getResponse();

        $contentRows = $this->assertAndParseJSONResponse($response);

        $this->assertInternalType('array', $contentRows);
        $this->assertEquals(2, count($contentRows));

        foreach ($contentRows as $row) {
            $this->assertObjectHasAttribute('id', $row);
            $this->assertContains($row->id, [$phone3->id, $phone4->id], 'One of the two created phone\'s id');
        }

        // Assert the old brand still has only one phone GET /api/brands/{id}/phones.json

        $this->jsonClient->request('GET', '/api/brands/'.$brand->id.'/phones.json');
        $response = $this->jsonClient->getResponse();

        $contentRows = $this->assertAndParseJSONResponse($response);

        $this->assertInternalType('array', $contentRows);
        $this->assertEquals(1, count($contentRows));

        $this->assertEquals($phone2->id, $contentRows[0]->id);

        // Get all the phones (all brands) GET /api/phones.json

        $this->jsonClient->request('GET', '/api/phones.json');
        $response = $this->jsonClient->getResponse();

        $contentRows = $this->assertAndParseJSONResponse($response);

        $this->assertInternalType('array', $contentRows);
        $this->assertEquals(3, count($contentRows));

    }
}