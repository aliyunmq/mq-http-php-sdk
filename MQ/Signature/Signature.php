<?php
namespace MQ\Signature;

use MQ\Constants;
use MQ\Requests\BaseRequest;

class Signature
{
    static public function SignRequest($accessKey, BaseRequest &$request)
    {
        $headers = $request->getHeaders();
        $contentMd5 = "";
        if (isset($headers['Content-MD5']))
        {
            $contentMd5 = $headers['Content-MD5'];
        }
        $contentType = "";
        if (isset($headers['Content-Type']))
        {
            $contentType = $headers['Content-Type'];
        }
        $date = $headers['Date'];
        $queryString = $request->getQueryString();
        $canonicalizedResource = $request->getResourcePath();
        if ($queryString != NULL)
        {
            $canonicalizedResource .= "?" . $request->getQueryString();
        }
        if (0 !== strpos($canonicalizedResource, "/"))
        {
            $canonicalizedResource = "/" . $canonicalizedResource;
        }

        $tmpHeaders = array();
        foreach ($headers as $key => $value)
        {
            if (0 === strpos($key, Constants::HEADER_PREFIX))
            {
                $tmpHeaders[$key] = $value;
            }
        }
        ksort($tmpHeaders);

        $canonicalizedHeaders = implode("\n", array_map(function ($v, $k) { return $k . ":" . $v; }, $tmpHeaders, array_keys($tmpHeaders)));

        $stringToSign = strtoupper($request->getMethod()) . "\n" . $contentMd5 . "\n" . $contentType . "\n" . $date . "\n" . $canonicalizedHeaders . "\n" . $canonicalizedResource;

        return base64_encode(hash_hmac("sha1", $stringToSign, $accessKey, $raw_output = TRUE));
    }
}

?>
