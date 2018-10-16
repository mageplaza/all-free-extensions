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

namespace Mageplaza\Security\Cron;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Security\Helper\Data;
use Mageplaza\Security\Model\Config\Source\LoginLog\Status;
use Mageplaza\Security\Model\LoginLogFactory;
use Mageplaza\Security\Model\ResourceModel\LoginLog\CollectionFactory;
use Psr\Log\LoggerInterface;

/**
 * Class SendMail
 * @package Mageplaza\Security\Cron
 */
class SendMail
{
    /**
     * @var LoginLogFactory
     */
    protected $logFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CollectionFactory
     */
    protected $logCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @var UrlInterface
     */
    protected $backendUrl;

    /**
     * SendMail constructor.
     * @param UrlInterface $backendUrl
     * @param TimezoneInterface $timezone
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param LoginLogFactory $logFactory
     * @param CollectionFactory $logCollectionFactory
     * @param Data $helper
     */
    public function __construct(
        UrlInterface $backendUrl,
        TimezoneInterface $timezone,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        LoginLogFactory $logFactory,
        CollectionFactory $logCollectionFactory,
        Data $helper
    )
    {
        $this->transportBuilder     = $transportBuilder;
        $this->storeManager         = $storeManager;
        $this->logger               = $logger;
        $this->logFactory           = $logFactory;
        $this->helper               = $helper;
        $this->logCollectionFactory = $logCollectionFactory;
        $this->timezone             = $timezone;
        $this->backendUrl           = $backendUrl;
    }

    /**
     * Send Mail
     *
     * @return void
     */
    public function execute()
    {
        $logCollection = $this->logCollectionFactory->create()
            ->addFieldToFilter('is_warning', 1);
        if ($logCollection->getSize()) {
            $failedCount       = (float)$this->helper->getConfigBruteForce('failed_count');
            $failedTime        = (float)$this->helper->getConfigBruteForce('failed_time');
            $warningLog        = $logCollection->getFirstItem();
            $warningTime       = $warningLog->getTime();
            $availableTime     = $this->getAvailableTime($warningTime, $failedTime);
            $logMailCollection = $this->logCollectionFactory->create()
                ->addFieldToFilter('status', Status::STATUS_FAIL)
                ->addFieldToFilter('time', ['gteq' => $availableTime])
                ->setOrder('time', 'DESC');
            $logArr            = [];
            if ($logMailCollection->getSize()) {
                foreach ($logMailCollection as $item) {
                    if ($item->getIsSentMail()) {
                        break;
                    }
                    $logArr[] = [
                        'user_name' => $item->getUserName(),
                        'ip'        => $item->getIp(),
                        'time'      => $this->timezone->date($item->getTime())->format('M d Y H:i:s')
                    ];
                }
            }

            if ($this->helper->getConfigGeneral('email')) {
                $sendTo   = explode(',', $this->helper->getConfigGeneral('email'));
                $sendTo   = array_map('trim', $sendTo);
                $storeUrl = parse_url($this->backendUrl->getBaseUrl(), PHP_URL_HOST);
                try {
                    $store        = $this->storeManager->getStore();
                    $templateVars = [
                        'logs'         => $logArr,
                        'failed_count' => $failedCount,
                        'failed_time'  => $failedTime,
                        'viewLogUrl'   => $this->backendUrl->getUrl('mpsecurity/loginlog/'),
                        'logo_url'     => 'https://www.mageplaza.com/media/mageplaza-security-email.png',
                        'logo_alt'     => 'Mageplaza',
                        'store_url'    => $storeUrl
                    ];

                    $this->transportBuilder
                        ->setTemplateIdentifier($this->helper->getConfigBruteForce('email_template'))
                        ->setTemplateOptions([
                            'area'  => Area::AREA_FRONTEND,
                            'store' => $store->getId()
                        ])
                        ->setTemplateVars($templateVars)
                        ->setFrom('general')
                        ->addTo($sendTo);
                    $transport = $this->transportBuilder->getTransport();
                    $transport->sendMessage();
                    $logFactory = $this->logFactory->create();
                    foreach ($logMailCollection as $item) {
                        if ($item->getIsSentMail()) {
                            break;
                        }
                        $logFactory->load($item->getId())
                            ->setIsSentMail(1)
                            ->setIsWarning(0)
                            ->save();;
                    }
                } catch (\Magento\Framework\Exception\MailException $e) {
                    $this->logger->critical($e->getLogMessage());
                }
            }
        }
    }

    /**
     * @param $warningTime
     * @param $failedTime
     * @return false|string
     */
    private function getAvailableTime($warningTime, $failedTime)
    {
        $date = date_create($warningTime);
        date_sub($date, date_interval_create_from_date_string($failedTime . " minutes"));

        return date_format($date, 'Y-m-d H:i:s');
    }
}
