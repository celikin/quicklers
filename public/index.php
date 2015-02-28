<?php

require __DIR__.'/../vendor/autoload.php';

$app = new Slim\Slim();

// Чекаем авторизацию
// input: id, hash
function CheckAuth($id, $hash){
    $result = false;
    $user = User::find($id);
    if(!($user->hash == $hash)){
        echo json_encode(array('status' => false, 'code' => 403, 'msg'=> 'Ошибка авторизации!'));
        die();
    }
}

// Получаем всех поль зователей
$app->get('/users', function() {
    $users = User::all();
    echo json_encode($users);
});


// Проверяем наличие пользователя
// input: phone
$app->post('/user/check', function () use ($app){
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
        echo json_encode(array('status' => false, 'code' => 400, 'msg'=>$e->getMessage()));
    }
});

// Добавление пользователя
// input: phone, username, address
$app->post('/user/add', function () use ($app) {
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
        echo json_encode(array('status' => true, 'id' => $user->id, 'hash' => $user->hash));
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'code' => 400, 'msg'=>$e->getMessage()));
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
        $user = User::where('phone', '=', $input->phone)->first();
        $user->hash = (string) md5(md5((string)$input->phone).mktime());
        $user->save();

        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');

        echo json_encode(array('status' => true, 'id' => $user->id, 'hash' => $user->hash));

    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'code' => 400, 'msg'=>$e->getMessage()));
    }
});

// Проверка авторизации (исли вдруг понадобится)
// input: id, hash
$app->post('/auth/check', function () use ($app){
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        // Проверка авторизации
        CheckAuth($input->id, $input->hash);

        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));

    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'code' => 400, 'msg'=>$e->getMessage()));
    }
});

//Добавление категории
// input: alias, desc
$app->post('/category/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        // Проверка авторизации
        CheckAuth($input->id, $input->hash);

        $category = new Category;
        $category->alias = (string)$input->alias;
        $category->desc = (string)$input->desc;
        $category->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'code' => 400, 'msg'=>$e->getMessage()));
    }
});

//Добавление подкатегории
// input: alias, desc, category_id
$app->post('/category/sub/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        // Проверка авторизации
        CheckAuth($input->id, $input->hash);

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
        echo json_encode(array('status' => false, 'code' => 400, 'msg'=>$e->getMessage()));
    }
});

//Вывод категорий
$app->get('/categories', function() {
    $categories = Category::with('subcategory')->get();
    echo json_encode($categories);
});


//Добавление заявки
// input: title, desc, deadline, city_id, subcategory_id
// output: $bid    
$app->post('/bid/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $bid = new Bid;
        $bid->title = (string)$input->title;
        $bid->desc = (string)$input->desc;
        $bid->deadline = (integer)$input->deadline;
        $bid->status = 1;
        $bid->city_id = (integer)$input->city_id;
        $bid->user_id = null;
        $bid->performer_id = null;
        $bid->subcategory_id = (integer)$input->subcategory_id;
        $bid->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true, 'bid' => $bid));
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'code' => 400, 'msg'=>$e->getMessage()));
    }
});

//Вывод заявок
//input: count
$app->get('/bids/:count', function($count) {
    $bids = Bid::all()->sortBy('created_at')->reverse()->where('status','1')->take($count);
    echo json_encode($bids);

});
//Вывод заявки по ИД
//input: id
$app->get('/bid/:id', function($id) {
    $bids = Bid::where('id',$id)->first();
    echo json_encode($bids);

});

// Сделать Кандидата Исполнителем задания
// input: bid_id, performer_id
$app->post('/bid/performer/add',  function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        $bid = Bid::where('id', $input->bid_id)->first();
        $bid->performer_id = $input->performer_id;
        $bid->save();

        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true);
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'code' => 400, 'msg'=>$e->getMessage()));
    }
});

$app->run();