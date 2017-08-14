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
    private $paginationValue;
    private $productImages;
    private $currentHost;
    private $stop;

    public function __construct()
    {
        $this->paginationValue = 6;
        $this->currentHost = 'http://' . request()->getHttpHost() . '/';
        $this->productImages = $this->currentHost . 'assets/admin/images/products/';

    }

    public function index()
    {
        $suppliers = Supplier::all();
        return view('website.index', ['suppliers' => $suppliers]);
    }

    public function categoryProducts($gender, $categoryName, Request $request)
    {
        $checkedMap = $this->initializeMap();
        $colorConverter = new ColorConverter();
        $suppliers = Supplier::all();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $categories = Category::all();
        $colorNames = [];
        foreach ($colors as $color) {
            $colorNames[] = $colorConverter->getApproximateColorName($color->colorcode);
        }
        $category = Category::where('categoryName', $categoryName)->first();
        if (!$category)
            return back();
        $categoryProducts = Product::where('categoryID', $category->categoryID)->where('gender', $gender)->with(['colors' => function ($query) {
            $query->where('productStatus', '1');
        }, 'colors.images', 'colors.sizes' => function ($query) {
            $query->where('availableUnits', '>', 0);
        }])->orderBy('price',$request->sortBy)->get();
        if ($request->ajax()) {
            return response()->json(['products' => $this->getPaginatedProducts($categoryProducts, $request->index,$request), 'stop' => $this->stop]);
        }
        $categoryProducts = $this->getPaginatedProducts($categoryProducts, 0,$request);
        return view('website.products', ['categories' => $categories
            , 'gender' => $gender, 'products' => $categoryProducts, 'suppliers' => $suppliers, 'colors' => $colors
            , 'colorNames' => $colorNames, 'sizes' => $sizes, 'checkedMap' => $checkedMap]);
    }

    public function supplierProducts($supplierName, Request $request)
    {
        $checkedMap = $this->initializeMap();
        $colorConverter = new ColorConverter();
        $suppliers = Supplier::all();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $categories = Category::all();
        $colorNames = [];
        foreach ($colors as $color) {
            $colorNames[] = $colorConverter->getApproximateColorName($color->colorcode);
        }
        $supplier = Supplier::where('supplierName', $supplierName)->first();
        if (!$supplier)
            return back();
        $supplierProducts = Product::where('supplierID', $supplier->supplierID)->with(['colors' => function ($query) {
            $query->where('productStatus', '1');
        }, 'colors.images', 'colors.sizes' => function ($query) {
            $query->where('availableUnits', '>', 0);
        }])->orderBy('price',$request->sortBy)->get();
        if ($request->ajax()) {
            return response()->json(['products' => $this->getPaginatedProducts($supplierProducts, $request->index,$request), 'stop' => $this->stop]);
        }
        $supplierProducts = $this->getPaginatedProducts($supplierProducts, 0,$request);
        return view('website.products', ['supplierName' => $supplierName,
            'products' => $supplierProducts, 'suppliers' => $suppliers, 'colors' => $colors,
            'colorNames' => $colorNames, 'sizes' => $sizes, 'checkedMap' => $checkedMap,'categories'=>$categories]);
    }

    public function filterProducts(Request $request)
    {
        $checkedMap = $this->initializeMap();
        $colorConverter = new ColorConverter();
        $suppliers = Supplier::all();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $categories = Category::all();
        $colorNames = [];
        foreach ((array)$request->categories as $category){
            $checkedMap[$category] = true;
        }
        foreach ($colors as $color) {
            $colorNames[] = $colorConverter->getApproximateColorName($color->colorcode);
        }
        foreach ((array)$request->gender as $gender) {
            $checkedMap[$gender] = true;
        }
        foreach ((array)$request->brands as $brand) {
            $checkedMap[$brand] = true;

        }
        foreach ((array)$request->sizes as $size) {
            $checkedMap[$size] = true;
        }
        foreach ((array)$request->colors as $color) {
            $checkedMap[$color] = true;
        }
        $min = $request->prices[0];
        $max = $request->prices[1];
        $products = Product::where(function ($query) use ($request) {
            foreach ((array)$request->categories as $key =>$category){
                if ($key == 0)
                    $query->where('categoryID', $category);
                else
                    $query->orWhere('categoryID', $category);
            }
            foreach ((array)$request->gender as $key => $gender) {
                if ($key == 0)
                    $query->where('gender', $gender);
                else
                    $query->orWhere('gender', $gender);
            }
            foreach ((array)$request->brands as $key => $brand) {
                if ($key == 0)
                    $query->where('brand', $brand);
                else
                    $query->orWhere('brand', $brand);
            }
        })->with(['colors' => function ($query) use ($request) {
            $query->where('productStatus', '1');
            foreach ((array)$request->colors as $key => $color) {
                if ($key == 0)
                    $query->where('colorcode', $color);
                else
                    $query->orWhere('colorcode', $color);
            }

        }, 'colors.sizes' => function ($query) use ($request) {
            $query->where('availableUnits', '>', 0);
            foreach ((array)$request->sizes as $key => $size) {
                if ($key == 0)
                    $query->where('size', $size);
                else
                    $query->orWhere('size', $size);
            }
        }, 'colors.images'])->orderBy('price',$request->sortBy)->whereBetween('price', [$min, $max])->get();
        if ($request->ajax()) {
            return response()->json(['products' => $this->getPaginatedProducts($products, $request->index,$request), 'stop' => $this->stop]);
        }
        $products = $this->getPaginatedProducts($products, 0,$request);
        return view('website.products', ['categories'=>$categories,'products' => $products, 'suppliers' => $suppliers, 'colors' => $colors, 'colorNames' => $colorNames, 'sizes' => $sizes, 'checkedMap' => $checkedMap]);
    }

    public function product($productName, $colorID)
    {
        $colorConverter = new ColorConverter();
        $productID = Product::where('productName', $productName)->first();
        if ($productID)
            $productID = $productID->productID;
        else
            return back();
        $product = Product::where('productID', $productID)->with(['colors' => function ($query) use ($colorID) {
            $query->where('colorID', $colorID);
        }, 'colors.images', 'colors.sizes'])->first();
        $mainColorName = $colorConverter->getApproximateColorName($product->colors[0]->colorcode);
        $otherColorsNames = [];
        $otherColors = Color::where('productID', $productID)->where('colorID', '!=', $colorID)->get();
        foreach ($otherColors as $otherColor) {
            $otherColorsNames[] = $colorConverter->getApproximateColorName($otherColor->colorcode);
        }
        return view('website.single_product', ['product' => $product, 'otherColors' => $otherColors, 'mainColorName' => $mainColorName, 'otherColorsNames' => $otherColorsNames]);
    }

    public function addToCart(Request $request)
    {
        $customer = Customer::find(Auth::guard('customer')->user()->customerID);
        $productExists = Cartproduct::where('productID', $request->productID)->where('sizeID', $request->sizeID)->where('colorID', $request->colorID)->where('customerID', $customer->customerID)->first();
        $cartProduct = new Cartproduct();
        $cartProduct->productID = $request->productID;
        $cartProduct->sizeID = $request->sizeID;
        $cartProduct->colorID = $request->colorID;
        if ($productExists) {
            $productExists->quantity++;
            $productExists->update();
        } else {
            $customer->cartproducts()->save($cartProduct);
        }
        return redirect()->route('myCart');
    }

    public function myCart()
    {
        $colorConverter = new ColorConverter();
        $cartProducts = Cartproduct::where('customerID', Auth::guard('customer')->user()->customerID)
            ->with(['product', 'color', 'size', 'color.images' => function ($query) {
                $query->where('type', 'main');
            }])->get();
        $colorNames = [];
        foreach ($cartProducts as $cartProduct) {
            $colorNames[] = $colorConverter->getApproximateColorName($cartProduct->color->colorcode);
        }
        return view('website.cart', ['cartProducts' => $cartProducts, 'colorNames' => $colorNames]);
    }

    public function removeCartProduct(Request $request)
    {
        Cartproduct::where('cartProductID', $request->cartProductID)->delete();
        return response()->json(['msg' => 'success'], 200);
    }

    public function cartQuantity(Request $request)
    {
        $cartProduct = Cartproduct::find($request->cartProductID);
        $cartProduct->quantity = $request->quantity;
        $cartProduct->update();
        return response()->json(['msg' => $request->quantity], 200);
    }

    public function emptyCart(Request $request){
        Cartproduct::where('customerID', Auth::guard('customer')->user()->customerID)->delete();
        return response()->json(['msg'=>'done']);
    }

    public function myOrders(Request $request)
    {
        return view('website.orders');
    }
    public function getUniqueSizes()
    {
        $sizes = Size::all();
        $map = [];
        $uniqueSizes = [];
        foreach ($sizes as $size) {
            $map[(string)$size->size] = 0;
        }
        foreach ($sizes as $size) {
            if ($map[(string)$size->size] == 0) {
                $uniqueSizes[] = $size;
            }
            $map[(string)$size->size]++;
        }
        usort($uniqueSizes, function ($a, $b) {
            return $a['size'] <=> $b['size'];
        });
        return $uniqueSizes;
    }

    public function getUniqueColors()
    {
        $colors = Color::all();
        $map = [];
        $uniqueColors = [];
        foreach ($colors as $color) {
            $map[(string)$color->colorcode] = 0;
        }

        foreach ($colors as $color) {
            if ($map[(string)$color->colorcode] == 0) {
                $uniqueColors[] = $color;
            }
            $map[(string)$color->colorcode]++;
        }
        return $uniqueColors;
    }

    public function initializeMap()
    {
        $checkedMap = [];
        $categories = Category::all();
        $colors = Color::all();
        $suppliers = Supplier::all();
        $sizes = Size::all();
        foreach ($colors as $color) {
            $checkedMap[$color->colorcode] = false;
        }
        foreach ($categories as $category){
            $checkedMap[$category->categoryID] = false;
        }
        foreach ($sizes as $size) {
            $checkedMap[$size->size] = false;
        }
        foreach ($suppliers as $supplier) {
            $checkedMap[$supplier->supplierName] = false;
        }
        $checkedMap['Men'] = false;
        $checkedMap['Women'] = false;
        $checkedMap['Boys'] = false;
        $checkedMap['Girls'] = false;
        return $checkedMap;
    }

    public function getPaginatedProducts($products, $index,Request $request)
    {
        $temp = [];
        foreach ($products as $key => $product) {
            foreach ($product->colors as $key2 => $color) {
                if (count($color->sizes) == 0) {
                    unset($product->colors[$key2]);
                }
            }

            if (count($product->colors) != 0) {
                $temp[] = $product;
            }
        }
        unset($products);
        $products = $temp;
        $paginatedItems = [];
        $cnt = 0;
        for ($i = $index * $this->paginationValue; $i < ($index * $this->paginationValue) + ($this->paginationValue) && $i < count($products); $i++) {
            $paginatedItems[] = $products[$i];
            $cnt = $i + 1;
        }
        $this->stop = ($cnt == count($products));
        unset($products);
        $products = $paginatedItems;
        if (!$request->ajax())
            return $products;
        $temp = [];
        $obj = null;
        foreach ($products as $product) {
            $obj['image'] = [];
            $first = key(reset($product->colors));
            foreach ($product->colors[$first]->images as $image) {
                $obj['image'][] = $this->productImages . $image->image;
            }
            $obj['productID'] = $product->productID;
            $obj['productName'] = $product->productName;
            $obj['price'] = $product->price;
            $obj['brand'] = $product->brand;
            $obj['color'] = $product->colors[$first]->colorID;
            $temp[] = $obj;
        }
        return $temp;
    }

}
