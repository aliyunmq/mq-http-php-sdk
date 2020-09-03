<?php
namespace MQ\Traits;

use MQ\Constants;

trait MessagePropertiesForConsume
{
    use MessagePropertiesForPublish;

    protected $publishTime;
    protected $nextConsumeTime;
    protected $firstConsumeTime;
    protected $consumedTimes;

    /**
     * @return mixed
     */
    public function getMessageBody()
    {
        return $this->messageBody;
    }

    /**
     * @return mixed
     */
    public function getPublishTime()
    {
        return $this->publishTime;
    }

    /**
     * @return mixed
     */
    public function getNextConsumeTime()
    {
        return $this->nextConsumeTime;
    }

    /**
     * 对于顺序消费没有意义
     *
     * @return mixed
     */
    public function getFirstConsumeTime()
    {
        return $this->firstConsumeTime;
    }

    /**
     * @return mixed
     */
    public function getConsumedTimes()
    {
        return $this->consumedTimes;
    }

    public function getProperty($key)
    {
        if ($this->properties == NULL)
        {
            return NULL;
        }
        return $this->properties[$key];
    }

    /**
     * 消息KEY
     */
    public function getMessageKey()
    {
        return $this->getProperty(Constants::MESSAGE_PROPERTIES_MSG_KEY);
    }

    /**
     * 定时消息时间戳，单位毫秒（ms
     */
    public function getStartDeliverTime()
    {
        $temp = $this->getProperty(Constants::MESSAGE_PROPERTIES_TIMER_KEY);
        if ($temp === NULL)
        {
            return 0;
        }
        return (int)$temp;
    }

    /**
     * 事务消息第一次消息回查的最快时间，单位秒
     */
    public function getTransCheckImmunityTime()
    {
        $temp = $this->getProperty(Constants::MESSAGE_PROPERTIES_TRANS_CHECK_KEY);
        if ($temp === NULL)
        {
            return 0;
        }
        return (int)$temp;
    }

    /**
     * 顺序消息分区KEY
     */
    public function getShardingKey()
    {
        return $this->getProperty(Constants::MESSAGE_PROPERTIES_SHARDING);
    }
}

?>
