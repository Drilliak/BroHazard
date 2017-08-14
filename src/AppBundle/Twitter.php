<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 29/07/2017
 * Time: 11:16
 */

namespace AppBundle;


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

    public function lastTweets($screenName, $limit = 3)
    {
        $cache = new FilesystemCache();
        if (!$cache->has('twitter.last_tweets')) {
            $twitter = new TwitterOAuth($this->consumerKey, $this->consumerSecret, null, $this->getAppAccessToken());
            $tweets = $twitter->get('statuses/user_timeline', [
                'screen_name'     => $screenName,
                'exclude_replies' => false,
                'count'           => 50
            ]);
            $cache->set('twitter.last_tweets', $tweets, 180);
        } else {
            $tweets = $cache->get('twitter.last_tweets');
        }

        return array_splice($tweets, 0, $limit);
    }
}