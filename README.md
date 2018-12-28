# MQ PHP HTTP SDK  
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
     "aliyunmq/mq-php-sdk": ">=1.0.0"
  }
}
```
Use Composer to install requires
```bash
composer install
``` 

## Samples

You must fulfill the AccessId/AccessKey/Endpoint in the example before running.   

```php
<?php

require "vendor/autoload.php";

use MQ\Model\TopicMessage;
use MQ\MQClient;

function getMillisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}

class Sample
{
    private $client;

    public function __construct($accessId, $accessKey, $endPoint)
    {
        $this->client = new MQClient($endPoint, $accessId, $accessKey);
    }

    public function run()
    {
        // get message producer
        // 使用实例则带上实例ID，否则为NULL，注意：默认实例不需要实例ID
        $producer = $this->client->getProducer(NULL, "abc");
        // get message consumer
        $consumer = $this->client->getConsumer(NULL, "abc", "CID-abc");

        while (True) {
            // publish one message to topic abc
            $topicMessage = $producer->publishMessage(
                new TopicMessage("xxxxxxxx")
            );

            print "\npublish finish -> " . $topicMessage->getMessageId() . " " . $topicMessage->getMessageBodyMD5() . "\n";

            try {
                $messages = $consumer->consumeMessage(4, 3);
            } catch (\Exception $e) {
                if ($e instanceof MQ\Exception\MessageNotExistException) {
                    // no new message;
                    // long polling again.
                    print "no new message\n";
                    continue;
                }

                print_r($e->getMessage() . "\n");

                sleep(3);
                continue;
            }

            print "consume finish, messages:\n";

            $receiptHandles = array();
            foreach ($messages as $message) {
                $receiptHandles[] = $message->getReceiptHandle();
                $latency = getMillisecond() - $message->getPublishTime();
                printf("ID:%s LAT:%d TAG:%s BODY:%s \nPublishTime:%d, FirstConsumeTime:%d, \nConsumedTimes:%d, NextConsumeTime:%d\n",
                    $message->getMessageId(), $latency, $message->getMessageTag(), $message->getMessageBody(),
                    $message->getPublishTime(), $message->getFirstConsumeTime(), $message->getConsumedTimes(), $message->getNextConsumeTime());
            }

            print_r($receiptHandles);

            print_r($consumer->ackMessage($receiptHandles));

            print "ack finish\n";

            sleep(3);

            print ".....................->>>>";
        }
    }
}

// Your Aliyun Access ID
$accessId = "";
// Your Aliyun Access Secret Key
$accessKey = "";
// Your Aliyun MQ Http Endpoint
$endPoint = "";

if (empty($accessId) || empty($accessKey) || empty($endPoint))
{
    echo "Must Provide AccessId/AccessKey/EndPoint to Run the Example. \n";
    return;
}


$instance = new Sample($accessId, $accessKey, $endPoint);
$instance->run();

?>

```
