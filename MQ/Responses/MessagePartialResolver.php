<?php

namespace MQ\Responses;

use MQ\Model\FailResolveMessage;
use MQ\Model\Message;
use MQ\Model\MessagePartialResult;

class MessagePartialResolver
{

    public static function resolve($source)
    {
        $isMatched = preg_match_all('/<Message>[\s\S]*?<\/Message>/', $source, $matches);
        if (!$isMatched) {
            return NULL;
        }
        $messages = array();
        $failResolveMessages = array();
        foreach ($matches[0] as $match) {
            $message = NULL;
            try {
                $message = self::tryToResolveToMessage($match);
            } catch (\Exception $e) {
                $message = NULL;
            }
            if ($message === NULL) {
                $failResolveMessages[] = self::tryToConvertToFailResult($match);
            } else {
                $messages[] = $message;
            }
        }
        return new MessagePartialResult($messages, $failResolveMessages);
    }

    private static function tryToResolveToMessage($content)
    {
        $xmlReader = new \XMLReader();
        $isXml = $xmlReader->XML($content);
        if ($isXml === FALSE) {
            return NULL;
        }
        $message = Message::fromXML($xmlReader);
        if ($message === NULL || $message->getMessageId() === NULL) {
            return NULL;
        }
        return $message;
    }

    private static function tryToConvertToFailResult($content)
    {
        $newContent = preg_replace('/(<MessageBody>[\s\S]*<\/MessageBody>)|(<Properties>[\s\S]*<\/Properties>)/', '', $content);
        if ($newContent === NULL) {
            return NULL;
        }
        $xmlReader = new \XMLReader();
        $isXml = $xmlReader->XML($newContent);
        if ($isXml === FALSE) {
            return NULL;
        }
        $message = Message::fromXML($xmlReader);
        return new FailResolveMessage($message->getMessageId(), $message->getReceiptHandle(), $content);
    }
}

?>