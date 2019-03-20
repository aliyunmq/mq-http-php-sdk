<?php
namespace MQ;

class Constants
{
    const GMT_DATE_FORMAT = "D, d M Y H:i:s \\G\\M\\T";

    const VERSION_HEADER = "x-mq-version";
    const HEADER_PREFIX = "x-mq-";
    const XML_NAMESPACE = "http://mq.aliyuncs.com/doc/v1/";

    const VERSION_VALUE = "2015-06-06";
    const AUTHORIZATION = "Authorization";
    const AUTH_PREFIX = "MQ";

    const CONTENT_LENGTH = "Content-Length";
    const CONTENT_TYPE = "Content-Type";
    const SECURITY_TOKEN = "security-token";
    const USER_AGENT = "User-Agent";

    // XML Tag
    const ERROR = "Error";
    const ERRORS = "Errors";
    const MESSAGE_BODY = "MessageBody";
    const MESSAGE_TAG = "MessageTag";
    const MESSAGE_ID = "MessageId";
    const MESSAGE_BODY_MD5 = "MessageBodyMD5";
    const PUBLISH_TIME = "PublishTime";
    const NEXT_CONSUME_TIME = "NextConsumeTime";
    const FIRST_CONSUME_TIME = "FirstConsumeTime";
    const RECEIPT_HANDLE = "ReceiptHandle";
    const RECEIPT_HANDLES = "ReceiptHandles";
    const CONSUMED_TIMES = "ConsumedTimes";
    const ERROR_CODE = "ErrorCode";
    const ERROR_MESSAGE = "ErrorMessage";

    // some ErrorCodes
    const INVALID_ARGUMENT = "InvalidArgument";
    const MALFORMED_XML = "MalformedXML";
    const MESSAGE_NOT_EXIST = "MessageNotExist";
    const RECEIPT_HANDLE_ERROR = "ReceiptHandleError";
    const ACK_FAIL = "AckFail";

    const TOPIC_NOT_EXIST = "TopicNotExist";
}
