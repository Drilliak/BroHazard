<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 20/08/17
 * Time: 18:39
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends Controller
{

    /**
     * Références autorisés (ie éléments qui peuvent être likés)
     */
    private const ALLOWED_REF = ['post', 'comment'];

    /**
     * Valeur du like
     */
    private const LIKE_VALUE = 1;

    /**
     * Valeur du dislike
     */
    private const DISLIKE_VALUE = -1;

    /**
     * Action appelée lors d'un like
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function likeAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            throw new \Exception('You are not allowed to like.');
        }
        $ref = $request->get('ref');
        $this->referenceAllowed($ref);

        $refId = $request->get('ref_id');

        $this->recordExist($ref, $refId);

        return $this->json($this->vote($ref, $refId, self::LIKE_VALUE));

    }

    /**
     * Action appelée lors d'un dislike
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dislikeAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            throw new \Exception('You are not allowed to dislike.');
        }

        $ref = $request->get('ref');
        $this->referenceAllowed($ref);

        $refId = $request->get('ref_id');

        $this->recordExist($ref, $refId);

        return $this->json($this->vote($ref, $refId, self::DISLIKE_VALUE));
    }

    /**
     * Vérifie si une référence est autorisée.
     *
     * @param string $ref
     * @throws \Exception
     */
    private function referenceAllowed(string $ref)
    {
        if (!in_array($ref, self::ALLOWED_REF)) {
            throw new \Exception('Reference not allowed');
        }
    }

    /**
     * Vérifie si l'enregistrement existe
     *
     * @param string $ref
     * @param int $refId
     * @throws \Exception
     */
    private function recordExist(string $ref, int $refId)
    {
        $em = $this->getDoctrine()->getManager();

        if ($ref === "post") {
            $repoPost = $em->getRepository('AppBundle:Post');
            $post = $repoPost->find($refId);
            if ($post === null) {
                throw new \Exception('You cannot vote for an inexisting record.');
            }
        } elseif ($ref == "comment") {

        }
    }

    /**
     * Like/Dislike action
     *
     * @param string $ref
     * @param int $refId
     * @param int $value
     * @return bool
     */
    private function vote(string $ref, int $refId, int $value): array
    {
        $em = $this->getDoctrine()->getManager();
        $repoVote = $em->getRepository('AppBundle:Vote');

        $repoRef = null;
        if ($ref == 'post') {
            $repoRef = $em->getRepository('AppBundle:Post');
        }

        if ($repoRef == null) {
            throw new \Exception('Ref not allowed');
        }
        $votableEntity = $repoRef->find($refId);
        $user = $this->getUser();
        $vote = $repoVote->findOneBy(['ref' => $ref, 'refId' => $refId, 'user' => $user]);
        if ($vote) {
            if ($vote->getValue() == $value) {
                if ($value == self::LIKE_VALUE) {
                    $votableEntity->setLike($votableEntity->getLike() - 1);
                } else {
                    $votableEntity->setDislike($votableEntity->getDislike() - 1);
                }
                $em->remove($vote);
                $em->flush();
                return [
                    'msg'           => 'Vote canceled',
                    "success"       => false,
                    'like_count'    => $votableEntity->getLike(),
                    'dislike_count' => $votableEntity->getDislike()
                ];
            }
            if ($value == self::LIKE_VALUE) {
                $votableEntity->setLike($votableEntity->getLike() + 1);
                $votableEntity->setDislike($votableEntity->getDislike() - 1);
            } else {
                $votableEntity->setDislike($votableEntity->getDislike() + 1);
                $votableEntity->setLike($votableEntity->getLike() - 1);
            }
            $vote->setValue($value);
            $vote->setCreatedAt(new \DateTime());
            $em->flush();
            return [
                'msg'           => 'Vote changed',
                'success'       => true,
                'like_count'    => $votableEntity->getLike(),
                'dislike_count' => $votableEntity->getDislike()
            ];

        }

        if ($value == self::LIKE_VALUE) {
            $votableEntity->like();
        } else if ($value == self::DISLIKE_VALUE) {
            $votableEntity->dislike();
        }

        $vote = new Vote();
        $vote->setRef($ref);
        $vote->setRefId($refId);
        $vote->setCreatedAt(new \DateTime());
        $vote->setUser($user);
        $vote->setValue($value);
        $em->persist($vote);
        $em->flush();

        return [
            'msg'           => 'Vote saved',
            'success'       => true,
            'like_count'    => $votableEntity->getLike(),
            'dislike_count' => $votableEntity->getDislike()
        ];
    }


    /**
     * Retourne la class css correspondant à l'état actuel du like/dislike
     *
     * @param Vote $vote
     * @return null|string
     */
    public static function getClass(?Vote $vote): ?string
    {
        if ($vote) {
            return $vote->getValue() == 1 ? 'is-liked' : 'is-disliked';
        }
        return null;
    }

}