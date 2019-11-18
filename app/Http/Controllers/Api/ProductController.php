<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Product;
use Auth;
use App\Notifications\productNotification;


class ProductController extends Controller
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
//        Auth::login($user);

        $product->notify(new productNotification('Product added','gracegicheru3@gmail.com'));

    }

    public function show(){
        $product = Product::get();
        return $product;

    }
}
