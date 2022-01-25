<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use App\Models\User;
use App\Models\Orders;

class DietStationHelper {
    public static function CreateCustomer($order, $parent = []) {

        $phone = $order->customer->phone;
        $email = $order->customer->email;

        $user_id = $order->customer_id;
        $school = $order->customer->toArray();
        if (!empty($parent)) {
            $phone = $parent['email'];
            $email = $parent['phone'];
            $user_id = $parent['id'];
        }
        $meals = Self::createOrderObject($order->id);
        $post = [
            "order" => [
                "customer" => [
                    "firstName" => $order->customer->first_name,
                    "lastName" => $order->customer->last_name,
                    "arabicFirstName" => $order->customer->first_name,
                    "arabicLastName" => $order->customer->last_name,
                    "mobile" => $phone,
                    "email" => $email,
                    "area" => $school['school']['area'],
                    "block" => $school['school']['block'],
                    "zone" => "",
                    "street" => $school['school']['street'],
                    "shift" => "Morning",
                    "schoolName" => $school['school']['name'],
                    "schoolGrade"=> $order->customer->grade_slug,
                    "schoolSection"=> $order->customer->class,
                    "externalId"=>$order->customer_id, 
                ],
                "planName" => $order->meal->name,
                "meals" => $meals,
            ],
        ];

        // echo json_encode($post);exit;
        return Self::postCurl($post,$order->id);

    }


    public static function createOrderObject($order_id)
    {
        $orders = Orders::select('orders.*')->where('to_date','>=',date('Y-m-d'))->join('order_dates','order_dates.order_id','orders.id')
            ->where('order_dates.diet_station',0)
            ->with(['meal', 'customer','order_dates','meal.category','order_dates.order_products'])
            ->whereHas('customer', function ($query) {
                // $query->where('is_diet_station','!=','0');
            })
            ->where('orders.id',$order_id)
            ->groupBy('order_dates.order_id')
            ->get();
            // echo "<pre>";
        $post = [];
        $i=0;
        foreach($orders->toArray() as $order)
        {
            foreach($order['order_dates'] as $row)
            {
               $post['meals'][$i]['date'] = $row['date'];
               // $post['meals'][$i]['order_id'] = $order['id'];

               $j=0;
               foreach($order['meal']['category'] as $val)
               {
                    $products = array_filter($row['order_products'], function ($var) use($val){
                        return ($var['category_id'] == $val['category_id']);
                    });
                    
                    $post['meals'][$i]['items'][$j]['mealCategory']=$val['category_name'];     
                    $post['meals'][$i]['items'][$j]['meals']=array_column($products,'kitchen_id');     
                    $j++;
               }      
               $i++;    
            }
           
        }
        if(!empty($post))
        {
            return $post['meals'];    
        }
        return $post;
    }

    public static function postCurl($data,$order_id) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('DIET_STATION_URL').'canteeny/create-order',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer 123',
            ),
        ));

        $response = curl_exec($curl);
        $myfile = fopen($order_id.".txt", "w") or die("Unable to open file!");
        $txt = json_encode("inside success");
        fwrite($myfile, $response.'\n'.json_encode($data));
        fclose($myfile);         
        curl_close($curl);
        $result = json_decode($response);

        if(!empty($result))
        {
            if($result->result->status == "success")
            {
                $so_order_id = $result->result->order->number;
                Orders::whereId($order_id)->update(['is_diet_station'=>$so_order_id]);
                return true;
            }
            else
            {
                Orders::whereId($order_id)->update(['diet_station_reason'=>$response]);
                return false;
            }
        }
        return false;
    }
}