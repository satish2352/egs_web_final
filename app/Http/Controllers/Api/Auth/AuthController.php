<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// namespace App\Http\Controllers\Api\Auth;
// use Illuminate\Support\Facades\Hash; // Importing Hash facade
// use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

use Validator;
use App\Models\ {
	User
};
class AuthController extends Controller
{
public function login(Request $request)
{
    $credentials = $request->only('u_email', 'u_password');

    $user = User::where('u_email', $credentials['u_email'])->first();

    if (!$user || !Hash::check($credentials['u_password'], $user->u_password)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $token = $user->createToken('AuthToken')->plainTextToken;
    // Update the access token in the database
    $user->remember_token = $token;
    $user->save();

    return $this->responseWithToken($token, $user);
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out successfully']);
}


public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'f_name'=>'required',
        'u_email'=>'required',
        'u_password'=>'required',
    ]);

    if ($validator->fails()) {
        return $validator->errors()->all();
    } else {
        try {
            $user = new User(); // Assign $user instead of $news
            
            // Check if there are any existing records
            $existingRecord = User::first();
            
            $user->role_id = $request->role_id;
            $user->f_name = $request->f_name;
            $user->m_name = $request->m_name;
            $user->l_name = $request->l_name;
            $user->number = $request->number;
            $user->designation = $request->designation;
            $user->address = $request->address;
            $user->state = $request->state;
            $user->city = $request->city;
            $user->pincode = $request->pincode;
            // $user->ip_address = $request->ip_address;
            $user->u_email = $request->u_email;
            // $user->u_password = $request->u_password;
            // $user->u_password = Hash::make($request->u_password
            $user->u_password = Hash::make($request->u_password // Using Hash facade directly
        );

            $user->save();
            
            // Generate token for the registered user
            $token = auth()->login($user);

            // Return response with token and user details
            return $this->responseWithToken($token, $user);
            
            return response()->json(['status' => 'Success', 'message' => 'Uploaded successfully','statusCode'=>'200']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
public function responseWithToken($token, $user)
{
    return response()->json([
        'status' => 'success',
        'user' => $user,
        'access_token' => $token,
        'type' => 'bearer',
        // 'expires_in' => auth()->factory()->getTTL() * 60 // Multiply by 60 to convert minutes to seconds
    ]);
}

// public function responseWithToken($token, $user)
// {
//     return response()->json([
//         'status'=>'success',
//         'user'=>$user,
//         'access_token'=>$token,
//         'type'=>'bearer', // Corrected typo from 'beares' to 'bearer'
//         'expires_in' => auth()->factory()->getTTL() * 60 * 24

//     ]);
// }

}
