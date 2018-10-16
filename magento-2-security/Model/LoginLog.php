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

namespace Mageplaza\Security\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class LoginLog
 * @package Mageplaza\Security\Model
 */
class LoginLog extends AbstractModel
{
    /**
     * Initialize model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Mageplaza\Security\Model\ResourceModel\LoginLog');
    }
}
