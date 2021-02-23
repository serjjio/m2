<?php
namespace Training\Test\Controller\Index;

use Magento\Framework\Controller\Result\RawFactory;
use \Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param RawFactory $resultRawFactory
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory
    )
    {
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents('simple text');

        return $resultRaw;
    }
}
