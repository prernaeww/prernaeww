<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Grade;
use DataTables;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if ($request->ajax())
       {
            $data = Grade::select('*');
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('action', function ($row)
            {
               $btn = '<a href="'.route('admin.grade.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
               $btn .= '<a href="'.route('admin.grade.destroy', $row['id']).'" data-url="grade" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
               return $btn;
            })
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'name', 'name' => 'name','title' => __("Name")],
               ['data' => 'slug', 'name' => 'slug','title' => __("Slug")],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.grade.index');
           $params['dateTableTitle'] = "Grades";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.grade.create');
           return view('admin.pages.grade.index',$params);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['pageTittle'] = "Add Grade";
        $params['backUrl'] = route('admin.grade.index');
        return view('admin.pages.grade.post',$params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'slug' => 'required|max:50',
        ]);

        $grade = Grade::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        // redirect
        return redirect()->route('admin.grade.index')->with('success','Grade created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $params['pageTittle'] = "View Grade";
        $grade = Grade::all();
        $params['grade'] = $grade->toArray();
        $params['backUrl'] = route('admin.grade.index');
        return view('admin.pages.grade.view',$params);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $params['pageTittle'] = "Edit Grade";
        $params['grade'] = Grade::find($id);

        $params['backUrl'] = route('admin.grade.index');
        return view('admin.pages.grade.put',$params);
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
        $request->validate([
            'name' => 'required|max:50',
            'slug' => 'required|max:50',
        ]);

        $grade['name'] = $request->name;
        $grade['slug'] = $request->slug;
    
        Grade::whereId($id)->update($grade);
    
        return redirect()->route('admin.grade.index')
                        ->with('success','Grade updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Grade::whereId($id)->delete();
        return redirect()->route('admin.grade.index')
                        ->with('success','Grade deleted successfully');

    }

}
