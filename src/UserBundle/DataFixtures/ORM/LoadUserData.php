<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {


    /** @var  ContainerInterface */
    private $container;
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');
        $user = new User();
        $user->setUsername('testUsername');
        $user->setEmail('test@email.fr');
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword($user, '0000'));
        $this->setReference('user', $user);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Robin');
        $user->setEmail('robin@lecopain.fr');
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword($user, 'robin'));
        $this->setReference('user.robin', $user);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Hugo');
        $user->setEmail('hugo@lebro.fr');
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword($user, 'hugo'));
        $user->setRoles(["ROLE_ADMIN"]);
        $this->setReference('user.hugo', $user);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Lemy');
        $user->setEmail('lemy@lemy.fr');
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword($user, 'lemy'));
        $this->setReference('user.lemy', $user);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Drilliak');
        $user->setEmail('drilliak@drilliak.fr');
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword($user, 'drilliak'));
        $user->setRoles(["ROLE_SUPER_ADMIN"]);
        $this->setReference('user.drilliak', $user);
        $manager->persist($user);


        $manager->flush();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 1;
    }
}