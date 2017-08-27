<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * Tese si la page est correctement accessible pour un utilisateur quelconque.
     */
    public function testIndexLoading()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        $this->isSuccessful($client->getResponse());
    }
}
