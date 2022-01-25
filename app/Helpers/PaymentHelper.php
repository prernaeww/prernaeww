<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use BridgeCommRequest;
define('BRIDGEPAY_URL',"https://www.bridgepaynetsecuretest.com/paymentservice/requesthandler.svc");
define('BRIDGEPAY_URL_LIVE',"https://www.bridgepaynetsecuretx.com/PaymentService/RequestHandler.svc");
class PaymentHelper
{
    public static function makeRequest($token, $amount)
    {
        $serializar = new BridgeCommRequest();
        $serializar->ClientIdentifier = "SOAP";
        // $serializar->TransactionID = "17800220190919093402";
        $serializar->TransactionID = date("Ymdhis");
        $serializar->RequestDateTime = "20211111120757";
        $serializar->PrivateKey = "xRkp014OBAyrw4aC";
        $serializar->AuthenticationTokenId = $token;
        $serializar->RequestType = "004";
        // $serializar->User = "madams";
        // $serializar->Password = "T1Ger51983#";

        /*$serializar->User = "dal117test";
        $serializar->Password = "57!sE@3Fm";*/


        $serializar->requestMessage = new \RequestMessage();
        // print_r($serializar->requestMessage);exit;

        $serializar->requestMessage->TransIndustryType = "RE";
        /*$serializar->requestMessage->TransIndustryType = "RS";*/
        $serializar->requestMessage->TransactionType = "sale";
        $serializar->requestMessage->AcctType = "R";
        $serializar->requestMessage->AcctZip = "33774";
        $serializar->requestMessage->Amount = $amount * 100;
        $serializar->requestMessage->HolderType = "P";

        // $serializar->requestMessage->MerchantCode = "178000";
        // $serializar->requestMessage->MerchantAccountCode = "178002";

        $serializar->requestMessage->MerchantCode = "856000";
        $serializar->requestMessage->MerchantAccountCode = "856001";

        // $serializar->requestMessage->Track2 = ";341111597241002=22122011317125989?";
        /*$serializar->requestMessage->EntryPINMode = "S";
        $serializar->requestMessage->TerminalCapabilities = "manual|stripe|icc|signature|rfid";
        $serializar->requestMessage->EntryMode = "SX";
        $serializar->requestMessage->EntryMedium = "MC";*/
        /*$serializar->requestMessage->PartialAuthorization = "false";*/


        $coco = \BridgeCommConnection::Serialize($serializar);


        $coco2 = \BridgeCommConnection::DeserializeStringXMLToObject($coco, "BridgeCommRequest");
        // print_r($coco2);
        // echo "<br/>";
        $conn = new \BridgeCommConnection();
        $response = $conn->processRequest(BRIDGEPAY_URL, $coco2);

        // 4005 5500 0000 0019  222
        return $response;
        // if ($response->ResponseCode == "00000") {
        //      return true;
        // }else{
        //      return false;
        // }

    }

    public static function makeRefund($TransactionID, $GatewayTransID, $amount = 0)
    {
        $serializar = new BridgeCommRequest();
        $serializar->ClientIdentifier = "SOAP";
        // $serializar->TransactionID = "17800220190919093402";
        $serializar->TransactionID = $TransactionID;
        $serializar->RequestDateTime = date("Ymdhis");

        $serializar->RequestType = "012";
        // $serializar->User = "madams";
        // $serializar->Password = "T1Ger51983#";

        /*$serializar->User = "dal117test";
        $serializar->Password = "57!sE@3Fm";*/


        $serializar->requestMessage = new \RequestMessage();
        // print_r($serializar->requestMessage);exit;


        $serializar->requestMessage->Amount = $amount * 100;


        // $serializar->requestMessage->MerchantCode = "178000";
        // $serializar->requestMessage->MerchantAccountCode = "178002";

        $serializar->requestMessage->MerchantCode = "856000";
        $serializar->requestMessage->MerchantAccountCode = "856001";
        $serializar->requestMessage->ReferenceNumber = $GatewayTransID;

        $serializar->requestMessage->TransactionCode = $TransactionID; //TransactionID /
        $serializar->requestMessage->TransactionType = "refund";


        $coco = \BridgeCommConnection::Serialize($serializar);


        $coco2 = \BridgeCommConnection::DeserializeStringXMLToObject($coco, "BridgeCommRequest");

        $conn = new \BridgeCommConnection();
        $response = $conn->processRequest(BRIDGEPAY_URL, $coco2);

        // 4005 5500 0000 0019  222
        return $response;
        // if ($response->ResponseCode == "00000") {
        //      return true;
        // }else{
        //      return false;
        // }

    }
}