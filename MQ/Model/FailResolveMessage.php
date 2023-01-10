<?php
namespace MQ\Model;

class FailResolveMessage {

    private $messageId;
    private $receiptHandle;
    private $orgResponseData;

    /**
     * @param $messageId
     * @param $receiptHandle
     * @param $orgResponseData
     */
    public function __construct($messageId, $receiptHandle, $orgResponseData)
    {
        $this->messageId = $messageId;
        $this->receiptHandle = $receiptHandle;
        $this->orgResponseData = $orgResponseData;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getReceiptHandle()
    {
        return $this->receiptHandle;
    }

    /**
     * @return string
     */
    public function getOrgResponseData()
    {
        return $this->orgResponseData;
    }
}

?>
