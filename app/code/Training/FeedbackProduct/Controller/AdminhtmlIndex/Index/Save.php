<?php
declare(strict_types=1);

namespace Training\FeedbackProduct\Controller\AdminhtmlIndex\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Training_Feedback::feedback_save';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Magento\Framework\Event\Manager
     */
    private $eventManager;

    /**
     * @var \Training\Feedback\Api\FeedbackRepositoryInterface
     */
    private $feedbackRepository;

    /**
     * @var \Training\Feedback\Model\FeedbackFactory
     */
    private $feedbackFactory;

    /**
     * @var \Training\FeedbackProduct\Model\FeedbackDataLoader
     */
    private $feedbackDataLoader;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Training\Feedback\Api\FeedbackRepositoryInterface $feedbackRepository
     * @param \Training\Feedback\Model\FeedbackFactory $feedbackFactory
     * @param \Training\FeedbackProduct\Model\FeedbackDataLoader $feedbackDataLoader
     * @param \Magento\Framework\Event\Manager $eventManager
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        \Training\Feedback\Api\FeedbackRepositoryInterface $feedbackRepository,
        \Training\Feedback\Model\FeedbackFactory $feedbackFactory,
        \Training\FeedbackProduct\Model\FeedbackDataLoader $feedbackDataLoader,
        \Magento\Framework\Event\Manager $eventManager
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->feedbackRepository = $feedbackRepository;
        $this->feedbackFactory = $feedbackFactory;
        $this->feedbackDataLoader = $feedbackDataLoader;
        $this->eventManager = $eventManager;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = \Training\Feedback\Model\Feedback::STATUS_ACTIVE;
            }
            if (empty($data['feedback_id'])) {
                $data['feedback_id'] = null;
            }
            $model = $this->feedbackFactory->create();
            $id = $this->getRequest()->getParam('feedback_id');
            if ($id) {
                try {
                    $model = $this->feedbackRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This feedback no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $model->setData($data);
            try {
                $this->setProductsToFeedback($model, $data);
                $this->feedbackRepository->save($model);
                $this->eventManager->dispatch('training_feedback_save_after', ['feedback'=>$model]);
                $this->messageManager->addSuccessMessage(__('You saved the feedback.'));
                $this->dataPersistor->clear('training_feedback');
                return $this->processRedirect($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager
                    ->addExceptionMessage($e, __('Something went wrong while saving the feedback.'));
            }
            $this->dataPersistor->set('training_feedback', $data);
            return $resultRedirect->setPath(
                '*/*/edit',
                ['feedback_id' => $this->getRequest()->getParam('feedback_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
    private function setProductsToFeedback($feedback, $post)
    {
        $productIds = [];
        if (isset($post['assigned_feedback_products']) && !empty($post['assigned_feedback_products'])) {
            foreach ($post['assigned_feedback_products'] as $productData) {
                $productIds[] = $productData['id'];
            }
        }
        $this->feedbackDataLoader->addProductsToFeedbackByIds($feedback, $productIds);
    }

    private function processRedirect($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';
        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['feedback_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect;
    }
}
