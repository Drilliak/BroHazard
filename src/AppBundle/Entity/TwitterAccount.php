<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 17/08/17
 * Time: 19:16.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Twitter accoount.
 *
 * @ORM\Table(name="twitter_accounts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TwitterAccountRepository")
 */
class TwitterAccount
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
     * @var string
     *
     * @ORM\Column(name="username", type="string")
     */
    private $username;

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
     * Set username.
     *
     * @param string $username
     *
     * @return TwitterAccount
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
