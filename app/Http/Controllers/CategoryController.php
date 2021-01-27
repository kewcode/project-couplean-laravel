<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Category::get();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required'
        ]);

        if($request->img){
            $validatedData['img'] = $this->uploadImg('/category/',$request->img);
        }
        // Create Slug
        $validatedData['slug'] = Str::slug($request->name, "-");
        $validatedData['sub_id'] = $request->sub_id;

        $category = Category::create($validatedData);

        return $category;


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

        return Category::where("id",$id)->orWhere("slug",$id)->first();
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

        
        $update = Category::find($id);

       
        if($request->img){
            $update->img = $this->uploadImg('/category/',$request->img);
        }
        if($request->name){
            $update->name = $request->name;
            $update->slug = Str::slug($request->name, "-");
        }
        if($request->sub_id){
            $request->sub_id = $request->sub_id;
        }
        
        $update->save();

        return $update;

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
