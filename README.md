# MQ HTTP PHP SDK  
Alyun MQ Documents: http://www.aliyun.com/product/ons

Aliyun MQ Console: https://ons.console.aliyun.com  

## Intall Composer

To install composer by following commands, or see [composer](https://docs.phpcomposer.com/00-intro.html)
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```
## Install & Use

Add require to your `composer.json`
```json
{
  "require": {
     "aliyunmq/mq-http-sdk": ">=1.0.0"
  }
}
```
Use Composer to install requires
```bash
composer install
``` 

## Samples

[Publish Message](https://github.com/aliyunmq/mq-http-samples/blob/master/php/Producer.php)

[Consume Message](https://github.com/aliyunmq/mq-http-samples/blob/master/php/Consumer.php)
