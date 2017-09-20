<?php

/**
 * @author BRAMILLE Sébastien <contact@oktapodia.com>
 */

namespace Maxmind\Bundle\GeoipBundle\Twig;

use Maxmind\Bundle\GeoipBundle\Service\GeoipManager;
use Twig_Environment;

/**
 * Class GeoipExtension
 */
class GeoipExtension extends \Twig_Extension
{
    /**
     * @var GeoipManager
     */
    private $geoipManager;

    /**
     * GeoipExtension constructor.
     *
     * @param GeoipManager $geoipManager
     */
    public function __construct(GeoipManager $geoipManager)
    {
        $this->geoipManager = $geoipManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('geoip', array($this, 'geoipFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'code',
                array($this, 'getCode'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            ),
        );
    }

    /**
     * @param string $ip
     *
     * @return false|GeoipManager
     */
    public function geoipFilter($ip)
    {
        return $this->geoipManager->lookup($ip);
    }

    /**
     * @param Twig_Environment $env
     * @param $template
     * @return bool|mixed
     * @throws \Twig_Error_Runtime
     */
    public function getCode(Twig_Environment $env, $template)
    {
        if ($env->hasExtension('demo')) {
            $functions = $env->getExtension('demo')->getFunctions();
            foreach ($functions as $function) {
                if ($function->getName() === 'code') {
                    return call_user_func($function->getCallable(), $template);
                }
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'geoip_extension';
    }
}
