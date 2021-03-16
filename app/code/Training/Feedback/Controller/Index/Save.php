<?php
declare(strict_types=1);

namespace Training\Feedback\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Training\Feedback\Model\FeedbackFactory;
use Training\Feedback\Model\ResourceModel\Feedback;

class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var FeedbackFactory
     */
    private $feedbackFactory;

    /**
     * @var Feedback
     */
    private $feedbackResource;

    /**
     * Save constructor.
     * @param Context $context
     * @param FeedbackFactory $feedbackFactory
     * @param Feedback $feedbackResource
     */
    public function __construct(
        Context $context,
        FeedbackFactory $feedbackFactory,
        Feedback $feedbackResource
    ) {
        $this->feedbackFactory = $feedbackFactory;
        $this->feedbackResource = $feedbackResource;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultRedirectFactory->create();
        if ($post = $this->getRequest()->getPostValue()) {
            try {
                $this->validatePost($post);
                $feedback = $this->feedbackFactory->create();
                $feedback->setData($post);
                $feedback->save();
                $this->messageManager->addSuccessMessage(
                    __('Thank you for your feedback.')
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('An error occurred while processing your form. Please try again later.')
                );
                $result->setPath('*/form/index');
                return $result;
            }
        }
        return $result->setPath('*/*/index');

    }

    /**
     * @param $post
     * @return void
     * @throws \Exception
     */
    private function validatePost($post)
    {
        if (!isset($post['author_name']) || trim($post['author_name']) === '') {
            throw new LocalizedException(__('Name is missing'));
        }
        if (!isset($post['message']) || trim($post['message']) === '') {
            throw new LocalizedException(__('Comment is missing'));
        }
        if (!isset($post['author_email']) || false === \strpos($post['author_email'], '@')) {
            throw new LocalizedException(__('Invalid email address'));
        }
        if (trim($this->getRequest()->getParam('hideit')) !== '') {
            throw new \Exception();
        }
    }
}
