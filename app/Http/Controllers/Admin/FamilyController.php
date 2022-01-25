<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Family;
use App\Models\Product;
use DataTables;

use App\Models\SystemConfig;
use Twilio\Rest\Client;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    if ($request->ajax())
       {
            $data = Family::select('*');
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('status', function ($row)
            {
                if($row['status'] == 0){
                    return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="family" data-status="' . $row['status']. '">Inactive</button>';
                }else{
                    return '<button onclick="active_deactive(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-success btn-xs waves-effect waves-light" data-table="family" data-status="' . $row['status']. '">Active</button>';
                }
            })
            ->editColumn('action', function ($row)
            {
               $btn = '<a href="'.route('admin.family.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
               $btn .= '<a href="'.route('admin.family.destroy', $row['id']).'" data-url="family" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
               return $btn;
            })
            ->rawColumns(['action','status'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'name', 'name' => 'name','title' => __("Name")],
               ['data' => 'status','title' => __("Status"),'searchable'=>false],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.family.index');
           $params['dateTableTitle'] = "Family";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.family.create');
           return view('admin.pages.family.index',$params);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // $recipient = '3362107441';
        // $message = "This is testing SMS #5245";
        // if($recipient != '' && $message != ''){
        //     $recipient = '+1'.$recipient;
        //     $account_sid = SystemConfig::where('path','twilio_account_sid')->value('value');
        //     $auth_token = SystemConfig::where('path','twilio_auth_token')->value('value');
        //     $twilio_number = SystemConfig::where('path','twilio_twilio_number')->value('value');
        //     $client = new Client($account_sid, $auth_token);

        //     try{
        //         $phone_number = $client->lookups->v1->phoneNumbers($recipient)->fetch(array( "Type" => array("carrier")));

        //         // if($phone_number->carrier['error_code'] == '' && $phone_number->carrier['type'] != ''){
        //         if($phone_number){
                    
        //             try {
        //                 $result = $client->messages->create($recipient, ['from' => $twilio_number, 'body' => $message]);
        //                 $data['status'] = TRUE;
        //                 $data['data'] = $result;
        //             } catch (\Exception $e) {
        //                 $data['status'] = FALSE;
        //                 $data['data'] = $e->getMessage();
        //             }

        //         }else{
        //             $data['status'] = FALSE;
        //             $data['error'] = 'Invalid phone number type.';
        //         }
                
        //     }catch (\Exception $e){
        //         $data['status'] = FALSE;
        //         $data['error'] = $e->getMessage();
        //     }

        // }else{
        //     $data['status'] = FALSE;
        //     $data['data'] = "Parameter Missing";
        // }

        // dd($data);

        $params['pageTittle'] = "Add Family";
        $params['backUrl'] = route('admin.family.index');
        
        return view('admin.pages.family.post',$params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
          $request->validate([
            'name' => 'required',
        ]);


        $user = Family::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.family.index')->with('success','Family created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $params['pageTittle'] = "Edit Family";
        $params['family'] = family::find($id);
        $params['backUrl'] = route('admin.family.index');
        
        return view('admin.pages.family.put',$params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
         $request->validate([
            'name' => 'required',
        ]);        

     
        $family['name'] = $request->name;
        
    
        family::whereId($id)->update($family);
    
        return redirect()->route('admin.family.index')
                        ->with('success','Family updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // family::whereId($id)->delete();
        $family = Product::whereFamilyId($id)->get();       
        if (isset($family) && !empty($family) && count($family) > 0) {
            $data['status'] = false;
            $data['message'] = 'You can not delete as Product(s) already exist for this family';        
        } else
        {
            Family::whereId($id)->delete();
            $data['status'] = TRUE;        
            $data['message'] = 'Deleted';
        }
        echo json_encode($data);
        // return redirect()->route('admin.family.index')
                        // ->with('success','Family deleted successfully');
    }

   
}
