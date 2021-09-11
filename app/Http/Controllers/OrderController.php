<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
//use Illuminate\Http\Support\Facades\DB;
Use PDF;
Use App\User;
Use DB;
Use App\Models\products;



class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = new Order();
        if($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $orders = $orders->with(['items', 'payments', 'customer'])->latest()->paginate(10);

        $total = $orders->map(function($i) {
            return $i->total();
        })->sum();
        $receivedAmount = $orders->map(function($i) {
            return $i->receivedAmount();
        })->sum();

        return view('orders.index', compact('orders', 'total', 'receivedAmount'));
    }
    public function createPDF() {
        
   
        // retreive all records from db
       // $data = Product::all();
       //$users = User::join('posts', 'users.id', '=', 'posts.user_id')
 //$data = Order::join('orders','user_id','=','payments.user_id')
 $data = DB::table('products') 
    ->join('order_items', 'products.id', '=', 'order_items.product_id')
    ->join('payments', 'order_items.order_id', '=', 'payments.order_id') 
    ->select('products.name','products.description','order_items.price','order_items.quantity',
    'payments.amount')
  //  ->where('products.id',$id)
   
    ->get();
    //view()->share('table',$data);
    //$pdf = PDF::loadView('pdf', $data);
    
    //dd($data);
    $pdf = PDF::loadView('pdf',compact('data'));
    return $pdf->download('receipt.pdf');
  
   
 

 
 
 
 
 //dd($data);
        // share data to view
       // return view()->share('order',$data);
       // $pdf = PDF::loadView('pdf', $data);
  
        // download PDF file with download method
       // return $pdf->download('pdf_file.pdf');
      }
  

    public function store(OrderStoreRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user()->id,
        ]);

        $cart = $request->user()->cart()->get();
        foreach ($cart as $item) {
            $order->items()->create([
                'price' => $item->price,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        }
        $request->user()->cart()->detach();
        $order->payments()->create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
        ]);
        return 'success';
    }
}
