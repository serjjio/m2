<?php
declare(strict_types=1);

namespace Training\FeedbackProduct\Model;

class FeedbackDataLoader
{
    const PRODUCT_ID_FIELD = 'entity_id';
    const PRODUCT_SKU_FIELD = 'sku';

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;

    /**
     * FeedbackDataLoader constructor.
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @param $feedback
     * @param $skus
     * @return mixed
     */
    public function addProductsToFeedbackBySkus($feedback, $skus)
    {
        $feedback->getExtensionAttributes()
            ->setProducts($this->getProductsByField(self::PRODUCT_SKU_FIELD, $skus));
        return $feedback;
    }

    /**
     * @param $feedback
     * @param $ids
     * @return mixed
     */
    public function addProductsToFeedbackByIds($feedback, $ids)
    {
        $feedback->getExtensionAttributes()
            ->setProducts($this->getProductsByField(self::PRODUCT_ID_FIELD, $ids));
        return $feedback;
    }

    /**
     * @param $field
     * @param $value
     * @return array|\Magento\Catalog\Api\Data\ProductInterface[]
     */
    private function getProductsByField($field, $value)
    {
        print_r($value);
        if (!is_array($value) || !count($value)) {
            return [];
        }
        $skuFilter = $this->filterBuilder
            ->setField($field)
            ->setValue($value)
            ->setConditionType('in')
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilters([$skuFilter])
            ->create();

        return $this->productRepository->getList($searchCriteria)->getItems();
    }
}
