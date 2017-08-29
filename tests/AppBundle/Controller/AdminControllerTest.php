<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testUnauthorized()
    {
        $client = $this->makeClient();
        $client->request('GET', '/admin/dashboard');
        $this->assertStatusCode('302', $client);
    }
}
