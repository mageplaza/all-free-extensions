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

namespace Mageplaza\Security\Observer;

use Magento\Backend\Model\Session;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Mageplaza\Security\Helper\Data;
use Mageplaza\Security\Model\Config\Source\LoginLog\Status;
use Mageplaza\Security\Model\LoginLogFactory;
use Mageplaza\Security\Model\ResourceModel\LoginLog\CollectionFactory;

/**
 * Class LoginFailed
 * @package Mageplaza\Security\Observer
 */
class LoginFailed implements ObserverInterface
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Session
     */
    protected $_backendSession;

    /**
     * @var LoginLogFactory
     */
    protected $_loginLogFactory;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @var CollectionFactory
     */
    protected $_loginLogCollectionFactory;

    /**
     * LoginFailed constructor.
     * @param Request $request
     * @param Session $session
     * @param LoginLogFactory $loginLogFactory
     * @param CollectionFactory $loginLogCollectionFactory
     * @param Data $helperData
     */
    public function __construct(
        Request $request,
        Session $session,
        LoginLogFactory $loginLogFactory,
        CollectionFactory $loginLogCollectionFactory,
        Data $helperData
    )
    {
        $this->_request                   = $request;
        $this->_backendSession            = $session;
        $this->_loginLogFactory           = $loginLogFactory;
        $this->_helperData                = $helperData;
        $this->_loginLogCollectionFactory = $loginLogCollectionFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        if ($this->_helperData->isEnabled()) {
            $clientIp = $this->_request->getClientIp();
            $userName = $observer->getUserName();
            $loginLog = [
                'time'          => time(),
                'user_name'     => $userName,
                'ip'            => $clientIp,
                'browser_agent' => $this->_backendSession->getBrowserAgent(),
                'url'           => $this->_backendSession->getUrl(),
                'referer'       => $this->_backendSession->getRefererUrl(),
                'status'        => Status::STATUS_FAIL
            ];

            if ($this->_helperData->getConfigBruteForce('enabled')) {
                $failedCount        = (float)$this->_helperData->getConfigBruteForce('failed_count');
                $failedTime         = (float)$this->_helperData->getConfigBruteForce('failed_time');
                $availableTime      = date('Y-m-d H:i:s', strtotime(
                        "-" . $failedTime . " minutes")
                );
                $loginLogCollection = $this->_loginLogCollectionFactory->create()
                    ->addFieldToFilter('status', Status::STATUS_FAIL)
                    ->addFieldToFilter('time', ['gteq' => $availableTime])
                    ->setOrder('id', 'DESC');

                $warningLog = $this->_loginLogFactory->create()->load(1, 'is_warning');

                if ($loginLogCollection->getSize() >= $failedCount && !$warningLog->getId()) {
                    $loginLog['is_warning'] = 1;
                }
            }
            $this->_loginLogFactory->create()->addData($loginLog)->save();
        }
    }
}