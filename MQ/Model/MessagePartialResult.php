<?php
namespace MQ\Model;

class MessagePartialResult
{

    private $messages;
    private $failResolveMessages;

    /**
     * @param array $messages
     * @param array $failResolveMessages
     */
    public function __construct(array $messages, array $failResolveMessages)
    {
        $this->messages = $messages;
        $this->failResolveMessages = $failResolveMessages;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function getFailResolveMessages()
    {
        return $this->failResolveMessages;
    }
}

?>
