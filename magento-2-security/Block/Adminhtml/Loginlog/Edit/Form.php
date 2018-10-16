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

namespace Mageplaza\Security\Block\Adminhtml\Loginlog\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Mageplaza\Security\Helper\Data;

/**
 * Class Form
 * @package Mageplaza\Security\Block\Adminhtml\Loginlog\Edit
 */
class Form extends Generic
{
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * Form constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Data $helper,
        array $data = [])
    {
        $this->_helper = $helper;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
                'data' => [
                    'id'      => 'edit_form',
                    'action'  => $this->getData('action'),
                    'method'  => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );
        $log  = $this->_coreRegistry->registry('mageplaza_security_loginlog');

        /** @var \Magento\Framework\Data\Form $form */
        $form->setHtmlIdPrefix('log_');
        $form->setFieldNameSuffix('log');

        $fieldset = $form->addFieldset('base_fieldset', [
                'legend' => __('Login information'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addField('id', 'label', [
            'name'  => 'id',
            'label' => __('ID'),
        ]);
        $fieldset->addField('time', 'label', [
                'name'  => 'time',
                'label' => __('Time'),
                'title' => __('Time'),
            ]
        );
        $fieldset->addField('user_name', 'label', [
                'name'  => 'user_name',
                'label' => __('User Name'),
                'title' => __('User Name'),
            ]
        );
        $fieldset->addField('ip', 'label', [
                'name'  => 'ip',
                'label' => __('IP'),
                'title' => __('IP'),
            ]
        );
        $fieldset->addField('url', 'label', [
                'name'  => 'url',
                'label' => __('URL'),
                'title' => __('URL'),
            ]
        );
        $fieldset->addField('referer', 'label', [
                'name'  => 'referer',
                'label' => __('Referer URL'),
                'title' => __('Referer URL'),
            ]
        );
        $fieldset->addField('stt', 'label', [
                'label' => __('Status'),
                'title' => __('Status'),
                'value' => $log->getStatus() ? __('Success') : __('Failed')
            ]
        );

        $browserFieldset = $form->addFieldset('browser_fieldset', [
                'legend' => __('Browser Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        $userAgent       = $log->getBrowserAgent();
        $userAgent       = explode('--', $userAgent);
        $userAgent       = $userAgent[1];
        $browser         = $this->_helper->getBrowser($userAgent, 1);

        $browserFieldset->addField('browser', 'label', [
            'label' => __('Brower'),
            'title' => __('Brower'),
            'value' => $browser['browser']
        ]);
        $browserFieldset->addField('browser_version', 'label', [
            'label' => __('Brower Version'),
            'title' => __('Brower Version'),
            'value' => $browser['browser_version']
        ]);
        $browserFieldset->addField('plat_form', 'label', [
            'label' => __('Platform'),
            'title' => __('Platform'),
            'value' => $browser['plat_form']
        ]);
        $browserFieldset->addField('plat_form_version', 'label', [
            'label' => __('Platform Version'),
            'title' => __('Platform Version'),
            'value' => $browser['plat_form_version']
        ]);

        $form->addValues($log->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
