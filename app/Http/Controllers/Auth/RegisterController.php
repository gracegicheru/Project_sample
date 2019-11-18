<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\AfricanStalkingRepository;
use App\User;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\GeneralNotification;




class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    public function register(){
        $credentials=request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:14'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        $user=new User;

        $user->name= $credentials['name'];
        $user->email= $credentials['email'];
        $user->phone= $credentials['phone'];
        $user->password= $credentials['password'];
        $user->save();


        Auth::login($user);
//        event(new Registered($user));
        $user =request()->user();
//        dd($user->email);
//        Notification::send(\request()->user(), new GeneralNotification2('user_registration',$user));
        $user->notify(new GeneralNotification('user_registration',$user));


        // $phone=$this->formatPhone('+254720032308');
     $phone=$user->phone;
//        dd($phone);
        $message='Registration Successful';

        //sending the verification code

        $africastalking = new AfricanStalkingRepository();
        $africastalking->sendMessage($phone,$message);


        return redirect('home');
    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
         User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user =request()->user();
        dd($user);
//
//        Auth::login($user);
//        event(new Registered($user));
//        Notification::send(\request()->user(), new generalNotification('user_registration',$slug ));


    }
    protected $redirectTo = '/home';
}
