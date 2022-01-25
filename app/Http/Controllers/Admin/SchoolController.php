<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Grade;
use App\Models\SchoolHoliday;
use App\Models\SchoolGrade;
use App\Models\OrdersDate;
use DataTables;
use Illuminate\Http\Request;
use App\Http\Requests;

class SchoolController extends Controller {
/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = School::select('*')->get();
            $datat = Datatables::of($data);

                if ($request->has('canteen')) {
                    $datat->filter(function ($instance) use ($request) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if ($request->get('canteen') == "all") {
                                return true;
                            }
                            return $row['canteen_id'] == $request->get('canteen') ? true : false;
                        });
                    });
                }
                return $datat->addIndexColumn()
                ->editColumn('canteen_name', function ($row) {
                    return '<a href="' . route("admin.canteen.show", $row->canteen_id) . '" >' . $row->canteen_name . '</a>';
                })
                ->editColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.school.edit', $row['id']) . '" class="mr-2"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="' . route('admin.school.show', $row['id']) . '" class="mr-2"><i class="fa fa-eye"></i></a>';
                    //$btn .= '<a href="' . route('admin.school.destroy', $row['id']) . '" data-url="school" data-id="' . $row["id"] . '" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="' . csrf_token() . '" ><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->editColumn('status', function ($row)
                {
                    if($row['status'] == 0){
                        return '<button onclick="active_deactive_school(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"  class="btn btn-danger btn-xs waves-effect waves-light" data-table="schools" data-status="' . $row['status']. '">In Active</button>';
                    }else{
                        return '<button onclick="active_deactive_school(this);" data-id="' . $row['id'] . '" data-token="' . csrf_token() . '"   class="btn btn-success btn-xs waves-effect waves-light" data-table="schools" data-status="' . $row['status']. '">Active</button>';
                    }
                })
                ->rawColumns(['canteen_name', 'action','status'])
                ->make(true);
        } else {
            $columns = [
                ['data' => 'id', 'name' => 'id', 'title' => "Id"],
                ['data' => 'name', 'name' => 'name', 'title' => __("Name")],
                ['data' => 'canteen_name', 'name' => 'canteen_id', 'title' => __("Canteen Name")],
                ['data' => 'status','title' => __("Status"),'searchable'=>false],
                ['data' => 'action', 'name' => 'action', 'title' => "Action", 'searchable' => false, 'orderable' => false]];
            $canteen = User::select('users.id', 'users.first_name', 'users.last_name')->join('users_group', 'users_group.user_id', 'users.id')->where('users_group.group_id', 2)->get();
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('admin.school.index');
            $params['dateTableTitle'] = "School Management";
            $params['dataTableId'] = time();
            $params['canteen'] = $canteen;
            $params['addUrl'] = route('admin.school.create');
            return view('admin.pages.school.index', $params);
        }
    }
/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function create() {
        $params['pageTittle'] = "Add School";
        $canteen = User::select('users.id', 'users.first_name', 'users.last_name')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', 2)->get();
        $grade = Grade::all();
        if (count($canteen) > 0) {
            // $canteen = $canteen->toArray();
            // dd($canteen);
            $params['canteen'] = $canteen;
        }
        if (count($grade) > 0) {
            // $grade = $grade->toArray();
            // dd($grade);
            $params['grade'] = $grade;
        }
        $params['backUrl'] = route('admin.school.index');
        return view('admin.pages.school.post', $params);
    }
/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
    public function store(Request $request) {
        
        $request->validate([
            'name' => 'required',
            'canteen_id' => 'required',
            'address' => 'required',
            'block' => 'required',
            'area' => 'required',
            'street' => 'required'
        ]);
        $school = School::create([
            'name' => $request->name,
            'canteen_id' => $request->canteen_id,
            'address'=>$request->address,
            'block'=>$request->block,
            'area'=>$request->area,
            'street'=>$request->street
        ]);
        $temp = $dates = $grades = $temp1 = [];
        if(isset($request->dates) && !empty($request->dates)){

            foreach ($request->range_datepicker as $key => $value) {
                if(strpos($value, 'to') !== false){
                    $exploded_dates = explode(' to ',$value);
                    $temp['user_id'] = $school->id;
                    $temp['from_date'] = $exploded_dates[0];
                    $temp['to_date'] = $exploded_dates[1];
                }else{
                    $temp['user_id'] = $school->id;
                    $temp['from_date'] = $value;
                    $temp['to_date'] = $value;
                }
                array_push($dates,$temp);
            }
            SchoolHoliday::insert($dates);
        }
        if(isset($request->school_grade) && !empty($request->school_grade)){

            foreach ($request->school_grade as $gkey => $gvalue) {
                $temp1['school_id'] = $school->id;
                $temp1['grade_id'] = $gvalue;
                array_push($grades,$temp1);
            }
            SchoolGrade::insert($grades);
        }
        // redirect
        return redirect()->route('admin.school.index')->with('success', 'School created successfully.');
    }
