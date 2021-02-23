<?php
declare(strict_types=1);

namespace Training\Test\Controller\Router;

class NoRouteHandler implements \Magento\Framework\App\Router\NoRouteHandlerInterface
{
    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function process(\Magento\Framework\App\RequestInterface $request): bool
    {
    $moduleName = 'cms';
    $controllerPath = 'index';
    $controllerName = 'index';
    $request->setModuleName($moduleName)
        ->setControllerName($controllerPath)
        ->setActionName($controllerName);
    return true;
}
}
