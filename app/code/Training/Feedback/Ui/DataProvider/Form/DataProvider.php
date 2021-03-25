<?php
declare(strict_types=1);

namespace Training\Feedback\Ui\DataProvider\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Training\Feedback\Model\ResourceModel\Feedback\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Training\Feedback\Model\ResourceModel\Feedback\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $data = $this->dataPersistor->get('training_feedback');
        if (!empty($data)) {
            $feedback = $this->collection->getNewEmptyItem();
            $feedback->setData($data);
            $this->loadedData[$feedback->getId()] = $feedback->getData();
            $this->dataPersistor->clear('training_feedback');
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Training\Feedback\Model\Feedback $feedback */
        foreach ($items as $feedback) {
            $this->loadedData[$feedback->getId()] = $feedback->getData();
        }

        return $this->loadedData;
    }
}
