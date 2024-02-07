<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'user_username';
    }

    public function logout(Request $request) 
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($request->ajax()) {
            return response()->json(['message' => 'Logged out successfully']);
        }
        return redirect('/login');
    }

    public function login(Request $request)
    {   
        $input = $request->all();
        $this->validate($request, [
            'user_username' => 'required',
            'password' => 'required',
        ]);
     
        if(auth()->attempt(array('user_username' => $input['user_username'], 'password' => $input['password'])))
        {
            if (auth()->user()) {
                return redirect()->route('dashboard.index');
            }
        }else{
            return redirect()->route('login')
                ->with('error','Check Your Username and Password.');
        }   
    }

    public function loginapi(Request $request)
    {   
        $input = $request->all();

        $this->validate($request, [
            'user_username' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['user_username','password']);
        if(auth()->attempt($credentials))
        {
            $user = User::where('user_username',$input['user_username'])->first();
            $authToken = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'data' => $user,
                'token' => $authToken,
            ], status: 200); 
        }else{
            return response()->json([
                'message' => 'Invalid Data',
            ], status: 422);
        }
          
    }
}
