<?php

require __DIR__.'/../vendor/autoload.php';

$app = new Slim\Slim();

// Чекаем авторизацию
// input: user_id, hash
function CheckAuth($user_id, $hash){
    $result = false;
    $user = User::find($user_id);
    if(!($user->hash == $hash)){
        echo json_encode(array('status' => false, 'error'=>array('code' => 403, 'msg'=> 'Ошибка авторизации!')));
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
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
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
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
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
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
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
        CheckAuth($input->user_id, $input->hash);

        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));

    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
    }
});

//Добавление категории
// input: alias, desc
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
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
    }
});

//Добавление подкатегории
// input: alias, desc, category_id
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
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
    }
});

//Вывод категорий
$app->get('/categories', function() {
    $categories = Category::with('subcategory')->get();
    echo json_encode(array('status' => true, 'data'=>$categories,'error'=>[]));
});


//Добавление заявки
// input: title, desc, deadline, city_id, subcategory_id
// output: $bid    
$app->post('/bid/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        // Проверка авторизации
//        РАССКОМЕНТИТЬ!
//        CheckAuth($input->user_id, $input->hash);

        $bid = new Bid;
        $bid->title = (string)$input->title;
        $bid->desc = (string)$input->desc;
        $bid->deadline = (integer)$input->deadline;
        $bid->status = 1;
        $bid->city_id = (integer)$input->city_id;
        $bid->user_id = null;
//        $bid->user_id = $input->id; // ДОБАВИЛИ ЗАНЕСЕНИЕ ЮЗЕРА
        $bid->performer_id = null;
        $bid->subcategory_id = (integer)$input->subcategory_id;
        $bid->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true, 'bid' => $bid));
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
    }
});

//Вывод заявок
//input: count
$app->get('/bids/:count', function($count) {
    $bids = Bid::all()->sortBy('created_at')->reverse()->where('status','1')->take($count);
    echo json_encode(array('status' => true, 'data'=>$bids,'error'=>[]));


});
//Вывод заявки по ИД
//input: id
$app->get('/bid/:id', function($id) {
    $bids = Bid::where('id',$id)->first();
    echo json_encode(array('status' => true, 'data'=>$bids,'error'=>[]));
    //echo json_encode($bids);

});

// Сделать Пользователя Исполнителем
// input: user_id
$app->post('/performer/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        $performer = new Performer;
        $performer->user_id = (integer)$input->user_id;
        $performer->desc = (string)$input->desc;
        $performer->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
    }
});

// Добавление кандидата в стэк кандидатов
// input: bid_id, user_id == performer_id, cost - цена, предлагаемая исполнителем
$app->post('/bid/stack/performer/add',  function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        // Проверка авторизации
        CheckAuth($input->user_id, $input->hash);

        $bidstack = new PerformersBidsStack;
        $bidstack->cost = (integer)$input->cost;
        $bidstack->performer_id = (integer)$input->performer_id;
        $bidstack->bid_id = (integer)$input->bid_id;

        $bidstack->save();

        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
    }
});

// Сделать Кандидата Исполнителем конкретного задания
// input: bid_id, performer_id, cost
$app->post('/bid/performer/add',  function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        // Проверка авторизации
        CheckAuth($input->user_id, $input->hash);

        $bid = Bid::where('id', $input->bid_id)->first();
        $bid->performer_id = $input->performer_id;
//        ПО-ХОРОШЕМУ нужно добавлять еще и цену от исполнителя
        $bid->cost = $input->cost;
        $bid->save();

        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false,'error'=>array( 'code' => 400, 'msg'=>$e->getMessage())));
    }
});

//Вывод кандидатов для заявки
$app->get('/bid/performers/stacks/:bid_id', function($bid_id) {
    $performersbidsstack = PerformersBidsStack::where('bid_id',$bid_id)->get();
    //echo json_encode($performersbidsstacks);
    $data=[];
    foreach($performersbidsstack as $arr) {
        $performer = Performer::where('id','=', $arr->performer_id)->first();
        $user = User::where('id','=',$performer->user_id)->first();
        $data[]=$user;
    }
    //echo json_encode($data);
    echo json_encode(array('status' => true, 'data'=>$data,'error'=>[]));
});

// Занести Пользователя в Исполнители
// input: user_id
$app->post('/performer/add', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);

        $performer = new Performer;
        $performer->user_id = (string)$input->user_id;
        $performer->save();
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array('status' => true));
    } catch (Exception $e) {
        $app->response()->status(400);
        echo json_encode(array('status' => false, 'error'=>array('code' => 400, 'msg'=>$e->getMessage())));
    }
});


$app->run();