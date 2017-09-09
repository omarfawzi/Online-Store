<?php

namespace App\Http\Controllers;

use App\Cartproduct;
use App\Category;
use App\Color;
use App\Customer;
use App\Favourite;
use App\Image;
use App\Order;
use App\Orderdetail;
use App\Product;
use App\Size;
use App\Supplier;
use App\User;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Pusher\Pusher;

class APIController extends Controller
{
    private $pusher;
    private $options;
    private $productImages;
    private $logoImages;
    private $currentHost;
    private $stop;
    private $paginationValue = 4;

    public function __construct()
    {
        $this->options = array(
            'cluster' => 'eu',
            'encrypted' => true
        );

        $this->pusher = new Pusher(
            '9f182cebbd2f9bd2fde7',
            'a10fe92e966286a1f3fe',
            '366206',
            $this->options
        );
        $this->currentHost = 'http://' . request()->getHttpHost() . '/';
        $this->productImages = $this->currentHost . 'assets/admin/images/products/';
        $this->logoImages = $this->currentHost . 'assets/admin/images/logos/';
    }


    public function login(Request $request)
    {
        $customer = Customer::where('email', $request->email)->first();
        if(!$customer)
            return response()->json(['valid' => '0','error'=>'email'], 200);
        if (!Hash::check($request->password, $customer->password))
            return response()->json(['valid' => '0','error'=>'password'], 200);
        return response()->json(['valid' => '1'], 200);
    }

    public function fbLogin(Request $request)
    {
        $customer = Customer::where('email', $request->email)->orWhere('provider_id', (int)($request->provider_id))->first();
        if ($customer)
            return response()->json(['valid' => 'true'], 200);
        else {
            $customer = new Customer();
            $customer->email = $request->email;
            $customer->provider_id = (int)$request->provider_id;
            $customer->firstName = $request->firstName;
            $customer->lastName = $request->lastName;
            $customer->gender = $request->gender;
            $customer->provider = 'facebook';
            $customer->save();
            return response()->json(['valid' => 'true'], 200);
        }
    }

    public function register(Request $request)
    {
        $customer = Customer::where('email', $request->email)->first();
        if ($customer)
            return response()->json(['valid' => 'false'], 200);
        else {
            $customer = new Customer();
            $customer->email = $request->email;
            $customer->password = bcrypt($request->password);
            $customer->save();

            return response()->json(['valid' => 'true'], 200);
        }
    }

//    public function track(Request $request)
//    {
//        $users = User::get(['id']);
//        foreach ($users as $user) {
//            $this->pusher->trigger((string)$user->id, 'tracking', ['longitude' => $request->longitude, 'latitude' => $request->latitude]);
//        }
//        // DB::table('test')->insert(['longitude'=>$request->longitude,'latitude'=>$request->latitude]);
//    }

    public function products()
    {
        $Products = Product::with(['colors' => function ($q) {
            $q->where('productStatus', '1');
        }, 'colors.images', 'colors.sizes'])->get();
        $temp = [];
        foreach ($Products as $product) {
            if (count($product->colors) != 0) {
                array_push($temp, $product);
            }
        }
        return response()->json($temp, 200);
    }

    public function suppliers(Request $request)
    {
        $suppliers = Supplier::select('suppImage', 'supplierID')->get();
        $paginatedItems = [];
        $cnt = 0;
        $index = intval($request->index);
        for ($i = $index * $this->paginationValue; $i < ($index * $this->paginationValue) + ($this->paginationValue) && $i < count($suppliers); $i++) {
            $suppliers[$i]->suppImage = $this->logoImages . $suppliers[$i]->suppImage;
            array_push($paginatedItems, $suppliers[$i]);
            $cnt = $i + 1;
        }
        $stop = ($cnt == count($suppliers));
        return response()->json(['Suppliers' => $paginatedItems, 'stop' => ($stop) ? 1 : 0], 200);
    }

