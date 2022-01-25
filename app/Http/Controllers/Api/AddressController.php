<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use App\Http\Requests\Api\AddAddressRequest;

use App\Http\Requests\Api\EditAddressRequest;

use App\Http\Resources\AddressResource;

use App\Models\Address;

use Illuminate\Http\Request;

class AddressController extends Controller

{



    protected $UserAddressService;    



    /**

     * Create a new user instance after a valid registration.

     *

     * @param  array  $data     

     */

    protected function index()

    {

        $user_id = request()->user()->id;

        $address = Address::whereUserId($user_id)->whereDeletedAt(NULL)->paginate(25);

        return (AddressResource::collection($address))->additional([

            'status' => true,

            'message' => 'Address created successfully'

            // 'data' => $address  
        ]);
        return ([

            'status' => true,

            'message' => 'successfully',

            'data' => $address

        ]);

    }



    protected function add(AddAddressRequest $request)

    {        
        // dd('ehere');
        $user_id = request()->user()->id;
        $array=$request->safe()->toArray();
        $array['user_id']=$user_id;
        $address=  Address::create($array);

        return (new AddressResource($address))->additional([

            'status' => true,

            'message' => 'Address created successfully'

        ]);

    }



    protected function edit(EditAddressRequest $request, $id)
    {        

        $user_id = request()->user()->id;

        $address=  Address::where('user_id',$user_id)->findOrFail($id);

        $address->update($request->safe()->toArray());

        return (new AddressResource($address))->additional([

            'status' => true,

            'message' => 'Address updated successfully'

        ]);

    }



    protected function delete($id)
    {
       $address=  Address::where('user_id',request()->user()->id)->findOrFail($id);

       $address->delete();

        return ([

            'status' => true,

            'message' => 'Address deleted successfully'

        ]);

    }

}

