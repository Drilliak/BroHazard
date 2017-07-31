<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 31/07/2017
 * Time: 22:45
 */

namespace Tests\AppBundle\Controller;


use AppBundle\DataFixtures\ORM\LoadCategoryData;
use AppBundle\DataFixtures\ORM\LoadPostData;
use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use FOS\UserBundle\Model\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use UserBundle\DataFixtures\ORM\LoadUserData;

class PostControllerTest extends WebTestCase
{

    /**
     * @var ReferenceRepository
     */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures([
            LoadUserData::class,
            LoadPostData::class,
            LoadCategoryData::class
        ])->getReferenceRepository();
        parent::setUp();
    }

    /**
     * Teste si les droits d'édition d'un article sont bien gérés
     */
    public function testEditAnon()
    {
        // Utilisateur anonyme
        $anonClient = $this->makeClient();
        /** @var Post $post */
        $post = $this->fixtures->getReference("post");
        $slug = $post->getSlug();
        $url = "/post/$slug";
        $crawler = $anonClient->request("GET", $url);
        $this->isSuccessful($anonClient->getResponse());
        $button = $crawler->selectLink("Modifier");
        $this->assertEquals($button->count(), 0);


    }

    public function testEditAuthor(){
        $post = $this->fixtures->getReference("post");
        $slug = $post->getSlug();
        $url = "/post/$slug";
        /** @var User $author */
        $author = $this->fixtures->getReference("user.robin");
        $this->loginAs($author, "main");
        $authorClient = $this->makeClient();
        $crawler = $authorClient->request("GET", $url);
        $button = $crawler->selectLink("Modifier");
        $this->assertEquals($button->count(), 1);
    }

    public function testEditNotAuthor(){
        $post = $this->fixtures->getReference("post");
        $slug = $post->getSlug();
        $url = "/post/$slug";
        /** @var User $notAuthor */
        $notAuthor = $this->fixtures->getReference("user.lemy");
        $this->loginAs($notAuthor, "main");
        $authorClient = $this->makeClient();
        $crawler = $authorClient->request("GET", $url);
        $button = $crawler->selectLink("Modifier");
        $this->assertEquals($button->count(), 0);
    }

    public function testEditAdmin(){
        $post = $this->fixtures->getReference("post");
        $slug = $post->getSlug();
        $url = "/post/$slug";
        /** @var User $admin */
        $admin = $this->fixtures->getReference("user.hugo");
        $this->loginAs($admin, "main");
        $authorClient = $this->makeClient();
        $crawler = $authorClient->request("GET", $url);
        $button = $crawler->selectLink("Modifier");
        $this->assertEquals($button->count(), 1);
    }

    public function testEditSuperAdmin(){
        $post = $this->fixtures->getReference("post");
        $slug = $post->getSlug();
        $url = "/post/$slug";
        /** @var User $superAdmin */
        $superAdmin = $this->fixtures->getReference("user.drilliak");
        $this->loginAs($superAdmin, "main");
        $authorClient = $this->makeClient();
        $crawler = $authorClient->request("GET", $url);
        $button = $crawler->selectLink("Modifier");
        $this->assertEquals($button->count(), 1);
    }

}