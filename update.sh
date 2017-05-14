#!/bin/bash

git pull

rm -rf magento-2-blog/
git clone https://github.com/mageplaza/magento-2-blog
rm -rf magento-2-blog/.git

rm -rf magento-2-social-login/
git clone https://github.com/mageplaza/magento-2-social-login
rm -rf magento-2-social-login/.git

rm -rf magento-2-seo/
git clone https://github.com/mageplaza/magento-2-seo
rm -rf magento-2-seo/.git

rm -rf magento-2-product-slider/
git clone https://github.com/mageplaza/magento-2-product-slider
rm -rf magento-2-product-slider/.git

rm -rf magento-2-google-analytics/
git clone https://github.com/mageplaza/magento-2-google-analytics
rm -rf magento-2-google-analytics/.git

rm -rf magento-2-banner-slider/
git clone https://github.com/mageplaza/magento-2-banner-slider
rm -rf magento-2-banner-slider/.git


git add . && git commit -m "Update" && git push
