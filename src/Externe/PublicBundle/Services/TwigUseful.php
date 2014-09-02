<?php

namespace Externe\PublicBundle\Services;

class TwigUseful extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('url_decode', array($this, 'urlDecode')),
        );
    }

    public function urlDecode($url)
    {
        return urldecode($url);
    }

    public function getName()
    {
        return 'twig_useful';
    }
}