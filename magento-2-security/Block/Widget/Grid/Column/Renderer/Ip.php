<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Security
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     http://mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Security\Block\Widget\Grid\Column\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Mageplaza\Security\Model\LoginLogFactory;

/**
 * Class Ip
 * @package Mageplaza\Security\Block\Widget\Grid\Column\Renderer
 */
class Ip extends AbstractRenderer
{
    /**
     * @var LoginLogFactory
     */
    protected $_logFactory;

    /**
     * LastTimeLogin constructor.
     * @param \Mageplaza\Security\Model\LoginLogFactory $logFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct
    (
        LoginLogFactory $logFactory,
        Context $context,
        array $data = []
    )
    {
        $this->_logFactory = $logFactory;

        parent::__construct($context, $data);
    }

    /**
     * Renders grid column
     *
     * @param   \Magento\Framework\DataObject $row
     * @return  string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $userName = $row->getData('username');
        $lastLog  = $this->_logFactory->create()->getCollection()
            ->addFieldToFilter('user_name', $userName)
            ->addFieldToFilter('status', 1)
            ->getLastItem();

        return '<a href="http://www.traceip.net/?query=' . $lastLog->getIp() . '" target="_blank">' . $lastLog->getIp() . '</a>';
    }
}