    public function supplierProducts(Request $request)
    {
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customerID = Customer::where('email', $request->email)->orWhere('provider_id', (int)($request->provider_id))->first();
        if ($customerID)
            $customerID = $customerID->customerID;
        $supplierProducts = Supplier::where('supplierID', $request->supplierID)->with(['products' => function ($query) {
            $query->select('supplierID', 'productName', 'price', 'productID', 'brand');
        }, 'products.colors' => function ($query) {
            $query->select('colorID', 'productID')->where('productStatus', '1');
        }, 'products.colors.images' => function ($query) {
            $query->select('imageID', 'colorID', 'image')->where('type', 'main');
        }, 'products.favourites' => function ($query) use ($customerID) {
            $query->where('customerID', $customerID);
        }, 'products.colors.sizes' => function ($query) {
            $query->where('availableUnits', '>', 0);
        }])->first(['supplierID']);
        $paginatedItems['products'] = [];
        unset($supplierProducts->supplierID);
        $index = intval($request->index);
        $supplierProducts->products = $this->getPaginatedProducts($supplierProducts->products, $index);
        return response()->json(['supplierProducts' => $supplierProducts->products, 'stop' => ($this->stop) ? 1 : 0], 200);
    }

    public function categoryProducts(Request $request)
    {
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customerID = Customer::where('email', $request->email)->orWhere('provider_id', (int)($request->provider_id))->first();
        if ($customerID)
            $customerID = $customerID->customerID;
        else $customerID = -1;
        $categoryProducts = Category::where('categoryName', $request->categoryName)->with(['products' => function ($query) use ($request) {
            $query->select('categoryID', 'productName', 'price', 'productID', 'brand')->where('gender', $request->gender);
        }, 'products.colors' => function ($query) {
            $query->select('colorID', 'productID')->where('productStatus', '1');
        }, 'products.colors.images' => function ($query) {
            $query->select('imageID', 'colorID', 'image')->where('type', 'main');
        }, 'products.favourites' => function ($query) use ($customerID) {
            $query->where('customerID', $customerID);
        }, 'products.colors.sizes' => function ($query) {
            $query->where('availableUnits', '>', 0);
        }])->first();
        unset($categoryProducts->categoryID);
        $index = intval($request->index);
        $categoryProducts->products = $this->getPaginatedProducts($categoryProducts->products, $index);
        return response()->json(['categoryProducts' => $categoryProducts->products, 'stop' => ($this->stop) ? 1 : 0], 200);
    }

    public function product(Request $request)
    {
        $product = Product::where('productID', $request->productID)->with(['colors' => function ($query) {
            $query->where('productStatus','1');
            $query->select('colorID', 'productID', 'colorcode');
        }, 'colors.images', 'colors.sizes'=>function($query){
            $query->where('availableUnits', '>', 0);
        }])->first(['supplierID', 'categoryID', 'productName', 'brand', 'price', 'productID', 'description']);
        $category = Category::find($product->categoryID);
        $product['category'] = $category->categoryName;
        if ($product['description'] == null)
            $product['description'] = '';
        unset($product->productID);
        unset($product->supplierID);
        unset($product->categoryID);
        foreach ($product->colors as $key => $color) {
            unset($color->productID);
            if (count($color->sizes) == 0){
                unset($product->colors[$key]);
            }
            else {
                $temp = [];
                for ($i = 1; $i < count($color->images); $i++) {
                    if ($color->images[$i]->type == 'main') {
                        $swap = $color->images[0];
                        $color->images[0] = $color->images[$i];
                        $color->images[$i] = $swap;
                    }
                }

                foreach ($color->sizes as $size) {
                    $size['quantity'] = $size->pivot->availableUnits;
                    unset($size->pivot);
                }
                foreach ($color->images as $image) {
                    $image->image = $this->productImages . $image->image;
                    $temp[] = $image->image;
                }
                unset($color->images);
                $color->images = $temp;
            }
        }
        if (count($product->colors) == 0){
            unset($product);
        }
        return response()->json(['product' => $product], 200);
    }

