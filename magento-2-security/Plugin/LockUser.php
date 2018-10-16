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

namespace Mageplaza\Security\Plugin;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\User\Model\ResourceModel\User;
use Mageplaza\Security\Helper\Data;
use Psr\Log\LoggerInterface;
use Magento\Backend\App\ConfigInterface;

/**
 * Class LockUser
 * @package Mageplaza\Security\Plugin
 */
class LockUser
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var ConfigInterface
     */
    protected $_backendConfig;

    /**
     * LockUser constructor.
     * @param ConfigInterface $backendConfig
     * @param UrlInterface $backendUrl
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param Data $helper
     */
    public function __construct
    (
        ConfigInterface $backendConfig,
        UrlInterface $backendUrl,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        Data $helper
    )
    {
        $this->_backendConfig    = $backendConfig;
        $this->_backendUrl       = $backendUrl;
        $this->_logger           = $logger;
        $this->_storeManager     = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->_helper           = $helper;
    }

    /**
     * @param User $userModel
     * @param $user
     * @param $setLockExpires
     * @param $setFirstFailure
     */
    public function beforeUpdateFailure(User $userModel, $user, $setLockExpires, $setFirstFailure)
    {
        if ($this->_helper->isEnabled()
            && $this->_helper->getConfigBruteForce('lock_user')
            && $this->_helper->getConfigBruteForce('enabled')
        ) {
            $maxFailures = $this->_backendConfig->getValue('admin/security/lockout_failures');
            if ($setLockExpires && (((int)$user->getFailuresNum() + 1) == $maxFailures)) {
                //send mail if user is locked
                $storeUrl = parse_url($this->_backendUrl->getBaseUrl(), PHP_URL_HOST);
                $sendTo   = explode(',', $this->_helper->getConfigGeneral('email'));
                $sendTo   = array_map('trim', $sendTo);
                try {
                    $store        = $this->_storeManager->getStore();
                    $templateVars = [
                        'logo_url'  => 'https://www.mageplaza.com/media/mageplaza-security-email.png',
                        'logo_alt'  => 'Mageplaza',
                        'store_url' => $storeUrl,
                        'user_name' => $user->getUserName(),
                        'viewLogUrl'   => $this->_backendUrl->getUrl('mpsecurity/loginlog/'),
                    ];

                    $this->_transportBuilder
                        ->setTemplateIdentifier('mp_lock_user_email_template')
                        ->setTemplateOptions([
                            'area'  => Area::AREA_FRONTEND,
                            'store' => $store->getId()
                        ])
                        ->setTemplateVars($templateVars)
                        ->setFrom('general')
                        ->addTo($sendTo);
                    $transport = $this->_transportBuilder->getTransport();
                    $transport->sendMessage();
                } catch (MailException $e) {
                    $this->_logger->critical($e->getLogMessage());
                }
            }
        }
    }
}

