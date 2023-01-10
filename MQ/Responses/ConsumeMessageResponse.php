<?php
namespace MQ\Responses;

use MQ\Common\XMLParser;
use MQ\Constants;
use MQ\Exception\MessageNotExistException;
use MQ\Exception\MessageResolveException;
use MQ\Exception\MQException;
use MQ\Exception\TopicNotExistException;
use MQ\Model\Message;

class ConsumeMessageResponse extends BaseResponse
{
    protected $messages;

    public function __construct()
    {
        $this->messages = array();
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function parseResponse($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        if ($statusCode == 200) {
            $this->succeed = TRUE;
        } else {
            $this->parseErrorResponse($statusCode, $content);
        }

        try {
            if ($this->loadAndValidateXmlContent($content, $xmlReader)) {
                while ($xmlReader->read()) {
                    if ($xmlReader->nodeType == \XMLReader::ELEMENT
                        && $xmlReader->name == 'Message') {
                        $this->messages[] = Message::fromXML($xmlReader);
                    }
                }
                return $this->messages;
            }
            throw new MessageResolveException($statusCode, 'Some messages cannot be resolved', MessagePartialResolver::resolve($content));
        } catch (MessageResolveException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new MQException($statusCode, $e->getMessage(), $e);
        } catch (\Throwable $t) {
            throw new MQException($statusCode, $t->getMessage(), $t);
        }
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
            if ($result['Code'] == Constants::MESSAGE_NOT_EXIST)
            {
                throw new MessageNotExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
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
