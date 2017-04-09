<?php

namespace CommonServices\UserServiceBundle\Controller\MetaInfo;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiRoutesController
 * @package CommonServices\UserServiceBundle\Controller\MetaInfo
 */
class ApiRoutesController extends Controller
{
    /**
     * Login user with email or mobile number
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAPiRoutesAction()
    {
        /** @var $router \Symfony\Component\Routing\Router */
        $router = $this->container->get('router');

        /** @var $collection \Symfony\Component\Routing\RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();

        $routes = array();

        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params)
        {
            $defaults = $params->getDefaults();
            $path     = $params->getPath();

            sizeof($params->getMethods()) === 0 ?
                $method = "GET" :
                $method   = implode("," , $params->getMethods());

            if (isset($defaults['_controller']))
            {
                $controllerAction = explode(':', $defaults['_controller']);
                $controller = $controllerAction[0];

                if (!isset($routes[$controller])) {
                    $routes[$controller] = [];
                }

                $routes[$controller]= [
                    'route' =>$route,
                    'method' =>$method,
                    'path' =>$path,
                ];
            }
        }

        // remove all unwanted routes patterns
        $patterns =['/assetic./', '/web_profiler./','/twig./'];

        foreach ($patterns as $patternKey => $patternValue)
        {
            foreach($routes as $key => $value)
            {
                if (preg_match($patternValue, $key))
                {
                    unset($routes[$key]);
                }
            }
        }

        $routesListing = [];

        foreach ($routes as $index => $route)
        {
            $routesListing[$route['route']] = [
                'path'   => $route['path'],
                'method' => $route['method'],
            ];
        }

        unset($routesListing['fos_js_routing_js']);
        unset($routesListing['nelmio_api_doc_index']);
        unset($routesListing['user_service_api_info']);

        $response = $this->render('@UserService/info/service-info.html.twig', ['routes' => json_encode($routesListing)] );
        $response->setSharedMaxAge(3600);
        $response->setCache(array(
            'max_age'       => 10,
            's_maxage'      => 10,
            'public'        => true,
        ));
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}
