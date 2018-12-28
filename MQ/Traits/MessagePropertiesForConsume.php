<?php
namespace MQ\Traits;

use MQ\Model\Message;
use MQ\Traits\MessagePropertiesForPeek;

trait MessagePropertiesForConsume
{
    use MessagePropertiesForPublish;

    protected $receiptHandle;
    protected $publishTime;
    protected $nextConsumeTime;
    protected $firstConsumeTime;
    protected $consumedTimes;

    /**
     * @return mixed
     */
    public function getReceiptHandle()
    {
        return $this->receiptHandle;
    }

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


    public function readMessagePropertiesForConsumeXML(\XMLReader $xmlReader)
    {
        $message = Message::fromXML($xmlReader);
        $this->messageId = $message->getMessageId();
        $this->messageBodyMD5 = $message->getMessageBodyMD5();
        $this->messageBody = $message->getMessageBody();
        $this->publishTime = $message->getPublishTime();
        $this->nextConsumeTime = $message->getNextConsumeTime();
        $this->firstConsumeTime = $message->getFirstConsumeTime();
        $this->consumedTimes = $message->getConsumedTimes();
    }
}

?>
