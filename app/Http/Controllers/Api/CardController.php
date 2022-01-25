<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data     
     */

    protected function index()
    {

        $user_id = request()->user()->id;
        $card = Card::whereUserId($user_id)->orderBy('id','desc')->get();

        return response([
            'status' => true,
            'message' => '',
            'data' => $card,
        ]);

    }

    protected function delete($id)
    {
       $card = Card::where('user_id',request()->user()->id)->where('id', $id)->first();
       if($card){
            $card->delete();
            return ([
                'status' => true,
                'message' => 'Card deleted successfully'
            ]);
       }else{
            return ([
                'status' => false,
                'message' => 'Card not found'
            ]);
       }
       
    }

}

