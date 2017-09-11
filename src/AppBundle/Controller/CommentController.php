<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 08/09/2017
 * Time: 03:33
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{

    public function deleteAction(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('AppBundle:Comment')->findOneBy(['id' => $id]);

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($comment->getUser() !== $user) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedException('Not allowed');
            }
        }
        $em->remove($comment);
        $em->flush();

        return $this->json("The comment $id has been removed");
    }

    public function editAction(Request $request)
    {

    }


}