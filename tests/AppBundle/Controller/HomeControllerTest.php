<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * Vérifie que la page d'accueil renvoie une réponse 200.
     */
    public function testHomepageResponse()
    {
        $client = $this->makeClient();
        $client->request('GET', '/');
        $this->isSuccessful($client->getResponse());
    }

    /**
     * Vérifie que la page d'accueil n'affiche bien que 5 articles.
     */
    public function testNbPostsHomepage()
    {
        $crawler = $this->fetchCrawler('/');
        $this->assertSame(5, $crawler->filter('article')->count());
    }
}
