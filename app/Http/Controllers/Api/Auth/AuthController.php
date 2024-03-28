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
        // 'device_id'=>'required',
    ]);

    $email = $request->input('email');
    $password = $request->input('password');
    // $device_id = $request->input('device_id');

    $user = User::where('email', $email)->first();
    if (!$user) {
        return response()->json(['status' => 'False','message' => 'User not found','error' => $e->getMessage()], 200);
    }

    // Check if the provided password matches the user's password
    if (!Hash::check($password, $user->password)) {
        return response()->json(['status' => 'False','message' => 'Invalid password','error' => $e->getMessage()], 200);
    }

    // Attempt to authenticate the user with email and password
    if (!Auth::attempt(['email' => $email, 'password' => $password])) {
        return response()->json(['error' => 'Unauthorized'], 200);
    }

    // if ($user->device_id == 'null') {
    //     $user->update(['device_id' => $device_id]);
    // }

    // if ($user->device_id != 'null' && $user->device_id != $device_id) {
    //     return response()->json(['status' => 'False', 'message' => 'Device Id  mismatch'], 200);
    // }
    
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
        return response()->json(['status' => 'False', 'message' => 'Token not provided','error' => $e->getMessage()], 200);
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
        return response()->json(['status' => 'False',  'error' => 'Failed to logout'], 500);
    }

    // Return a success response
    return response()->json(['status' => 'True', 'message' => 'Successfully logged out','error' => $e->getMessage()]);
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
