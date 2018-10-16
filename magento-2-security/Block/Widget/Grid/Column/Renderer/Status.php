<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Security
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Security\Block\Widget\Grid\Column\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

/**
 * Class Status
 * @package Mageplaza\Security\Block\Widget\Grid\Column\Renderer
 */
class Status extends AbstractRenderer
{
    /**
     * Renders grid column
     *
     * @param   \Magento\Framework\DataObject $row
     * @return  string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        if ($row->getData($this->getColumn()->getIndex()) == \Mageplaza\Security\Model\Config\Source\LoginLog\Status::STATUS_FAIL) {
            return '<div class="grid-severity-minor">' . __('Failed') . '</div>';
        } else {
            return '<div class="grid-severity-notice">' . __('Success') . '</div>';
        }
    }
}
