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

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\User\Model\UserFactory;
use Mageplaza\Security\Helper\Data;

/**
 * Class Checklist
 * @package Mageplaza\Security\Block\Adminhtml
 */
class Checklist extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Mageplaza_Security::checklist/index.phtml';

    /**
     * @var UserFactory
     */
    protected $_userFactory;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var DeploymentConfig
     */
    protected $_deploymentConfig;

    /**
     * @var ProductMetadataInterface
     */
    protected $_metadata;

    /**
     * @var array
     */
    protected $commonNames = ['admin', 'root', 'test', 'magento'];

    /**
     * Checklist constructor.
     * @param ProductMetadataInterface $metadata
     * @param UserFactory $userFactory
     * @param Data $helper
     * @param DeploymentConfig $deploymentConfig
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ProductMetadataInterface $metadata,
        UserFactory $userFactory,
        Data $helper,
        DeploymentConfig $deploymentConfig,
        Context $context,
        array $data = [])
    {
        $this->_metadata         = $metadata;
        $this->_helper           = $helper;
        $this->_userFactory      = $userFactory;
        $this->_deploymentConfig = $deploymentConfig;

        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function checkAdminUserName()
    {
        $userCollection = $this->_userFactory->create()->getCollection();
        $unSecureNames  = [];
        foreach ($userCollection as $user) {
            if (in_array($user->getUserName(), $this->commonNames)) {
                $unSecureNames[] = [
                    'username' => $user->getUserName(),
                    'user_id'  => $user->getUserId()
                ];
            }
        }

        return $unSecureNames;
    }

    /**
     * @return mixed
     */
    public function checkFrontendCaptcha()
    {
        $customerCaptcha = $this->_helper->getConfigValue('customer/captcha/enable');

        return $customerCaptcha;
    }

    /**
     * @return mixed
     */
    public function checkBackendCaptcha()
    {

        $adminCaptcha = $this->_helper->getConfigValue('admin/captcha/enable');

        return $adminCaptcha;
    }

    /**
     * @return array
     */
    public function checkLatestVersion()
    {
        $releases   = file_get_contents('https://raw.githubusercontent.com/mageplaza/magento-versions/master/releases/releases.json');
        $arr        = json_decode($releases);
        $versionArr = [];
        foreach ($arr as $ver => $item) {
            list($major, $minor, $patch) = explode('.', $ver);
            if ($major == 2) {
                $versionArr[$major . '.' . $minor][] = $ver;
            }
        }

        $currentVersion = $this->_metadata->getVersion();
        list($currentMajor, $currentMinor, $currentPatch) = explode('.', $currentVersion);
        $latestVer = $currentVersion;
        foreach ($versionArr[$currentMajor . '.' . $currentMinor] as $version) {
            if (version_compare($latestVer, $version, '<')) {
                $latestVer = $version;
            }
        }

        return [
            'latestVer'      => $latestVer,
            'currentVersion' => $currentVersion
        ];
    }

    /**
     * @return string
     */
    public function getDatabasePrefix()
    {
        return (string)$this->_deploymentConfig->get(ConfigOptionsListConstants::CONFIG_PATH_DB_PREFIX);
    }

    /**
     * @return bool
     */
    public function hasProPackage()
    {
        return false;
    }

    /**
     * @param $unSecureName
     * @return string
     */
    public function getUserNameFixitUrl($unSecureName)
    {
        return '';
    }

    /**
     * @return string
     */
    public function getFrontendCaptchaFixitUrl()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getBackendCaptchaFixitUrl()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getVersionFixitUrl()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getDbFixitAdditionData()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getAdditionalJavascript()
    {
        return '';
    }
}