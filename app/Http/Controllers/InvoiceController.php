<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrdersDate;
use App\Models\Meal;
use Carbon\Carbon;
use PDF;

class InvoiceController extends Controller
{
    public function pdf($id)
    {

        $id=base64_decode($id);
        $id = explode('|',$id);     
        $order = Orders::with(['customer','canteen'])->find($id[1]);
        // echo "<pre>";
        // print_r($order->toArray());exit;
        if (isset($order) ) {
            $order = $order->toArray();
            $meals = Meal::whereId($order['meal_id'])->with(['category'])->first();
            
            $order_dates = OrdersDate::where('order_id',$id)->with(['order_products'])->get();
        
            $order['meal'] = $meals->toArray();
            $order['order_dates'] = $order_dates->toArray();
            

            $data['order'] = $order;
            $pdf = PDF::loadView('invoice', $data);
            // return $pdf->setPaper('a4')->stream();
            // return view('invoice',$data);
            $name='invoice_'.date('m-d-Y').'.pdf';
            return $pdf->download($name);
        }
    }
}