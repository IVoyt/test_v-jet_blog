<?php

    namespace base;

    class Router
    {

        private $routes;

        public function __construct()
        {
            $this->routes = require __DIR__.'/../config/routes.php';
            $this->parseRoute();
        }

        public function parseRoute()
        {
            foreach ($this->routes as $route => $routeAction) {
                $parseAction = $this->parseAction($routeAction);
                $controller = $parseAction[0];
                $action = $parseAction[1];

                // check if route has dynamic params
                preg_match_all('/{([a-z0-9\-_]+)}/i', $route, $matchedParams);
                if(substr($route,0,1) != '/') $route = '/'.$route;
                $pattern = str_replace(['/', '?'], ['\/', '\?'], preg_replace('/{[a-z0-9\-_]+}/i', '([a-z0-9\-_]+)', $route));
                // check if route equals pattern
                preg_match_all('/'.$pattern.'/i', Application::getInstance()->getPath(), $matches);
                $matchedRoute = $matches[0][0] ?? '';

                if($matchedRoute == Application::getInstance()->getPath()) {
                    $controller = new $controller();
                    // route has params
                    if(!empty($matchedParams[1])) {
                        $params = [];
                        foreach ($matchedParams[1] as $key => $param) {
                            $params[$param] = $matches[$key+1][0];
                        }
                        return call_user_func_array([$controller, $action], $params);
                    }
                    // route has no params
                    else {
                        return $controller->$action();
                    }
                }
            }
            // no routes match
            $controller = new BaseController();
            return $controller->notFound();
        }

        public function parseAction($routeAction)
        {
            $parseAction = explode('/', $routeAction);
            $controller = ucfirst($parseAction[count($parseAction) - 2]).'Controller';
            $action = $parseAction[count($parseAction) - 1];
            // convert action name to camelCase if it contains hyphens
            $actionElms = explode('-', $action);
            foreach ($actionElms as $key => &$elm) {
                if($key != 0) $elm = ucfirst($elm);
            }
            $action = implode('', $actionElms);
            // unset controller and action to make path
            unset($parseAction[count($parseAction) - 1], $parseAction[count($parseAction) - 1]);
            $path = 'controller\\';
            if(count($parseAction) > 0)
                $path .= str_replace('/', '\\', implode('/', $parseAction)).'\\';

            return [$path.$controller, $action];
        }

    }