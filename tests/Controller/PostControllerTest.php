<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testGetPosts(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/posts');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testPostPosts(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'Content of a new post',
            'email'   => 'user2@gmail.com'
        ];

        $client->request('POST',
            '/api/posts',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($data)
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testPostPostsWithoutEmail(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'The new content is created'
        ];

        $client->request('POST',
            '/api/posts',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($data)
        );
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testPostInvalidPosts(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'T',
            'email' => 'rares.naghi@russmedia.com'
        ];

        $client->request('POST',
            '/api/posts',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($data)
        );
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testUpdatePosts(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'An updated content by functional tests',
        ];
        $client->request('PUT',
            '/api/posts/2',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($data)
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testUpdateInexistentPosts(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'An updated content by functional tests',
        ];
        $client->request('PATCH',
            '/api/posts/99999',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($data)
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testUpdateInvalidPosts(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'Y',
        ];
        $client->request('PUT',
            '/api/posts/3',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode($data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testDeletePosts(): void
    {
        $client = static::createClient();
        $client->request('DELETE','/api/posts/9');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testDeleteInexistentPosts(): void
    {
        $client = static::createClient();
        $client->request('DELETE','/api/posts/99999');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
