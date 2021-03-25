<?php
declare(strict_types=1);

namespace Training\FeedbackProduct\Plugin\Ui\Form\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\Data\ProductInterface;
use Training\Feedback\Api\FeedbackRepositoryInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

class AddProductToFeedback
{
    /**
     * @var FeedbackRepositoryInterface
     */
    private $feedbackRepository;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var AttributeSetRepositoryInterface
     */
    private $attributeSetRepository;

    /**
     * AddProductToFeedback constructor.
     * @param FeedbackRepositoryInterface $feedbackRepository
     * @param ImageHelper $imageHelper
     * @param Status $status
     * @param AttributeSetRepositoryInterface $attributeSetRepository
     */
    public function __construct(
        FeedbackRepositoryInterface $feedbackRepository,
        ImageHelper $imageHelper,
        Status $status,
        AttributeSetRepositoryInterface $attributeSetRepository
    ) {
        $this->feedbackRepository = $feedbackRepository;
        $this->imageHelper = $imageHelper;
        $this->status = $status;
        $this->attributeSetRepository = $attributeSetRepository;
    }

    public function afterGetData(
        \Training\Feedback\Ui\DataProvider\Form\DataProvider $subject,
        $result
    ) {
        if (count($result) > 0) {
            foreach ($result as $index => $feedbackData) {
                try {
                    $feedback = $this->feedbackRepository->getById($feedbackData['feedback_id']);
                } catch (NoSuchEntityException $e) {
                    continue;
                }
                $products = $feedback->getExtensionAttributes()->getProducts();
                if (!$products) {
                    $result[$index]['assigned_feedback_products'] = [];
                } else {
                    $assignedProducts = [];
                    foreach ($products as $product) {
                        $assignedProducts[] = $this->prepareProductDataData($product);
                    }
                    $result[$index]['assigned_feedback_products'] = $assignedProducts;
                }
            }
        }
        return $result;
    }
    /**
     * Prepare data column
     *
     * @param ProductInterface $assignedProduct
     * @return array
     */
    private function prepareProductDataData(ProductInterface $assignedProduct)
    {
        return [
            'id' => $assignedProduct->getId(),
            'thumbnail' => $this->imageHelper->init($assignedProduct, 'product_listing_thumbnail')->getUrl(),
            'name' => $assignedProduct->getName(),
            'status' => $this->status->getOptionText($assignedProduct->getStatus()),
            'attribute_set' => $this->attributeSetRepository
                ->get($assignedProduct->getAttributeSetId())
                ->getAttributeSetName(),
            'sku' => $assignedProduct->getSku(),
            'price' => $assignedProduct->getPrice(),
        ];
    }
}
