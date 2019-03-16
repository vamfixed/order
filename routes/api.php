<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/dinein/save/', function() {
    $this->middleware('auth');
    $data = Input::all();
    //SELECT `id`, `bill_no`, `order_date`, `total_amount`, 
    //`paid_amount`, `change_amount`, `status`, `tables_id`, 
    //`created_at`, `updated_at`, `users_id` FROM `orders` WHERE 1



    $order = new App\Order;
    $order->total_amount = array_sum(array_map(function($v) {
        return floatval($v['total']);
    }, $data['items']));
    $order->status = 'PENDING';
    $order->tables_id = $data['table']['id'];
    $order->users_id = Auth::id();
    $order->save();
    
    foreach ($data['items'] as $item) {
        // save
        $oItem = new App\OrderItem;
        $oItem->products_id = $item['product_id'];
        $oItem->orders_id = $order->id;
        $oItem->qty = $item['quantity'];
        $oItem->rate = $item['price'];
        $oItem->amount = $item['total'];
        $oItem->save();
    }

    $table = App\Table::findOrFail($data['table']['id']);
    $table->available = '0';
    $table->save();
    

});

Route::get('/dinein/tables', function () {
    return [
        'tables' => DB::select('SELECT a.*, (SELECT x.id FROM orders AS x WHERE x.tables_id = a.id AND x.paid_amount IS NULL LIMIT 1) as order_id, (SELECT x.total_amount FROM orders AS x WHERE x.tables_id = a.id AND x.paid_amount IS NULL LIMIT 1) as total_amount, (SELECT x.status FROM orders AS x WHERE x.tables_id = a.id AND x.paid_amount IS NULL LIMIT 1) as order_status FROM tables AS a'),
        'products' => App\Product::where('active', '1')->get()
    ];
});

Route::get('/order/get/', function() {
    return App\Order::where('status', '=', 'PENDING')->with(['orderItems', 'table', 'orderItems.products'])->get();
});

Route::get('/order/serve/{id}', function($id) {
   $order = App\Order::findOrFail($id);
   $order->status = 'SERVED';
   $order->save();
});

Route::get('/dinein/orders/{id}', function ($id) {
    return App\OrderItem::where('orders_id', $id)->with('products')->get();
});

Route::post('/dinein/payment/', function() {
    $data = Input::all();

    $order = App\Order::findOrFail($data["order_id"]);
    $order->paid_amount = $data['cash'];
    $order->change_amount = $data['change'];
    $order->status = "PAID";
    $order->save();

    $table = App\Table::findOrFail($data["table_id"]);
    $table->available = '1';
    $table->save();
    return $data;
});

Route::get('/report/daily/{monthYear}', function ($monthYear) {
    $m = explode('-', $monthYear);
    $year = $m[1];
    $month = $m[0];
    $data = DB::select("SELECT SUBSTRING(order_date, 1, 10) as date, SUM(total_amount) as total FROM orders WHERE status = 'PAID' AND YEAR(order_date) = '$year' AND MONTH(order_date) = '$month' GROUP BY SUBSTRING(order_date, 1, 10)");
    return $data;
});

Route::get('/report/monthly/{year}', function ($year) {
    $data = DB::select("SELECT DATE_FORMAT(order_date, '%M') as month, SUM(total_amount) as total FROM orders WHERE status = 'PAID' AND YEAR(order_date) = '$year' GROUP BY DATE_FORMAT(order_date, '%M')");
    return $data;
});

Route::post('/report/getYear', function() {
    $data = DB::select("SELECT YEAR(order_date) as year FROM orders GROUP BY YEAR(order_date)");
    return collect($data)->map(function($row) {
        return $row->year;
    });
});

Route::post('/report/getMonthYear', function() {
    $data = DB::select("SELECT SUBSTRING(order_date, 1, 7) as date FROM orders GROUP BY SUBSTRING(order_date, 1, 7)");
    return collect($data)
    ->map(function($row) {
        $time = strtotime($row->date . '-01');

        return array(
            'year' => date('Y', $time),
            'month' => date('F', $time),
            'm' => date('m', $time)
        );
    });
});

Route::get('/stats', function() {
    // { title: 'Orders', value: 50, link: '/order'},

    $orderPending = App\Order::whereDate('created_at', Carbon::today())->where('status', 'PENDING')->count();
    $orderPaid = App\Order::whereDate('created_at', Carbon::today())->where('status', 'PAID')->sum('total_amount');
    $orderServed = App\Order::whereDate('created_at', Carbon::today())->where('status', 'PAID')->count();
    $tableAvailable = App\Table::where('status', 'Active')->where('available', '1')->count();
    return array(
        array(
            'title' => 'PENDING ORDERS',
            'value' => $orderPending,
            'link' => '/order',
            'icon' => 'nav-icon fa fa-utensils',
            'color' => 'danger'
        ),
        array(
            'title' => 'PAID ORDERS',
            'value' => '₱' .$orderPaid,
            'link' => '',
            'icon' => 'nav-icon fa fa-money-bill-alt',
            'color' => 'success'
        ),
        array(
            'title' => 'SERVED ORDERS',
            'value' => $orderServed,
            'link' => '',
            'icon' => 'nav-icon fa fa-cart-plus',
            'color' => 'info'
        ),
        array(
            'title' => 'AVAILABLE TABLES',
            'value' => $tableAvailable,
            'link' => '/dinein',
            'icon' => 'nav-icon fa fa-chair',
            'color' => 'warning'
        )
    );
});