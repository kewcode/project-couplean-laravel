<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Storage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\VarianProduct;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::with("user")->with("address")->orderBy("created_at","DESC")->get()->map(function($order){
            $order->items = OrderItem::where("order_id",$order->id)
            ->with("varian")->get();
            return $order;
        });
    }
    public function myOrder(){
        return Order::with("user")->with("address")->where("user_id",Auth::id())->get()->map(function($order){
            $order->items = OrderItem::where("order_id",$order->id)
            ->with("varian")->get();
            return $order;
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'address_id' => 'required',
            'shipping' => 'required',
            'product' => 'required',
        ]);

        $order = new Order;
        $order->user_id = Auth::id();
        $order->seller = $request->seller;
        $order->address_id = $request->address_id;
        $order->shipping = $request->shipping;
        $order->total = 0;
        $order->payment_ref = $request->payment_ref;
        $order->status = 1;
        $order->save();

        $total = 0;


        foreach($request->product as $p){

            $item = new OrderItem;
            $item->order_id = $order->id;
            $item->varian_product_id = $p['varian_product_id'];

            $VProduct = VarianProduct::find($p['varian_product_id']);

            $item->price = $VProduct->price;
            $item->discount =  $VProduct->discount;
            $item->total =  $p['total'];
            $item->price_total =  ($item->price - ($VProduct->discount/100 * $VProduct->price)) * $p['total'];
            $item->notes =  $p['notes'];
            $item->save();

            $total = $total + $item->price_total;
        }

        $order->total = $total + $order->shipping;
        $order->save();

        return $order;




    }
    public function updateStatus(Request $request,$id)
    {
        $order = Order::find($id);
        $order->payment_ref = $request->payment_ref;
        $order->resi = $request->resi;
        $order->status = $request->status;
        $order->save();

        return $order;
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $order = Order::with("user")->with("address")->where("id",$id)->first();
       
        $order->items = OrderItem::where("order_id",$order->id)
            ->with("varian")->get();

        
            return $order;



            
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
