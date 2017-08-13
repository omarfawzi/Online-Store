<?php

namespace App\Http\Controllers;

use App\Cartproduct;
use App\Category;
use App\Color;
use App\Customer;
use App\Favourite;
use App\Image;
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
    private $productImages ;
    private $logoImages;
    private $currentHost;
    private $categories = [];
    private $paginationValue = 4 ;
    public function __construct()
    {
        $this->setCategories();
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
        $this->productImages = $this->currentHost.'assets/admin/images/products/';
        $this->logoImages = $this->currentHost.'assets/admin/images/logos/';
    }

    public function setCategories()
    {
        $this->categories['Men'] = ['Shirts','T-Shirts','Trousers','Coats','Jackets','Footwear'];
        $this->categories['Women'] = ['Dresses','Skirts','Shirts','T-Shirts','Trousers','Coats','Jackets','Footwear'];
        $this->categories['Girls'] = ['Shirts','T-Shirts','Trousers','Coats','Jackets','Footwear','Pyjamas'];
        $this->categories['Boys'] = ['Dresses','Skirts','Shirts','T-Shirts','Trousers','Coats','Jackets','Footwear','Pyjamas'];
    }

    public function login(Request $request)
    {
        $customer = Customer::where('email', $request->email)->first();
        if ($customer && Hash::check($request->password, $customer->password))
            return response()->json(['valid' => 'true'], 200);
        return response()->json(['valid' => 'false'], 200);
    }

    public function fbLogin(Request $request){
        $customer = Customer::where('email',$request->email)->orWhere('provider_id',(int)($request->provider_id))->first();
        if ($customer)
            return response()->json(['valid' => 'true'], 200);
        else {
            $customer = new Customer();
            $customer->email = $request->email ;
            $customer->provider_id = (int) $request->provider_id;
            $customer->firstName = $request->firstName ;
            $customer->lastName = $request->lastName ;
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
            //if ($request->flag == 0) { // login normally
                $customer->email = $request->email;
                $customer->password = bcrypt($request->password);
               $customer->gender = $request->gender;
//            $customer->firstName = $request->firstName;
//            $customer->lastName = $request->lastName;
//            $customer->birthDate = $request->birthDate;
//            $customer->phoneNumber = $request->phone;
//            $customer->address = $request->address;
            //}
            $customer->save();

            return response()->json(['valid' => 'true'], 200);
        }
    }

    public function track(Request $request)
    {
        $users = User::get(['id']);
        foreach ($users as $user) {
            $this->pusher->trigger((string)$user->id, 'tracking', ['longitude' => $request->longitude, 'latitude' => $request->latitude]);
        }
        // DB::table('test')->insert(['longitude'=>$request->longitude,'latitude'=>$request->latitude]);
    }

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
        $cnt = 0 ;
        $index = intval($request->index);
        for ($i = $index * $this->paginationValue ; $i < ($index * $this->paginationValue)+($this->paginationValue) && $i < count($suppliers) ; $i++){
            $suppliers[$i]->suppImage = $this->logoImages.$suppliers[$i]->suppImage;
            array_push($paginatedItems,$suppliers[$i]);
            $cnt = $i + 1 ;
        }
        $stop = ($cnt == count($suppliers));
        foreach ($suppliers as $supplier){
           // $supplier->suppImage = $this->logoImages.$supplier->suppImage;
        }
        return response()->json(['Suppliers' => $paginatedItems,'stop'=>($stop)?1:0], 200);
    }

    public function supplierProducts(Request $request)
    {
        $customerID = (Customer::where('email',$request->email)->orWhere('provider_id',(int)($request->provider_id))->first());
        if ($customerID)
            $customerID = $customerID->customerID;
        $supplierProducts = Supplier::where('supplierID', $request->supplierID)->with(['products' => function ($query) {
            $query->select('supplierID', 'productName', 'price', 'productID','brand');
        }, 'products.colors' => function ($query) {
            $query->select('colorID', 'productID')->where('productStatus', '1');
        }, 'products.colors.images' => function ($query) {
            $query->select('imageID', 'colorID', 'image')->where('type', 'main');
        },'products.favourites' => function($query) use($customerID){
            $query->where('customerID',$customerID);
        }])->first(['supplierID']);
        $temp = [];

        foreach ($supplierProducts->products as $key=> $product){
            if (count($product->colors) != 0 ){
                $temp[] = $product;
            }
        }
        $paginatedItems['products']=[];
        unset($supplierProducts->supplierID);
        unset($supplierProducts->products);
        $supplierProducts->products = $temp;
        $cnt = 0 ;
        $index = intval($request->index);
        for ($i = $index * $this->paginationValue ; $i < ($index * $this->paginationValue)+($this->paginationValue) && $i < count($supplierProducts->products) ; $i++){
            array_push($paginatedItems['products'],$supplierProducts->products[$i]);
            $cnt = $i + 1 ;
        }
        $stop = ($cnt == count($supplierProducts->products));
        foreach ($paginatedItems['products'] as $product) {
            unset($product->supplierID);
            $product['image'] = $this->productImages.$product->colors[0]->images[0]->image;
            $liked = false ;
            foreach ($product->favourites as $favourite){
                if ($favourite->productID == $product->productID){
                    $liked = true ;
                    break;
                }
            }
            $product['liked'] = $liked;
            unset($product->favourites);
            unset($product->colors);
        }

        return response()->json(['supplierProducts' => $paginatedItems['products'],'stop'=>($stop)?1:0], 200);
    }

    public function product(Request $request)
    {
        $product = Product::where('productID', $request->productID)->with(['colors' => function ($query) {
            $query->select('colorID', 'productID', 'colorcode');
        }, 'colors.images', 'colors.sizes'])->first(['supplierID', 'categoryID', 'productName', 'brand', 'price', 'productID', 'description']);
        $category = Category::find($product->categoryID);
        $product['category'] = $category->categoryName;
        if ($product['description'] == null)
            $product['description'] = '';
        unset($product->productID);
        unset($product->supplierID);
        unset($product->categoryID);
        foreach ($product->colors as $color) {
            unset($color->productID);
            $temp = [];
            for($i = 1 ; $i < count($color->images) ; $i++ ){
                if ($color->images[$i]->type == 'main'){
                    $swap = $color->images[0];
                    $color->images[0] = $color->images[$i];
                    $color->images[$i] = $swap;
                }
            }
            foreach ($color->images as $image) {
                $image->image = $this->productImages.$image->image;
                $temp[] = $image->image;
            }
            unset($color->images);
            $color->images = $temp;
            foreach ($color->sizes as $size) {
                $size['quantity'] = $size->pivot->availableUnits;
                unset($size->pivot);
            }

        }
        return response()->json(['product' => $product], 200);
    }

    public function categoryProducts(Request $request)
    {
        $customerID = (Customer::where('email',$request->email)->orWhere('provider_id',(int)($request->provider_id))->first());
        if ($customerID)
            $customerID = $customerID->customerID;
        else $customerID = -1 ;
        $categoryProducts = Category::where('categoryName', $request->categoryName)->with(['products' => function ($query) use ($request) {
            $query->select('categoryID', 'productName', 'price', 'productID','brand')->where('gender', $request->gender);
        }, 'products.colors' => function ($query) {
            $query->select('colorID', 'productID')->where('productStatus', '1');
        }, 'products.colors.images' => function ($query) {
            $query->select('imageID', 'colorID', 'image')->where('type', 'main');
        },'products.favourites' => function($query) use($customerID){
            $query->where('customerID',$customerID);
        }])->first();
        unset($categoryProducts->categoryID);
        $temp = [];
        foreach ($categoryProducts->products as $product){
            if (count($product->colors) != 0 ){
                $temp[] = $product;
            }
        }
        unset($categoryProducts->products);
        $categoryProducts->products = $temp;
        foreach ($categoryProducts->products as $product){
                unset($product->categoryID);
                $product['image'] = $this->productImages.$product->colors[0]->images[0]->image;
                unset($product->colors);
        }
        $cnt = 0 ;
        $index = intval($request->index);
        $paginatedItems['products']=[];
        for ($i = $index * $this->paginationValue ; $i < ($index * $this->paginationValue)+($this->paginationValue) && $i < count($categoryProducts->products) ; $i++){
            array_push($paginatedItems['products'],$categoryProducts->products[$i]);
            $cnt = $i + 1 ;
        }
        $stop = ($cnt == count($categoryProducts->products));
        foreach ($paginatedItems['products'] as $product) {
            unset($product->supplierID);
            $product['image'] = $this->productImages.$product->colors[0]->images[0]->image;
            $liked = false ;
            foreach ($product->favourites as $favourite){
                if ($favourite->productID == $product->productID){
                    $liked = true ;
                    break;
                }
            }
            $product['liked'] = $liked;
            unset($product->favourites);
            unset($product->colors);
        }

        return response()->json(['categoryProducts' => $paginatedItems['products'],'stop'=>($stop)?1:0], 200);
    }

    public function addFavourites(Request $request){
        $customerID = (Customer::where('email',$request->email)->orWhere('provider_id',(int)($request->provider_id))->first());
        if ($customerID)
            $customerID = $customerID->customerID;
        else
            return response()->json(['status'=>'failure'],401);
        $checkLiked = Favourite::where('customerID',$customerID)->where('productID',$request->productID)->first();
        if ($checkLiked)
            $checkLiked->delete();
        else {
            $favourite = new Favourite();
            $favourite->customerID = $customerID;
            $favourite->productID = $request->productID;
            $favourite->save();
        }
        return response()->json(['status'=>'success'],200);
    }

    public function categories(){
        return response()->json(['categories'=>$this->categories],200);
    }

    public function showFavourites(Request $request){
        $customerID = (Customer::where('email',$request->email)->orWhere('provider_id',(int)($request->provider_id))->first());
        if ($customerID)
            $customerID = $customerID->customerID;
        else
            return response()->json(['status'=>'failure'],401);

        $favourites = Favourite::with(['product','product.colors','product.colors.images'=>function($q){
            $q->where('type','main');
        }])->where('customerID',$customerID)->get();
        $favObj = null;
        $favTemp =[];
        foreach ($favourites as $favourite){
            $favObj['favouriteID'] = $favourite->favouriteID;
            $favObj['productName'] = $favourite->product->productName;
            $favObj['productID'] = $favourite->product->productID;
            $favObj['price'] = $favourite->product->price;
            $favObj['brand'] = $favourite->product->brand;
            $favObj['image'] = $this->productImages.$favourite->product->colors[0]->images[0]->image ;
            $favTemp[]=$favObj;
        }
        return response()->json(['favourites'=>$favTemp],200);

    }

    public function addToCart(Request $request){
        $customer = (Customer::where('email',$request->email)->orWhere('provider_id',(int)($request->provider_id))->first());
        if (!$customer)
            return response()->json(['status'=>'failure'],401);
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
        return response()->json(['status'=>'success'],200);
    }

    public function cartProductQuantity(Request $request){
        $customer = (Customer::where('email',$request->email)->orWhere('provider_id',(int)($request->provider_id))->first());
        if (!$customer)
            return response()->json(['status'=>'failure'],401);
        $cartProduct = Cartproduct::find($request->cartProductID);
        $cartProduct->quantity = $request->quantity;
        $cartProduct->update();
        return response()->json(['status'=>'success'],200);
    }

    public function removeCartProduct(Request $request){
        $cartProductID = (int)($request->cartProductID);
        Cartproduct::where('cartProductID',$cartProductID)->delete();
        return response()->json(['status'=>'success'],200);
    }

    public function cartProducts(Request $request){
        $customer = (Customer::where('email',$request->email)->orWhere('provider_id',(int)($request->provider_id))->first());
        $customerID = -1;
        if ($customer)
            $customerID = $customer->customerID;
        else
            return response()->json(['status'=>'failure'],401);

        $cartProducts = Cartproduct::with(['product'])->where('customerID',$customerID)->get();
        $temp =[];
        $cartProduct = null;
        foreach ($cartProducts as $cartproduct){
            $color = Color::find($cartproduct->colorID);
            $size = Size::find($cartproduct->sizeID);
            $cartProduct['cartProductID'] = $cartproduct->cartProductID;
            $cartProduct['image'] = $this->productImages.$color->images[0]->image;
            $cartProduct['productName'] = $cartproduct->product->productName;
            $cartProduct['quantity'] = $cartproduct->quantity;
            $cartProduct['size'] = $size->size;
            $cartProduct['price'] = $cartproduct->product->price;
            array_push($temp,$cartProduct);
        }
        return response()->json(['cartProducts'=>$temp]);
    }

    public function placeOrder(Request $request){
        DB::table('test')->insert(['test'=>serialize(($request->all()))]);
        return response()->json(['status'=>'success']);

    }

    public function removeFavourite(Request $request){
        $favouriteID = (int)($request->favouriteID);
        Favourite::where('favouriteID',$favouriteID)->delete();
        return response()->json(['status'=>'success'],200);
    }

    public function updateProfile(Request $request){
        $customer = Customer::where('email',$request->email)->orWhere('provider_id',$request->provider_id)->first();
        if($request->firstName)
            $customer->firstName = $request->firstName;
        if($request->lastName)
            $customer->lastName = $request->lastName;
        if($request->birthDate)
            $customer->birthDate = $request->birthDate;
        if($request->address)
            $customer->address = $request->address;
        if($request->phoneNumber)
            $customer->phoneNumber = $request->phoneNumber;
        $customer->update();
        return response()->json(['status'=>'success']);
    }
}
