<?php

namespace App\Http\Controllers;

use App\Cartproduct;
use App\Category;
use App\Color;
use App\ColorConverter;
use App\Customer;
use App\Order;
use App\Orderdetail;
use App\Product;
use App\Size;
use App\Supplier;
use function Couchbase\basicDecoderV1;
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
        $genders = ['Men', 'Women', 'Boys', 'Girls'];
        $map = [];
        for ($i = 0; $i < count($genders); $i++) {
            $map[$genders[$i]] = 0;
        }
        $genders = [];
        $checkedMap = $this->initializeMap();
        $colorConverter = new ColorConverter();
        $brands = $this->getUniqueBrands();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $categories = Category::with(['products'])->get();
        foreach ($categories as $key => $category) {
            if (count($category->products) == 0) {
                unset($categories[$key]);
            } else {
                foreach ($category->products as $product) {
                    if ($map[$product->gender] == 0) {
                        $genders[] = $product->gender;
                    }
                    $map[$product->gender] = 1;
                }
            }
            unset($category->products);
        }
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
        }])->orderBy('price', $request->sortBy)->get();
        if ($request->ajax()) {
            return response()->json(['products' => $this->getPaginatedProducts($categoryProducts, $request->index, $request), 'stop' => $this->stop]);
        }
        $categoryProducts = $this->getPaginatedProducts($categoryProducts, 0, $request);
        return view('website.products', ['categories' => $categories
            , 'gender' => $gender, 'products' => $categoryProducts, 'brands' => $brands, 'colors' => $colors
            , 'colorNames' => $colorNames, 'sizes' => $sizes, 'checkedMap' => $checkedMap, 'genders' => $genders]);
    }

    public function supplierProducts($supplierName, Request $request)
    {
        $genders = ['Men', 'Women', 'Boys', 'Girls'];
        $map = [];
        for ($i = 0; $i < count($genders); $i++) {
            $map[$genders[$i]] = 0;
        }
        $genders = [];
        $checkedMap = $this->initializeMap();
        $colorConverter = new ColorConverter();
        $brands = $this->getUniqueBrands();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $categories = Category::with(['products'])->get();
        foreach ($categories as $key => $category) {
            if (count($category->products) == 0) {
                unset($categories[$key]);
            } else {
                foreach ($category->products as $product) {
                    if ($map[$product->gender] == 0) {
                        $genders[] = $product->gender;
                    }
                    $map[$product->gender] = 1;
                }
            }
            unset($category->products);
        }
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
        }])->orderBy('price', $request->sortBy)->get();
        if ($request->ajax()) {
            return response()->json(['products' => $this->getPaginatedProducts($supplierProducts, $request->index, $request), 'stop' => $this->stop]);
        }
        $supplierProducts = $this->getPaginatedProducts($supplierProducts, 0, $request);
        return view('website.products', ['supplierName' => $supplierName,
            'products' => $supplierProducts, 'brands' => $brands, 'colors' => $colors,
            'colorNames' => $colorNames, 'sizes' => $sizes, 'checkedMap' => $checkedMap, 'categories' => $categories, 'genders' => $genders]);
    }

    public function filterProducts(Request $request)
    {
        $genders = ['Men', 'Women', 'Boys', 'Girls'];
        $map = [];
        for ($i = 0; $i < count($genders); $i++) {
            $map[$genders[$i]] = 0;
        }
        $genders = [];
        $checkedMap = $this->initializeMap();
        $colorConverter = new ColorConverter();
        $brands = $this->getUniqueBrands();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $categories = Category::with(['products'])->get();
        foreach ($categories as $key => $category) {
            if (count($category->products) == 0) {
                unset($categories[$key]);
            } else {
                foreach ($category->products as $product) {
                    if ($map[$product->gender] == 0) {
                        $genders[] = $product->gender;
                    }
                    $map[$product->gender] = 1;
                }
            }
            unset($category->products);
        }
        $colorNames = [];
        foreach ((array)$request->categories as $category) {
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
            foreach ((array)$request->categories as $key => $category) {
                if ($key == 0)
                    $query->where('categoryID', $category);
                else
                    $query->orWhere('categoryID', $category);
            }
        })->where(function ($query) use ($request){
            foreach ((array)$request->gender as $key => $gender) {
                if ($key == 0)
                    $query->where('gender', $gender);
                else
                    $query->orWhere('gender', $gender);
            }
        })->where(function ($query) use ($request){
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
        }, 'colors.images'])->orderBy('price', $request->sortBy)->whereBetween('price', [$min, $max])->get();
        if ($request->ajax()) {
            return response()->json(['products' => $this->getPaginatedProducts($products, $request->index, $request), 'stop' => $this->stop]);
        }
        $products = $this->getPaginatedProducts($products, 0, $request);
        return view('website.products', ['genders' => $genders, 'categories' => $categories, 'products' => $products, 'brands' => $brands, 'colors' => $colors, 'colorNames' => $colorNames, 'sizes' => $sizes, 'checkedMap' => $checkedMap]);
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
        }, 'colors.images', 'colors.sizes' => function ($query) {
            $query->where('availableUnits', '>', 0);
        },'supplier'])->first();

        $mainColorName = $colorConverter->getApproximateColorName($product->colors[0]->colorcode);
        $otherColorsNames = [];
        $otherColors = Color::where('productID', $productID)->where('colorID', '!=', $colorID)->where('productStatus','1')->get();
        foreach ($otherColors as $otherColor) {
            $otherColorsNames[] = $colorConverter->getApproximateColorName($otherColor->colorcode);
        }
        if (count($product->colors[0]->sizes) == 0) {
            return view('website.single_product', ['product' => $product, 'otherColors' => $otherColors, 'mainColorName' => $mainColorName, 'otherColorsNames' => $otherColorsNames])->withErrors(['msg' => 'Sorry this product has finished', 'disable' => true]);

        }
        return view('website.single_product', ['product' => $product, 'otherColors' => $otherColors, 'mainColorName' => $mainColorName, 'otherColorsNames' => $otherColorsNames]);
    }


    public function updateProfile(Request $request)
    {
        $customer = Customer::find(Auth::guard('customer')->user()->customerID);
        if ($request->firstName)
            $customer->firstName = $request->firstName;
        if ($request->lastName)
            $customer->lastName = $request->lastName;
        if ($request->address)
            $customer->address = $request->address;
        if ($request->phoneNumber)
            $customer->phoneNumber = $request->phoneNumber;
        $customer->update();
        return back();
    }

    public function addToCart(Request $request)
    {
        $size = Size::find($request->sizeID);
        if (!$size) {
            return redirect()->back()->withErrors(['msg' => 'Sorry this product has finished', 'disabled' => true]);
        }
        $customer = Customer::find(Auth::guard('customer')->user()->customerID);
        $product = Product::with(['colors' => function ($query) use ($request) {
            $query->where('colorID', $request->colorID);
        }, 'colors.sizes' => function ($query) use ($request, $size) {
            $query->where('size', $size->size);
        }])->where('productID', $request->productID)->first();
        if ($product->colors[0]->sizes[0]->pivot->availableUnits == 0) {
            return redirect()->back()->withErrors(['msg' => 'Sorry this size has finished']);
        }
        $productExists = Cartproduct::where(['customerID' => $customer->customerID, 'productID' => $request->productID, 'colorID' => $request->colorID, 'sizeID' => $request->sizeID])->first();
        if ($productExists) {
            $productExists->quantity++;
            $productExists->update();
        } else {
            $cartProduct = new Cartproduct();
            $cartProduct->productID = $request->productID;
            $cartProduct->sizeID = $request->sizeID;
            $cartProduct->colorID = $request->colorID;
            $customer->cartproducts()->save($cartProduct);
        }
        return redirect()->route('myCart');
    }

    public function myCart()
    {
        $productQuantity = [];
        $colorConverter = new ColorConverter();
        $cartProducts = Cartproduct::where('customerID', Auth::guard('customer')->user()->customerID)
            ->with(['product', 'color', 'size', 'color.sizes', 'color.images' => function ($query) {
                $query->where('type', 'main');
            }])->get();
        $colorNames = [];
        foreach ($cartProducts as $key => $cartProduct) {
            foreach ($cartProduct->color->sizes as $size) {
                if ($size->sizeID == $cartProduct->size->sizeID) {
                    $productQuantity[$key] = $size->pivot->availableUnits;
                    if($cartProduct->quantity >  $size->pivot->availableUnits) {
                        $cartProduct->quantity = $size->pivot->availableUnits;
                        $cartProduct->update();
                    }
                    break;
                }
            }
        }
        foreach ($cartProducts as $key => $cartProduct) {
            $colorNames[$key] = $colorConverter->getApproximateColorName($cartProduct->color->colorcode);
        }
        return view('website.cart', ['cartProducts' => $cartProducts, 'colorNames' => $colorNames, 'productQuantity' => $productQuantity]);
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

    public function emptyCart()
    {
        Cartproduct::where('customerID', Auth::guard('customer')->user()->customerID)->delete();
        return response()->json(['msg' => 'done']);
    }

    public function placeOrder(Request $request)
    {
        foreach ($request->cartProductIDs as $cartProductID) {
            $cartProduct = Cartproduct::where('cartProductID', $cartProductID)->with(['color', 'color.sizes'])->first();
            foreach ($cartProduct->color->sizes as $size) {
                if ($cartProduct->sizeID == $size->sizeID && ($size->pivot->availableUnits < $cartProduct->quantity||$cartProduct->quantity == 0)) {
                    return back()->withErrors(['cartProduct' . $cartProductID => $size->pivot->availableUnits]);
                }
                if ($cartProduct->sizeID == $size->sizeID)
                    break;
            }
        }
        $order = new Order();
        $order->customerID = Auth::guard('customer')->user()->customerID;
        $order->address = $request->address;
        $order->name = $request->firstName . ' ' . $request->lastName;
        $order->phone = $request->phoneNumber;
        $order->save();
        foreach ($request->cartProductIDs as $cartProductID) {
            $cartProduct = Cartproduct::where('cartProductID', $cartProductID)->with(['color', 'color.sizes'])->first();
            foreach ($cartProduct->color->sizes as $size) {
                if ($cartProduct->sizeID == $size->sizeID ) {
                    $size->pivot->availableUnits -= $cartProduct->quantity;
                    $size->pivot->update();
                    break;
                }
            }
            $product = Product::find($cartProduct->productID);
            $orderDetails = new Orderdetail();
            $orderDetails->productID = $cartProduct->productID;
            $orderDetails->sizeID = $cartProduct->sizeID;
            $orderDetails->colorID = $cartProduct->colorID;
            $orderDetails->quantity = $cartProduct->quantity;
            $orderDetails->supplierID = $product->supplierID;
            $order->orderdetails()->save($orderDetails);
            $cartProduct->delete();
        }
        return redirect()->route('index')->withErrors(['order' => 'show']);
//        $order->orderdetails
    }

    public function myOrders($orderID)
    {
        $colorNames = [];
        $colorConverter = new ColorConverter();
        $order = Order::where('orderID', $orderID)->with(['orderdetails', 'orderdetails.product', 'orderdetails.color', 'orderdetails.size'])->first();
        if (!$order)
            return redirect()->route('index');
        foreach ($order->orderdetails as $key => $orderdetail) {
            $colorNames[$key] = $colorConverter->getApproximateColorName($orderdetail->color->colorcode);
        }
        return view('website.orders')->with(['orderDetails' => $order->orderdetails, 'colorNames' => $colorNames]);
    }

    public function cancelOrderProduct(Request $request)
    {
        $orderDetail = Orderdetail::find($request->orderDetailID);
        $color = Color::where('colorID',$orderDetail->colorID)->with(['sizes'])->first();
        foreach ($color->sizes as $size){
            if ($orderDetail->sizeID == $size->sizeID){
                $size->pivot->availableUnits += $orderDetail->quantity;
                $size->pivot->update();
                break;
            }
        }
        $orderID = $orderDetail->orderID;
        $orderDetail->delete();
        $order = Order::where('orderID', $orderID)->with(['orderdetails'])->first();
        if (count($order->orderdetails) == 0) {
            $order->delete();
        }
        return response()->json(['msg' => 'success']);
    }

    public function getUniqueSizes()
    {
        $map = [];
        $uniqueSizes = [];
        $colors = Color::with(['sizes' => function ($query) {
            $query->where('availableUnits', '>', 0);
        }])->where('productStatus', 1)->get();
        foreach ($colors as $key => $color) {
            if (count($color->sizes) == 0) {
                unset($colors[$key]);
            } else {
                foreach ($color->sizes as $size) {
                    $map[(string)$size->size] = 0;
                }
            }
        }
        foreach ($colors as $color) {
            foreach ($color->sizes as $size) {
                if ($map[(string)$size->size] == 0) {
                    $uniqueSizes[] = $size;
                }
                $map[(string)$size->size]++;
            }
        }
        usort($uniqueSizes, function ($a, $b) {
            return $a['size'] <=> $b['size'];
        });
        return $uniqueSizes;
    }

    public function getUniqueColors()
    {
        $map = [];
        $uniqueColors = [];
        $colors = Color::with(['sizes' => function ($query) {
            $query->where('availableUnits', '>', 0);
        }])->where('productStatus', 1)->get();
        foreach ($colors as $key => $color) {
            if (count($color->sizes) == 0) {
                unset($colors[$key]);
            } else {
                $map[(string)$color->colorcode] = 0;
            }
        }
        foreach ($colors as $color) {
            if ($map[(string)$color->colorcode] == 0) {
                $uniqueColors[] = $color;
            }
            $map[(string)$color->colorcode]++;
        }
        return $uniqueColors;
    }

    public function getUniqueBrands()
    {
        $map = [];
        $uniqueBrands = [];
        $brands = Product::select('brand')->get();
        foreach ($brands as $brand) {
            $map[(string)$brand->brand] = 0;
        }
        foreach ($brands as $brand) {
            if ($map[(string)$brand->brand] == 0) {
                $uniqueBrands[] = strtoupper($brand->brand);
            }
            $map[(string)$brand->brand]++;
        }
        return $uniqueBrands;
    }

    public function initializeMap()
    {
        $checkedMap = [];
        $categories = Category::all();
        $colors = $this->getUniqueColors();
        $brands = $this->getUniqueBrands();
        foreach ($categories as $category) {
            $checkedMap[$category->categoryID] = false;
        }
        foreach ($colors as $color) {
            $checkedMap[$color->colorcode] = false;
            foreach ($color->sizes as $size) {
                $checkedMap[$size->size] = false;
            }
        }
        foreach ($brands as $brand) {
            $checkedMap[$brand] = false;
        }
        $checkedMap['Men'] = false;
        $checkedMap['Women'] = false;
        $checkedMap['Boys'] = false;
        $checkedMap['Girls'] = false;
        return $checkedMap;
    }

    public function getPaginatedProducts($products, $index, Request $request)
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
            $obj['color'] = $product->colors;
            $temp[] = $obj;
        }
        return $temp;
    }

}
