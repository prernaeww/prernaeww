<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\User;
use App\Traits\ApiResponser;
use BookeeyHelper;
use CommonHelper;
use DietStationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use NotificationHelper;

class PaymentController extends Controller {
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request) {

        $validator = Validator::make(request()->all(), [
            'amount' => 'required',
            'order_id' => 'required',
        ]);
        if (!$validator->fails()) {
            // echo "sdfsdf";exit;
            BookeeyHelper::setTitle('Canteeny');
            BookeeyHelper::setDescription('Payment');
            BookeeyHelper::setMerchantID(CommonHelper::ConfigGet('bookeey_mer_id')); // Set the Merchant ID
            BookeeyHelper::setSecretKey(CommonHelper::ConfigGet('bookeey_mer_secretkey')); // Set the Secret Key
            BookeeyHelper::setOrderId($request->order_id); // Set Order ID - This should be unique for each transaction.
            BookeeyHelper::setAmount($request->amount); // Set amount in KWD
            // BookeeyHelper::setPayerName($request->name);  // Set Payer Name
            // BookeeyHelper::setPayerPhone($request->phone);  // Set Payer Phone Numner
            BookeeyHelper::setSelectedPaymentOption('knet');
            // setSelectedPaymentOption('credit');
            // setSelectedPaymentOption('Bookeey');
            // setSelectedPaymentOption('amex');

            $transactionDetails[0]['SubMerchUID'] = CommonHelper::ConfigGet('bookeey_mer_id');
            $transactionDetails[0]['Txn_AMT'] = $request->amount;
            $response = BookeeyHelper::initiatePayment($transactionDetails);
            if ($response['status']) {
                $data['payment_url'] = $response['url'];
                return $this->successResponse($data, __('Payment request url request success'));
            } else {
                return $this->errorResponse($response['error']);
            }
        }
        return $this->errorResponse($validator->messages(), true);
    }

    public function success() {
        $myfile = fopen("success.txt", "w") or die("Unable to open file!");
        $txt = json_encode("inside success");
        fwrite($myfile, $txt);
        fclose($myfile);
        if (isset($_REQUEST['txnId'])) {
            $transactionId = $_REQUEST['txnId'];
            Orders::where('id', $_REQUEST['merchantTxnId'])->update(array("transaction_id" => $transactionId, "status" => 1));
            $myfile = fopen("success.txt", "w") or die("Unable to open file!");
            $txt = json_encode($_REQUEST);
            fwrite($myfile, $txt);
            fclose($myfile);
            $order = Orders::where('id', $_REQUEST['merchantTxnId'])->with(['meal', 'customer','customer.school'])->first();
            $user = User::where('id', $order->customer_id)->with(['devices'])->first();

            $user = $user->toArray();
            $parent = [];
            $post_flag = $order->customer->is_diet_station;
            if ($user['parent_id'] != 0) {
                $parent = User::where('id', $user['parent_id'])->with(['devices'])->first();
                if (isset($parent)) {
                    $parent = $parent->toArray();
                    $post_flag = $parent['is_diet_station'];
                }
            }
            $check_api_is_enable = User::find($order->canteen_id);
            $api_status = true;
            if($check_api_is_enable->is_api)
            {
                $api_status = DietStationHelper::CreateCustomer($order, $parent);
            }

            
            if ($user['parent_id'] != 0) {
                $fullname = $parent['last_name'] . ' ' . $user['first_name'];
                $phone = $parent['phone'];
                $notification_id = $parent['id'];
                $device_token = $parent['devices']['token'];
            } else {
                $fullname = $user['first_name'] . ' ' . $user['last_name'];
                $phone = $user['phone'];
                $notification_id = $user['id'];
                $device_token = $user['devices']['token'];
            }
            // echo "--".$api_status;exit; 
            // print_r($device_token);exit;
            if($api_status)
            {
                $push_title = 'Order Confirmed';
                $push_data = array();
                $push_data['order_id'] = $_REQUEST['merchantTxnId'];
                $push_data['message'] = "Your order has been confirmed";
                $push_type = 'order_confirmed';
                $add_notification = NotificationHelper::add($notification_id, $push_data['message'], $push_type, $_REQUEST['merchantTxnId']);
                if (isset($device_token) && $device_token != '') {
                    $notification = NotificationHelper::send($device_token, $push_title, $push_data, $push_type);
                }
            }

            return $this->successResponse([], __('Payment successful'));
        } else {
            return $this->errorResponse(__('Transaction ID is missing'));
        }
    }

    public function success_test() {
        $order = Orders::where('id', $_REQUEST['merchantTxnId'])->with(['meal', 'customer','customer.school'])->first();
        $parent = [];
        $post_flag = $order->customer->is_diet_station;
        if ($order->customer->parent_id > 0) {
            $parent = User::where('id', $order->customer->parent_id)->first();
            $post_flag = $parent->is_diet_station;
        }
        if(!$post_flag)
        {
            $data = DietStationHelper::CreateCustomer($order, $parent);
        }
    }
    public function fail() {
        $myfile = fopen("success.txt", "w") or die("Unable to open file!");
        $txt = json_encode("inside fail");
        fwrite($myfile, $txt);
        fclose($myfile);
        if (isset($_REQUEST['merchantTxnId'])) {
            $transactionId = $_REQUEST['txnId'];

            //http://15.184.119.5/api/payment/fail?merchantTxnId=8&errorMessage=Not%20Captured&errorCode=1&txnId=1032512344

            Orders::where('id', $_REQUEST['merchantTxnId'])->update(array("transaction_id" => $transactionId, "status" => 2, "reason" => $_REQUEST['errorMessage']));
            $myfile = fopen("fail.txt", "w") or die("Unable to open file!");
            $txt = json_encode($_REQUEST);
            fwrite($myfile, $txt);
            fclose($myfile);
            return $this->errorResponse($_REQUEST['errorMessage']);
        } else {
            return $this->errorResponse(__('Transaction ID is missing'));
        }

    }

}
