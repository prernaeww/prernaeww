<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\FavoriteStoreRequest;

use Illuminate\Http\Request;

use App\Models\FavoriteStore;
use App\Models\User;

use App\Traits\ApiResponser;

use Illuminate\Support\Facades\Validator;

class FavoriteStoreController extends Controller

{

    use ApiResponser;
    /**

     *  makes a service provider favourite or removes him from favourite

     */

    public function add(Request $request)

    {


        $validator = Validator::make(request()->all(), [            

            'store_id' => 'required',

        ]);

        if (!$validator->fails()) {

            if (FavoriteStore::whereStoreId($request->store_id)->whereUserId($request->user()->id)->first()) {

                return response([

                    'status' => false,

                    'message' => 'store already exits',

                ], 200);

            } else

            {

                $favorite_store = new FavoriteStore;

                $favorite_store->user_id = $request->user()->id;

                $favorite_store->store_id = $request->store_id;

                $favorite_store->save();

                if(isset($favorite_store)){

                    return response([

                        'status' => true,

                        'message' => 'store add successfully',

                    ], 200);

                }

            }

        }

        return $this->errorResponse($validator->messages(), true);

    }

    /**

     *  returns the favorites of the customer

     */

    public function remove(Request $request)

    {

        $validator = Validator::make(request()->all(), [            

            'store_id' => 'required',

        ]);

        if (!$validator->fails()) {

            if (FavoriteStore::whereStoreId($request->store_id)->whereUserId($request->user()->id)->first()) {            

                FavoriteStore::whereStoreId($request->store_id)->whereUserId($request->user()->id)->delete();

                

                return response([

                    'status' => true,

                    'message' => 'store remove successfully',

                ], 200);

            } else

            {

                return response([

                    'status' => false,

                    'message' => 'store not found in favorite list',

                ], 200);

            }    

        }

        return $this->errorResponse($validator->messages(), true);

    }

}
