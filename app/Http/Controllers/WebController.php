<?php

namespace App\Http\Controllers;

use App\Cartproduct;
use App\Category;
use App\Color;
use App\ColorConverter;
use App\Customer;
use App\Product;
use App\Size;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function __construct()
    {
    }

    public function index(){
        $suppliers = Supplier::all();
        return view('website.index',['suppliers'=>$suppliers]);
    }

    public function categoryProducts($gender,$categoryName){
        $checkedMap = $this->initializeMap() ;

        $colorConverter = new ColorConverter();
        $suppliers = Supplier::all();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $colorNames =[];
        foreach ($colors as $color){
            $colorNames[] = $colorConverter->getApproximateColorName($color->colorcode);
        }
        $category = Category::where('categoryName',$categoryName)->first();
        if (!$category)
            return back();
        $categoryProducts = Product::where('categoryID',$category->categoryID)->where('gender',$gender)->with(['colors'=> function ($query) {
            $query->where('productStatus', '1');
        }, 'colors.images'])->get();
        foreach ($categoryProducts as $key => $product){
            if (count($product->colors) == 0 ){
                unset($categoryProducts[$key]);
            }
        }
        return view('website.products',['products'=>$categoryProducts,'suppliers'=>$suppliers,'colors'=>$colors,'colorNames'=>$colorNames,'sizes'=>$sizes,'checkedMap'=>$checkedMap]);
    }

    public function supplierProducts($supplierName){
        $checkedMap = $this->initializeMap() ;

        $colorConverter = new ColorConverter();
        $suppliers = Supplier::all();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $colorNames =[];
        foreach ($colors as $color){
            $colorNames[] = $colorConverter->getApproximateColorName($color->colorcode);
        }
        $supplier = Supplier::where('supplierName',$supplierName)->first();
        if (!$supplier)
            return back();
        $supplierProducts = Product::where('supplierID',$supplier->supplierID)->with(['colors'=> function ($query) {
            $query->where('productStatus', '1');
        }, 'colors.images'])->get();
        foreach ($supplierProducts as $key => $product){
            if (count($product->colors) == 0 ){
                unset($supplierProducts[$key]);
            }
        }

        return view('website.products',['products'=>$supplierProducts,'suppliers'=>$suppliers,'colors'=>$colors,'colorNames'=>$colorNames,'sizes'=>$sizes,'checkedMap'=>$checkedMap]);
    }

    public function product($productName,$colorID){
        $colorConverter = new ColorConverter();
        $productID = Product::where('productName',$productName)->first();
        if ($productID)
            $productID = $productID->productID;
        else
            return back();
        $product = Product::where('productID',$productID)->with(['colors'=>function($query) use($colorID){
            $query->where('colorID',$colorID);
        },'colors.images','colors.sizes'])->first();
        $mainColorName = $colorConverter->getApproximateColorName($product->colors[0]->colorcode);
        $otherColorsNames = [];
        $otherColors = Color::where('productID',$productID)->where('colorID','!=',$colorID)->get();
        foreach ($otherColors as $otherColor){
            $otherColorsNames[] = $colorConverter->getApproximateColorName($otherColor->colorcode);
        }
        return view('website.single_product',['product'=>$product,'otherColors'=>$otherColors,'mainColorName'=>$mainColorName,'otherColorsNames'=>$otherColorsNames]);
    }

    public function filterProducts(Request $request){
        $checkedMap = $this->initializeMap();
        $colorConverter = new ColorConverter();
        $suppliers = Supplier::all();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $colorNames =[];
        foreach ($colors as $color){
            $colorNames[] = $colorConverter->getApproximateColorName($color->colorcode);
        }
        foreach ((array)$request->gender as $gender){
            $checkedMap[$gender] = true;
        }
        foreach ((array)$request->brands as $brand) {
            $checkedMap[$brand] = true;

        }
        foreach ((array)$request->sizes as $size) {
            $checkedMap[$size] = true;

        }
        foreach ((array)$request->colors as  $color){
            $checkedMap[$color] = true;
        }
        $min = $request->prices[0];
        $max = $request->prices[1];
        $products = Product::where(function ($query) use($request) {
            foreach ((array)$request->gender as $gender){
                $query->orWhere('gender',$gender);
            }
            foreach ((array)$request->brands as $brand) {
                $query->orWhere('brand', $brand);
            }
        })->whereBetween('price', [$min, $max])->with(['colors' =>function($query) use($request){
            $query->where('productStatus', '1');
            foreach ((array)$request->colors as $key => $color){
                if ($key == 0)
                    $query->where('colorcode',$color);
                else
                    $query->orWhere('colorcode',$color);
            }

        },'colors.sizes'=>function($query)use($request){
            foreach ((array)$request->sizes as $key => $size){
                if ($key == 0)
                    $query->where('size',$size);
                else
                    $query->orWhere('size',$size);
            }

        },'colors.images'])->get();
        foreach ($products as $key => $product){
            foreach ($product->colors as $key2 => $color){
                if (count($color->sizes)==0){
                    unset($product->colors[$key2]);
                }
            }
            if (count($product->colors) == 0 ){
                unset($products[$key]);
            }
        }
        return view('website.products',['products'=>$products,'suppliers'=>$suppliers,'colors'=>$colors,'colorNames'=>$colorNames,'sizes'=>$sizes,'checkedMap'=>$checkedMap]);
    }

    public function addToCart(Request $request){
        $customer = (Customer::where('email',Auth::guard('customer')->user()->email)->orWhere('provider_id',Auth::guard('customer')->user()->provider_id)->first());
        $productExists = Cartproduct::where('productID',$request->productID)->where('sizeID',$request->sizeID)->where('colorID',$request->colorID)->where('customerID',$customer->customerID)->first();
        $cartProduct = new Cartproduct();
        $cartProduct->productID = $request->productID;
        $cartProduct->sizeID = $request->sizeID;
        $cartProduct->colorID = $request->colorID;
        if ($productExists){
            $productExists->quantity++;
            $productExists->update();
        }
        else {
            $customer->cartproducts()->save($cartProduct);
        }
        return redirect()->route('myCart');
    }

    public function myCart(){
        return view('website.cart');
    }

    public function removeCartProduct(Request $request){
        Cartproduct::where('cartProductID',$request->cartProductID)->delete();
        return response()->json(['msg'=>'success'],200);
    }


    public function getUniqueSizes(){
        $sizes = Size::all();
        $map = [];
        $uniqueSizes = [];
        foreach ($sizes as $size){
            $map[(string)$size->size] = 0 ;
        }
        foreach ($sizes as $size){
            if ($map[(string)$size->size] == 0){
                $uniqueSizes[] = $size;
            }
            $map[(string)$size->size] ++ ;
        }
        usort($uniqueSizes,function($a, $b) {
            return $a['size'] <=> $b['size'];
        });
        return $uniqueSizes;
    }

    public function getUniqueColors(){
        $colors = Color::all();
        $map = [];
        $uniqueColors = [];
        foreach ($colors as $color){
            $map[(string)$color->colorcode] = 0 ;
        }

        foreach ($colors as $color){
            if ($map[(string)$color->colorcode] == 0){
                $uniqueColors[] = $color;
            }
            $map[(string)$color->colorcode] ++ ;
        }
        return $uniqueColors;
    }

    public function initializeMap(){
        $checkedMap = [];
        $colors = Color::all();
        $suppliers = Supplier::all();
        $sizes = Size::all();
        foreach ($colors as $color){
            $checkedMap[$color->colorcode] = false;
        }
        foreach ($sizes as $size){
            $checkedMap[$size->size] = false;
        }
        foreach ($suppliers as $supplier){
            $checkedMap[$supplier->supplierName] = false;
        }
        $checkedMap['Men'] = false;
        $checkedMap['Women'] = false;
        $checkedMap['Boys'] = false;
        $checkedMap['Girls'] = false;
        return $checkedMap;
    }

}
