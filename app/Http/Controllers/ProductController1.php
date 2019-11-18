<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController1 extends Controller
{
    public function Store(Request $request){
        $name = $request->name;
        $description= $request->description;
        $quantity= $request->quantity;
        $price= $request->price;

        $product= new Product;
        $product->name= $name;
        $product->description= $description;
        $product->quantity= $quantity;
        $product->price= $price;
        $product->save();
    }
}
