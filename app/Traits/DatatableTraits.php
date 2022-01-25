<?php
namespace App\Traits;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
// use App\Events\UserNotify;

trait DatatableTraits {
    public function index(Request $request) {

         // event(new UserNotify(User::find(1)));
        if ($request->ajax())
        {
            $data = User::select('*');
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('action', function ($row)
            {
                $btn = '<a href="javascript:void(0)"><i class="fa fa-search "></i></a>';
                return $btn;
            })
            ->make(true);
        }
        else
        {
            $columns = [['data' => 'id','name' => "Id"], ['data' => 'name', 'name' => __("Name")],['data' => 'action', 'name' => "Action",'searchable'=>false,'orderable'=>false]];
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('admin.users.index');
            $params['dateTableTitle'] = "Users";
            $params['dataTableId'] = time();
            return view('admin.pages.users.index',$params);
        }
    }
}