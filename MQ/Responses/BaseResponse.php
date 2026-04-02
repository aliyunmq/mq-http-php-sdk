<?php
namespace MQ\Responses;

use MQ\Exception\MQException;

abstract class BaseResponse
{
    protected $succeed;
    protected $statusCode;
    // from header
    protected $requestId;

    abstract public function parseResponse($statusCode, $content);

    abstract public function parseErrorResponse($statusCode, $content, MQException $exception = NULL);

    public function isSucceed()
    {
        return $this->succeed;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    protected function loadXmlContent($content)
    {
        $xmlReader = new \XMLReader();
        $isXml = $xmlReader->XML($content);
        if ($isXml === FALSE) {
            throw new MQException($this->statusCode, $content);
        }
        try {
            while ($xmlReader->read()) {}
        } catch (\Exception $e) {
            throw new MQException($this->statusCode, $content);
        }
        $xmlReader->XML($content);
        return $xmlReader;
    }

    protected function loadAndValidateXmlContent($content, &$xmlReader)
    {
        $doc = new \DOMDocument();
        if(!$doc->loadXML($content)) {
            $content = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $content);
            if(!$doc->loadXML($content)) {
               return false;
            }
        }
        $xmlReader = $this->loadXmlContent($content);
        return true;
    }
}

?>
