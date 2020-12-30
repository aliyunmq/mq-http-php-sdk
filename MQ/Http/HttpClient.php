<?php
namespace MQ\Http;

use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use MQ\AsyncCallback;
use MQ\Config;
use MQ\Constants;
use MQ\Exception\MQException;
use MQ\Requests\BaseRequest;
use MQ\Responses\BaseResponse;
use MQ\Responses\MQPromise;
use MQ\Signature\Signature;

class HttpClient
{
    private $client;
    private $endpoint;
    private $accessId;
    private $accessKey;
    private $securityToken;
    private $requestTimeout;
    private $connectTimeout;

    private $agent;

    public function __construct($endPoint, $accessId,
        $accessKey, $securityToken = NULL, Config $config = NULL)
    {
        if ($config == NULL)
        {
            $config = new Config;
        }
        $this->accessId = $accessId;
        $this->accessKey = $accessKey;
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $endPoint,
            'defaults' => [
                'headers' => [
                    'Host' => $endPoint
                ],
                'proxy' => $config->getProxy(),
                'expect' => $config->getExpectContinue()
            ]
        ]);
        $this->requestTimeout = $config->getRequestTimeout();
        $this->connectTimeout = $config->getConnectTimeout();
        $this->securityToken = $securityToken;
        $this->endpoint = $endPoint;
        $guzzleVersion = '';
        if (defined('\GuzzleHttp\Client::VERSION')) {
            $guzzleVersion = $this->client::VERSION;
        } else {
            $guzzleVersion = $this->client::MAJOR_VERSION;
        }
        $this->agent = Constants::CLIENT_VERSION . $guzzleVersion . " PHP/" . PHP_VERSION . ")";
    }

    private function addRequiredHeaders(BaseRequest &$request)
    {
        $body = $request->generateBody();
        $queryString = $request->generateQueryString();

        $request->setBody($body);
        $request->setQueryString($queryString);

        $request->setHeader(Constants::USER_AGENT, $this->agent);
        if ($body != NULL)
        {
            $request->setHeader(Constants::CONTENT_LENGTH, strlen($body));
        }
        $request->setHeader('Date', gmdate(Constants::GMT_DATE_FORMAT));
        if (!$request->isHeaderSet(Constants::CONTENT_TYPE))
        {
            $request->setHeader(Constants::CONTENT_TYPE, 'text/xml');
        }
        $request->setHeader(Constants::VERSION_HEADER, Constants::VERSION_VALUE);

        if ($this->securityToken != NULL)
        {
            $request->setHeader(Constants::SECURITY_TOKEN, $this->securityToken);
        }

        $sign = Signature::SignRequest($this->accessKey, $request);
        $request->setHeader(Constants::AUTHORIZATION,
            Constants::AUTH_PREFIX . " " . $this->accessId . ":" . $sign);
    }

    public function sendRequestAsync(BaseRequest $request,
        BaseResponse &$response, AsyncCallback $callback = NULL)
    {
        $promise = $this->sendRequestAsyncInternal($request, $response, $callback);
        return new MQPromise($promise, $response);
    }

    public function sendRequest(BaseRequest $request, BaseResponse &$response)
    {
        $promise = $this->sendRequestAsync($request, $response);
        return $promise->wait();
    }

    private function sendRequestAsyncInternal(BaseRequest &$request, BaseResponse &$response, AsyncCallback $callback = NULL)
    {
        $this->addRequiredHeaders($request);

        $parameters = array('exceptions' => false, 'http_errors' => false);
        $queryString = $request->getQueryString();
        $body = $request->getBody();
        if ($queryString != NULL) {
            $parameters['query'] = $queryString;
        }
        if ($body != NULL) {
            $parameters['body'] = $body;
        }

        $parameters['timeout'] = $this->requestTimeout;
        $parameters['connect_timeout'] = $this->connectTimeout;

        $request = new Request(strtoupper($request->getMethod()),
            $request->getResourcePath(), $request->getHeaders());
        try
        {
            if ($callback != NULL)
            {
                return $this->client->sendAsync($request, $parameters)->then(
                    function ($res) use (&$response, $callback) {
                        try {
                            $response->setRequestId($res->getHeaderLine("x-mq-request-id"));
                            $callback->onSucceed($response->parseResponse($res->getStatusCode(), $res->getBody()));
                        } catch (MQException $e) {
                            $callback->onFailed($e);
                        }
                    }
                );
            }
            else
            {
                return $this->client->sendAsync($request, $parameters);
            }
        }
        catch (TransferException $e)
        {
            $message = $e->getMessage();
            if ($e->hasResponse()) {
                $message = $e->getResponse()->getBody();
            }
            throw new MQException($e->getCode(), $message, $e);
        }
    }
}

?>
