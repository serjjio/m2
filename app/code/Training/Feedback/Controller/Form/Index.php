<?php
declare(strict_types=1);

namespace Training\Feedback\Controller\Form;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var ResultFactory
     */
    private $resultPage;

    /**
     * Index constructor.
     * @param ResultFactory $resultPage
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ResultFactory $resultPage
    ) {
        $this->resultPage = $resultPage;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        return $this->resultPage->create(ResultFactory::TYPE_PAGE);
    }
}
