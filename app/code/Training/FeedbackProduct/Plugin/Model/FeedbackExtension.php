<?php
declare(strict_types=1);

namespace Training\FeedbackProduct\Plugin\Model;

use Training\Feedback\Api\Data\FeedbackExtensionInterfaceFactory;

class FeedbackExtension
{
    /**
     * @var FeedbackExtensionInterfaceFactory
     */
    private $extensionAttributesFactory;

    /**
     * FeedbackExtension constructor.
     * @param FeedbackExtensionInterfaceFactory $extensionAttributesFactory
     */
    public function __construct(FeedbackExtensionInterfaceFactory $extensionAttributesFactory)
    {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }

    /**
     * @param \Training\Feedback\Api\Data\FeedbackInterface $subject
     * @param $result
     * @return \Training\Feedback\Api\Data\FeedbackExtensionInterface
     */
    public function afterGetExtensionAttributes(
        \Training\Feedback\Api\Data\FeedbackInterface $subject,
        $result
    ) {
        if (!is_null($result)) {
            return $result;
        }
        /** @var \Training\Feedback\Api\Data\FeedbackExtensionInterface $extensionAttributes */
        $extensionAttributes = $this->extensionAttributesFactory->create();
        $subject->setExtensionAttributes($extensionAttributes);
        return $extensionAttributes;
    }
}
