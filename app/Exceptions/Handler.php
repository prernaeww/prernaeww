<?php



namespace App\Exceptions;



use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// use Illuminate\Auth\AuthenticationException;

use Throwable;

use \Illuminate\Validation\ValidationException;


class Handler extends ExceptionHandler {

    /**

     * A list of the exception types that are not reported.

     *

     * @var array

     */

    protected $dontReport = [

        //

    ];



    /**

     * A list of the inputs that are never flashed for validation exceptions.

     *

     * @var array

     */

    protected $dontFlash = [

        'current_password',

        'password',

        'password_confirmation',

    ];



    /**

     * Register the exception handling callbacks for the application.

     *

     * @return void

     */

    public function register() 
    {

        $this->reportable(function (Throwable $e) {

        });        

        $this->renderable(function (NotFoundHttpException $e, $request) {

            //handles the 404 error messages

            if ($request->is('api/*')) {

                return response()->json([

                    'status' => false,

                    'message' => 'Record/Route Not Found.'

                ], 200);

            }

        });



        $this->renderable(function (ValidationException $e, $request) {
            
            //handles the validation error messages for only apis

            if ($request->is('api/*')) {

                $validate_array = $e->errors();

                $error = [];



                if (empty($validate_array)) {

                    return $error;

                }



                foreach ($validate_array as $item) {

                    array_push($error, $item[0]);

                }



                $errors = implode("\n",  $error);



                return response()->json([

                    'status' => false,

                    'message' => $errors

                ]);

            }

        });

    }



    // public function render($request, Throwable $e) {        
    //         // print_r($e->getStatusCode());exit;

    //     if(str_contains($request->path(),'api')){

    //         if($e->getMessage() == "Unauthenticated.")

    //         {

    //             return response(['status'=>false,'logout'=>true,'error'=>$e->getMessage()],200);      

    //         }

    //       return response(['status'=>false,'error'=>$e->getMessage()],405);

    //     }       

    //     return parent::render($request, $e);

    // }





}

