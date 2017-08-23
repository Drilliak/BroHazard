<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 21/08/17
 * Time: 15:06
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class AbstractVote
{
    /**
     * @var int
     * @ORM\Column(name="likes", type="integer", options={"default":0})
     */
    private $like = 0;

    /**
     * @var int
     * @ORM\Column(name="dislikes", type="integer", options={"default":0})
     */
    private $dislike = 0;

    public function getLike(): int{
        return $this->like;
    }

    public function setLike(int $like): self{
        $this->like = $like;
        return $this;
    }

    public function getDislike(): int{
        return $this->dislike;
    }

    public function setDislike(int $dislike): self{
        $this->dislike = $dislike;
        return $this;
    }

    public function like(){
        $this->like++;
    }

    public function dislike(){
        $this->dislike++;
    }
}