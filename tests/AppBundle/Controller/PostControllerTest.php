<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadCategoryData;
use AppBundle\DataFixtures\ORM\LoadPostData;
use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use UserBundle\DataFixtures\ORM\LoadUserData;
use UserBundle\Entity\User;

class PostControllerTest extends WebTestCase
{
    /**
     * @var ReferenceRepository
     */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures([
            LoadPostData::class,
            LoadCategoryData::class,
            LoadUserData::class])->getReferenceRepository();
    }

    private function generatePostUrl(Post $post): string
    {
        $categorySlug = $post->getCategory()->getSlug();
        $postSlug = $post->getSlug();

        return "/posts/$categorySlug/$postSlug";
    }

    /**
     * Teste si la page se charge correctement en tant qu'anonyme.
     */
    public function testPostPageAsAnon()
    {
        /** @var Post $post */
        $post = $this->fixtures->getReference('post');
        $client = $this->createClient();
        $crawler = $client->request('GET', $this->generatePostUrl($post));

        // Réponse de la page
        $this->isSuccessful($client->getResponse());

        // Le bouton et le champ pour commenter sont-ils bien désactivés ?
        $textarea = $crawler->filter('#appbundle_comment_content');
        $button = $crawler->selectButton('Ajouter un commentaire');
        $this->assertSame('disabled', $textarea->attr('disabled'));
        $this->assertSame('disabled', $button->attr('disabled'));
    }

    public function testPostPageAsLogedUser()
    {
        /** @var Post $post */
        $post = $this->fixtures->getReference('post');

        /** @var User $user */
        $user = $this->fixtures->getReference('user');

        $this->loginAs($user, 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', $this->generatePostUrl($post));
        $this->isSuccessful($client->getResponse());
    }

    /**
     * Teste si la redirection vers la page d'accueil se fait bien pour un utilisateur
     * anonyme
     */
    public function testNewPostAsAnon()
    {
        $client = $this->createClient();
        $client->request('GET', '/posts/new-post');

        $this->assertStatusCode(302, $client);
    }

    /**
     * Teste si la page d'un auteur renvoie une réponse 200
     */
    public function testAuthorPage()
    {
        /** @var Post $post */
        $post = $this->fixtures->getReference('post');
        $author = $post->getAuthor();
        $client = $this->makeClient();
        $client->request('GET', "/posts/author/{$author->getId()}");
        $this->isSuccessful($client->getResponse());
    }

    /**
     * Teste si la page relative à une catégorie renvoie une réponse 200
     */
    public function testCategoryPage()
    {
        /** @var Post $post */
        $post = $this->fixtures->getReference('post');
        $client = $this->makeClient();
        $client->request('GET', "/posts/{$post->getCategory()->getSlug()}");
        $this->isSuccessful($client->getResponse());
    }

    /**
     * Teste si la page affichant tous les articles du site renvoie une réponse 200
     */
    public function testPostsPage()
    {
        $client = $this->makeClient();
        $client->request('GET', '/posts/');
        $this->isSuccessful($client->getResponse());
    }
}
