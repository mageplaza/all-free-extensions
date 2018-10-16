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

namespace Mageplaza\Security\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class LoginLog
 * @package Mageplaza\Security\Block\Adminhtml
 */
class LoginLog extends Container
{
    /**
     * LoginLog constructor.
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_loginlog';
        $this->_blockGroup = 'Mageplaza_Security';
        $this->_headerText = __('Login Log');
        parent::_construct();
    }
}