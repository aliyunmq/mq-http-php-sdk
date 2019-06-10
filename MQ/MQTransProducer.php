<?php
namespace MQ;

use MQ\Exception\InvalidArgumentException;
use MQ\Http\HttpClient;
use MQ\Requests\ConsumeMessageRequest;
use MQ\Requests\AckMessageRequest;
use MQ\Responses\AckMessageResponse;
use MQ\Responses\ConsumeMessageResponse;

class MQTransProducer extends MQProducer
{
    private $groupId;

    function __construct(HttpClient $client, $instanceId = NULL, $topicName, $groupId)
    {
        if (empty($groupId)) {
            throw new InvalidArgumentException(400, "GroupId is null");
        }
        parent::__construct($client, $instanceId, $topicName);
        $this->groupId = $groupId;
    }

    /**
     * consume transaction half message
     *
     * @param $numOfMessages: consume how many messages once, 1~16
     * @param $waitSeconds: if > 0, means the time(second) the request holden at server if there is no message to consume.
     *                      If <= 0, means the server will response back if there is no message to consume.
     *                      It's value should be 1~30
     *
     * @return Message
     *
     * @throws TopicNotExistException if queue does not exist
     * @throws MessageNotExistException if no message exists
     * @throws InvalidArgumentException if the argument is invalid
     * @throws MQException if any other exception happends
     */
    public function consumeHalfMessage($numOfMessages, $waitSeconds = -1)
    {
        if ($numOfMessages < 0 || $numOfMessages > 16) {
            throw new InvalidArgumentException(400, "numOfMessages should be 1~16");
        }
        if ($waitSeconds > 30) {
            throw new InvalidArgumentException(400, "numOfMessages should less then 30");
        }
        $request = new ConsumeMessageRequest($this->instanceId, $this->topicName, $this->groupId, $numOfMessages, $this->messageTag, $waitSeconds);
        $request->setTrans(Constants::TRANSACTION_POP);
        $response = new ConsumeMessageResponse();
        return $this->client->sendRequest($request, $response);
    }

    /**
     * commit transaction message
     *
     * @param $receiptHandle:
     *            $receiptHandle, which is got from consumeHalfMessage or publishMessage
     *
     * @return AckMessageResponse
     *
     * @throws TopicNotExistException if queue does not exist
     * @throws ReceiptHandleErrorException if the receiptHandle is invalid
     * @throws InvalidArgumentException if the argument is invalid
     * @throws AckMessageException if any message not deleted
     * @throws MQException if any other exception happends
     */
    public function commit($receiptHandle)
    {
        $request = new AckMessageRequest($this->instanceId, $this->topicName, $this->groupId, array($receiptHandle));
        $request->setTrans(Constants::TRANSACTION_COMMIT);
        $response = new AckMessageResponse();
        return $this->client->sendRequest($request, $response);
    }


    /**
     * rollback transaction message
     *
     * @param $receiptHandle:
     *            $receiptHandle, which is got from consumeHalfMessage or publishMessage
     *
     * @return AckMessageResponse
     *
     * @throws TopicNotExistException if queue does not exist
     * @throws ReceiptHandleErrorException if the receiptHandle is invalid
     * @throws InvalidArgumentException if the argument is invalid
     * @throws AckMessageException if any message not deleted
     * @throws MQException if any other exception happends
     */
    public function rollback($receiptHandle)
    {
        $request = new AckMessageRequest($this->instanceId, $this->topicName, $this->groupId, array($receiptHandle));
        $request->setTrans(Constants::TRANSACTION_ROLLBACK);
        $response = new AckMessageResponse();
        return $this->client->sendRequest($request, $response);
    }
}

?>
