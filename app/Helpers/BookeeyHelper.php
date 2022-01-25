<?php // Code within app\Helpers\Helper.php
namespace App\Helpers;
/**
 * @package Bookeey Payment Gateway Library
 * @version 2.0.0
 * @author Writerz Wall
 * @link https://writerzwall.com
 *
 * This is the core library class for the implementation of
 * Bookeey Payment Gateway in PHP.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License version 3.0
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details at
 * https://www.gnu.org/licenses/lgpl-3.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. Please check the license for more details.
 *
 */

define('APP_VERSION',"2.0.0");
define('API_VERSION',"2.0.0");

///////////////////////////////////////
//Payment Option Codes. DO NOT CHANGE//
///////////////////////////////////////

define('KNET_CODE',"knet");
define('CREDIT_CODE',"credit");
define('BOOKEEY_CODE',"Bookeey");
define('AMEX_CODE',"amex");

///////////////////////////////////////////////////////////
//Payment Option Titles. Merchant may change these titles//
///////////////////////////////////////////////////////////

define('KNET_TITLE',"KNET");
define('CREDIT_TITLE',"Credit Card");
define('BOOKEEY_TITLE',"Bookeey PG");
define('AMEX_TITLE',"AMEX");

/////////////////////////////////////////////
//Bookeey Payment Gateway Merchant Settings//
/////////////////////////////////////////////

/**
 * Default Payment Option
 * Type: Constant Variable | String
 * Possible Values: Enter the payment option code or the corresponding constant variable which will be used as default payment option.
 */
define('DEFAULT_PAYMENT_OPTION',BOOKEEY_CODE);

/**
 * Enable/Disable Payment Method
 * Type: Integer
 * Possible Values: 0 (Disable) | 1 (Enable)
 */
define('IS_ENABLE',1);

/**
 * Enable/Disable Test Mode
 * Type: Integer
 * Possible Values:  0 (Enable Live Mode) | 1 (Enable Test Mode)
 */
define('IS_TEST_MODE_ENABLE',1);
define('IS_TEST_MODE_ENABLEABLE',1); 

/**
 * Payment Method Title
 * Type: String
 * Possible Values: Any string type values possible
 */
define('TITLE',"Canteeny Payment");

/**
 * Payment Method Description
 * Type: String
 * Possible Values: Any string type values possible
 */
define('DESCRIPTION',"Pay with Bookeey payment");

/**
 * Merchant ID
 * Type: String
 * Possible Values: Enter the Merchant Id provided by Bookeey.
 */
define('MERCHANT_ID',"");

/**
 * Secret Key
 * Type: String
 * Possible Values: Enter the Secret Key provided by Bookeey.
 */
define('SECRET_KEY',"");

/////////////////////////////////////////
//Bookeey Payment Gateway Configuration//
/////////////////////////////////////////

/**
 * Success URL
 * Type: String
 * Possible Values: Enter the Success Page URL as per your project.
 */
define('SUCCESS_URL',URL("api/payment/success"));

/**
 * Failure URL
 * Type: String
 * Possible Values: Enter the Failure Page URL as per your project.
 */
define('FAILURE_URL',URL("api/payment/fail"));

/**
 * Test Bookeey Payment Gateway URL
 * Type: String
 * CRITICAL: DO NOT CHANGE THIS VALUE.
 */
define('TEST_BOOKEEY_PAYMENT_GATEWAY_URL',"https://apps.bookeey.com/pgapi/api/payment/requestLink");

/**
 * Live Bookeey Payment Gateway URL
 * Type: String
 * CRITICAL: DO NOT CHANGE THIS VALUE.
 */
define('LIVE_BOOKEEY_PAYMENT_GATEWAY_URL',"https://pg.bookeey.com/internalapi/api/payment/requestLink");

/**
 * Test Bookeey Payment Requery URL
 * Type: String
 * CRITICAL: DO NOT CHANGE THIS VALUE.
 */
define('TEST_BOOKEEY_PAYMENT_REQUERY_URL',"https://apps.bookeey.com/pgapi/api/payment/paymentstatus");

