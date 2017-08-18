<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 29/07/2017
 * Time: 11:16
 */

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




    public function __construct(string $consumerKey, string $consumerSecret)
    {

        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
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
        if (!$cache->has('twitter.last_tweets')) {
            $tweets = [];
            foreach ($screenNames as $screenName){
                $twitter = new TwitterOAuth($this->consumerKey, $this->consumerSecret, null, $this->getAppAccessToken());
                $tweets = array_merge($tweets,$twitter->get('statuses/user_timeline', [
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