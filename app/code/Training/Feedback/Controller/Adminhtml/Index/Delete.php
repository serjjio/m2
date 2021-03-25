<?php
declare(strict_types=1);

namespace Training\Feedback\Controller\Adminhtml\Index;

use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action\Context;

class Delete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Training_Feedback::feedback_delete';

    /**
     * @var \Training\Feedback\Api\FeedbackRepositoryInterface
     */
    private $feedbackRepository;

    /**
     * @param Context $context
     * @param \Training\Feedback\Api\FeedbackRepositoryInterface $feedbackRepository
     */
    public function __construct(
        Context $context,
        \Training\Feedback\Api\FeedbackRepositoryInterface $feedbackRepository
    ) {
        $this->feedbackRepository = $feedbackRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('feedback_id');
        if ($id) {
            try {
                $this->feedbackRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the feedback.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['feedback_id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t delete the feedback.'));
                return $resultRedirect->setPath('*/*/edit', ['feedback_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a feedback to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
