<?php
namespace Training\Render\Controller\Onecolumn;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultPage;

    /**
     * Index constructor.
     * @param ResultFactory $resultPage
     */
    public function __construct(ResultFactory $resultPage)
    {
        $this->resultPage = $resultPage;
    }

    public function execute()
    {
        $page = $this->resultPage->create(ResultFactory::TYPE_PAGE);

        return $page;
    }
}
