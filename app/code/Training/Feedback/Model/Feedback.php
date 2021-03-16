<?php
declare(strict_types=1);

namespace Training\Feedback\Model;

class Feedback extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_INACTIVE = '0';

    /**
     * Initialize resources
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Training\Feedback\Model\ResourceModel\Feedback::class);
    }
}
