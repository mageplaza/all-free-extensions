#!/bin/bash

git pull

git clone https://github.com/mageplaza/magento-2-blog
rm -rf magento-2-blog/.git

git clone https://github.com/mageplaza/magento-2-social-login
rm -rf magento-2-social-login/.git

git clone https://github.com/mageplaza/magento-2-seo
rm -rf magento-2-seo/.git

git clone https://github.com/mageplaza/magento-2-product-slider
rm -rf magento-2-product-slider/.git

git clone https://github.com/mageplaza/magento-2-google-analytics
rm -rf magento-2-google-analytics/.git

git clone https://github.com/mageplaza/magento-2-banner-slider
rm -rf magento-2-banner-slider/.git



git add . && git commit -m "Update" && git push