/**
 * Live Bookeey Payment Requery URL
 * Type: String
 * CRITICAL: DO NOT CHANGE THIS VALUE.
 */
define('LIVE_BOOKEEY_PAYMENT_REQUERY_URL',"https://pg.bookeey.com/internalapi/api/payment/paymentstatus");

/**
 * Payment Options
 * Type: Array
 * CRITICAL: DO NOT CHANGE THESE VALUES
 */
const PAYMENT_OPTIONS = array(
    array(
        'is_active' => 1,
        'title' => KNET_TITLE,
        'code' => KNET_CODE,
    ),
    array(
        'is_active' => 1,
        'title' => CREDIT_TITLE,
        'code' => CREDIT_CODE,
    ),
    array(
        'is_active' => 1,
        'title' => BOOKEEY_TITLE,
        'code' => BOOKEEY_CODE,
    ),
    array(
        'is_active' => 1,
        'title' => AMEX_TITLE,
        'code' => AMEX_CODE,
    ),
);
class BookeeyHelper {

     private static $isEnable =IS_TEST_MODE_ENABLEABLE;
     private static $isTestModeEnable= IS_TEST_MODE_ENABLE;
     private static $title = TITLE;
     private static $description = DESCRIPTION;
     private static $merchantID = MERCHANT_ID;
     private static $secretKey = SECRET_KEY;
     private static $successUrl = SUCCESS_URL;
     private static $failureUrl = FAILURE_URL;
     private static $testBookeeyPaymentGatewayUrl = TEST_BOOKEEY_PAYMENT_GATEWAY_URL;
     private static $liveBookeeyPaymentGatewayUrl = LIVE_BOOKEEY_PAYMENT_GATEWAY_URL;
     private static $testBookeeyPaymentRequeryUrl = TEST_BOOKEEY_PAYMENT_REQUERY_URL;
     private static $liveBookeeyPaymentRequeryUrl = LIVE_BOOKEEY_PAYMENT_REQUERY_URL;
     private static $defaultPaymentOption = DEFAULT_PAYMENT_OPTION;
     private static $paymentOptions = PAYMENT_OPTIONS;
     private static $amount='';
     private static $selectedPaymentOption = DEFAULT_PAYMENT_OPTION;
     private static $orderId ='';
     private static $payerName = '';
     private static $payerPhone = '';
     private static $systemInfo = '';

    

    /**
     * Get the Enable/Disable Status of the Payment Method
     * Return Type: Integer
     * Possible Values: 0 (Disable) | 1 (Enable)
     */

    public static function isEnable() {

        return 1;
    }

    /**
     * Set the Enable/Disable Status of the Payment Method
     * Argument Type: Integer
     * Possible Values: 0 (Disable) | 1 (Enable)
     */
    public static function setIsEnable($data) {
        static::$isEnable = $data;
    }

    /**
     * Get the Enable/Disable status of the Test Mode
     * Return Type: Integer
     * Possible Values:  0 (Enable Live Mode) | 1 (Enable Test Mode)
     */
    public static function isTestModeEnable() {
        return static::$isTestModeEnable;
    }

    /**
     * Set the Enable/Disable status of the Test Mode
     * Argument Type: Integer
     * Possible Values:  0 (Enable Live Mode) | 1 (Enable Test Mode)
     */
    public static function setIsTestModeEnable($data) {
        static::$isTestModeEnable = $data;
    }

    /**
     * Get the Payment Method Title
     * Return Type: String
     * Possible Values: Any string type values possible
     */
    public static function getTitle() {
        return static::$title;
    }

    /**
     * Set the Payment Method Title
     * Argument Type: String
     * Possible Values: Any string type values possible
     */
    public static function setTitle($data) {
        static::$title = $data;
    }


    /**
     * Get the Payment Method Description
     * Return Type: String
     * Possible Values: Any string type values possible
     */
    public static function getDescription() {
        return static::$description;
    }

