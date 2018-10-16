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

use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Error\Processor;
use Magento\Framework\View\Element\Template\File\Resolver;

require_once BP . '/pub/errors/processor.php';

/**
 * Class ErrorProcessor
 * @package Mageplaza\Security\Helper
 */
class ErrorProcessor extends Processor
{
    /**
     * @var Resolver
     */
    protected $_resolver;

    /**
     * @var string
     */
    protected $errorCode;

    /**
     * ErrorProcessor constructor.
     * @param Http $response
     * @param Resolver $resolver
     */
    public function __construct(
        Http $response,
        Resolver $resolver
    )
    {
        parent::__construct($response);

        $this->_resolver = $resolver;
    }

    /**
     * Process security report
     *
     * @param string $errorCode
     * @param string $reportData
     * @param string $title
     * @return null
     */
    public function processSecurityReport($errorCode = '', $reportData = '', $title = '')
    {
        $this->pageTitle  = $title ?: __('You don\'t have permission to access this page');
        $this->reportData = $reportData;
        $this->errorCode  = $errorCode;

        $html            = '';
        $baseTemplate    = $this->_getTemplatePath('page.phtml');
        $contentTemplate = $this->_resolver->getTemplateFileName('report.phtml', ['module' => 'Mageplaza_Security', 'area' => FrontNameResolver::AREA_CODE]);
        if ($baseTemplate && $contentTemplate) {
            ob_start();
            require_once $baseTemplate;
            $html = ob_get_clean();
        }

        $this->_response->setHttpResponseCode(401);
        $this->_response->setBody($html);
        $this->_response->sendResponse();

        return null;
    }
}
