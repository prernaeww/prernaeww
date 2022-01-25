<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig;
use App\Models\ApplicationConfig;
use Illuminate\Http\Request;
use CommonHelper;

class SystemConfigController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $data['title'] = __('System Configuration');
        $data['backup'] =ApplicationConfig::all();

        return view('admin.pages.system_config', $data);
    }

    public function app() {
        $data['title'] = __('Application Configuration');
        $data['app'] =ApplicationConfig::all();
        // echo $data['app'][0]->force_update;exit;
        return view('admin.pages.app_config', $data);
    }

    public function submit_app(Request $request)
    {
        // $request = $request;

        $android['version']=$request['android_version'];
        $android['force_update']=isset($request['android_update'])?1:0;
        $android['maintenance']=isset($request['maintenance'])?1:0;
        ApplicationConfig::where('type','android')->update($android);
        $ios['version']=$request['ios_version'];
        $ios['force_update']=isset($request['ios_update'])?1:0;
        $ios['maintenance']=isset($request['maintenance'])?1:0;
        ApplicationConfig::where('type','ios')->update($ios);

        return redirect()->route('admin.app.config')->with('success', __('App Configuration details updated successfully.'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemConfig  $systemConfig
     * @return \Illuminate\Http\Response
     */
    public function show(SystemConfig $systemConfig) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemConfig  $systemConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemConfig $systemConfig) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemConfig  $systemConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {

        // dd($request->all());
        $params = $request->all();
          
        unset($params['_token']);
        if(!empty($params))
        {
            foreach ($params as $key => $value) {
                $key_array[$key] = $value;
            }
            CommonHelper::ConfigSet($key_array);
        }
        return redirect()->route('admin.system.config')->with('success','System Configuration successfully saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemConfig  $systemConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemConfig $systemConfig) {
        //
    }
}
