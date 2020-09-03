<?php

namespace MQ\Model;


use MQ\Constants;
use MQ\Traits\MessagePropertiesForPublish;

class TopicMessage
{

    use MessagePropertiesForPublish;

    public function __construct($messageBody)
    {
        $this->messageBody = $messageBody;
    }

    public function putProperty($key, $value)
    {
        if ($key === NULL || $value === NULL || $key === "" || $value === "")
        {
            return;
        }
        $this->properties[$key . ""] = $value . "";
    }

    /**
     * 设置消息KEY，如果没有设置，则消息的KEY为RequestId
     *
     * @param $key
     */
    public function setMessageKey($key)
    {
        $this->putProperty(Constants::MESSAGE_PROPERTIES_MSG_KEY, $key);
    }

    /**
     * 定时消息，单位毫秒（ms），在指定时间戳（当前时间之后）进行投递。
     * 如果被设置成当前时间戳之前的某个时刻，消息将立刻投递给消费者
     *
     * @param $timeInMillis
     */
    public function setStartDeliverTime($timeInMillis)
    {
        $this->putProperty(Constants::MESSAGE_PROPERTIES_TIMER_KEY, $timeInMillis);
    }

    /**
     * 在消息属性中添加第一次消息回查的最快时间，单位秒，并且表征这是一条事务消息
     * 范围: 10~300
     * @param $timeInSeconds
     */
    public function setTransCheckImmunityTime($timeInSeconds)
    {
        $this->putProperty(Constants::MESSAGE_PROPERTIES_TRANS_CHECK_KEY, $timeInSeconds);
    }

    /**
     * 分区顺序消息中区分不同分区的关键字段，sharding key 于普通消息的 key 是完全不同的概念。
     * 全局顺序消息，该字段可以设置为任意非空字符串。
     *
     * @param $shardingKey
     */
    public function setShardingKey($shardingKey)
    {
        $this->putProperty(Constants::MESSAGE_PROPERTIES_SHARDING, $shardingKey);
    }
}