<?php



namespace App\Traits;



trait ApiResponser {



    protected function successResponse($data, $message = null) {

        return response([

            'status' => true,

            'message' => $message,

            'data' => $data,

        ]);

    }



    protected function errorResponse($message = null, $validation = false) {

        if ($validation) {

            return response([

                'status' => false,

                'message' => $message->first(),

                //'data' => [],

            ]);

        }

        return response([

            'status' => false,

            'message' => $message,

            //'data' => [],

        ]);

    }



}