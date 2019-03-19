<?php

namespace MQ;

use MQ\Exception\AckMessageException;
use MQ\Exception\InvalidArgumentException;
use MQ\Exception\MessageNotExistException;
use MQ\Exception\MQException;
use MQ\Exception\ReceiptHandleErrorException;
use MQ\Exception\TopicNotExistException;
use MQ\Http\HttpClient;
use MQ\Model\Message;
use MQ\Requests\AckMessageRequest;
use MQ\Requests\ConsumeMessageRequest;
use MQ\Responses\AckMessageResponse;
use MQ\Responses\ConsumeMessageResponse;

class MQConsumer
{
    private $instanceId;
    private $topicName;
    private $consumer;
    private $messageTag;
    private $client;


    public function __construct(HttpClient $client, $instanceId, $topicName, $consumer, $messageTag = null)
    {
        if (empty($topicName)) {
            throw new InvalidArgumentException(400, "TopicName is null");
        }
        if (empty($consumer)) {
            throw new InvalidArgumentException(400, "TopicName is null");
        }

        $this->instanceId = $instanceId;
        $this->topicName = $topicName;
        $this->consumer = $consumer;
        $this->messageTag = $messageTag;
        $this->client = $client;
    }

    public function getInstanceId()
    {
        return $this->instanceId;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function getConsumer()
    {
        return $this->consumer;
    }

    public function getMessageTag()
    {
        return $this->messageTag;
    }


    /**
     * consume message
     *
     * @param int $numOfMessages consume how many messages once, 1~16
     * @param int $waitSeconds   if > 0, means the time(second) the request holden at server if there is no message to
     *                           consume. If <= 0, means the server will response back if there is no message to
     *                           consume. It's value should be 1~30
     *
     * @return Message
     *
     * @throws TopicNotExistException if queue does not exist
     * @throws MessageNotExistException if no message exists
     * @throws InvalidArgumentException if the argument is invalid
     * @throws MQException if any other exception happends
     */
    public function consumeMessage($numOfMessages, $waitSeconds = -1)
    {
        if ($numOfMessages < 0 || $numOfMessages > 16) {
            throw new InvalidArgumentException(400, "numOfMessages should be 1~16");
        }
        if ($waitSeconds > 30) {
            throw new InvalidArgumentException(400, "numOfMessages should less then 30");
        }
        $request = new ConsumeMessageRequest(
            $this->instanceId,
            $this->topicName,
            $this->consumer,
            $numOfMessages,
            $this->messageTag,
            $waitSeconds
        );
        $response = new ConsumeMessageResponse();
        return $this->client->sendRequest($request, $response);
    }

    /**
     * ack message
     *
     * @param array $receiptHandles array of $receiptHandle, which is got from consumeMessage
     *
     * @return AckMessageResponse
     *
     * @throws TopicNotExistException if queue does not exist
     * @throws ReceiptHandleErrorException if the receiptHandle is invalid
     * @throws InvalidArgumentException if the argument is invalid
     * @throws AckMessageException if any message not deleted
     * @throws MQException if any other exception happends
     */
    public function ackMessage($receiptHandles)
    {
        $request = new AckMessageRequest($this->instanceId, $this->topicName, $this->consumer, $receiptHandles);
        $response = new AckMessageResponse();
        return $this->client->sendRequest($request, $response);
    }
}
