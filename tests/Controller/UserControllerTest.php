<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSend(): void
    {
        $client = static::createClient();
        $client->request('POST','api/users/7/unicorns/7/purchase');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testInvalidSend(): void
    {
        $client = static::createClient();
        $client->request('POST','api/users/9999/unicorns/9999/purchase');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
