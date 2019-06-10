<?php
namespace MQ;

use MQ\Exception\InvalidArgumentException;
use MQ\Http\HttpClient;
use MQ\Model\TopicMessage;
use MQ\Requests\PublishMessageRequest;
use MQ\Responses\PublishMessageResponse;

class MQProducer
{
    protected $instanceId;
    protected $topicName;
    protected $client;

    function __construct(HttpClient $client, $instanceId = NULL, $topicName)
    {
        if (empty($topicName)) {
            throw new InvalidArgumentException(400, "TopicName is null");
        }
        $this->instanceId = $instanceId;
        $this->client = $client;
        $this->topicName = $topicName;
    }

    public function getInstanceId() {
        return $this->instanceId;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function publishMessage(TopicMessage $topicMessage)
    {

        $request = new PublishMessageRequest(
            $this->instanceId, $this->topicName, $topicMessage->getMessageBody(),
            $topicMessage->getProperties(), $topicMessage->getMessageTag()
        );
        $response = new PublishMessageResponse();
        return $this->client->sendRequest($request, $response);
    }
}

?>
