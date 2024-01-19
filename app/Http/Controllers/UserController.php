<?php


namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CodeRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\updateProfileRequest;
use App\Http\Requests\VerifiyEmailRequest;
use App\Mail\SendMailable;
use App\Traits\ApiTrait;
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{

    public function __construct()
    {
         $this->middleware('auth:api');
    }

    public function getdata(Request $request)
    {
       
        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            return ApiTrait::data(compact('user'));
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>"id is invalid"],'Unprocessable content',200);
        }
    }
    

    public function getuserByemail($email)
    {
       
        try {
           
            $user = User::Where('email',$email)->get();
            if(Sizeof($user) != 1)
            {
                return ApiTrait::errorMessage(['email'=>"email is invalid"],'email is invalid',200);
            }
            else
            {
                $user = $user[0];
                return ApiTrait::data(compact('user'));
            }
           
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>"id is invalid"],'Unprocessable content',200);
        }
    }


    public function getuserByid($id)
    {
       
        try {
           
            $user = User::Where('id',$id)->get();
            if(Sizeof($user) != 1)
            {
                return ApiTrait::errorMessage(['id'=>"id is invalid"],'id is invalid',200);
            }
            else
            {
                $user = $user[0];
                return ApiTrait::data(compact('user'));
            }
           
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>"id is invalid"],'Unprocessable content',200);
        }
    }

    public function upadteuserprofile(updateProfileRequest $request)
    {
        
        try {
            $User = User::findOrFail($request->id);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>'The Given Id Is Invalid'],'Unprocessable content',200);
        }
        $data = $request->except('photo');
        
        if ($request->hasFile('photo')) {

            try {

                $path = Media::upload('',$request->file('photo'),'users/images');
                if($path != null)
                {
                   
                    $data['photo'] = $path;
                //    Media::delete("images/users/{$request->image}");
                    
                }
              
                } catch (\Exception $e) {
                    return ApiTrait::errorMessage(["id"=>$path, "name"=>$path],'Ivalid Img File',200);
                // return ApiTrait::errorMessage(['path'=>],'img Not Uploaded content',200);
                }

            }
        // update product into database
        try {
            User::where('id', $request->id)->update($data);
            return ApiTrait::successMessage('Profile Updated Successfully',200);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage([],'Some Thing Error',422);
        }
    
    }


      
    public function ChangePassword(ChangePasswordRequest $request)
    {
        
        try {
            if(Hash::check($request->old_password, Auth::user()->password))
            {
                $user = User::find(Auth::user()->id);
                $user->password = Hash::make($request->new_password);
                if ($user->save()) {
                    return  ApiTrait::successMessage('Your Password Updated',200);
            } 
           
                return ApiTrait::errorMessage([],'Some Thing Error',200);
           
            }
            return ApiTrait::errorMessage([],'Invalid Password',200);
         }catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>'The Given Id Is Invalid'],'Unprocessable content',422);
        }
       
    
    }


    public function Deleteuserprofile($id)
    {
        
        try {
            $User = User::findOrFail($id);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>'The Given Id Is Invalid'],'Unprocessable content',200);
        }

        try {
            User::where('id', $id)->delete();
            return ApiTrait::successMessage('Profile Deleted Successfully',200);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage([],'Some Thing Error',422);
        }
    
    }

}
