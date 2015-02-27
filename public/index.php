<?php

require __DIR__.'/../vendor/autoload.php';

$app = new Slim\Slim();

$app->get('/users', function() {
    $users = User::all();
    echo json_encode($users);
});

$app->post('/user/auth/check', function () use ($app){
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $result = false;
        if(User::where('phone', '=', $input->phone)->count() != 0){
            $result = true;
        }
        return $result;

    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});

$app->post('/user/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $user = new User;
        $user->username = (string)$input->username;
        $user->phone = (string)$input->phone;
        $user->address = (string)$input->address;
        $user->hash = (string) md5((string)$input->phone);
        $user->banned = false;
        $user->deleted = false;
        $user->city_id = (integer)$input->city_id;
        $user->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => 'ok'));
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});

// Авторизация пользователя
$app->post('/user/auth/login', function () use ($app){
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

//Добавление категории
$app->post('/category/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $category = new Category;
        $category->alias = (string)$input->alias;
        $category->desc = (string)$input->desc;
        $category->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => 'ok'));
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});

//Добавление подкатегории
$app->post('/category/sub/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $subcategory = new SubCategory;
        $subcategory->alias = (string)$input->alias;
        $subcategory->desc = (string)$input->desc;
        $subcategory->category_id = (string)$input->category_id;
        $subcategory->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => 'ok'));
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});

$app->run();
