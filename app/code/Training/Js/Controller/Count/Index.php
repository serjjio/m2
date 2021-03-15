<?php
declare(strict_types=1);

namespace Training\Js\Controller\Count;

use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var StockItemRepository
     */
    private $stockItemRepository;

    /**
     * Index constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        StockItemRepository $stockItemRepository
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->stockItemRepository = $stockItemRepository;
        parent::__construct($context);
    }

    /**
     * Return response as JSON.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        if ($this->getRequest()->isAjax()) {
            $productId = $this->getRequest()->getParam('productId');
            if ($productId) {
                $resultJson->setData(
                    $data = [
                        'success' => true,
                        'qty' => $this->getQtyProduct($productId)
                    ]
                );
            }
        }
        return $resultJson;
    }

    /**
     * @param $productId
     * @return float|int
     */
    private function getQtyProduct($productId)
    {
        try {
            $productStock = $this->stockItemRepository->get($productId);
            $qty = $productStock->getQty();
        } catch (\Exception $e) {
            $qty = 0;
        }
        return $qty;
    }
}
