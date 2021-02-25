<?php
namespace Training\Test\Plugin\Block\Product\View;

class DescriptionPlugin
{
    public function beforeToHtml(
        \Magento\Catalog\Block\Product\View\Description $subject
    ) {
        /*Task 3.4*/
        //$subject->getProduct()->setData('description', 'Test Description');

        /*Task 3.7*/
        //$subject->setTemplate('Training_Test::description.phtml');

        /*Task 3.8*/
        if ($subject->getNameInLayout() == 'product.info.sku') {
            $subject->setTemplate('Training_Test::description.phtml');
        }
    }
}