    public function addFavourites(Request $request)
    {
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customerID = Customer::where('email', $request->email)->orWhere('provider_id', (int)($request->provider_id))->first();
        $productIDs = json_decode($request->productsIDs);
        if ($customerID)
            $customerID = $customerID->customerID;
        else
            return response()->json(['status' => 'failure'], 401);
        foreach ($productIDs as $id){
            $checkLiked = Favourite::where('customerID', $customerID)->where('productID', $id)->first();
            if ($checkLiked)
                $checkLiked->delete();
            else {
                $favourite = new Favourite();
                $favourite->customerID = $customerID;
                $favourite->productID = $id;
                $favourite->save();
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function categories(Request $request)
    {
        $genders = ['Men','Women','Boys','Girls'];
        $map = [];
        $categories = Category::with(['products'])->get();
        $ret = [];
        foreach ($categories as $category){
            for ($i = 0 ; $i < count($genders) ; $i++){
                $map[$genders[$i]] = 0 ;
            }
            if (count($category->products) != 0){
                foreach ($category->products as $product){
                    if($map[$product->gender] == 0){
                        $ret[$product->gender][] = $category->categoryName;
                    }
                    $map[$product->gender] = 1;
                }
            }
        }
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customer = Customer::where('email', $request->email)->orWhere('provider_id', (int)($request->provider_id))->first();
        if ($customer){
            $ret['firstName'] = $customer->firstName;
            $ret['lastName'] = $customer->lastName;
        }
        return response()->json(['categories' => $ret], 200);
    }

    public function showFavourites(Request $request)
    {
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customerID = Customer::where('email', $request->email)->orWhere('provider_id', (int)($request->provider_id))->first();
        if ($customerID)
            $customerID = $customerID->customerID;
        else
            return response()->json(['status' => 'failure'], 401);
        $favourites = Favourite::with(['product', 'product.colors', 'product.colors.images' => function ($q) {
            $q->where('type', 'main');
        }])->where('customerID', $customerID)->get();
        $favObj = null;
        $favTemp = [];
        foreach ($favourites as $favourite) {
            $favObj['favouriteID'] = $favourite->favouriteID;
            $favObj['productName'] = $favourite->product->productName;
            $favObj['productID'] = $favourite->product->productID;
            $favObj['price'] = $favourite->product->price;
            $favObj['brand'] = $favourite->product->brand;
            $favObj['image'] = $this->productImages . $favourite->product->colors[0]->images[0]->image;
            $favTemp[] = $favObj;
        }
        return response()->json(['favourites' => $favTemp], 200);
    }

    public function addToCart(Request $request)
    {
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customer = Customer::where('email', $request->email)->orWhere('provider_id', (int)($request->provider_id))->first();
        if (!$customer)
            return response()->json(['status' => 'failure'], 401);
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
        return response()->json(['status' => 'success'], 200);
    }

    public function cartProductQuantity(Request $request)
    {
        $cartQuantities = json_decode($request->cartQuantities) ;
        foreach($cartQuantities as $cartQuantity) {
            $cartProduct = Cartproduct::find((int)$cartQuantity->cartProductID);
            $cartProduct->quantity = (int) $cartQuantity->quantity ;
            $cartProduct->update() ;
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function cartProducts(Request $request)
    {
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customer = Customer::where('email', $request->email)->orWhere('provider_id', (int)($request->provider_id))->first();
        $customerID = -1;
        if ($customer)
            $customerID = $customer->customerID;
        else
            return response()->json(['status' => 'failure'], 200);
        $cartProducts = Cartproduct::with(['product', 'color', 'size','color.sizes','color.images'])->where('customerID', $customerID)->get();
        $temp = [];
        $cartProduct = null;
        foreach ($cartProducts as $cartProductItem) {
            $cartProduct['cartProductID'] = $cartProductItem->cartProductID;
            $cartProduct['image'] = $this->productImages . $cartProductItem->color->images[0]->image;
            $cartProduct['productName'] = $cartProductItem->product->productName;
            $cartProduct['quantity'] = $cartProductItem->quantity;
            $cartProduct['size'] = $cartProductItem->size->size;
            $cartProduct['price'] = $cartProductItem->product->price;
            foreach ($cartProductItem->color->sizes as $size) {
                if ($cartProductItem->sizeID == $size->sizeID) {
                    $cartProduct['availableUnits'] = $size->pivot->availableUnits;
                    break;
                }
            }
            array_push($temp, $cartProduct);
        }
        return response()->json(['cartProducts' => $temp]);
    }

    public function removeCartProduct(Request $request)
    {
        $cartProductsID=json_decode($request->cartProductsID);
        foreach ($cartProductsID as $cartProductID){
            Cartproduct::where('cartProductID', (int)$cartProductID)->delete();
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function placeOrder(Request $request)
    {
        $customer = Customer::where('email',$request->email)->first();
        $cartProducts = Cartproduct::where('customerID',$customer->customerID)->with(['color','color.sizes'])->get();
        $availableCartProducts = [];
        $flag = false;
        foreach ($cartProducts as $cartProduct) {
            foreach ($cartProduct->color->sizes as $size) {
                if ($cartProduct->sizeID == $size->sizeID) {
                    if ($size->pivot->availableUnits < $cartProduct->quantity) {
                        $obj = [];
                        $obj['cartProductID'] = $cartProduct->cartProductID;
                        $obj['availableUnits'] = $size->pivot->availableUnits;
                        $availableCartProducts[] =  $obj;
                        $flag = true;
                    }
                    break;
                }
            }
        }
        if ($flag)
            return response()->json(['availableCartProducts'=>$availableCartProducts,'status'=>'failure'],200);
        $order = new Order();
        $order->customerID = (int) $customer->customerID;
        $order->address = $request->address;
        $order->phone = $request->mobileNumber;
        $order->name = $request->firstName . ' ' . $request->lastName;
        $order->save();
        foreach ($cartProducts as $cartProduct){
            $product = Product::find($cartProduct->productID);
            foreach ($cartProduct->color->sizes as $size) {
                if ($cartProduct->sizeID == $size->sizeID ) {
                    $size->pivot->availableUnits -= $cartProduct->quantity;
                    $size->pivot->update();
                    break;
                }
            }
            $orderDetails = new Orderdetail();
            $orderDetails->quantity = $cartProduct->quantity;
            $orderDetails->colorID = $cartProduct->colorID;
            $orderDetails->sizeID = $cartProduct->sizeID;
            $orderDetails->productID = $cartProduct->productID;
            $orderDetails->supplierID = $product->supplierID;
            $order->orderdetails()->save($orderDetails);
            $cartProduct->delete();
        }
//        DB::table('test')->insert(['test' => serialize(($request->all()))]);
        return response()->json(['status' => 'success']);

    }

    public function getOrders(Request $request){
        $customer = Customer::where('email',$request->email)->first();
        $customerID = $customer->customerID;
        $orders = Order::with(['orderdetails','orderdetails.size','orderdetails.color','orderdetails.product'])->where('customerID',$customerID)->get();
        $finalOrders = [];
        foreach ($orders as $order){
            $obj['orderID'] = $order->orderID;
            $obj['address'] = $order->address;
            $obj['name'] = $order->name;
            $obj['date'] = explode('-',explode(" ",$order->date)[0])[2].'-'.explode('-',explode(" ",$order->date)[0])[1].'-'.explode('-',explode(" ",$order->date)[0])[0];
            $obj['status'] = $order->status;
            foreach ($order->orderdetails as $orderdetail){
                $image = Image::where('colorID',$orderdetail->colorID)->first();
                $product = Product::find($orderdetail->productID);
                $orderdetailObj = [];
                $orderdetailObj['price'] = $orderdetail->quantity * $product->price;
                $orderdetailObj['quantity'] = $orderdetail->quantity;
                $orderdetailObj['color'] = $orderdetail->color->colorcode;
                $orderdetailObj['size'] =$orderdetail->size->size ;
                $orderdetailObj['image'] = $this->productImages . $image->image;
                $orderdetailObj['productName'] = $orderdetail->product->productName;
                $orderdetailObj['brand'] = $orderdetail->product->brand;
                $obj['orderdetails'][] = $orderdetailObj;
            }
            $finalOrders[] = $obj;
        }
        return \response()->json(['Orders'=>$finalOrders],200);
    }

    public function removeFavourite(Request $request)
    {
        $favouriteProductsID = json_decode($request->favouriteProductsID) ;
        foreach ($favouriteProductsID as $favouriteID){
            Favourite::where('favouriteID', (int) $favouriteID)->delete();
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function showProfile(Request $request){
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customer = Customer::where('email', $request->email)->orWhere('provider_id', $request->provider_id)->first();
        unset($customer->customerID);
        unset($customer->provider);
        unset($customer->provider_id);
        unset($customer->created_at);
        unset($customer->updated_at);
        return \response()->json(['profile'=>$customer]);
    }


    public function updateProfile(Request $request)
    {
        if (!$request->provider_id)
            $request->provider_id = -1 ;
        $customer = Customer::where('email', $request->email)->orWhere('provider_id', $request->provider_id)->first();
        if ($request->firstName)
            $customer->firstName = $request->firstName;
        if ($request->lastName)
            $customer->lastName = $request->lastName;
        if ($request->birthDate)
            $customer->birthDate = $request->birthDate;
        if ($request->address)
            $customer->address = $request->address;
        if ($request->phoneNumber)
            $customer->phoneNumber = $request->phoneNumber;
        $customer->update();
        return response()->json(['status' => 'success']);
    }

    public function filterComponents(){
        $genders = ['Men', 'Women', 'Boys', 'Girls'];
        $categoryMap = [];
        $brands = $this->getUniqueBrands();
        $colors = $this->getUniqueColors();
        $sizes = $this->getUniqueSizes();
        $categories = Category::with(['products'])->get();
        foreach ($categories as $key => $category) {
            if (count($category->products) == 0) {
                unset($categories[$key]);
            } else {
                for ($i = 0; $i < count($genders); $i++) {
                    $categoryMap[$genders[$i]][$category->categoryName] = 0 ;
                }
            }
        }
        $categoriesNames = [];
        foreach ($categories as $key => $category) {
            foreach ($category->products as $product) {
                if($categoryMap[$product->gender][$category->categoryName] == 0){
                    $categoriesNames[$product->gender][] = $category->categoryName;
                }
                $categoryMap[$product->gender][$category->categoryName] = 1 ;
            }
        }
        return response()->json(['brands' => $brands,'colors'=>$colors,'sizes'=>$sizes,'categories'=>$categoriesNames],200);
    }

    public function filterBy(Request $request){
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
        }, 'colors.images'])->orderBy('price', $request->sortBy)->whereBetween('price', [(int)$request->min, (int)$request->max])->get();
        return response()->json(['products' => $this->getPaginatedProducts($products, $request->index), 'stop' => $this->stop]);
    }


    public function sendLocation(Request $request){
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $name = $request->name;
        $phone = $request->phone;
        $orderChannel = "Order".(string)$request->orderID;
        $this->pusher->trigger($orderChannel, 'getLocation', ['name'=>$name,'phone'=>$phone,'latitude'=>$latitude,'longitude'=>$longitude]);
    }

    public function getPaginatedProducts($products, $index)
    {
        $temp = [];
        $paginatedItems['products'] = [];
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
        $cnt = 0;
        for ($i = $index * $this->paginationValue; $i < ($index * $this->paginationValue) + ($this->paginationValue) && $i < count($temp); $i++) {
            array_push($paginatedItems['products'], $temp[$i]);
            $cnt = $i + 1;
        }
        $this->stop = ($cnt == count($temp));
        foreach ($paginatedItems['products'] as $product) {
            unset($product->supplierID);
            unset($product->categoryID);
            $first = key(reset($product->colors));
            $product['image'] = $this->productImages . $product->colors[$first]->images[0]->image;
            $liked = false;
            foreach ($product->favourites as $favourite) {
                if ($favourite->productID == $product->productID) {
                    $liked = true;
                    break;
                }
            }
            $product['liked'] = $liked;
            unset($product->favourites);
            unset($product->colors);
        }
        return $paginatedItems['products'];
    }

    public function getUniqueSizes()
    {
        $map = [];
        $uniqueSizes = [];
        $colors = Color::with(['sizes'=>function($query){
            $query->where('availableUnits','>',0);
        }])->where('productStatus',1)->get();
        foreach ($colors as $key => $color){
            if (count($color->sizes) == 0){
                unset($colors[$key]);
            }else{
                foreach ($color->sizes as $size){
                    $map[(string)$size->size] = 0;
                }
            }
        }
        foreach ($colors as $color){
            foreach ($color->sizes as $size) {
                if ($map[(string)$size->size] == 0) {
                    $uniqueSizes[] = $size->size;
                }
                $map[(string)$size->size]++;
            }
        }
        usort($uniqueSizes, function ($a, $b) {
            return $a <=> $b;
        });
        return $uniqueSizes;
    }

    public function getUniqueColors()
    {
        $map = [];
        $uniqueColors = [];
        $colors = Color::with(['sizes'=>function($query){
            $query->where('availableUnits','>',0);
        }])->where('productStatus',1)->get();
        foreach ($colors as $key => $color){
            if (count($color->sizes) == 0){
                unset($colors[$key]);
            }else{
                $map[(string)$color->colorcode] = 0;
            }
        }
        foreach ($colors as $color) {
            if ($map[(string)$color->colorcode] == 0) {
                $uniqueColors[] = $color->colorcode;
            }
            $map[(string)$color->colorcode]++;
        }
        return $uniqueColors;
    }

    public function getUniqueBrands(){
        $map = [];
        $uniqueBrands = [];
        $brands = Product::select('brand')->get();
        foreach ($brands as $brand) {
            $map[(string)$brand->brand] = 0 ;
        }
        foreach ($brands as $brand) {
            if ($map[(string)$brand->brand] == 0) {
                $uniqueBrands[] = $brand->brand;
            }
            $map[(string)$brand->brand]++;
        }
        return $uniqueBrands;
    }

}