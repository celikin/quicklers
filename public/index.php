<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../public/Utills.php';

$app = new Slim\Slim();

$app->get('/users', function() {
    $users = User::all();
    echo json_encode($users);
});

$app->post('/reg', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $user = new User;
        $user->username = (string)$input->username;
        $user->phone = (string)$input->phone;
        $user->address = (string)$input->address;
        $user->hash = (string) md5(md5((string)$input->phone).mktime());
        $user->banned = false;
        $user->deleted = false;
        $user->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => 'ok'));
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});

$app->post('/auth', function () use ($app){
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $user = new User;
        if($user->where('phone', '=', $input->phone)->count() != 0){
            $user->hash = (string) md5(md5((string)$input->phone).mktime());
        }


    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});

$app->run();
