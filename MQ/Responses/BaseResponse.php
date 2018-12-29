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
}

?>
