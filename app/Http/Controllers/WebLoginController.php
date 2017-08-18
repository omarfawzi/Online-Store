<?php
namespace App\Http\Controllers;
use App\Customer;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class WebLoginController extends Controller
{
    protected $redirectTo  ;
    public function __construct()
    {
        $this->redirectTo ;
    }
    public function login(Request $request)
    {
        $customer = Customer::where('email',$request->email)->first();
        if ($customer && Hash::check($request->password,$customer->password)){
            $this->guard()->login($customer);
            return back()->withErrors(['showProfile'=>'true']);
        }
        if (!$customer)
            return back()->withErrors(['email'=>"Mail doesn't exist"]);
        if (!Hash::check($request->password,$customer->password)){
            return back()->withErrors(['password'=>"Wrong Password"])->withInput(['email'=>$request->email]);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return back();
    }

    protected function guard()
    {
        return Auth::guard('customer');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $customer = Socialite::driver($provider)->user();
        $explodeName = explode(' ',$customer->name);
        $customer['firstName'] = $explodeName[0];
        $customer['lastName'] = $explodeName[1];
        $authUser = $this->findOrCreateUser($customer, $provider);
        $this->guard()->login($authUser);
        return redirect()->route('index')->withErrors(['showProfile'=>'true']);
    }

    public function findOrCreateUser($customer, $provider)
    {
        $customer = $customer->user;
        $authUser = Customer::where('provider_id', $customer['id'])->orWhere('email',$customer['email'])->first();
        if ($authUser) {
            return $authUser;
        }
        return Customer::create([
            'firstName'  => $customer['firstName'],
            'lastName'  => $customer['lastName'],
            'email'=>$customer['email'],
            'gender' => $customer['gender'],
            'provider' => $provider,
            'provider_id' => $customer['id']
        ]);
    }



}
