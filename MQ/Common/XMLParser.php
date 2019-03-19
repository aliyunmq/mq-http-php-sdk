<?php
namespace MQ\Common;

class XMLParser
{
    /**
     * Most of the error responses are in same format.
     */
    public static function parseNormalError(\XMLReader $xmlReader)
    {
        $result = array('Code' => null, 'Message' => null, 'RequestId' => null, 'HostId' => null);
        while ($xmlReader->Read()) {
            if ($xmlReader->nodeType == \XMLReader::ELEMENT) {
                switch ($xmlReader->name) {
                    case 'Code':
                        $xmlReader->read();
                        if ($xmlReader->nodeType == \XMLReader::TEXT) {
                            $result['Code'] = $xmlReader->value;
                        }
                        break;
                    case 'Message':
                        $xmlReader->read();
                        if ($xmlReader->nodeType == \XMLReader::TEXT) {
                            $result['Message'] = $xmlReader->value;
                        }
                        break;
                    case 'RequestId':
                        $xmlReader->read();
                        if ($xmlReader->nodeType == \XMLReader::TEXT) {
                            $result['RequestId'] = $xmlReader->value;
                        }
                        break;
                    case 'HostId':
                        $xmlReader->read();
                        if ($xmlReader->nodeType == \XMLReader::TEXT) {
                            $result['HostId'] = $xmlReader->value;
                        }
                        break;
                }
            }
        }
        return $result;
    }
}
