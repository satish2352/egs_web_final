<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use JWTAuth;
use Validator;
use App\Models\ {
	User
};
class AuthController extends Controller
{

public function login(Request $request){
   
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'imei_no'=>'required',
    ]);

    $email = $request->input('email');
    $password = $request->input('password');
    $imei_no = $request->input('imei_no');

    $user = User::where('email', $email)->first();
    if (!$user) {
        return response()->json(['status' => 'False','error' => 'User not found'], 200);
    }

    // Check if the provided password matches the user's password
    if (!Hash::check($password, $user->password)) {
        return response()->json(['status' => 'False','error' => 'Invalid password'], 200);
    }

    // Attempt to authenticate the user with email and password
    if (!Auth::attempt(['email' => $email, 'password' => $password])) {
        return response()->json(['error' => 'Unauthorized'], 200);
    }

    if ($user->imei_no === 'null') {
        $user->update(['imei_no' => $imei_no]);
    }

    if ($user->imei_no !== null && $user->imei_no !== $imei_no) {
        return response()->json(['status' => 'False', 'error' => 'IMEI number mismatch'], 200);
       }
    
    $token = JWTAuth::fromUser($user);

    $user->update(['remember_token' => $token]);

    // Return response with token and user details
    return response()->json([
        'status' => 'True',
        'message' => 'Login successfully',
        'data' => $user,
        // 'access_token' => $token,
        'token_type' => 'bearer',
        // 'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 365 * 10, // 10 years
        // 'expires_in' => auth()->factory()->getTTL() * 60,
    ]);
}
public function logout(Request $request)
{
    // Extract token from the request headers
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['status' => 'False', 'error' => 'Token not provided'], 200);
    }

    try {
        // Invalidate the token
        JWTAuth::setToken($token)->invalidate();
        
        // Get the authenticated user
        $user = auth()->user();

        // Update the remember_token to null
        $user->update(['remember_token' => null]);

    } catch (\Exception $e) {
        // If token invalidation or user update fails, return an error response
        return response()->json(['status' => 'False', 'error' => 'Failed to logout'], 500);
    }

    // Return a success response
    return response()->json(['status' => 'True', 'message' => 'Successfully logged out']);
}

// public function responseWithToken($token, $user)
// {
//     return response()->json([
//         'status' => 'success',
//         'user' => $user,
//         'access_token' => $token,
//         'type' => 'bearer',
//         // 'expires_in' => auth()->factory()->getTTL() * 60 // Multiply by 60 to convert minutes to seconds
//     ]);
// }

}
