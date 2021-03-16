<?php
declare(strict_types=1);

namespace Training\Feedback\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Feedback extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('training_feedback', 'feedback_id');
    }
}