/**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function show($id) {
        $params['pageTittle'] = "View School";
        $school = School::with(['holiday','grades'])->find($id);
        $params['backUrl'] = route('admin.school.index');
        if (isset($school)) {
            $school = $school->toArray();
            $params['school'] = $school;
        }
        return view('admin.pages.school.view', $params);
    }
/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function edit($id) {
        $params['pageTittle'] = "Edit School";
        $school = School::with(['holiday','grades'])->find($id);
        $grade = Grade::all();

        $canteen = User::select('users.id', 'users.first_name', 'users.last_name')->join('users_group', 'users.id', '=', 'users_group.user_id')->where('users_group.group_id', 2)->get();
        if (isset($canteen)) {
            // $canteen = $canteen->toArray();
            $params['canteen'] = $canteen;
        }
        if (isset($school)) {
            $school = $school->toArray();
            $grades_ids = [];
            if(count($school['grades']) > 0){
                $grades_ids = array_column($school['grades'],'grade_id');
            }
            // dd($grades_ids);
            $params['school'] = $school;
            $params['grades_ids'] = $grades_ids;
        }
        if (count($grade) > 0) {
            // $grade = $grade->toArray();
            $params['grade'] = $grade;
        }
        $params['backUrl'] = route('admin.school.index');
        return view('admin.pages.school.put', $params);
    }
/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'canteen_id' => 'required',
            'address' => 'required',
            'block' => 'required',
            'area' => 'required',
            'street' => 'required'
        ]);


        $school['name'] = $request->name;
        $school['canteen_id'] = $request->canteen_id;
        $school['address'] = $request->address;
        $school['block'] = $request->block;
        $school['area'] = $request->area;
        $school['street'] = $request->street;
        $school['calendar_constants'] = $request->calendar_constants;

        School::whereId($id)->update($school);
        SchoolHoliday::where('user_id',$id)->delete();
        SchoolGrade::where('school_id',$id)->delete();
        $temp = $dates = $temp1 = $grades = [];
        if (isset($request->dates) && !empty($request->dates) ) {
            foreach ($request->range_datepicker as $key => $value) {
                if (strpos($value, 'to') !== false) {
                    $exploded_dates = explode(' to ', $value);
                    $temp['user_id'] = $id;
                    $temp['from_date'] = $exploded_dates[0];
                    $temp['to_date'] = $exploded_dates[1];
                } else {
                    $temp['user_id'] = $id;
                    $temp['from_date'] = $value;
                    $temp['to_date'] = $value;
                }
                array_push($dates, $temp);
            }
            SchoolHoliday::insert($dates);
        }

        // school holiday send notification for customer start

        // foreach ($dates as $val) {
        //     $order_data[] = OrdersDate::with(['orders.customer.devices'])->where('date', '>=', $val['from_date'])->where('date', '<=', $val['to_date'])->get();
        // }
        // $token = [];
        // foreach ($order_data as $value1) {
        //     if (isset($value1)) {
        //         foreach ($value1 as $key => $row) {
        //             if (isset($row)) {
        //                 if (isset($row->orders)) {
        //                     if (isset($row->orders->customer)) {
        //                         if (isset($row->orders->customer->devices->token)) {
        //                             // $token[$key] = $row->orders->customer->devices->token;
        //                             array_push($token, $row->orders->customer->devices->token);
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        // $tokens = [];
        // if(!empty($token))
        // {
        //     $token = array_unique($token);
        //     $tokens = array_chunk($token,900);

        //     foreach($tokens as $val)
        //     {
        //         // NotificationHelper::send_bulk($val, $title, $description, $push_type);
        //     }
        // }

        // school holiday send notification for customer end

        if(isset($request->school_grade) && !empty($request->school_grade))
        {
            foreach ($request->school_grade as $gkey => $gvalue) 
            {
                $temp1['school_id'] = $id;
                $temp1['grade_id'] = $gvalue;
                array_push($grades,$temp1);
            }
            SchoolGrade::insert($grades);
        }

        return redirect()->route('admin.school.index')
            ->with('success', 'School updated successfully');
    }
/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function destroy($id) {
        School::whereId($id)->delete();
        return redirect()->route('admin.school.index')
            ->with('success', 'School deleted successfully');
    }

    public function active_deactive_school()
    {
        if($_POST['table'] == 'schools'){
			if($_POST['status'] == 0){
				$status = 1;
			}else{
				$status = 0;
			}
            School::where('id', $_POST['id'])->update(['status' => $status]);
		}
		echo $status;
    }
}