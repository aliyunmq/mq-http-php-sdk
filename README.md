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

*Note: php version>=5.5.0, and xml extension of php is required.*

## Samples

### V1.0.0 Samples
[Publish Message](https://github.com/aliyunmq/mq-http-samples/blob/master/php/Producer.php)

[Consume Message](https://github.com/aliyunmq/mq-http-samples/blob/master/php/Consumer.php)

### V1.0.1 Samples
[Publish Message](https://github.com/aliyunmq/mq-http-samples/tree/101-dev/php/Producer.php)

[Consume Message](https://github.com/aliyunmq/mq-http-samples/tree/101-dev/php/Consumer.php)

[Transaction Message](https://github.com/aliyunmq/mq-http-samples/tree/101-dev/php/TransProducer.php)

Note for 1.0.1: Http consumer only support timer msg(less than 3 days), no matter the msg is produced from http or tcp protocal.
