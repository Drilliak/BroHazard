<?php

namespace AppBundle\Command;

use AppBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:database:update')
            ->setDescription("Update la BDD pour apdapter le modèle des commentaires
            sans perdre les commentaires déjà présents en prod. On récupère le username
            présent dans l'entity de base, on cherche l'User correspondant et on update l'entity
            avec l'User.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Début de la transformation de la BDD');

        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $userRepository = $em->getRepository('UserBundle:User');
        $commentRepository = $em->getRepository('AppBundle:Comment');

        $comments = $commentRepository->findAll();

        /** @var Comment $comment */
        foreach ($comments as $comment) {
            $user = $userRepository->findOneBy(['username' => $comment->getUsername()]);
            $comment->setUser($user);

        }
        $em->flush();

        $output->writeln([
            "La table correspondant à l'entité Comment a bien été mise à jour",
            "Vous pouvez désormais mettre à jour l'entité Comment en supprimant l'attribut username"
        ]);
    }
}