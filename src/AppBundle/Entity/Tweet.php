<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 23/08/17
 * Time: 21:30.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tweets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TweetRepository")
 */
class Tweet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="id_twitter", type="string", length=50)
     */
    private $idTwitter;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=140)
     */
    private $text;

    /**
     * @var bool
     *
     * @ORM\Column(name="truncated", type="boolean")
     */
    private $truncated;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Tweet
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set idTwitter.
     *
     * @param string $idTwitter
     *
     * @return Tweet
     */
    public function setIdTwitter($idTwitter)
    {
        $this->idTwitter = $idTwitter;

        return $this;
    }

    /**
     * Get idTwitter.
     *
     * @return string
     */
    public function getIdTwitter()
    {
        return $this->idTwitter;
    }

    /**
     * Set text.
     *
     * @param string $text
     *
     * @return Tweet
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set truncated.
     *
     * @param bool $truncated
     *
     * @return Tweet
     */
    public function setTruncated($truncated)
    {
        $this->truncated = $truncated;

        return $this;
    }

    /**
     * Get truncated.
     *
     * @return bool
     */
    public function getTruncated()
    {
        return $this->truncated;
    }
}
