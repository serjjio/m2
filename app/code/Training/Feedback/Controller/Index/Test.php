<?php
declare(strict_types=1);

namespace Training\Feedback\Controller\Index;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Training\Feedback\Api\Data\FeedbackInterfaceFactory as FeedbackFactory;
use Training\Feedback\Api\FeedbackRepositoryInterface;

class Test extends \Magento\Framework\App\Action\Action
{
    /**
     * @var FeedbackFactory
     */
    private $feedbackFactory;

    /**
     * @var FeedbackRepositoryInterface
     */
    private $feedbackRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Training\Feedback\Api\Data\FeedbackInterfaceFactory $feedbackFactory,
        \Training\Feedback\Api\FeedbackRepositoryInterface $feedbackRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
    ) {
        $this->feedbackFactory = $feedbackFactory;
        $this->feedbackRepository = $feedbackRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
        // create new item
        $newFeedback = $this->feedbackFactory->create();
        $newFeedback->setAuthorName('some name');
        $newFeedback->setAuthorEmail('test@test.com');
        $newFeedback->setMessage('ghj dsghjfghs sghkfgsdhfkj sdhjfsdf gsfkj');
        $newFeedback->setIsActive(1);
        $this->feedbackRepository->save($newFeedback);

        // load item by id
        $feedback = $this->feedbackRepository->getById(9);
        $this->printFeedback($feedback);

        // update item
        $feedbackToUpdate = $this->feedbackRepository->getById(9);
        $feedbackToUpdate->setMessage('CUSTOM ' . $feedbackToUpdate->getMessage());

        // delete feedback
        $this->feedbackRepository->deleteById(9);

        // load multiple items
        $this->searchCriteriaBuilder
            ->addFilter('is_active', 1);
        $sortOrder = $this->sortOrderBuilder
            ->setField('author_name')
            ->setAscendingDirection()
            ->create();
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResult = $this->feedbackRepository->getList($searchCriteria);
        foreach ($searchResult->getItems() as $item) {
            $this->printFeedback($item);
        }
        exit();
    }

    private function printFeedback($feedback)
    {
        echo $feedback->getId() . ' : '
            . $feedback->getAuthorName()
            . ' (' . $feedback->getAuthorEmail() . ')';
        echo "<br/>\n";
    }
}
