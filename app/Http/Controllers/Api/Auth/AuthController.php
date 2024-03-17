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

use App\Models\ {
	Gender,
    Maritalstatus,
    Skills,
    RelationModel,
    Documenttype
};

class AuthController extends Controller
{
// public function login(Request $request)
// {
//     // Validate incoming request
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//         'user_id' => 'required',
//     ]);

//     // Retrieve email and password from the request
//     $credentials = $request->only('email', 'password');
//     $user_id = $request->input('user_id');

//      // Check if the user exists in the database
//      $user = User::where('id', $user_id)->first();

//      if (!$user) {
//          return response()->json(['error' => 'User not found'], 404);
//      }

//     // Attempt to authenticate the user without updating remember_token
//     if (!Auth::attempt($credentials, $request->has('remember'))) {
//         return response()->json(['error' => 'Unauthorized'], 401);
//     }

//     // User is authenticated, generate JWT token
//     $user = Auth::user();
//     $token = JWTAuth::fromUser($user);

//     // Save token in the user's record
//     $user->update(['remember_token' => $token]);


//     // Return response with token and user details
//     return response()->json([
//         'status' => 'success',
//         'data' => $user,
//         // 'access_token' => $token,
//         'token_type' => 'bearer',
//     ]);

//     // Return response with token, user details, and user_id
//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'user_id' => $user->id,
//             'email' => $user->email,
//             'token_type' => 'bearer',
//         ],
//     ]);
// }
// public function login(Request $request)
// {
//     // Validate incoming request
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//         'user_id' => 'required',
//     ]);

//     // Retrieve email, password, and user_id from the request
//     $email = $request->input('email');
//     $password = $request->input('password');
//     $user_id = $request->input('user_id');

//     // Check if the user exists in the database
//     $user = User::find($user_id);

//     if (!$user) {
//         return response()->json(['error' => 'User not found'], 404);
//     }

//     // Check if the provided email matches the user's email
//     if ($user->email !== $email) {
//         return response()->json(['error' => 'Invalid email'], 401);
//     }

//     // Check if the provided password matches the user's password
//     if (!Hash::check($password, $user->password)) {
//         return response()->json(['error' => 'Invalid password'], 401);
//     }

//     if (!$user) {
//         return response()->json(['error' => 'User not found'], 404);
//     }

//     // Attempt to authenticate the user with email and password
//     if (!Auth::attempt(['email' => $email, 'password' => $password])) {
//         return response()->json(['error' => 'Unauthorized'], 401);
//     }

//     // Check if the authenticated user's ID matches the provided user_id
//     if (Auth::user()->id != $user_id) {
//         return response()->json(['error' => 'Unauthorized'], 401);
//     }

//     // User is authenticated, generate JWT token
//     $user = Auth::user();
//     $token = JWTAuth::fromUser($user);

//     // Save token in the user's record
//     $user->update(['remember_token' => $token]);

//     // Return response with token and user details
//     return response()->json([
//         'status' => 'success',
//         'data' => $user,
//         // 'access_token' => $token,
//         'token_type' => 'bearer',
//     ]);
// }
public function login(Request $request)
{
    // Validate incoming request
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Retrieve email and password from the request
    $email = $request->input('email');
    $password = $request->input('password');

    // Check if the user exists in the database
    $user = User::where('email', $email)->first();
    if (!$user) {
        return response()->json(['status' => 'False','error' => 'User not found'], 404);
    }

    // Check if the provided password matches the user's password
    if (!Hash::check($password, $user->password)) {
        return response()->json(['status' => 'False','error' => 'Invalid password'], 401);
    }

    // Attempt to authenticate the user with email and password
    if (!Auth::attempt(['email' => $email, 'password' => $password])) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // User is authenticated, generate JWT token
    $token = JWTAuth::fromUser($user);

    // Save token in the user's record
    $user->update(['remember_token' => $token]);

    // Return response with token and user details
    return response()->json([
        'status' => 'True',
        'message' => 'Login successfully',
        'data' => $user,
        // 'access_token' => $token,
        'token_type' => 'bearer',
        // 'expires_in' => auth()->factory()->getTTL() * 60,
    ]);
}
public function logout(Request $request)
{
    // Extract token from the request headers
    $token = $request->bearerToken();

    // Check if the token exists
    if (!$token) {
        return response()->json(['status' => 'False', 'error' => 'Token not provided'], 400);
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
public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'f_name' => 'required|string|max:255',
        'm_name' => 'nullable|string|max:255',
        'l_name' => 'nullable|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
        'number' => 'required|string|regex:/^[0-9]{10}$/',
        'designation' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'pincode' => 'nullable|string|max:10', 
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        
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
            $user->email = $request->email;
            // $user->password = $request->password;
            // $user->password = Hash::make($request->password
            $user->password = Hash::make($request->password // Using Hash facade directly
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

    public function getAllMasters(){
        try {
            $data = [];
            $data['gender'] = Gender::all();
            $data['maritalstatus'] = Maritalstatus::all();
            $data['skills'] = Skills::all();
            $data['relation'] = RelationModel::all();
            $data['documenttype'] = Documenttype::all();
        
            return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}
