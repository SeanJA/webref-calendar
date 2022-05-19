<?php

namespace App;

use DOMDocument;
use DOMXPath;
use Gregwar\Cache\Cache;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

class WebRefClient
{
    use CacheableTrait;

    /**
     * @var ClientInterface
     */
    private $guzzle;

    public function __construct(ClientInterface $guzzle, Cache $cache)
    {
        $this->guzzle = $guzzle;
        $this->setCache($cache);
    }

    /**
     * @return mixed
     */
    private function getHtml()
    {
        return $this->remember(function () {
            $loginUrl = 'http://webreferee.net/Leagues/login_submit.asp';
            $response = $this->guzzle->post($loginUrl, [
                RequestOptions::FORM_PARAMS => [
                    'vAssoc' => getenv('V_ASSOC'),
                    'vUser' => getenv('V_USER'),
                    'vPass' => getenv('V_PASS'),
                ]
            ]);

            $response->getBody()->rewind();

            $cookieJar = $this->guzzle->getConfig('cookies');

            $response2 = $this->guzzle->get(getenv('MULTI_DAY_URL'), [
                RequestOptions::COOKIES => $cookieJar
            ]);


            $response2->getBody()->rewind();

            return $response2->getBody()->getContents();
        });
    }

    public function getRows()
    {
        $html = $this->getHtml();

        $doc = new DOMDocument();
        @$doc->loadHtml($html);

        $xpath = new DOMXpath($doc);

        return @$xpath->query('//*[@id="frmCheckSched"]/table//tr');
    }
}
