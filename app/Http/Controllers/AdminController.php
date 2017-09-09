<?php

namespace App\Http\Controllers;

use App\Cartproduct;
use App\Category;
use App\Color;
use App\Colorsize;
use App\Favourite;
use App\Image;
use App\Order;
use App\Orderdetail;
use App\Product;
use App\Size;
use App\Supplier;
use App\User;
use App\Notification;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Pusher\Pusher;
use App\ImgCompressor;

class AdminController extends Controller
{
    private $defaultRedirection = '/admin';
    private $clothesSizes = [];
    private $options;
    private $pusher;
    private $imageCompressor;
    private $currentHost;
    private $postContext;
    private $previousRoute;
    private $productsImages;
    private $logosImages;

    // default sizes
    public function sizes()
    {
        $this->clothesSizes['Men']['Shirts'] = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Men']['T-Shirts'] = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Men']['Trousers'] = ['36', '38', '40', '42', '44', '46', '48'];
        $this->clothesSizes['Men']['Coats'] = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Men']['Jackets'] = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Men']['Footwear'] = ['38', '39', '40', '41', '42', '43', '44', '45', '46'];
        $this->clothesSizes['Women']['Shirts'] = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Women']['T-Shirts'] = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Women']['Skirts'] = ['32', '34', '36', '38', '40', '42', '44', '46', '48'];
        $this->clothesSizes['Women']['Trousers'] = ['32', '34', '36', '38', '40', '42', '44', '46', '48'];
        $this->clothesSizes['Women']['Dresses'] = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Women']['Coats'] = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Women']['Jackets'] = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $this->clothesSizes['Women']['Footwear'] = ['36', '37', '38', '39', '40', '41'];
        $this->clothesSizes['Girls']['Shirts'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Girls']['T-Shirts'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Girls']['Skirts'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Girls']['Trousers'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Girls']['Dresses'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Girls']['Coats'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Girls']['Jackets'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Girls']['Pyjamas'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Girls']['Footwear'] = ['25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38'];
        $this->clothesSizes['Boys']['Shirts'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Boys']['T-Shirts'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Boys']['Trousers'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Boys']['Coats'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Boys']['Jackets'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Boys']['Pyjamas'] = ['XS', 'S', 'M', 'L', 'XL'];
        $this->clothesSizes['Boys']['Footwear'] = ['25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38'];

    }


    public function __construct()
    {
        // initialize sizes
        $this->sizes();
        $this->productsImages = public_path('/assets/admin/images/products/');
        $this->logosImages = public_path('/assets/admin/images/logos/');
        // previous route
        $this->previousRoute = app('router')->getRoutes()->match(app('request')->create(URL::previous()))->getName();
        $setting = array(
            'directory' => $this->productsImages, // directory file compressed output
            'file_type' => array( // file format allowed
                'image/jpeg',
                'image/png',
                'image/gif'
            )
        );
        $this->imageCompressor = new ImgCompressor($setting);
        $this->options = array(
            'cluster' => 'eu',
            'encrypted' => true
        );
        // realtime laravel pusher
        $this->pusher = new Pusher(
            '9f182cebbd2f9bd2fde7',
            'a10fe92e966286a1f3fe',
            '366206',
            $this->options
        );
        $this->currentHost = 'http://' . request()->getHttpHost();
        $this->postContext = stream_context_create([
            'http' => [
                'method' => 'POST',
            ],
        ]);
    }

    public function tracking($orderID){
        if (Auth::guard('web')->user()->type == 'company') {
            $order = Order::where('orderID', $orderID)->with(['orderdetails' => function ($query) {
                $query->where('supplierID', Auth::guard('web')->user()->id);
            }])->first();
            if (count($order->orderdetails) == 0){
                return back();
            }
        }
     //   $this->pusher->trigger('tracking-channel', 'tracking', ['message' => 'Hello Omar']);
        return view('tracking',['orderID'=>$orderID]);
    }
    // HomePage after login
    public function index()
    {
        if (auth()->user()->type == 'admin') {
            $companies = User::where('type', 'company')->paginate(10);
            return view('adminPanel.companies', ['companies' => $companies]);
        } else {
            return redirect()->route('ApprovedProducts');
        }
    }


    // add Category request
    public function addCategory(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required|unique:categories'
        ]);
        $category = new Category();
        $category->pass($request);
        $category->save();
        return back();
    }

    // update Category request
    public function updateCategory(Request $request)
    {
        $category = Category::find($request->categoryID);
        $category->categoryName = $request->categoryName;
        $category->update();
        return back();
    }

    // delete Category request
    public function deleteCategory($categoryID)
    {
        $category = Category::find($categoryID);
        $category->delete();
        return back();
    }

    // categories view
    public function categoriesView()
    {
        $categories = Category::orderBy('categoryID')->paginate(10);
        return view('adminPanel.categories', ['categories' => $categories]);
    }

    // Approved Products View
    public function ApprovedProducts()
    {
        $allProducts = $this->getAllProducts(1);
        $products = null;
        if (count($allProducts) == 0) {
            $products = [];
        } else {
            $products = Product::with(['supplier', 'colors', 'category'])->where(function ($query) use ($allProducts) {
                foreach ($allProducts as $key => $product) {
                    if ($key == 0) {
                        $query->where('productID', $product->productID);
                    } else
                        $query->orWhere('productID', $product->productID);
                }
            })->paginate(10);
        }
        return view('adminPanel.products', ['products' => $products, 'allProducts' => $allProducts]);
    }

    // Waiting Products View

    public function WaitingProducts()
    {
        $allProducts = $this->getAllProducts(0);
        $products = null;
        if (count($allProducts) == 0) {
            $products = [];
        } else {
            $products = Product::with(['supplier', 'colors', 'category'])->where(function ($query) use ($allProducts) {
                foreach ($allProducts as $key => $product) {
                    if ($key == 0) {
                        $query->where('productID', $product->productID);
                    } else
                        $query->orWhere('productID', $product->productID);
                }
            })->paginate(10);
        }
        return view('adminPanel.products', ['products' => $products, 'allProducts' => $allProducts]);
    }

    // Rejected Products View

    public function RejectedProducts()
    {
        $allProducts = $this->getAllProducts(2);
        $products = null;
        if (count($allProducts) == 0) {
            $products = [];
        } else {
            $products = Product::with(['supplier', 'colors', 'category'])->where(function ($query) use ($allProducts) {
                foreach ($allProducts as $key => $product) {
                    if ($key == 0) {
                        $query->where('productID', $product->productID);
                    } else
                        $query->orWhere('productID', $product->productID);
                }
            })->paginate(10);
        }
        return view('adminPanel.products', ['products' => $products, 'allProducts' => $allProducts]);
    }

    // add new color view
    public function newColor($productID)
    {
        try {
            $productID = decrypt($productID);
            $product = Product::with(['category'])->where('productID', $productID)->first();
            if (!$product)
                return back();
            return view('adminPanel.add_new_color', ['product' => $product, 'clothesSizes' => $this->clothesSizes]);
        } catch (DecryptException $e) {
            return back();
        }
    }

    // single product view
    public function product($productName, $colorID, Request $request)
    {
        //  dd($colorID.' '.$request->colorID);
        // try {
        //$colorID = decrypt($colorID);
        $notifications = Notification::where('colorID', $colorID)->where('userID', Auth::guard('web')->user()->id)->get();
        foreach ($notifications as $notification) {
            $notification->seen = true;
            $notification->update();
        }
        $productID = Product::where('productName', $productName)->first();
        if ($productID)
            $productID = $productID->productID;
        $color = Color::find($colorID);
        //$productID = decrypt($productID);
        $categories = Category::all();
        $otherColors = Color::where('productID', $productID)->where('colorID', '!=', $colorID)->get();
        $product = Product::with(['category', 'supplier', 'colors' => function ($q) use ($colorID) {
            $q->where('colorID', $colorID);
        }, 'colors.images', 'colors.sizes'])->where('productID', $productID)->first();
        if (!$product)
            return redirect($this->defaultRedirection);
        $mainImage = Image::with('color')->where('colorID', $colorID)->where('type', 'main')->first();
        if ($mainImage) {
            for ($i = 1; $i < count($product->colors[0]->images); $i++) {
                if ($product->colors[0]->images[$i]->imageID == $mainImage->imageID) {
                    $temp = $product->colors[0]->images[$i];
                    $product->colors[0]->images[$i] = $product->colors[0]->images[0];
                    $product->colors[0]->images[0] = $temp;
                    break;
                }
            }
        } else {
            if (count($product->colors[0]->images) > 0) {
                $product->colors[0]->images[0]->type = 'main';
                $product->colors[0]->images[0]->update();
                $mainImage = Image::with('color')->where('colorID', $colorID)->where('type', 'main')->first();
            }
        }
        return view('adminPanel.single_product_view', ['product' => $product, 'colour' => $color, 'categories' => $categories, 'mainImage' => $mainImage, 'clothesSizes' => $this->clothesSizes, 'otherColors' => $otherColors]);
//        } catch (DecryptException $e) {
//            return back();
//        }
    }

    // update product request
    public function updateProduct(Request $request)
    {
        $Color = Color::find($request->colorID);
        if ($request->colorcode != $Color->colorcode) {
            $this->validate($request, [
                'colorcode' => 'unique:colors,colorcode,NULL,colorID,productID,' . $request->productID
            ]);
        }
        $sizes = $request->size;
        $quantities = $request->quantity;
        $newSizes = $request->newSize;
        $newQuantities = $request->newQuantity;
        $product = Product::where('productID',$request->productID)->with(['colors'=>function($query) use($request){
            $query->where('colorID',$request->colorID);
        },'colors.sizes'])->first();
        foreach ($product->colors[0]->sizes as $key => $size){
            $size->size = $sizes[$key];
            $size->pivot->availableUnits = $quantities[$key] ;
            $size->pivot->update();
            $size->update();
        }
        $product->colors[0]->colorcode = $request->colorcode;
        $product->colors[0]->update();
        $category = Category::where('categoryName', $request->category)->first();
        $product->pass($request);
        $category->products()->save($product);
        $files = $request->file('imageFile');
        if ($files) {
            foreach ($files as $file) {
                $image = new Image();
                $imageNo = null;
                if (!Image::all()->last())
                    $imageNo = 1;
                else
                    $imageNo = (Image::all()->last()->imageID) + 1;
                $imageName = $imageNo . '.' . $file->getClientOriginalExtension();
                $file->move($this->productsImages, $imageName);

                $this->imageCompressor->run($this->productsImages . '/' . $imageName, $file->getClientOriginalExtension(), 5);
                $image->image = $imageName;
                $Color->images()->save($image);
            }
            $Color->productStatus = 0;
            $Color->update();
            $url = route('product', ['productName' => $product->productName, 'colorID' => $Color->colorID]);
            $admins = User::where('type', 'admin')->get(['id']);
            $message = auth()->user()->name . ' company added a new image to a product';
            foreach ($admins as $admin) {
                $this->pusher->trigger((string)$admin->id, 'event', ['message' => $message, 'url' => $url]);
            }
            foreach ($admins as $admin) {
                $myNotifications = new Notification();
                $myNotifications->data = $message;
                $myNotifications->url = $url;
                $myNotifications->userID = $admin->id;
                $myNotifications->seen = false;
                $myNotifications->colorID = $Color->colorID;
                $myNotifications->save();
            }
        }

        for ($i = 0; $i < count($newSizes); $i++) {
            Size::insert(['size' => $newSizes[$i]]);
            Colorsize::insert(['colorID' => $request->colorID, 'sizeID' => Size::all()->last()->sizeID, 'availableUnits' => $newQuantities[$i]]);
        }

        return back();
    }

    // remove color request
    public function removeColor($productID, $colorID)
    {
        try {
            $productID = decrypt($productID);
            $colorID = decrypt($colorID);
            $product = Product::find($productID);
            if (!$product)
                return redirect($this->defaultRedirection);
            if (count($product->colors) == 1) {
                return $this->removeWholeProduct(encrypt($productID));
            }
            foreach ($product->colors as $color) {
                if ($color->colorID == $colorID) {
                    Cartproduct::where('colorID', $colorID)->delete();
                    foreach ($color->sizes as $size) {
                        $size->pivot->delete();
                        $size->delete();
                    }
                    foreach ($color->images as $image) {
                        $temp = $image->image;
                        $image->delete();
                        $file = $this->productsImages . $temp;
                        if (file_exists($file))
                            unlink($file);
                    }
                    $color->delete();
                    Notification::where('colorID', $colorID)->delete();
                    Favourite::where('productID', $productID)->delete();
                    break;
                }
            }
            return redirect($this->defaultRedirection);
        } catch (DecryptException $e) {
            return redirect($this->defaultRedirection);
        }
    }

    // add Products View
    public function addProductView()
    {
        $categories = Category::all();
        return view('adminPanel.add_product', ['categories' => $categories, 'clothesSizes' => $this->clothesSizes]);
    }

    // add Product request
    public function addProduct(Request $request)
    {
        $this->validate($request, [
            'productName' => 'unique:products'
        ]);
        $sizes = $request->size;
        $quantities = $request->quantity;
        $category = Category::where('categoryName', $request->category)->first();
        $product = new Product();
        $product->pass($request);
        $supplier = Supplier::where('supplierID', Auth::guard('web')->user()->id)->first();
        $category->products()->save($product);
        $supplier->products()->save($product);
        $color = new Color();
        $color->productStatus = 0;
        $color->colorcode = $request->color;
        $product->colors()->save($color);
        $files = $request->file('imageFile');
        foreach ($files as $file) {
            $image = new Image();
            $imageNo = null;
            if (!Image::all()->last())
                $imageNo = 1;
            else
                $imageNo = (Image::all()->last()->imageID) + 1;
            $imageName = $imageNo . '.' . $file->getClientOriginalExtension();
            $file->move($this->productsImages, $imageName);
            $this->imageCompressor->run($this->productsImages . '/' . $imageName, $file->getClientOriginalExtension(), 5);
            //  $this->imageCompressor->run($this->productsImages . '/' . $imageName, $file->getClientOriginalExtension(), 10);
//            if ($file->getClientOriginalExtension == 'png') {
//                $read_from_path = $destinationPath . '/' . $imageName;
//                $save_to_path = $destinationPath . '/' . $imageName;
//                $compressed_png_content = $this->imageCompressor->compress_png($read_from_path);
//                file_put_contents($save_to_path, $compressed_png_content);
//            }
            $image->image = $imageName;
            $color->images()->save($image);
        }
        for ($i = 0; $i < count($sizes); $i++) {
            Size::insert(['size' => $sizes[$i]]);
            Colorsize::insert(['colorID' => $color->colorID, 'sizeID' => Size::all()->last()->sizeID, 'availableUnits' => $quantities[$i]]);
        }
        $company = User::find(Auth::guard('web')->user()->id);
        $url = route('product', ['productName' => $product->productName, 'colorID' => $color->colorID]);
        $admins = User::where('type', 'admin')->get(['id']);
        $message = $company->name . ' company published a new product';
        foreach ($admins as $admin) {
            $this->pusher->trigger((string)$admin->id, 'event', ['message' => $message, 'url' => $url]);
        }
        foreach ($admins as $admin) {
            $myNotifications = new Notification();
            $myNotifications->data = $message;
            $myNotifications->url = $url;
            $myNotifications->userID = $admin->id;
            $myNotifications->seen = false;
            $myNotifications->colorID = $color->colorID;
            $myNotifications->save();
        }
        return redirect()->route('WaitingProducts');
    }

    // add product new color request
    public function addProductColor(Request $request)
    {
        $this->validate($request, [
            'colorcode' => 'unique:colors,colorcode,NULL,colorID,productID,' . $request->productID
        ]);
        $product = Product::find($request->productID);
        $sizes = $request->size;
        $quantities = $request->quantity;
        $color = new Color();
        $color->colorcode = $request->colorcode;
        $color->productStatus = 0;
        $product->colors()->save($color);
        $files = $request->file('imageFile');
        $flag = false;

        foreach ($files as $file) {
            $image = new Image();
            $imageNo = null;
            if (!Image::all()->last())
                $imageNo = 1;
            else
                $imageNo = (Image::all()->last()->imageID) + 1;
            $imageName = $imageNo . '.' . $file->getClientOriginalExtension();
            $file->move($this->productsImages, $imageName);
            $this->imageCompressor->run($this->productsImages . '/' . $imageName, $file->getClientOriginalExtension(), 5);
            //  $this->imageCompressor->run($this->productsImages . '/' . $imageName, $file->getClientOriginalExtension(), 10);
//            if ($file->getClientOriginalExtension == 'png') {
//                $read_from_path = $destinationPath . '/' . $imageName;
//                $save_to_path = $destinationPath . '/' . $imageName;
//                $compressed_png_content = $this->imageCompressor->compress_png($read_from_path);
//                file_put_contents($save_to_path, $compressed_png_content);
//            }
            $image->image = $imageName;
            $color->images()->save($image);
            if (!$flag)
                $image->type = 'main';
            $flag = true;
        }

        for ($i = 0; $i < count($sizes); $i++) {
            Size::insert(['size' => $sizes[$i]]);
            Colorsize::insert(['colorID' => $color->colorID, 'sizeID' => Size::all()->last()->sizeID, 'availableUnits' => $quantities[$i]]);
        }

        $company = User::find(Auth::guard('web')->user()->id);
        $url = route('product', ['productName' => $product->productName, 'colorID' => $color->colorID]);
        $admins = User::where('type', 'admin')->get(['id']);
        $message = $company->name . ' company added new color to a product';
        foreach ($admins as $admin) {
            $this->pusher->trigger((string)$admin->id, 'event', ['message' => $message, 'url' => $url]);
        }

        foreach ($admins as $admin) {
            $myNotifications = new Notification();
            $myNotifications->data = $message;
            $myNotifications->url = $url;
            $myNotifications->userID = $admin->id;
            $myNotifications->seen = false;
            $myNotifications->colorID = $color->colorID;
            $myNotifications->save();
        }

        return redirect($url);
    }

    // remove WholeProduct request
    public function removeWholeProduct($productID)
    {
        try {
            $productID = decrypt($productID);
            $product = Product::find($productID);
            if (!$product)
                return redirect($this->defaultRedirection);
            foreach ($product->colors as $color) {
                Cartproduct::where('colorID', $color->colorID)->delete();
                foreach ($color->sizes as $size) {
                    $size->pivot->delete();
                    $size->delete();
                }
                foreach ($color->images as $image) {
                    $temp = $image->image;
                    $image->delete();
                    $file = $this->productsImages . $temp;
                    if (file_exists($file))
                        unlink($file);
                }
                $color->delete();
                Notification::where('colorID', $color->colorID)->delete();
            }
            Favourite::where('productID', $productID)->delete();
            $product->delete();
            return redirect($this->defaultRedirection);
        } catch (DecryptException $e) {
            return redirect($this->defaultRedirection);
        }
    }

    // profile view
    public function profile()
    {
        $supplier = Supplier::find(Auth::guard('web')->user()->id);
        if (!$supplier)
            return redirect($this->defaultRedirection);
        return view('adminPanel.company_profile', ['supplier' => $supplier]);
    }

    // set main product photo request
    public function setMain($colorID, $imageID)
    {
        try {
            $colorID = decrypt($colorID);
            $imageID = decrypt($imageID);
            $Color = Color::find($colorID);
            if (!$Color)
                return redirect($this->defaultRedirection);
            foreach ($Color->images as $image) {
                if ($image->imageID == $imageID) {
                    $image->type = 'main';
                } else $image->type = '';
                $image->update();
            }
            return back();
        } catch (DecryptException $e) {
            return redirect($this->defaultRedirection);
        }
    }

    // remove product photo request
    public function removeImage($colorID, $imageID)
    {
        try {
            $colorID = decrypt($colorID);
            $imageID = decrypt($imageID);
            $Color = Color::find($colorID);
            foreach ($Color->images as $image) {
                if ($image->imageID == $imageID) {
                    $temp = $image->image;
                    $image->delete();
                    $file = $this->productsImages . $temp;
                    if (file_exists($file))
                        unlink($file);
                    break;
                }
            }
            return back();
        } catch (DecryptException $e) {
            return redirect($this->defaultRedirection);
        }
    }

    // approve product color
    public function approve($colorID)
    {
        try {
            $colorID = decrypt($colorID);
            $color = Color::find($colorID);
            $product = Product::find($color->productID);
            $supplier = Supplier::find($product->supplierID);
            $url = route('product', ['productName' => $product->productName, 'colorID' => $color->colorID]);
            $message = 'Your Product ( ' . $product->productName . ' ) had been approved';
            $this->pusher->trigger((string)$supplier->supplierID, 'event', ['message' => $message, 'url' => $url]);
            $myNotifications = new Notification();
            $myNotifications->data = $message;
            $myNotifications->url = $url;
            $myNotifications->userID = $supplier->supplierID;
            $myNotifications->seen = false;
            $myNotifications->colorID = $color->colorID;
            $myNotifications->save();
            $color->productStatus = 1;
            $color->update();
            return back();
        } catch (DecryptException $e) {
            return redirect($this->defaultRedirection);
        }
    }

    // reject product color
    public function reject($colorID)
    {
        try {
            $colorID = decrypt($colorID);
            $color = Color::find($colorID);
            $product = Product::find($color->productID);
            $supplier = Supplier::find($product->supplierID);
            $url = route('product', ['productName' => $product->productName, 'colorID' => $color->colorID]);
            $message = 'Your Product ( ' . $product->productName . ' ) had been rejected';
            $this->pusher->trigger((string)$supplier->supplierID, 'event', ['message' => $message, 'url' => $url]);
            $myNotifications = new Notification();
            $myNotifications->data = $message;
            $myNotifications->url = $url;
            $myNotifications->userID = $supplier->supplierID;
            $myNotifications->seen = false;
            $myNotifications->colorID = $color->colorID;
            $myNotifications->save();
            $color->productStatus = 2;
            $color->update();
            return back();
        } catch (DecryptException $e) {
            return redirect($this->defaultRedirection);
        }
    }

    // notifications view
    public function notifications()
    {
        return view('adminPanel.notifications');
    }

    // update profile request
    public function updateProfile(Request $request)
    {
        $supplier = Supplier::find(Auth::guard('web')->user()->id);
        $logo = $request->file('logo');
        $destinationPath = $this->logosImages;
        $imageName = $supplier->supplierName . $logo->getClientOriginalName();
        if ($logo) {
            if ($supplier->suppImage) {
                $file = $this->logosImages . $supplier->suppImage;
                if (file_exists($file))
                    unlink($file);
            }
            $logo->move($destinationPath, $imageName);
        }
        $supplier->supplierName = $request->supplierName;
        $supplier->Address = $request->address;
        $supplier->phoneNumber = $request->number;
        $supplier->Description = $request->description;
        $supplier->suppImage = $imageName;
        $supplier->update();
        return back();
    }

    public function cancelOrder($orderID){
        $orderDetails = Orderdetail::where('orderID',$orderID)->get();
        foreach ($orderDetails as $orderDetail){
            $color = Color::where('colorID',$orderDetail->colorID)->with(['sizes'])->first();
            foreach ($color->sizes as $size){
                if ($orderDetail->sizeID == $size->sizeID){
                    $size->pivot->availableUnits += $orderDetail->quantity;
                    $size->pivot->update();
                    break;
                }
            }
        }
        Orderdetail::where('orderID',$orderID)->delete();
        Order::where('orderID',$orderID)->delete();
        return back();
    }

