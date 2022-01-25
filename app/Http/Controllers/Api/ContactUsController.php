<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ContactUs;
use App\Models\User;

use CommonHelper;

use Validator;



class ContactUsController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {       

        //

         $validator = Validator::make($request->all(),[            

            'email' => 'required',

            'message' => 'required',
            'user_id' => '',

            // 'image' => 'required',

        ]);



        if($validator->fails()){

           return response([

                'status' => false,

                'message' => $validator->errors()->all(),

            ], 200);

        }


        $user_id = isset($request->user_id) ? $request->user_id : null;
        if ($user_id) {
            
            if (User::find($user_id)) {
                $contact = new ContactUs;

                // $contact->user_id = request()->user()->id;
                $contact->user_id = $user_id;

                $contact->email = $request->email;

                $contact->message = $request->message;

                if (isset($request->image)) {

                    $dir = "images/contact_us";

                    $image = CommonHelper::docsUpload($request->image, $dir);

                    $contact->document = $image;

                }        

                $contact->save();             
            } else
            {
                return response([

                    'status' => false,
                    'message' => 'User not found',

                ], 200);
            }
        } else
        {
            $contact = new ContactUs;

            // $contact->user_id = request()->user()->id;
            $contact->user_id = 0;

            $contact->email = $request->email;

            $contact->message = $request->message;

            if (isset($request->image)) {

                $dir = "images/contact_us";

                $image = CommonHelper::docsUpload($request->image, $dir);

                $contact->document = $image;

            }        

            $contact->save();  
        }

        if(isset($contact)){

            return response([

                'status' => true,
                'message' => 'Information saved successfully. Administrator will contact you soon.',

            ], 200);

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

      

        $validator = Validator::make($request->all(),[

            // 'user_id' => 'required',

            'email' => 'required',

            'message' => 'required',

            'image' => 'required',

             

        ]);



        if($validator->fails()){

           return response([

                'status' => false,

                'message' => $validator->errors()->all(),

            ], 200);

        }







        $contact = new ContactUs;

        $contact->user_id = $request->user()->id;

        $contact->email = $request->email;

        $contact->message = $request->message;

        if ($request->file('image')) 

        {

            $imagename = rand().'.'.$request->image->extension();           

            $request->image->move(public_path('assets/images/contact'), $imagename);

        }

        $contact->image = $imagename;

        $contact->save(); 



        



        if(isset($contact)){

            return response([

                'status' => true,

                'data' => $contact,

                'message' => 'Contact Successfully Save Data',

            ], 200);

        }

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

    }

}

