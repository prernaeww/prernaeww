<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Issue;
use DataTables;
use App\Models\User;
use App\Models\SystemConfig;
use NotificationHelper;

class CMSController extends Controller
{        

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function terms_conditions(Request $request)
    {
        $type_val = request()->input('type');        
        if ($type_val == 'terms_conditions')
        {
            $update['value'] = request()->input('description');
            SystemConfig::where('path','terms_conditions')->update($update);
            return redirect()->route('admin.terms_conditions')->with('success', 'terms conditions updated successfully.');
        }

        $params['type'] = "Terms Conditions";
        $params['type_val'] = "terms_conditions";
        $params['pageTittle'] = "Terms Conditions";
        // $params['backUrl'] = route('admin.canteen.index');
        $params['breadcrumb_name'] = 'all';
        $params['data'] = SystemConfig::where('path','terms_conditions')->value('value');
        return view('admin.pages.cms.index', $params);
    }

    public function privacy_policy(Request $request)
    {
        $type_val = request()->input('type');
        if ($type_val == 'privacy_policy')
        {
            $update['value'] = request()->input('description');
            SystemConfig::where('path','privacy_policy')->update($update);    
            return redirect()->route('admin.privacy_policy')->with('success', 'privacy policy updated successfully.');
        }

        $params['type'] = "Privacy Policy";
        $params['type_val'] = "privacy_policy";
        $params['pageTittle'] = "Privacy Policy";        
        $params['breadcrumb_name'] = 'all';
        $params['data'] = SystemConfig::where('path','privacy_policy')->value('value');
        return view('admin.pages.cms.index', $params);
    }

    public function interest_bases_ads(Request $request)
    {
        $type_val = request()->input('type');
        if ($type_val == 'interest_bases_ads')
        {
            $update['value'] = request()->input('description');
            SystemConfig::where('path','interest_bases_ads')->update($update);    
            return redirect()->route('admin.interest_bases_ads')->with('success', 'Interest bases ads updated successfully.');
        }

        $params['type'] = "Interest bases ads";
        $params['type_val'] = "interest_bases_ads";
        $params['pageTittle'] = "Interest bases ads";        
        $params['breadcrumb_name'] = 'all';
        $params['data'] = SystemConfig::where('path','interest_bases_ads')->value('value');
        return view('admin.pages.cms.index', $params);
    }

    public function education_out_reach(Request $request)
    {
        $type_val = request()->input('type');
        if ($type_val == 'education_out_reach')
        {
            $update['value'] = request()->input('description');
            SystemConfig::where('path','education_out_reach')->update($update);    
            return redirect()->route('admin.education_out_reach')->with('success', 'Education out reach updated successfully.');
        }

        $params['type'] = "Education out reach";
        $params['type_val'] = "education_out_reach";
        $params['pageTittle'] = "Education out reach";        
        $params['breadcrumb_name'] = 'all';
        $params['data'] = SystemConfig::where('path','education_out_reach')->value('value');
        return view('admin.pages.cms.index', $params);
    }   
}
