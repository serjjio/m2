<?php

namespace Training\TestOM\Controller\Cms;

use Magento\Cms\Helper\Page;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Training\TestOM\Model\Test;

class Index extends \Magento\Cms\Controller\Index\Index
{
    private $test;

    private $playWithTest;

    /**
     * Index constructor.
     *
     * @param Test $test
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param ScopeConfigInterface|null $scopeConfig
     * @param Page|null $page
     */
    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        ScopeConfigInterface $scopeConfig = null,
        Page $page = null,
        Test $test,
        \Training\TestOM\Model\PlayWithTest $playWithTest
    ) {
        $this->test = $test;
        $this->playWithTest = $playWithTest;
        parent::__construct($context, $resultForwardFactory, $scopeConfig, $page);
    }

    public function execute($coreRoute = null)
    {
        //$this->test->log();
        $this->playWithTest->run();
        return parent::execute($coreRoute);
    }
}
