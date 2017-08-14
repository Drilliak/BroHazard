<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 13/08/2017
 * Time: 22:17
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{

    /**
     * Accès admin nécessaire
     * @param Category $category
     */
    public function deleteAction(Category $category){
        return new Response('salut');
    }
}