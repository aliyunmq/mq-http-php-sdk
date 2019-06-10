<?php
namespace MQ\Model;

use MQ\Constants;
use MQ\Traits\MessagePropertiesForConsume;

class Message
{
    use MessagePropertiesForConsume;

    public function __construct($messageId, $messageBodyMD5, $messageBody, $publishTime, $nextConsumeTime,
                                $firstConsumeTime, $consumedTimes, $receiptHandle, $messageTag, $properties)
    {
        $this->messageId = $messageId;
        $this->messageBodyMD5 = $messageBodyMD5;
        $this->messageBody = $messageBody;
        $this->publishTime = $publishTime;
        $this->nextConsumeTime = $nextConsumeTime;
        $this->firstConsumeTime = $firstConsumeTime;
        $this->consumedTimes = $consumedTimes;
        $this->receiptHandle = $receiptHandle;
        $this->messageTag = $messageTag;
        $this->properties = $properties;
    }

    static public function fromXML(\XMLReader $xmlReader)
    {
        $messageId = NULL;
        $messageBodyMD5 = NULL;
        $messageBody = NULL;
        $publishTime = NULL;
        $nextConsumeTime = NULL;
        $firstConsumeTime = NULL;
        $consumedTimes = NULL;
        $receiptHandle = NULL;
        $messageTag = NULL;
        $properties = NULL;

        while ($xmlReader->read())
        {
            switch ($xmlReader->nodeType)
            {
            case \XMLReader::ELEMENT:
                switch ($xmlReader->name) {
                case Constants::MESSAGE_ID:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $messageId = $xmlReader->value;
                    }
                    break;
                case Constants::MESSAGE_BODY_MD5:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $messageBodyMD5 = $xmlReader->value;
                    }
                    break;
                case Constants::MESSAGE_BODY:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $messageBody = $xmlReader->value;
                    }
                    break;
                case Constants::PUBLISH_TIME:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $publishTime = $xmlReader->value;
                    }
                    break;
                case Constants::NEXT_CONSUME_TIME:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $nextConsumeTime = $xmlReader->value;
                    }
                    break;
                case Constants::FIRST_CONSUME_TIME:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $firstConsumeTime = $xmlReader->value;
                    }
                    break;
                case Constants::CONSUMED_TIMES:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $consumedTimes = $xmlReader->value;
                    }
                    break;
                case Constants::RECEIPT_HANDLE:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $receiptHandle = $xmlReader->value;
                    }
                    break;
                case Constants::MESSAGE_TAG:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $messageTag = $xmlReader->value;
                    }
                    break;
                case Constants::MESSAGE_PROPERTIES:
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT)
                    {
                        $propertiesString = $xmlReader->value;
                        if ($propertiesString != NULL)
                        {
                            $kvArray = explode("|", $propertiesString);
                            foreach ($kvArray as $kv)
                            {
                                $kAndV = explode(":", $kv);
                                if (sizeof($kAndV) == 2)
                                {
                                    $properties[$kAndV[0]] = $kAndV[1];
                                }
                            }
                        }
                    }
                    break;
                }
                break;
            case \XMLReader::END_ELEMENT:
                if ($xmlReader->name == 'Message')
                {
                    $message = new Message(
                        $messageId,
                        $messageBodyMD5,
                        $messageBody,
                        $publishTime,
                        $nextConsumeTime,
                        $firstConsumeTime,
                        $consumedTimes,
                        $receiptHandle,
                        $messageTag,
                        $properties
                    );
                    return $message;
                }
                break;
            }
        }

        $message = new Message(
            $messageId,
            $messageBodyMD5,
            $messageBody,
            $publishTime,
            $nextConsumeTime,
            $firstConsumeTime,
            $consumedTimes,
            $receiptHandle,
            $messageTag,
            $properties
        );

        return $message;
    }
}

?>
