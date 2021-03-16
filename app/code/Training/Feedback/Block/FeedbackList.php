<?php
declare(strict_types=1);

namespace Training\Feedback\Block;

use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\View\Element\Template\Context;
use Training\Feedback\Model\ResourceModel\Feedback\CollectionFactory;

class FeedbackList extends \Magento\Framework\View\Element\Template
{
    const PAGE_SIZE = 5;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var
     */
    private $collection;

    /**
     * @var Timezone
     */
    private $timezone;

    /**
     * FeedbackList constructor.
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Timezone $timezone
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Timezone $timezone,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->timezone = $timezone;
    }

    /**
     * @return \Training\Feedback\Model\ResourceModel\Feedback\Collection
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $this->collection = $this->collectionFactory->create();
            $this->collection->addFieldToFilter('is_active', 1);
            $this->collection->setOrder('creation_time', 'DESC');
        }
        return $this->collection;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        $pagerBlock = $this->getChildBlock('feedback_list_pager');
        if ($pagerBlock instanceof \Magento\Framework\DataObject) {
            /* @var $pagerBlock \Magento\Theme\Block\Html\Pager */
            $pagerBlock
                ->setUseContainer(false)
                ->setShowPerPage(false)
                ->setShowAmounts(false)
                ->setLimit($this->getLimit())
                ->setCollection($this->getCollection());
            return $pagerBlock->toHtml();
        }
        return '';
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return static::PAGE_SIZE;
    }

    /**
     * @return string
     */
    public function getAddFeedbackUrl()
    {
        return $this->getUrl('training_feedback/form/index');
    }

    /**
     * @param $feedback
     * @return false|string
     */
    public function getFeedbackDate($feedback)
    {
        return $this->timezone->formatDateTime($feedback->getCreationTime());
    }
}