    /**
     * Set the Payment Method Description
     * Argument Type: String
     * Possible Values: Any string type values possible
     */
    public static function setDescription($data) {
        static::$description = $data;
    }


    /**
     * Get Merchant ID
     * Return Type: String
     */
    public static function getMerchantID() {
        return static::$merchantID;
    }

    /**
     * Set Merchant ID
     * Argument Type: String
     * Possible Values: Enter the Merchant Id provided by Bookeey.
     */
    public static function setMerchantID($data) {
        static::$merchantID = $data;
    }


    /**
     * Get Secret Key
     * Return Type: String
     */
    public static function getSecretKey() {
        return static::$secretKey;
    }

    /**
     * Set Secret Key
     * Argument Type: String
     * Possible Values: Enter the Secret Key provided by Bookeey.
     */
    public static function setSecretKey($data) {
        static::$secretKey = $data;
    }


    /**
     * Get Success URL
     * Return Type: String
     */
    public static function getSuccessUrl() {
        return static::$successUrl;
    }

    /**
     * Set Success URL
     * Argument Type: String
     * Possible Values: Enter the Success Page URL as per your project.
     */
    public static function setSuccessUrl($data) {
        static::$successUrl = $data;
    }


    /**
     * Get Failure URL
     * Return Type: String
     */
    public static function getFailureUrl() {
        return static::$failureUrl;
    }

    /**
     * Set Failure URL
     * Argument Type: String
     * Possible Values: Enter the Failure Page URL as per your project.
     */
    public static function setFailureUrl($data) {
        static::$failureUrl = $data;
    }


    /**
     * Get Amount
     * Return Type: Integer | Float
     */
    public static function getAmount() {
        return static::$amount;
    }

    /**
     * Set Amount
     * Argument Type: Integer | Float
     * Possible values: Enter any Integer or Float type number
     */
    public static function setAmount($data) {
        static::$amount = $data;
    }


    /**
     * Get Order Id
     * Type: Integer | String
     * Note: Order ID should be unique for each transaction.
     */
    public static function getOrderId() {
        return static::$orderId;
    }

    /**
     * Set Order Id
     * Type: Integer | String
     * Note: Order ID should be unique for each transaction.
     */
    public static function setOrderId($data) {
        static::$orderId = $data;
    }


    /**
     * Get Payer Name
     * Type: String
     */
    public  function getPayerName() {
        return static::$payerName;
    }

    /**
     * Set Payer Name
     * Type: String
     */
    public static function setPayerName($data) {
        static::$payerName = $data;
    }
    

    /**
     * Get Payer Phone
     * Type: String
     */
    public static function getPayerPhone() {
        return static::$payerPhone;
    }

    /**
     * Set Payer Phone
     * Type: String
     */
    public static function setPayerPhone($data) {
        static::$payerPhone = $data;
    }


    /**
     * Get Default Payment Option
     * Return Type: String
     */
    public static function getDefaultPaymentOption() {
        return static::$defaultPaymentOption;
    }

    /**
     * Set Default Payment Option
     * Argument Type: Constant Variable | String
     * Possible Values: Enter the payment option code or the corresponding constant variable which will be used as default payment option.
     */
    public static function setDefaultPaymentOption($data) {
        static::$defaultPaymentOption = $data;
    }

    /**
     * Get Selected Payment Option
     * Return Type: String
     */
    public static function getSelectedPaymentOption() {
        return static::$selectedPaymentOption;
    }

    /**
     * Set Selected Payment Option
     * Argument Type: Constant Variable | String
     * Possible Values: Enter the payment option code or the corresponding constant variable which will be used as selected payment option.
     */
    public static function setSelectedPaymentOption($data) {
        static::$selectedPaymentOption = $data;
    }


    /**
     * Get Test Bookeey Payment Gateway URL
     * Return Type: String
     */
    public static function getTestBookeeyPaymentGatewayUrl(){
        return static::$testBookeeyPaymentGatewayUrl;
    }

