<?php
namespace MQ\Requests;

use MQ\Constants;
use MQ\Traits\MessagePropertiesForPublish;

class PublishMessageRequest extends BaseRequest
{
    use MessagePropertiesForPublish;

    private $topicName;

    public function __construct($instanceId, $topicName, $messageBody, $properties = NULL, $messageTag = NULL)
    {
        parent::__construct($instanceId, 'post', 'topics/' . $topicName . '/messages');

        $this->topicName = $topicName;
        $this->messageBody = $messageBody;
        $this->messageTag = $messageTag;
        $this->properties = $properties;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function generateBody()
    {
        $xmlWriter = new \XMLWriter;
        $xmlWriter->openMemory();
        $xmlWriter->startDocument("1.0", "UTF-8");
        $xmlWriter->startElementNS(NULL, "Message", Constants::XML_NAMESPACE);
        $this->writeMessagePropertiesForPublishXML($xmlWriter);
        $xmlWriter->endElement();
        $xmlWriter->endDocument();
        return $xmlWriter->outputMemory();
    }

    public function generateQueryString()
    {
        if ($this->instanceId != null && $this->instanceId != "") {
            return http_build_query(array("ns" => $this->instanceId));
        }
        return NULL;
    }
}
?>
