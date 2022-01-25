<?php

namespace App\Traits;
use Illuminate\Support\Facades\Http;
use Session;

trait ApiWebsite {

    protected function api_call($api, $method, $variables = array()) {
        $base_url = config("app.url");
        $endpoint = $base_url.'api/'.$api;


        $token = Session::get('token');
        //$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5NGJmNDlmYy02NGQ4LTQyMDAtOWQzOC01NGIxYjY2YmNkNjciLCJqdGkiOiI5MDE5NzFhYTY3MjU4Mjg4ODVmNDE5YTlmZjg0NjkyZTIyZWE1NDkxYzEyODFhNGQ3ZDI1ODQ0MjU3ZjBkMTJhNThjY2IzNGFkNmM4NjQ0NyIsImlhdCI6MTY0MTY0MDU2NC45ODg2NjMsIm5iZiI6MTY0MTY0MDU2NC45ODg2NjcsImV4cCI6MTY3MzE3NjU2NC45ODY2MjEsInN1YiI6IjIiLCJzY29wZXMiOltdfQ.Sie8WFmtC5JhOrMvKysWPx3kuCnlCBGqHmi8cMbHfIsDtDptWNe3qlegoeSVaufJVZYndODgk0yUw7gHVRpkuhWzLafwd99DwXOtESJpg9x-26PUqTwBHQy_P-K3D7sFnCQDp4Rlo88bWGUoSTtOfKEQ_frYVglF-3vjhRJS94lfa0s_PzyBOO3Gd1oQTLEvUoX0fCu-HD-aGeEQSNIQH6dDSBJiHTollt4lPFUIN4dBd2HguaPMFlpiuJnzp5OBdD8Y7K2v4uKTL_cqO0_3VCUdIp10GcATST7pCSybiI6ldjoB7Xa4hQvLX8dQi1Io2HYov4uU7nVRB29Qx5uR8iht9fKpeC5HlGiWSTyC38ODId-W21KiimL554zOK8Sixzv5lUDaykOuOYWeBiXslwHoh5SqGhtmUG-kbnfCQCYa6mJ1SQCACItIg9rdh9hUww5WZ5wizP_fQ5wrFuYA4lYkRLqk5hUQMge87GwH7yVgwyB_7aWo62fAoK78XDdq7zaGoVXVKi7IhFJIjS8lIOS7KqTux33UYELiyHNawDhLo8SdzG4KpZ3GSCSDEhMHXDnaP-YFJELd3yrf7QFl4-pmSg7lh0J_zDfdGb7sSkVIRgfmVh9G3Y75CFIZ4_lrlV4wPgSuSk525WleY2RZDRdcv8MN-Ji-fAVB8bCRXIM';

        if($method == 1){
            $response = Http::accept('application/json')->withToken($token)->get($endpoint, $variables);
        }else{
            $response = Http::accept('application/json')->withToken($token)->post($endpoint, $variables);
        }

        return $response->json();

    }


}
