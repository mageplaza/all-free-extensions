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

namespace Mageplaza\Security\Helper;

use Mageplaza\Core\Helper\AbstractData;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;
use Sinergi\BrowserDetector\UserAgent;

/**
 * Class Data
 * @package Mageplaza\Security\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH        = 'security';
    const XML_PATH_BRUTE_FORCE      = 'brute_force';
    const XML_PATH_BLACK_WHITE_LIST = 'black_white_list';

    /**
     * @var \Sinergi\BrowserDetector\Browser
     */
    protected $browserLib;

    /**
     * @var \Sinergi\BrowserDetector\Os
     */
    protected $osLib;

    /**
     * Get Brute Force Config
     *
     * @param string $code
     * @param null $storeId
     * @return mixed
     */
    public function getConfigBruteForce($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getConfigValue(self::CONFIG_MODULE_PATH . '/' . self::XML_PATH_BRUTE_FORCE . $code, $storeId);
    }

    /**
     * Get Black-White List Config
     *
     * @param string $code
     * @param null $storeId
     * @return mixed
     */
    public function getConfigBlackWhiteList($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getConfigValue(self::CONFIG_MODULE_PATH . '/' . self::XML_PATH_BLACK_WHITE_LIST . $code, $storeId);
    }

    /**
     * Check Ip
     *
     * @param $ip
     * @param $range
     * @return bool
     */
    public function checkIp($ip, $range)
    {
        if (strpos($range, '*') !== false) {
            $low = $high = $range;
            if (strpos($range, '-') !== false) {
                list($low, $high) = explode('-', $range, 2);
            }
            $low   = str_replace('*', '0', $low);
            $high  = str_replace('*', '255', $high);
            $range = $low . '-' . $high;
        }
        if (strpos($range, '-') !== false) {
            list($low, $high) = explode('-', $range, 2);

            return $this->ipCompare($ip, $low, 1) && $this->ipcompare($ip, $high, -1);
        }

        return $this->ipCompare($ip, $range);
    }

    /**
     * @param $ip1
     * @param $ip2
     * @param int $op
     * @return bool
     */
    private function ipCompare($ip1, $ip2, $op = 0)
    {
        $ip1Arr = explode('.', $ip1);
        $ip2Arr = explode('.', $ip2);

        for ($i = 0; $i < 4; $i++) {
            if ($ip1Arr[$i] < $ip2Arr[$i]) {
                return ($op == -1);
            }
            if ($ip1Arr[$i] > $ip2Arr[$i]) {
                return ($op == 1);
            }
        }

        return ($op == 0);
    }

    /***
     * @param $userAgent
     * @param null $full
     * @return array|string
     */
    public function getBrowser($userAgent, $full = null)
    {
        $userAgent = new UserAgent($userAgent);
        $os        = new Os($userAgent);
        $browser   = new Browser($userAgent);

        if ($full) {
            return [
                'browser'           => $browser->getName(),
                'browser_version'   => $browser->getVersion(),
                'plat_form'         => $os->getName(),
                'plat_form_version' => $os->getVersion()
            ];
        }

        return $browser->getName();
    }
}
