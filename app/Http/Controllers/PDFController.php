<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
class PDFController extends Controller
{
    //
    public function invoice($id) {
        $data = Order::where('id', $id)->with(['orderItems', 'table', 'orderItems.products', 'user'])->first()->toArray();
        $pdf = \PDF::loadView('pdf.invoice', [ 'order' => $data ]);
        return $pdf->stream('invoice.pdf');
        //return view('pdf.invoice')->with('order', $data);
    }
}
