<?php

namespace MQ\Model;


use MQ\Traits\MessagePropertiesForPublish;

class TopicMessage
{

    use MessagePropertiesForPublish;

    public function __construct($messageBody)
    {
        $this->messageBody = $messageBody;
    }
}