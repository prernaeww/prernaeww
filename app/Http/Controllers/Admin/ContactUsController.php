<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactUs;
use DataTables;

class ContactUsController extends Controller
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
            $data = ContactUs::select('*')->orderBy('id','desc')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('document', function ($row){
                if(isset($row->document) && $row->document != '')
                {
                    $btn = '<a href=../'.$row['document'].' target="_blank">Click Here To Downlaod</a>';
                }
                else
                {
                    return '-';
                }
                return $btn;
            })
            ->editColumn('action', function ($row){
                $btn = '<a href="'.route('admin.contactus.show',$row['id']).'" class="mr-2"><i class="fa fa-eye"></i></a>';
                return $btn;
            })
            ->rawColumns(['action','document'])
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'email', 'name' => 'email','title' => __("Email") ],
               ['data' => 'document','name' => 'document', 'title' => __("Document")],
               ['data' => 'action','name' => 'action', 'title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.contactus.index');
           $params['dateTableTitle'] = "Contact Us";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.product.create');
           return view('admin.pages.contactus.index',$params);
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
        $params['pageTittle'] = "View Contact Us";
        $contactus =ContactUs::find($id);
        $params['contactus'] = $contactus->toArray();
        $params['backUrl'] = route('admin.contactus.index');
        return view('admin.pages.contactus.view',compact('contactus'),$params);

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
    }
}
