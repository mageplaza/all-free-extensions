# Magento 2 Multi Flat Rates extension

Native Magento 2 only supports one shipping flat rate which can lead to difficulty for stores in defining the best price for delivering products to customers in different countries.  

**Multiple flat rate shipping** is a must have for many online stores, especially international brands which deliver their products worldwide. Offering buyers different flat rates to select makes shopping easier and convenient for customers. Besides, this also helps stores manage shipping methods better.

## 1. Documentation

- [Installation guide](https://www.mageplaza.com/install-magento-2-extension/)
- [User guide](https://docs.mageplaza.com/multi-flat-rates/index.html)
- [Contribute on Github](https://github.com/mageplaza/magento-2-multi-flat-rates/)
- [Get Support](https://github.com/mageplaza/magento-2-multi-flat-rates/issues)

## 2. FAQs

- **Q: I got an error: Mageplaza_Core has been already defined**

- A: Read solution: https://github.com/mageplaza/module-core/issues/3

## 3. How to install Multi Flat Rates extension for Magento 2

### Install via composer (recommend)

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-multi-flat-rates
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
## 4. Highlight features

### 5 multiple flat rate shipping

**Mageplaza Multi flat rate shipping extension** allows store admins to add up to 5 other shipping rates. Admins are free to give a shipping flat rate a name and/or a title to help buyers distinguish it with others on Cart page and Checkout page. 

Each flat rate can be turned on/off easily on the backend. A set of configuration options for price, sort order, applicable countries, and error message are also ready to be selected.

### Sort multi flat rates neatly

As a store may want to have more than only one flat rate shipping, multiple rates should be in a certain order. Admins can choose this order by sorting every shipping rate on the backend. 

### Multi flat rate visibility

Each flat rate shipping can be applied on a restricted number of countries or all allowed countries depending on the settings on backend. 

Admins can multiple select countries to allow buyers in those countries to choose a specific shipping flat rate.

### Error message

Buyers who are not in applicable countries will not be allowed to select that shipping flat rate. In this case, admins can set an error message to inform buyers. This message can be enabled/disabled and changed easily on the backend.

### Compatible with One Step Checkout

Mageplaza Multi Flat Rates extension is amazingly compatible with [One Step Checkout for Magento 2](https://www.mageplaza.com/magento-2-one-step-checkout-extension/) which delivers the best shopping experience for your customers. 


## 5. How to configure multiple flat rate shipping on Magento 2

From your Magento admin panel, follow this route: `Stores > Settings > Configuration > Sales > Shipping methods`. Then, click on `Flat rate #1 - Flat rate #5` to configure these 5 shipping rates.

![multi flat rate](https://i.imgur.com/yiEzfE6.png)

### Configure flat rate #1

![multi flat rate 2](https://i.imgur.com/s3kpRye.png)

- **Enable**: Select Yes to run Flat Rate #1
- **Method Name**: This is the name the flat rate #1 that is displayed on the View cart page and the Checkout page. If you leave this field blank, the rate will have no name on these two pages.
- **Price**: This is the shipping fee of Flat Rate #1 which is displayed on the frontend. If you leave this field blank, the price will be automatically set $0.00 as default.
- **Sort Order**: This is the order of Flat Rate #1 on the flat rates list displayed on the frontend. For example: If Sort Order = 1 is set for Flat Rate #1 while Sort Order = 2 is set for Flat Rate #2, Flat Rate #1 will stand above Flat Rate #2 on the frontend.

![multi flat rate 3](https://i.imgur.com/sgrBrOz.png)


- **Title**: This is the title of the flat rate which is displayed on the Cart page and Checkout page. If you leave this field blank, no title is displayed.

![multi flat rate 4](https://i.imgur.com/bIfptAy.png)


- `Ship to Applicable Countries`: 
  - Select All Allowed Countries to apply the rate on all available countries.
  - Select Specific Countries to apply the rate on the countries which are selected on the field Ship to Specific Countries.

![multi flat rate 5](https://i.imgur.com/GWOQSeW.png)

- `Ship to Specific Countries`: Select one or several countries which the rate is applied for.
- `Display Error Message`: Enter an error message which is displayed to buyers when the rate is not available. If you leave this box blank, the default message will be displayed: `Sorry, but we can’t deliver to the destination country with this shipping module`. This error message is displayed only when `Show Method if Not Applicable` = Yes
- `Show Method if Not Applicable`: Select Yes to display all shipping methods even when they do not belong to any applicable countries.

Similarly, admins can add up to 5 shipping methods and configure them for their online store.

![multi flat rate 6](https://i.imgur.com/JvvkKHF.png)

![multi flat rate 7](https://i.imgur.com/bniI7zg.png)









