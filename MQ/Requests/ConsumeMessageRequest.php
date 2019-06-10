<?php
namespace MQ\Requests;

class ConsumeMessageRequest extends BaseRequest
{
    private $topicName;
    private $consumer;
    private $messageTag;
    private $numOfMessages;
    private $waitSeconds;
    private $trans;

    public function __construct($instanceId, $topicName, $consumer, $numOfMessages, $messageTag = NULL, $waitSeconds = NULL)
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
        return NULL;
    }

    function setTrans($trans)
    {
        $this->trans = $trans;
    }

    public function generateQueryString()
    {
        $params = array("numOfMessages" => $this->numOfMessages);
        $params["consumer"] = $this->consumer;
        if ($this->instanceId != NULL && $this->instanceId != "")
        {
            $params["ns"] = $this->instanceId;
        }
        if ($this->waitSeconds != NULL)
        {
            $params["waitseconds"] = $this->waitSeconds;
        }
        if ($this->messageTag != NULL)
        {
            $params["tag"] = $this->messageTag;
        }
        if ($this->trans != NULL)
        {
            $params["trans"] = $this->trans;
        }
        return http_build_query($params);
    }
}
?>
