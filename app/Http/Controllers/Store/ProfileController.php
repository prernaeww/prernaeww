<?php
namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use CommonHelper;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

	public function view() {
        $params['pageTittle'] = "Profile";
        $params['backUrl'] = route('store.dashboard');
        $params['user'] = Auth::user();      
        return view('store.pages.profile',$params);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'profile_picture' => 'nullable',
        ]);

        $user['first_name'] = $request->first_name;
        if (isset($request->profile_picture)) {
                $dir = "images/users";
                $image = CommonHelper::imageUpload($request->profile_picture, $dir);
        		$user['profile_picture'] = $image;
            }
    
        User::whereId($id)->update($user);
    
        return redirect()->route('store.profile')
                        ->with('success','Profile updated successfully');

    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|same:confirm_new_password',
        ]);


        if(Hash::check($request->current_password, Auth::user()->password)){

        	if(!Hash::check($request->new_password, Auth::user()->password)){

	        	$user['password'] = Hash::make($request->new_password);
	        	User::whereId($id)->update($user);

	        	// Auth::logout();
	    
	        	return redirect()->route('store.profile')
	                        ->with('success','Password has been updated successfully');
	        }else{
	        	return redirect()->back()
	                        ->with('error','Current password and New password must be different');
	        }

        }else{
        	return redirect()->back()
                        ->with('error','Current password is wrong');
        }

    

    }
}