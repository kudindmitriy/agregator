<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;

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

    public function register(Request $request)
    {
        $validation = Validator::make( $request->all(),[
            'email' => 'required|unique:users,email',
            'password' => 'required',
        ]);

        if ( $validation->fails() ){
            return response()->json(['errors' => ['This user is already registered ']], 401);
        }

        $user = User::create([
            'email'    => $request->email,
            'password' => $request->password,
            'name' => $request->name
        ]);
        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function login(): \Illuminate\Http\JsonResponse
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['errors' => ['You enter wrong email or password']], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ]);
    }
    public function getAuthenticatedUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        if ( !$user ) {
            return response()->json(['errors' => ['User not found']], 401);
        }
        return response()->json($user, 200);
    }
}
