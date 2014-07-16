<?php
//http://stackoverflow.com/questions/17212753/httpbasicauth-inside-of-a-slim-route
//http://help.slimframework.com/discussions/questions/296-middleware-usage-only-on-specific-routes

namespace Slim\Extras\Middleware;

class HttpBasicAuthCustom extends \Slim\Extras\Middleware\HttpBasicAuth {
    protected $route;

    public function __construct($username, $password, $realm = 'Protected Area', $route = '') {
        $this->route = $route;
        parent::__construct($username, $password, $realm);        
    }

    public function call() {
        if(strpos($this->app->request()->getPathInfo(), $this->route) !== false) {
            parent::call();
            return;
        }
        $this->next->call();
    }
}

