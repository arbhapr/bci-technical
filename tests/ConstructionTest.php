<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConstructionTest extends WebTestCase
{
    // update if you want to search another data
    private $search = "Charlie";
    
    public function testListConstruction()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/construction'
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $data = json_decode($jsonContent, true);
        $this->assertEquals('success', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsArray($data['data']);
    }

    public function testPostConstruction()
    {
        $client = static::createClient();
        $data = json_encode([
            "name" => "Charlie Project",
            "location" => "Jl. Testing",
            "stage" => "Design & Documentation",
            "category" => "Health",
            "otherCategory" => null,
            "startDate" => "2024-11-01",
            "description" => "Trial",
            "creatorId" => "admin"
        ]);
        $client->request(
            'POST',
            '/construction',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $data,
        );

        $response = $client->getResponse();
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $data = json_decode($jsonContent, true);
        $this->assertEquals('success', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsArray($data['data']);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals('Charlie Project', $data['data']['name']);
        $this->assertEquals('Jl. Testing', $data['data']['location']);
        $this->assertEquals('Design & Documentation', $data['data']['stage']);
        $this->assertEquals('Health', $data['data']['category']);
        $this->assertEquals('2024-11-01', $data['data']['startDate']);
        $this->assertEquals('Trial', $data['data']['description']);
        $this->assertEquals('admin', $data['data']['creatorId']);
    }

    public function testDetailConstruction()
    {
        $client = static::createClient();
        $data = $this->getOne($client, $this->search);
        $client->request(
            'GET',
            '/construction/' . $data['id'],
        );

        $response = $client->getResponse();
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $data = json_decode($jsonContent, true);
        $this->assertEquals('success', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsArray($data['data']);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals('Charlie Project', $data['data']['name']);
        $this->assertEquals('Jl. Testing', $data['data']['location']);
        $this->assertEquals('Design & Documentation', $data['data']['stage']);
        $this->assertEquals('Health', $data['data']['category']);
        $this->assertEquals('2024-11-01', $data['data']['startDate']);
        $this->assertEquals('Trial', $data['data']['description']);
        $this->assertEquals('admin', $data['data']['creatorId']);
    }

    public function testUpdateConstruction()
    {
        $client = static::createClient();
        $data = $this->getOne($client, $this->search);
        $update = json_encode([
            'category' => 'Others',
            'otherCategory' => 'Backyard'
        ]);
        $client->request(
            'PATCH',
            '/construction/' . $data['id'],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $update,
        );

        $response = $client->getResponse();
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $data = json_decode($jsonContent, true);
        $this->assertEquals('success', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsArray($data['data']);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals('Charlie Project', $data['data']['name']);
        $this->assertEquals('Jl. Testing', $data['data']['location']);
        $this->assertEquals('Design & Documentation', $data['data']['stage']);
        $this->assertEquals('Others', $data['data']['category']);
        $this->assertEquals('Backyard', $data['data']['otherCategory']);
        $this->assertEquals('2024-11-01', $data['data']['startDate']);
        $this->assertEquals('Trial', $data['data']['description']);
        $this->assertEquals('admin', $data['data']['creatorId']);
    }

    public function testDeleteConstruction()
    {
        $client = static::createClient();
        $data = $this->getOne($client, $this->search);
        $client->request(
            'DELETE',
            '/construction/' . $data['id']
        );

        $response = $client->getResponse();
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $response = json_decode($jsonContent, true);
        $this->assertEquals('success', $response['status']);

        $client->request(
            'GET',
            '/construction/' . $data['id']
        );
        $response = $client->getResponse();
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    // to centralize 1 data for testing
    private function getOne($client, $keyword)
    {
        $client->request(
            'GET',
            '/construction',
            ['filter' => $keyword],
        );
        $response = $client->getResponse();
        $jsonContent = $response->getContent();
        $data = json_decode($jsonContent, true);
        if ($data['status'] == 'success') {
            $data = $data['data'][0];
            $this->assertIsArray($data);
            return $data;
        }
        return false;
    }
}
