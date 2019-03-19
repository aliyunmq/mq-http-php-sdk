<?php
namespace MQ\Requests;

class ConsumeMessageRequest extends BaseRequest
{
    private $topicName;
    private $consumer;
    private $messageTag;
    private $numOfMessages;
    private $waitSeconds;

    public function __construct($instanceId, $topicName, $consumer, $numOfMessages, $messageTag = null, $waitSeconds = null)
    {
        parent::__construct($instanceId, 'get', 'topics/' . $topicName . '/messages');

        $this->topicName = $topicName;
        $this->consumer = $consumer;
        $this->messageTag = $messageTag;
        $this->numOfMessages = $numOfMessages;
        $this->waitSeconds = $waitSeconds;
    }

    /**
     * @return mixed
     */
    public function getTopicName()
    {
        return $this->topicName;
    }

    /**
     * @return mixed
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * @return null
     */
    public function getMessageTag()
    {
        return $this->messageTag;
    }

    /**
     * @return mixed
     */
    public function getNumOfMessages()
    {
        return $this->numOfMessages;
    }

    /**
     * @return null
     */
    public function getWaitSeconds()
    {
        return $this->waitSeconds;
    }


    public function generateBody()
    {
        return null;
    }

    public function generateQueryString()
    {
        $params = array("numOfMessages" => $this->numOfMessages);
        $params["consumer"] = $this->consumer;
        if ($this->instanceId != null && $this->instanceId != "") {
            $params["ns"] = $this->instanceId;
        }
        if ($this->waitSeconds != null) {
            $params["waitseconds"] = $this->waitSeconds;
        }
        if ($this->messageTag != null) {
            $params["tag"] = $this->messageTag;
        }
        return http_build_query($params);
    }
}
