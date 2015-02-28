<?php

require __DIR__.'/../vendor/autoload.php';

$app = new Slim\Slim();

$app->get('/users', function() {
    $users = User::all();
    echo json_encode($users);
});

// Проверяем наличие пользователя
// input: phone
$app->post('/users/check', function () use ($app){
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $result['status'] = false;
        if(User::where('phone', '=', $input->phone)->count() != 0){
            $result['status'] = true;
        }
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode($result);

    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});

// Добавление пользователя
// input: phone, username, address
$app->post('/users/add', function () use ($app) {
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
        $user->city_id = 1;
        $user->save();

        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
        print_r($input);
    }
});

// Авторизация пользователя (Который есть в базе!)
// input: phone
$app->post('/auth/login', function () use ($app){
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        // Типа если юзер правильный, даем ему хеш (в данном случае, юзер априори молодец)
        User::where('phone', '=', $input->phone)
            ->update(array('hash' => (string) md5(md5((string)$input->phone).mktime())));

        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));

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
        echo json_encode(array('status' => true));
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
        echo json_encode(array('status' => true));
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});

//Вывод категорий
$app->get('/categories', function() {
    //$categories = Category::all();
    $categories = Category::with('subcategory')->get();
    echo json_encode($categories);
});
//Вывод подкатегорий
$app->get('/categories/sub', function() {
    $subcategories = SubCategory::all();
    echo json_encode($subcategories);
});

$app->run();
