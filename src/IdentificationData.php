<?php

declare(strict_types = 1);

namespace phpares;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Client\ClientInterface;

class IdentificationData
{
    public const BASE_URL = 'https://wwwinfo.mfcr.cz/cgi-bin/ares/darv_std.cgi';
    private const NS_ARE = 'http://wwwinfo.mfcr.cz/ares/xml_doc/schemas/ares/ares_answer/v_1.0.1';
    private const NS_DATA_TYPES = 'http://wwwinfo.mfcr.cz/ares/xml_doc/schemas/ares/ares_datatypes/v_1.0.4';

    public string $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36';
    private string|false|null $loadedXml = null;

    public function __construct(private IValidator $validator, private ClientInterface $client)
    {
    }

    /**
     * @param int|string $ic
     * @return array<string>
     */
    public function getAddress(int|string $ic): array
    {
        $address = [];
        $xmlString = $this->loadXML($ic);
        if ($xmlString) {
            $xml = new \DOMDocument('1.0');
            $xml->loadXML($xmlString);
            $countOfRecordsNode = $xml->getElementsByTagNameNS(self::NS_ARE, 'Pocet_zaznamu');
            if ($countOfRecordsNode->length) {

                $basicElements = [
                    'id' => 'ID_adresy',
                    'district' => 'Nazev_okresu',
                    'city' => 'Nazev_obce',
                    'street' => 'Nazev_ulice',
                    'number' => 'Cislo_domovni',
                    'numberType' => 'Typ_cislo_domovni',
                    'numberSecondary' => 'Cislo_orientacni',
                    'zipCode' => 'PSC'
                ];

                foreach ($basicElements as $key => $element) {
                    $node = $xml->getElementsByTagNameNS(self::NS_DATA_TYPES, $element)->item(0);
                    if ($node) {
                        $address[$key] = $node->nodeValue;
                    }
                }
            }
        }

        return $address;
    }

    private function loadXML(int|string $ic): string|false
    {
        if ($this->loadedXml === null) {
            $this->loadedXml = false;
            if ($this->validator->isValid($ic, true)) {
                $uri = new Uri(sprintf(self::BASE_URL . '?ico=%s', (string)$ic));
                // @phpstan-ignore-next-line
                $request = new Request('GET', $uri, [
                    'headers' => ['User-Agent' => $this->userAgent],
                    'http_errors' => false
                ]);
                $response = $this->client->sendRequest($request);
                if ($response->getStatusCode() === 200) {
                    $this->loadedXml = (string)$response->getBody();
                }
            }
        }

        return $this->loadedXml;
    }
}