    /**
     * Get Live Bookeey Payment Gateway URL
     * Return Type: String
     */
    public static function getLiveBookeeyPaymentGatewayUrl() {
        return static::$liveBookeeyPaymentGatewayUrl;
    }


    /**
     * Get Test Bookeey Payment Requery URL
     * Return Type: String
     */
    public static function getTestBookeeyPaymentRequeryUrl(){
        return static::$testBookeeyPaymentRequeryUrl;
    }

    /**
     * Get Live Bookeey Payment Requery URL
     * Return Type: String
     */
    public static function getLiveBookeeyPaymentRequeryUrl() {
        return static::$liveBookeeyPaymentGatewayUrl;
    }


    /**
     * Get All Payment Options
     * Return Type: Array
     */
    public static function getPaymentOptions() {
        return static::$paymentOptions;
    }

    /**
     * Get Active Payment Options
     * Return Type: Array
     */
    public static function getActivePaymentOptions() {
        // $paymentOptions = static::$getPaymentOptions();
        $paymentOptions = static::$paymentOptions;

        $activePaymentOptions = array_filter($paymentOptions, function ($var) {
            return ($var['is_active'] == 1);
        });

        return $activePaymentOptions;
    }

    /**
     * Get Bookeey Payment Gateway URL as per Active Mode
     * Type: String
     */
    public static function getBookeeyPaymentGatewayUrl() {
        $isTestModeEnable = (new self)->isTestModeEnable();

        if($isTestModeEnable) {
            $bookeeyPaymentGatewayUrl = (new self)->getTestBookeeyPaymentGatewayUrl();
        }else{
            $bookeeyPaymentGatewayUrl = (new self)->getLiveBookeeyPaymentGatewayUrl();
        }
        
        return $bookeeyPaymentGatewayUrl;
    }

    /**
     * Get Bookeey Payment Requery URL as per Active Mode
     * Type: String
     */
    public static function getBookeeyPaymentRequeryUrl() {
        $isTestModeEnable = static::$isTestModeEnable();

        if($isTestModeEnable) {
            $bookeeyPaymentRequeryUrl = static::$getTestBookeeyPaymentRequeryUrl();
        }else{
            $bookeeyPaymentRequeryUrl = static::$getLiveBookeeyPaymentRequeryUrl();
        }
        return $bookeeyPaymentRequeryUrl;
    }


    public static function refund()
    {
        $rand = rand(1000,9999);
        // echo $rand."0862501";exit;
        echo "<pre>";
        $hashed_password = $rand."0862501".hash('SHA512',$rand."0862501");
        // echo $hashed_password;exit;
        self::initiateRefund([]);    
        // 123479D20A1EC500E1D2A3264569E85E2814E1943CDABB90302EF1E0F9E475F91C1623FBE8ED5093C00A2F3B60F39043C328219D56CB099F8A00BCFA2AA4F2B70C98
    }

