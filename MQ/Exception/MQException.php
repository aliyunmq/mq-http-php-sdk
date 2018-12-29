<?php
namespace MQ\Exception;

class MQException extends \RuntimeException
{
    private $onsErrorCode;
    private $requestId;
    private $hostId;

    public function __construct($code, $message, $previousException = NULL, $onsErrorCode = NULL, $requestId = NULL, $hostId = NULL)
    {
        parent::__construct($message, $code, $previousException);

        if ($onsErrorCode == NULL)
        {
            if ($code >= 500)
            {
                $onsErrorCode = "ServerError";
            }
            else
            {
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
        if ($this->onsErrorCode != NULL)
        {
            $str .= " ErrorCode: " . $this->onsErrorCode;
        }
        if ($this->requestId != NULL)
        {
            $str .= " RequestId: " . $this->requestId;
        }
        if ($this->hostId != NULL)
        {
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

?>
