<?php
class BhxhSoapCli extends SoapClient {
    private $xml;
    public function __construct () {
        $option = array(
            'location' => "http://125.212.205.4:1111/soap",
            'uri' => "http://tecapro.com.vn/bhxh/ldapws/soap/danhmuc",
            'trace' => 1,
        );
        parent::__construct(null, $option);
    }
    public function __doRequest($request, $location, $action, $version, $one_way = 0) {
        if($this->xml) {
            $dom = new DomDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->loadXML($this->xml);
            $request = $dom->saveXML();
        }

        return parent::__doRequest($request, $location, $action, $version);
    }

    public function cronAllUser() {
        $this->xml = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:dan="http://tecapro.com.vn/bhxh/soap/danhmuc">
           <soapenv:Header/>
           <soapenv:Body>
              <dan:userBhxhRequest RequestType="READ">
              </dan:userBhxhRequest>
           </soapenv:Body>
        </soapenv:Envelope>
        ';
        return $this->userBhxhRequest();
    }
}

/**
 * @todo: for now, hardcode post xml to soap server
 * how to use
 * $option = array(
 * 'location' => "http://125.212.205.4:1111/soap",
 * 'uri'      => "http://tecapro.com.vn/bhxh/ldapws/soap/danhmuc",
 * 'trace'    => 1,
 * );
 * $client = new BhxhSoapCli(null, $option);
 * $rs = $client->cronAllUser();
 */