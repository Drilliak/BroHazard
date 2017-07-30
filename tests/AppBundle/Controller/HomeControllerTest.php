<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * Tese si la page est correctement accessible pour un utilisateur quelconque
     */
    public function testIndexLoading(){
        $client = $this->makeClient();
        $client->request('GET', '/');
        $this->isSuccessful($client->getResponse());
    }

    /**
     * Teste le système de login : on s'attend à être redirigé après soumission du formulaire.
     */
    public function testLogin(){
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('_submit')->form();
        $form->setValues(['_username' => 'test', '_password' => 'test']);
        $client->submit($form);
        $this->assertStatusCode(302, $client);
    }


}