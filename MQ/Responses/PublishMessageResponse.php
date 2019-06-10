<?php
namespace MQ\Responses;

use MQ\Common\XMLParser;
use MQ\Constants;
use MQ\Exception\InvalidArgumentException;
use MQ\Exception\MalformedXMLException;
use MQ\Exception\MQException;
use MQ\Exception\TopicNotExistException;
use MQ\Model\Message;
use MQ\Model\TopicMessage;

class PublishMessageResponse extends BaseResponse
{
    public function __construct()
    {
    }

    public function parseResponse($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        if ($statusCode == 201) {
            $this->succeed = TRUE;
        } else {
            $this->parseErrorResponse($statusCode, $content);
        }

        $xmlReader = $this->loadXmlContent($content);
        try {
            return $this->readMessageIdAndMD5XML($xmlReader);
        } catch (\Exception $e) {
            throw new MQException($statusCode, $e->getMessage(), $e);
        } catch (\Throwable $t) {
            throw new MQException($statusCode, $t->getMessage());
        }

    }

    public function readMessageIdAndMD5XML(\XMLReader $xmlReader)
    {
        $message = Message::fromXML($xmlReader, TRUE);
        $topicMessage = new TopicMessage(NULL);
        $topicMessage->setMessageId($message->getMessageId());
        $topicMessage->setMessageBodyMD5($message->getMessageBodyMD5());
        $topicMessage->setReceiptHandle($message->getReceiptHandle());

        return $topicMessage;
    }

    public function parseErrorResponse($statusCode, $content, MQException $exception = NULL)
    {
        $this->succeed = FALSE;
        $xmlReader = $this->loadXmlContent($content);
        try {
            $result = XMLParser::parseNormalError($xmlReader);
            if ($result['Code'] == Constants::TOPIC_NOT_EXIST)
            {
                throw new TopicNotExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::INVALID_ARGUMENT)
            {
                throw new InvalidArgumentException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::MALFORMED_XML)
            {
                throw new MalformedXMLException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            throw new MQException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
        } catch (\Exception $e) {
            if ($exception != NULL) {
                throw $exception;
            } elseif($e instanceof MQException) {
                throw $e;
            } else {
                throw new MQException($statusCode, $e->getMessage());
            }
        } catch (\Throwable $t) {
            throw new MQException($statusCode, $t->getMessage());
        }
    }
}

?>
