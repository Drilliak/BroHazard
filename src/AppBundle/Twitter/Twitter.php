<?php

namespace AppBundle\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Component\Cache\Simple\FilesystemCache;

class Twitter
{

    /**
     * @var string
     */
    private $consumerKey;

    /**
     * @var string
     */
    private $consumerSecret;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $accessTokenSecret;

    /**
     * @var string
     */
    private $environment;


    public function __construct(string $consumerKey, string $consumerSecret, string $accessToken, string $accessTokenSecret, string $environment)
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->accessToken = $accessToken;
        $this->accessTokenSecret = $accessTokenSecret;
        $this->environment = $environment;
    }

    /**
     * @return mixed
     */
    private function getAppAccessToken()
    {
        $oauth = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
        $accessToken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);
        return $accessToken->access_token;
    }

    public function lastTweets(array $screenNames, $limit = 3)
    {
        $cache = new FilesystemCache();
        if (!$cache->has('twitter.last_tweets') || true) {
            $tweets = [];
            foreach ($screenNames as $screenName) {
                $twitter = new TwitterOAuth($this->consumerKey, $this->consumerSecret, null, $this->getAppAccessToken());
                $tweets = array_merge($tweets, $twitter->get('statuses/user_timeline', [
                    'screen_name'     => $screenName,
                    'exclude_replies' => false,
                    'count'           => 5
                ]));
            }
            usort($tweets, [$this, "sort"]);
            $cache->set('twitter.last_tweets', $tweets, 180);
        } else {
            $tweets = $cache->get('twitter.last_tweets');
        }

        return array_splice($tweets, 0, $limit);
    }

    /**
     * Permet de tweet un contenu.
     *
     * @param string $status Contenu du tweet. Doit être inférieur à 140 caractères.
     * @return array|object
     */
    public function tweet(string $status)
    {
        if ($this->environment === "prod") {
            $twitter = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->accessTokenSecret);

            return $twitter->post('statuses/update', [
                'status' => $status
            ]);
        }

    }

    public function destroy(string $id)
    {
        $twitter = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->accessTokenSecret);
        return $twitter->post('statuses/destroy/' . $id);
    }

    public function configuration()
    {
        $twitter = new TwitterOAuth($this->consumerKey, $this->consumerSecret, null, $this->getAppAccessToken());
        return $twitter->get('help/configuration');
    }

    private function sort($tweet1, $tweet2)
    {
        $format = "D M d H:i:s +B Y";
        $d1 = new \DateTime($tweet1->created_at);
        $d1->format($format);
        $d2 = new \DateTime($tweet2->created_at);
        $d2->format($format);

        if ($d1 == $d2) {
            return 0;
        } elseif ($d1 < $d2) {
            return 1;
        } else {
            return -1;
        }

    }
}