<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Issue;
use DataTables;
use App\Models\OrdersDate;

class IssueController extends Controller
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
            $data = Issue::select('*');
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('action', function ($row)
            {
               $btn = '<a href="'.route('admin.issue.edit',$row['id']).'" class="mr-2"><i class="fa fa-edit"></i></a>';
               $btn .= '<a href="'.route('admin.issue.destroy', $row['id']).'" data-url="issue" data-id="'.$row["id"].'" data-popup="tooltip" onclick="delete_notiflix(this);return false;" data-token="'.csrf_token().'" ><i class="fa fa-trash"></i></a>';
               return $btn;
            })
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'name', 'name' => 'name','title' => __("Name")],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.issue.index');
           $params['dateTableTitle'] = "Issue Subjects";
           $params['dataTableId'] = time();
           $params['addUrl'] = route('admin.issue.create');
           return view('admin.pages.issue.index',$params);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['pageTittle'] = "Add Issue";
        $params['backUrl'] = route('admin.issue.index');
        return view('admin.pages.issue.post',$params);
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
            'name' => 'required|min:2|max:50',
        ]);

        $issue = Issue::create([
            'name' => $request->name,
        ]);

        // redirect
        return redirect()->route('admin.issue.index')->with('success','Issue created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $params['pageTittle'] = "View Issue";
        $issue = Issue::all();
        $params['issue'] = $issue->toArray();
        $params['backUrl'] = route('admin.issue.index');
        return view('admin.pages.issue.view',$params);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $params['pageTittle'] = "Edit Issue";
        $params['issue'] = Issue::find($id);

        $params['backUrl'] = route('admin.issue.index');
        return view('admin.pages.issue.put',$params);
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
            'name' => 'required|min:2|max:50',
        ]);

        $issue['name'] = $request->name;
    
        Issue::whereId($id)->update($issue);
    
        return redirect()->route('admin.issue.index')
                        ->with('success','Issue updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Issue::whereId($id)->delete();
        return redirect()->route('admin.issue.index')
                        ->with('success','Issue deleted successfully');

    }

    public function reported_issue_index(Request $request)
    {
       if ($request->ajax())
       {
            $data =OrdersDate::select('*')->where('status',2);
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('action', function ($row)
            {
                // $btn ='<button  type="button" class="btn btn-primary btn-xs waves-effect waves-light" data-id="'.$row['id'].'" data-issue="'.$row['issue'].'" data-description="'.$row['description'].'" data-toggle="modal" data-target=".bs-example-modal-center" id="view">View</button>';
                $btn = '<a  data-id="'.$row['id'].'" data-issue="'.$row['issue'].'" data-description="'.$row['description'].'" data-toggle="modal" data-target=".bs-example-modal-center" id="view" class="mr-2"><i class="fa fa-eye"></i></a>';
                return $btn;
            })
            ->make(true);
       }
       else
       {
           $columns = [
               ['data' => 'id','name' => 'id','title' => "Id"], 
               ['data' => 'issuer_name', 'name' => 'issuer_name','title' => __("Issuer Name"),'searchable'=>false,'orderable'=>false],
               ['data' => 'date', 'name' => 'date','title' => __("Date")],
               ['data' => 'day', 'name' => 'day','title' => __("Day")],
               ['data' => 'meal_name', 'name' => 'meal_name','title' => __("Meal Name"),'searchable'=>false,'orderable'=>false],
               ['data' => 'action', 'name' => 'action','title' => "Action",'searchable'=>false,'orderable'=>false]];
           $params['dateTableFields'] = $columns;
           $params['dateTableUrl'] = route('admin.reported.issue');
           $params['dateTableTitle'] = "Reported Issues";
           $params['dataTableId'] = time();
           $params['addUrl'] = '';
           return view('admin.pages.reported.index',$params);
       }
    }

}
