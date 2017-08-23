<?php

namespace AppBundle\Twitter;


class AutolinkExtension extends \Twig_Extension
{

    public function autolink($tweet){
        return \Twitter_Autolink::create()->autoLink($tweet);
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('autolink', [$this, 'autolink'])
        ];
    }

    public function getName()
    {
        return 'AutolinkTwitterExtension';
    }
}