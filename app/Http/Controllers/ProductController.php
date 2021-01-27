<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\VarianProduct;
use App\Models\Category;
use App\Models\OrderItem;
use Auth;
use Storage;
use Illuminate\Support\Str;
use DB;




class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with("varian")->get();
    }

    // public function promo()
    // {
    //     return Product::limit(3)->latest()->get();
    // }

    public function new()
    {
        return Product::limit(3)->latest()->get();
    }

    public function popular()
    {
      return Product::
      groupBy("product_id")
      ->limit(3)->orderBy("sold","DESC")
      ->with("product")
      ->get();
    }

    public function search($search)
    {
        return Product::
        where("name","like","%".$search."%")
        ->orWhere("category_id","like","%".$search."%")
        ->paginate(10);
    }

    public function category($category)
    {
        $cat = Category::where("slug",$category)
        ->orWhere("id",$category)->first();
        
        return Product::where("category_id","like","%".$cat->name."%")
        ->get();
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

     public function uploadImg($path,$file){
            $image_64 = $file; //your base64 encoded data

            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        
            $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
        
            // find substring fro replace here eg: data:image/png;base64,
            
            $image = str_replace($replace, '', $image_64); 
            
            $image = str_replace(' ', '+', $image); 
            
            $imageName = $path. Str::random(10).'.'.$extension;
            
            Storage::disk('public')->put($imageName, base64_decode($image));

            return $imageName;        
     }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'weight' => 'required',
            'img' => 'required',
            'varian' => 'required'
        ]);

        

        $product = new Product;
        $product->seller_id = Auth::id();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name,'-');
        $product->category_id = implode(",",$request->category_id);
        $product->weight = $request->weight;
        $product->img = $this->uploadImg('/product/',$request->img);
        $product->save();

        foreach($request->varian as $v){
            $varian = new VarianProduct;
            $varian->img = $this->uploadImg('/product/'.$product->id."/",$v['img']);
            $varian->product_id = $product->id;
            $varian->name = $v['name'];
            $varian->stock = $v['stock'];
            $varian->price = $v['price'];
            $varian->discount = $v['discount'];
            $varian->size = $v['size'];
            $varian->color = $v['color'];
            $varian->description = $v['description'];
            $varian->seller_price = $v['seller_price'];
            $varian->seller_contact = $v['seller_contact'];
            $varian->status = $v['status'];
            $varian->save();
        }

        return $product;

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
        $product = Product::with("varian")
        ->where("id",$id)
        ->orWhere("slug",$id)
        ->first();
        
        return $product;

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
        $validatedData = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'weight' => 'required',
            'varian' => 'required'
        ]);

        $product =  Product::find($id);

        if($product->seller_id == Auth::id()){

            $product->name = $request->name;
            $product->slug = Str::slug($request->name,'-');
            $product->category_id = $request->category_id;
            $product->weight = $request->weight;
            if($request->img){
                $product->img = $this->uploadImg('/product/',$request->img);
            }
            $product->save();
    
            foreach($request->varian as $v){
                if(isset($v['id'])){
                    $varian = VarianProduct::find($v['id']);
                }else{
                    $varian = new VarianProduct;
                }
                
                if(isset($v['img'])){
                    $varian->img = $this->uploadImg('/product/'.$product->id."/",$v['img']);
                }
                
                $varian->product_id = $product->id;
                $varian->name = $v['name'];
                $varian->stock = $v['stock'];
                $varian->price = $v['price'];
                $varian->discount = $v['discount'];
                $varian->size = $v['size'];
                $varian->color = $v['color'];
                $varian->description = $v['description'];
                $varian->seller_price = $v['seller_price'];
                $varian->seller_contact = $v['seller_contact'];
                $varian->status = $v['status'];
                $varian->save();
            }
    
            return $product;
        }
       
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
