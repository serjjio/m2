<?php
namespace Training\Test\Plugin\Block\Product\View;

class DescriptionPlugin
{
    public function beforeToHtml(
        \Magento\Catalog\Block\Product\View\Description $subject
    ) {
        $subject->getProduct()->setData('description', 'Test Description');
    }
}
