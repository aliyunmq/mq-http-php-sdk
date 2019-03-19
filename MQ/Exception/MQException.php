<?php
namespace MQ\Exception;

class MQException extends \RuntimeException
{
    private $onsErrorCode;
    private $requestId;
    private $hostId;

    public function __construct($code, $message, $previousException = null, $onsErrorCode = null, $requestId = null, $hostId = null)
    {
        parent::__construct($message, $code, $previousException);

        if ($onsErrorCode == null) {
            if ($code >= 500) {
                $onsErrorCode = "ServerError";
            } else {
                $onsErrorCode = "ClientError";
            }
        }
        $this->onsErrorCode = $onsErrorCode;

        $this->requestId = $requestId;
        $this->hostId = $hostId;
    }

    public function __toString()
    {
        $str = "Code: " . $this->getCode() . " Message: " . $this->getMessage();
        if ($this->onsErrorCode != null) {
            $str .= " ErrorCode: " . $this->onsErrorCode;
        }
        if ($this->requestId != null) {
            $str .= " RequestId: " . $this->requestId;
        }
        if ($this->hostId != null) {
            $str .= " HostId: " . $this->hostId;
        }
        return $str;
    }

    public function getOnsErrorCode()
    {
        return $this->onsErrorCode;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function getHostId()
    {
        return $this->hostId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }
}
