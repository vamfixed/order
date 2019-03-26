<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page { size: 4in 10in; }
        .header {
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 20px;
        }
        .header p {
            text-align: center;
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .info p {
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .bold {
            font-weight: bold;
            font-size: 20px;
        }
        table {
            width: 100%;
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <?php 
        // echo "<pre>"; 
        // print_r($order); 
        // die();
    ?>
    <div class="header">
        <p>Invoice # {{ $order['id'] }} @ <?php echo date('Y-m-d H:i') ?></p>
        <p class="bold">Ordering System</p>
        <p>Tetuan, Zamboanga City</p>
        <p>Mobile #123456789</p>
    </div>

    <div class="info">
        <p>User: <u>{{ $order['user']['name'] }}</u></p>
        <p>Time: <u>{{ $order['order_date'] }}</u></p>
        <p>Table: <u>{{ $order['table']['table_number'] }}</u></p>
    </div>

    <div class="items">
        <table>
            <tr>
                <td>Item Name</td>
                <td>Qty</td>
                <td>Price</td>
                <td>Total</td>
            </tr>
            @foreach ($order['order_items'] as $item)
                <tr>
                    <td>{{ $item['products']['product_name'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>{{ $item['rate'] }}</td>
                    <td>{{ $item['amount'] }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="3">Total</td>
                <td>{{ $order['total_amount']}}</td>
            </tr>
        </table>
    </div>

    <div class="info">
            <p>Total: <u>{{ $order['total_amount'] }}</u></p>
            <p>Cash: <u>{{ $order['paid_amount'] }}</u></p>
            <p>Change: <u>{{ $order['change_amount'] }}</u></p>
        </div>
</body>
</html>