    /**
     * Initiate Payment
     * Argument: Array (Pass the sub merchant id(s) and amount for each transaction in the array)
     */
    public static function initiatePayment($transactionDetails){
        session_start();
        $sessionId = session_id();
        $systemInfo =  (new self)->systemInfo();
        $browser = (new self)->browser();
        $payerName = (new self)->getPayerName();
        $payerPhone = (new self)->getPayerPhone();
        $mid = (new self)->getMerchantID();
        $tex = $random_pwd = mt_rand(1000000000000000, 9999999999999999);
        $txnRefNo = $tex;
        $su = (new self)->getSuccessUrl();
        $fu = (new self)->getFailureUrl();
        $amt = (new self)->getAmount();
        $orderId = (new self)->getOrderId();
        // $txnTime = "1545633631518";
        // $txnTime = date("ymdHis");
        $rndnum = rand(10000,99999);
        $crossCat = "GEN";
        $secretKey = (new self)->getSecretKey();
        $defaultPaymentOption = (new self)->getDefaultPaymentOption();
        $selectedPaymentOption = (new self)->getSelectedPaymentOption();
        $paymentoptions = ($selectedPaymentOption == '') ? $defaultPaymentOption : $selectedPaymentOption;
        $data = "$mid|$txnRefNo|$su|$fu|$amt|$crossCat|$secretKey|$rndnum";
        $hashed = hash('sha512', $data);

        $paymentGatewayUrl = (new self)->getBookeeyPaymentGatewayUrl();

        $txnDtl = $transactionDetails;

        $txnHdr = array(
            "PayFor" => "ECom",
            "Txn_HDR" => $rndnum,
            "PayMethod" => $paymentoptions,
            "BKY_Txn_UID" => "",
            "Merch_Txn_UID" => $orderId,
            "hashMac" => $hashed
        );

        $appInfo = array(
            "APPTyp" => "",
            "OS" => $systemInfo['os'].' - '.$browser,
            "DevcType" => $systemInfo['device'],
            "IPAddrs" => $_SERVER['REMOTE_ADDR'],
            "Country" => "",
            "AppVer" => APP_VERSION,
            "UsrSessID" => $sessionId,
            "APIVer" => API_VERSION
        );

        $pyrDtl = array(
            "Pyr_MPhone" => $payerPhone,
            "Pyr_Name" => $payerName,
            "ISDNCD" => "965",
        );

        $merchDtl = array(
            "BKY_PRDENUM" => "ECom",
            "FURL" => $fu,
            "MerchUID" => $mid,
            "SURL" => $su
        );

        $moreDtl = array(
            "Cust_Data1" => "",
            "Cust_Data3" => "",
            "Cust_Data2" => ""
        );
        
        $postParams['Do_TxnDtl'] = $txnDtl;
        $postParams['Do_TxnHdr'] = $txnHdr;
        $postParams['Do_Appinfo'] = $appInfo;
        $postParams['Do_PyrDtl'] = $pyrDtl;
        $postParams['Do_MerchDtl'] = $merchDtl;
        $postParams['DBRqst'] = "PY_ECom";
        $postParams['Do_MoreDtl'] = $moreDtl;

        $ch = curl_init();

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        // echo "<pre>";
        // print_r($postParams);exit;
        curl_setopt($ch, CURLOPT_URL,$paymentGatewayUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $serverOutput = curl_exec($ch);
        $decodeOutput = json_decode($serverOutput, true);
        curl_close ($ch);
        //  echo "<pre>";
        // print_r($decodeOutput);exit;
        $data= [];
        if (isset($decodeOutput['PayUrl'])) {
            if ($decodeOutput['PayUrl'] == '') {
                $data['status'] = false;
                $data['error'] = $decodeOutput['ErrorMessage'];
            }else{
                $data['status'] = true;
                $data['url'] = $decodeOutput['PayUrl'];
            }   
        }else if(isset($decodeOutput['Message'])){
            $data['status'] = false;
            $data['error'] = $decodeOutput['Message'];
        }else{
            $data['status'] = false;
            $data['error'] ="Something went wrong!";
        }
        return $data;
    }

    public static function initiateRefund($transactionDetails){
        session_start();
        $sessionId = session_id();
        $systemInfo =  (new self)->systemInfo();
        $browser = (new self)->browser();
        $payerName = (new self)->getPayerName();
        $payerPhone = (new self)->getPayerPhone();
        $mid = (new self)->getMerchantID();
        $tex = $random_pwd = mt_rand(1000000000000000, 9999999999999999);
        $txnRefNo = $tex;
        $su = (new self)->getSuccessUrl();
        $fu = (new self)->getFailureUrl();
        $amt = (new self)->getAmount();
        $orderId = (new self)->getOrderId();
        // $txnTime = "1545633631518";
        // $txnTime = date("ymdHis");
        $rndnum = rand(1000,9999);
        $crossCat = "GEN";
        $secretKey = (new self)->getSecretKey();
        $defaultPaymentOption = (new self)->getDefaultPaymentOption();
        $selectedPaymentOption = (new self)->getSelectedPaymentOption();
        $paymentoptions = ($selectedPaymentOption == '') ? $defaultPaymentOption : $selectedPaymentOption;
        $data = "$rndnum|0862501";
        $hashed = hash('sha512', $data);

        $paymentGatewayUrl = "https://dev.bookeey.com/bkyapi/v1/Accounts/request-refund";

        $txnDtl = $transactionDetails;

        $Do_ReFndDtl = array(
          "BkyTrackUID" => "", 
          "MerchRefNo" => "107464698", 
          "Refnd_AMT" => 1, 
          "RefndTo" => "", 
          "Remark" => null, 
          "ProsStatCD" => 10, 
          "MerUID" => "mer1800056",
           "hashMac" => $hashed 
        );

        $appInfo = array(
             "APPID" => "ACNTS", 
             "MdlID" => "Refnd", 
             "AppTyp" => "WAPP", 
             "AppLicens" => "s", 
             "AppVer" => APP_VERSION, 
             "ApiVer" => API_VERSION, 
             "IPAddrs" => "", 
             "Country" => "" 
        );
        $Do_UsrAuth = array(
             "UsrSessnUID"=> "",
             "AuthTyp" => "5", 
        );

        $merchDtl = array(
            "MerUID" => $mid,
        );
        

        $postParams['Do_Appinfo'] = $appInfo;
        $postParams['Do_UsrAuth'] = $Do_UsrAuth;
        $postParams['Do_MerchAuth'] = $merchDtl;
        $postParams['Do_ReFndDtl'] = $Do_ReFndDtl;
        $postParams['DBRqst'] = "Req_New";

        $ch = curl_init();

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        
        curl_setopt($ch, CURLOPT_URL,$paymentGatewayUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $serverOutput = curl_exec($ch);
        $decodeOutput = json_decode($serverOutput, true);
        curl_close ($ch);
        $data= [];
        print_r($decodeOutput);exit;
        return $data;
    }


    /**
     * Get Updated Transaction Status from Bookeey Payment Requery Url
     * Argument: String (Pass the Transaction Id for which you want to get the updated status)
     */
    public static function getPaymentStatus($orderIds){
        $requeryUrl = static::$getBookeeyPaymentRequeryUrl();

        $mid = static::$getMerchantID();
        $rndnum = rand(10000,99999);
        $secretKey = static::$getSecretKey();

        $data = "$mid|$secretKey|$rndnum";
        $hashed = hash('sha512', $data);

        $postParams['Mid'] = $mid;
        $postParams['MerchantTxnRefNo'] = $orderIds;
        $postParams['HashMac'] = $hashed;

        $ch = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        curl_setopt($ch, CURLOPT_URL,$requeryUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $serverOutput = curl_exec($ch);
        $decodeOutput = json_decode($serverOutput, true);
        curl_close ($ch);

        return $decodeOutput;
    }


    /**
     * Get System information
     */
    public  function systemInfo()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform    = "Unknown OS Platform";
        $os_array       = array(
            '/windows nt 10.0/i'    =>  'Windows 10',
            '/windows phone 8/i'    =>  'Windows Phone 8',
            '/windows phone os 7/i' =>  'Windows Phone 7',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        $found = false;
        $device = '';

        foreach ($os_array as $regex => $value) 
        { 
            if($found)
                break;
            else if (preg_match($regex, $user_agent)) 
            {
                $os_platform = $value;
                $device = !preg_match('/(windows|mac|linux|ubuntu)/i',$os_platform)
                        ?'MOBILE':(preg_match('/phone/i', $os_platform)?'MOBILE':'SYSTEM');
            }
        }
        $device = !$device? 'SYSTEM':$device;

        return array('os'=>$os_platform,'device'=>$device);
    }


    /**
     * Get Browser information
     */
    public  function browser() 
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser        =   "Unknown Browser";
        $browser_array  = array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Mozilla Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Google Chrome',
            '/edge/i'       =>  'Microsoft Edge',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );

        $found = false;

        foreach ($browser_array as $regex => $value) 
        { 
            if($found)
            break;
            else if (preg_match($regex, $user_agent,$result)) 
            {
                $browser = $value;
            }
        }

        return $browser;
    }

}