//    public function cancelOrderProduct($orderDetailID){
//        $orderDetail = Orderdetail::find($orderDetailID);
//        $color = Color::where('colorID',$orderDetail->colorID)->with(['sizes'])->first();
//        foreach ($color->sizes as $size){
//            if ($orderDetail->sizeID == $size->sizeID){
//                $size->pivot->availableUnits += $orderDetail->quantity;
//                $size->pivot->update();
//                break;
//            }
//        }
//        Orderdetail::where('orderdetailsID',$orderDetailID)->delete();
//        return back();
//    }
    // orders view
    public function orders()
    {
        $orders = null;
        if (auth()->user()->type == 'admin') {
            $orders = Order::with(['customer'])->paginate(15);
        }
        else{
            $ordersToPaginate = Order::with(['orderdetails'=>function($query){
                $query->where('supplierID',auth()->user()->id);
            }])->get();
            $temp = [];
            foreach ($ordersToPaginate as $order){
                if (count($order->orderdetails) != 0){
                    $temp[] = $order;
                }
            }
            unset($ordersToPaginate);
            $ordersToPaginate = $temp;
            $orders = Order::with(['customer'])->where(function ($query) use ($ordersToPaginate){
                foreach ($ordersToPaginate as $key => $order){
                    if ($key == 0){
                        $query->where('orderID',$order->orderID);
                    }
                    else{
                        $query->orWhere('orderID',$order->orderID);
                    }
                }
            })->paginate(15);
        }
        return view('adminPanel.orders',['orders'=>$orders]);
    }

    public function orderDetails($orderID){
        $orderDetails = null;
        if (auth()->user()->type == 'company') {
            $orderDetails = Orderdetail::where('supplierID', auth()->user()->id)->where('orderID',$orderID)->with(['supplier','product','size','color'])->get();
        }
        else {
            $orderDetails = Orderdetail::where('orderID',$orderID)->with(['supplier','product','size','color'])->get();
        }
        if(count($orderDetails) == 0){
            return back();
        }
        $totalPrice = 0 ;
        foreach ($orderDetails as $orderDetail){
            $totalPrice += $orderDetail->quantity * $orderDetail->product->price;
        }
        return view('adminPanel.order_details',['orderDetails'=>$orderDetails,'totalPrice'=>$totalPrice]);
    }

    // get All Products with certain status
    public function getAllProducts($status){
        $allProducts = null;
        if (auth()->user()->type == 'admin') {
            $allProducts = Product::with(['supplier', 'colors' => function ($query)use ($status) {
                $query->where('productStatus',$status);
            }, 'category'])->get();
        } else {
            $allProducts = Product::with(['supplier', 'colors' => function ($query) use($status) {
                $query->where('productStatus', $status);
            }, 'category'])->where('supplierID', Auth::guard('web')->user()->id)->get();
        }
        $temp = [];
        foreach ($allProducts as $product) {
            if (count($product->colors) != 0) {
                $temp[] = $product;
            }
        }
        return $temp;
    }

}
