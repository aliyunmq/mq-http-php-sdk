<?php

namespace MQ;

use MQ\Exception\InvalidArgumentException;
use MQ\Http\HttpClient;

class MQClient
{
    private $client;

    /**
     * @param string $endPoint      the host url
     *                              could be "http://$accountId.mqrest.cn-hangzhou.aliyuncs.com"
     *                              accountId could be found in aliyun.com
     * @param string $accessId      accessId from aliyun.com
     * @param string $accessKey     accessKey from aliyun.com
     * @param string $securityToken securityToken from aliyun.com
     * @param Config $config        necessary configs
     */
    public function __construct(
        $endPoint,
        $accessId,
        $accessKey,
        $securityToken = null,
        Config $config = null
    ) {
        if (empty($endPoint)) {
            throw new InvalidArgumentException(400, "Invalid endpoint");
        }
        if (empty($accessId)) {
            throw new InvalidArgumentException(400, "Invalid accessId");
        }
        if (empty($accessKey)) {
            throw new InvalidArgumentException(400, "Invalid accessKey");
        }
        $this->client = new HttpClient(
            $endPoint,
            $accessId,
            $accessKey,
            $securityToken,
            $config
        );
    }


    /**
     * Returns a Producer reference for publish message to topic
     *
     * @param string $instanceId instance id
     * @param string $topicName  the topic name
     *
     * @return MQProducer $topic the Producer instance
     */
    public function getProducer($instanceId, $topicName)
    {
        if ($topicName == null || $topicName == "") {
            throw new InvalidArgumentException(400, "TopicName is null or empty");
        }
        return new MQProducer($this->client, $instanceId, $topicName);
    }

    /**
     * Returns a Consumer reference for consume and ack message to topic
     *
     * @param string $instanceId    instance id
     * @param string $topicName     the topic name
     * @param string $consumer      the consumer name / ons cid
     * @param string $messageTag    filter tag for consumer.
     *                              If not empty, only consume the message which's messageTag is equal to it.
     *
     * @return MQConsumer $topic: the Producer instance
     */
    public function getConsumer($instanceId, $topicName, $consumer, $messageTag = null)
    {
        if ($topicName === null || $topicName === "") {
            throw new InvalidArgumentException(400, "TopicName is null or empty");
        }
        if ($consumer === null || $consumer === "") {
            throw new InvalidArgumentException(400, "Consumer is null or empty");
        }
        return new MQConsumer($this->client, $instanceId, $topicName, $consumer, $messageTag);
    }
}
