<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Инициализируем роутер. по умолчанию трактует все запросы как REST,
     * за исключением отдельно определённых эндпоинтов
     */
    public function _initRoutes()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        $restRoute = new Zend_Rest_Route($frontController);
        $router->addRoute('default', $restRoute);
        $router->addRoute(
            'rates/refresh',
            new Zend_Controller_Router_Route(
                'rates/refresh',
                [
                    'controller' => 'rates',
                    'action' => 'refresh'
                ]
            )
        );
        $router->addRoute(
            'rates/toggle',
            new Zend_Controller_Router_Route(
                'rates/:id/toggle',
                [
                    'controller' => 'rates',
                    'action' => 'toggle'
                ],
                [
                    'id' => '\d+'
                ]
            )
        );
    }
}

