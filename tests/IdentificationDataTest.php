<?php

declare(strict_types = 1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use phpares\IdentificationData;
use phpares\Validator;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

$validator = new Validator();

$xml = '<?xml version="1.0" encoding="UTF-8"?>
<are:Ares_odpovedi xmlns:are="http://wwwinfo.mfcr.cz/ares/xml_doc/schemas/ares/ares_answer/v_1.0.1" xmlns:dtt="http://wwwinfo.mfcr.cz/ares/xml_doc/schemas/ares/ares_datatypes/v_1.0.4" xmlns:udt="http://wwwinfo.mfcr.cz/ares/xml_doc/schemas/uvis_datatypes/v_1.0.1" odpoved_datum_cas="2021-11-22T20:50:58" odpoved_pocet="1" odpoved_typ="Standard" vystup_format="XML" xslt="klient" validation_XSLT="/ares/xml_doc/schemas/ares/ares_answer/v_1.0.0/ares_answer.xsl" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://wwwinfo.mfcr.cz/ares/xml_doc/schemas/ares/ares_answer/v_1.0.1 http://wwwinfo.mfcr.cz/ares/xml_doc/schemas/ares/ares_answer/v_1.0.1/ares_answer_v_1.0.1.xsd" Id="ares">
<are:Odpoved>
<are:Pocet_zaznamu>1</are:Pocet_zaznamu>
<are:Typ_vyhledani>FREE</are:Typ_vyhledani>
<are:Zaznam>
<are:Shoda_ICO>
<dtt:Kod>9</dtt:Kod>
</are:Shoda_ICO>
<are:Vyhledano_dle>ICO</are:Vyhledano_dle>
<are:Typ_registru>
<dtt:Kod>2</dtt:Kod>
<dtt:Text>OR</dtt:Text>
</are:Typ_registru>
<are:Datum_vzniku>1991-01-22</are:Datum_vzniku>
<are:Datum_platnosti>2021-11-22</are:Datum_platnosti>
<are:Pravni_forma>
<dtt:Kod_PF>121</dtt:Kod_PF>
</are:Pravni_forma>
<are:Obchodni_firma>KRÁTKÝ FILM PRAHA a.s.</are:Obchodni_firma>
<are:ICO>00023574</are:ICO>
<are:Identifikace>
<are:Adresa_ARES>
<dtt:ID_adresy>209604861</dtt:ID_adresy>
<dtt:Kod_statu>203</dtt:Kod_statu>
<dtt:Nazev_okresu>Olomouc</dtt:Nazev_okresu>
<dtt:Nazev_obce>Olomouc</dtt:Nazev_obce>
<dtt:Nazev_casti_obce>Olomouc</dtt:Nazev_casti_obce>
<dtt:Nazev_ulice>Šemberova</dtt:Nazev_ulice>
<dtt:Cislo_domovni>66</dtt:Cislo_domovni>
<dtt:Typ_cislo_domovni>1</dtt:Typ_cislo_domovni>
<dtt:Cislo_orientacni>9</dtt:Cislo_orientacni>
<dtt:PSC>77900</dtt:PSC>
<dtt:Adresa_UIR>
<udt:Kod_oblasti>78</udt:Kod_oblasti>
<udt:Kod_kraje>124</udt:Kod_kraje>
<udt:Kod_okresu>3805</udt:Kod_okresu>
<udt:Kod_obce>500496</udt:Kod_obce>
<udt:Kod_casti_obce>413836</udt:Kod_casti_obce>
<udt:PSC>77900</udt:PSC>
<udt:Kod_ulice>326755</udt:Kod_ulice>
<udt:Cislo_domovni>66</udt:Cislo_domovni>
<udt:Typ_cislo_domovni>1</udt:Typ_cislo_domovni>
<udt:Cislo_orientacni>9</udt:Cislo_orientacni>
<udt:Kod_adresy>23319054</udt:Kod_adresy>
<udt:Kod_objektu>22950176</udt:Kod_objektu>
</dtt:Adresa_UIR>
</are:Adresa_ARES>
</are:Identifikace>
<are:Kod_FU>379</are:Kod_FU>
<are:Priznaky_subjektu>NAAANANANZANNNNNNNNNNNNNANNNNN</are:Priznaky_subjektu>
</are:Zaznam>
</are:Odpoved>
</are:Ares_odpovedi>';

$mock = new MockHandler([new Response(200, body: $xml)]);
$client = new Client(['handler' => $mock]);

$identificationData = new IdentificationData($validator, $client);
$excepted = [
    'id' => '209604861',
    'district' => 'Olomouc',
    'city' => 'Olomouc',
    'street' => 'Šemberova',
    'number' => '66',
    'numberType' => '1',
    'numberSecondary' => '9',
    'zipCode' => '77900'
];
Assert::same($excepted, $identificationData->getAddress(23574));

