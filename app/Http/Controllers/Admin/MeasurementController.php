<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Measurement;
use App\Models\Product;
use DataTables;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Measurement::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.measurement.edit', $row['id']) . '" class="mr-2"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="' . route('admin.measurement.destroy', $row['id']) . '" data-url="measurement" data-id="' . $row["id"] . '" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="' . csrf_token() . '" ><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->make(true);
        } else {
            $columns = [
                ['data' => 'id', 'name' => 'id', 'title' => "Id"],
                ['data' => 'name', 'name' => 'name', 'title' => __("Name")],
                ['data' => 'action', 'name' => 'action', 'title' => "Action", 'searchable' => false, 'orderable' => false]
            ];
            $params['dateTableFields'] = $columns;
            $params['dateTableUrl'] = route('admin.measurement.index');
            $params['dateTableTitle'] = "Measurement";
            $params['dataTableId'] = time();
            $params['addUrl'] = route('admin.measurement.create');
            return view('admin.pages.measurement.index', $params);
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
        $params['pageTittle'] = "Add Measurement";
        $params['backUrl'] = route('admin.measurement.index');

        return view('admin.pages.measurement.post', $params);
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


        $user = Measurement::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.measurement.index')->with('success', 'Measurement created successfully.');
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
        $params['pageTittle'] = "Edit Measurement";
        $params['measurement'] = Measurement::find($id);
        $params['backUrl'] = route('admin.measurement.index');

        return view('admin.pages.measurement.put', $params);
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
            'name' => 'required|alpha|min:2|max:15',
        ]);


        $measurement['name'] = $request->name;


        Measurement::whereId($id)->update($measurement);

        return redirect()->route('admin.measurement.index')
            ->with('success', 'Measurement updated successfully');
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
        $products = Product::whereMeasurementId($id)->get();
        if (isset($products) && !empty($products) && count($products) > 0) {
            $data['status'] = false;
            $data['message'] = 'You can not delete as Product(s) already exist for this measurement';
        } else {
            Measurement::whereId($id)->delete();
            $data['status'] = TRUE;
            $data['message'] = 'Deleted';
        }
        echo json_encode($data);

        // return redirect()->route('admin.measurement.index')
        //                 ->with('success','Measurement deleted successfully');
    }
}