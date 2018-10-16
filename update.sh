#!/bin/bash

git pull


rm -rf magento-2-security
git clone https://github.com/mageplaza/magento-2-security

rm -rf magento-2-reports
git clone https://github.com/mageplaza/magento-2-reports

rm -rf magento-2-blog
git clone https://github.com/mageplaza/magento-2-blog

rm -rf magento-2-multi-flat-rates
git clone https://github.com/mageplaza/magento-2-multi-flat-rates

rm -rf magento-2-ajax-layered-navigation
git clone https://github.com/mageplaza/magento-2-ajax-layered-navigation

rm -rf magento-2-social-login
git clone https://github.com/mageplaza/magento-2-social-login

rm -rf magento-2-google-recaptcha
git clone https://github.com/mageplaza/magento-2-google-recaptcha

rm -rf magento-2-better-popup
git clone https://github.com/mageplaza/magento-2-better-popup

rm -rf magento-2-smtp
git clone https://github.com/mageplaza/magento-2-smtp

rm -rf magento-2-google-xml-sitemap
git clone https://github.com/mageplaza/magento-2-google-xml-sitemap

rm -rf magento-2-delete-orders
git clone https://github.com/mageplaza/magento-2-delete-orders

rm -rf magento-2-banner-slider
git clone https://github.com/mageplaza/magento-2-banner-slider

rm -rf magento-2-product-slider
git clone https://github.com/mageplaza/magento-2-product-slider

rm -rf magento-2-seo
git clone https://github.com/mageplaza/magento-2-seo

rm -rf magento-2-social-login
git clone https://github.com/mageplaza/magento-2-social-login

rm -rf magento-2-blog
git clone https://github.com/mageplaza/magento-2-blog



git add . && git commit -m "Update" && git push
