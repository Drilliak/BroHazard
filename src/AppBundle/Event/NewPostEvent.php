<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 22/08/17
 * Time: 22:11
 */

namespace AppBundle\Event;

use AppBundle\Entity\Post;
use Symfony\Component\EventDispatcher\Event;

class NewPostEvent extends Event
{
    /**
     * @var Post
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getPost() : Post{
        return $this->post;
    }
}