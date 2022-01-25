<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemConfig;

class DocumentController extends Controller {

    public function education_outreach(Request $request)
    {             
        $data['data'] = SystemConfig::where('path','education_out_reach')->value('value');
        
        return view('doc_pages.education_outreach', $data);
    }

    public function about_us(Request $request)
    {     
        return view('doc_pages.about_us');
    }

    public function contact_us(Request $request)
    {     
        
        return view('doc_pages.contact_us');
    }  

    public function internet_based_ads(Request $request)
    {
        $data['data'] = SystemConfig::where('path','interest_bases_ads')->value('value');
        return view('doc_pages.internet_based_ads', $data);
    }

    public function privacy_notice(Request $request)
    {
        $data['data'] = SystemConfig::where('path','privacy_policy')->value('value');
        return view('doc_pages.privacy_notice', $data);
    }

    public function term_of_service(Request $request)
    {
        $data['data'] = SystemConfig::where('path','terms_conditions')->value('value');
        return view('doc_pages.term_of_service', $data);
    }





}

