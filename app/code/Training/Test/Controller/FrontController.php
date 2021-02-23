<?php
namespace Training\Test\Controller;

class FrontController extends \Magento\Framework\App\FrontController
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Magento\Framework\App\RouterListInterface $routerList
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\RouterListInterface $routerList,
        \Magento\Framework\App\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
        parent::__construct($routerList, $response);
    }

    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        foreach ($this->_routerList as $router) {
            $this->logger->info(get_class($router));
        }

        return parent::dispatch($request);
    }
}
