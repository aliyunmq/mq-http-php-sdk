<?php
namespace MQ\Traits;

use MQ\Constants;

trait MessagePropertiesForPublish
{
    public $messageId;
    public $messageBodyMD5;
    public $messageBody;
    public $messageTag;

    public function getMessageBody()
    {
        return $this->messageBody;
    }

    public function setMessageBody($messageBody)
    {
        $this->messageBody = $messageBody;
    }

    public function getMessageTag()
    {
        return $this->messageTag;
    }

    public function setMessageTag($messageTag)
    {
        $this->messageTag = $messageTag;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    public function getMessageBodyMD5()
    {
        return $this->messageBodyMD5;
    }

    public function setMessageBodyMD5($messageBodyMD5)
    {
        $this->messageBodyMD5 = $messageBodyMD5;
    }

    public function writeMessagePropertiesForPublishXML(\XMLWriter $xmlWriter)
    {
        if ($this->messageBody != null) {
            $xmlWriter->writeElement(Constants::MESSAGE_BODY, $this->messageBody);
        }
        if ($this->messageTag !== null) {
            $xmlWriter->writeElement(Constants::MESSAGE_TAG, $this->messageTag);
        }
    }
}
