<?php
namespace App\Http\Controllers;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WebRegisterController extends Controller
{
    protected $redirectPath;
    public function __construct()
    {
        $this->redirectPath = '/';
    }

    public function register(Request $request)
    {
        //Validates data
        $this->validator($request->all())->validate();
        //Create $customer
        $customer = $this->create($request->all());
//
//        //Authenticates $customer
        $this->guard()->login($customer);

        //Redirects sellers
        return back();
    }

    protected function validator(array $request)
    {
        $validator =  Validator::make($request, [
            'email' => 'required|email|max:255|unique:customers',
            'password' => 'required|min:6|confirmed',
            'gender' =>'required'
        ]);
        if ($validator->fails()) {
            $validator->after(function ($validator) {
                $validator->errors()->add('status', 'register');
            });
        }
        return $validator;
    }

    protected function create(array $data)
    {
        return Customer::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' =>$data['gender']
        ]);
    }

    protected function guard()
    {
        return Auth::guard('customer');
    }
}