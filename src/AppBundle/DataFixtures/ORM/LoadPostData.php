<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 29/07/2017
 * Time: 00:45
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setName("L'esport c'est vraiment trop cool");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.robin'));
        $post->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias autem incidunt nesciunt recusandae veritatis! 
        Cum ipsa laudantium maxime nobis unde. Cupiditate delectus et excepturi laboriosam maxime neque saepe sint, sunt!");
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $post = new Post();
        $post->setName("Teemo c'est le meilleur champion");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.hugo'));
        $post->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias autem incidunt nesciunt recusandae veritatis! 
        Cum ipsa laudantium maxime nobis unde. Cupiditate delectus et excepturi laboriosam maxime neque saepe sint, sunt!");
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $post = new Post();
        $post->setName("S'amuser avec Yorick");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.lemy'));
        $post->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias autem incidunt nesciunt recusandae veritatis! 
        Cum ipsa laudantium maxime nobis unde. Cupiditate delectus et excepturi laboriosam maxime neque saepe sint, sunt!");
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $manager->flush();
    }

    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 3;
    }
}