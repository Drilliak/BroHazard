<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 08/09/2017
 * Time: 03:33
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{

    public function deleteAction(Request $request)
    {

        if ($this->getUser()) {

        }
    }


}