<?php

namespace App\Http\Controllers;

use App\Models\{Role, User, Permission};
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\User\{UserCollection,UserResource};
use Illuminate\Support\Facades\{DB,Storage};
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Manage users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Gestion des utilisateurs';
        $user_permissions = Permission::all();
        $user_roles = Role::all();
        if(request()->wantsJson()){return new UserCollection(User::index());}
        return view('user.index',['title' => $title, 'user_permissions' => $user_permissions,'user_roles' => $user_roles]);
    }

    /**
     * Search users.
     *
     * @param $field
     * @param $value
     * @return UserCollection
     */
    public function search($field,$value)
    {
        return new UserCollection(User::search($field,$value));
    }

    /**
     * Store new user.
     *
     * @param Request $request
     * @return UserResource|void
     */
    public function store(Request $request)
    {
        // Validate data
        $this->validateUser($request);
        // Store user
        DB::beginTransaction();
        try{
            $password = 'chance2021';
            $user = new User();
            if($request->file('avatar')){$user->avatar = $this->uploadAvatar($request);}
            $this->add($user);
            $user->password = $password;
            $user->parent_id = (User::whereNull('parent_id')->first())->id;
            $user->save();
            if(request('permissions')){
                $user->permissions()->sync(json_decode(request('permissions')));
            }
            if(request('roles')){
                $user->roles()->sync(json_decode(request('roles')));
            }
            DB::commit();
            return response()->json(['OK'],200);
        }catch (Exception $e){
            DB::rollback();
            return abort(500);
        }
    }

    /**
     * Update  user.
     *
     * @param Request $request
     * @param $id
     * @return UserResource|void
     */
    public function update(Request $request, $id)
    {
        // Validate data
        $this->validateUser($request,$id);
        // Update user
        DB::beginTransaction();
        try{
            $password = null;
            $user = User::findOrFail($id);
            if($request->password){$password = 'chance2021';}
            if ($request->file('avatar')){$user->avatar = $this->uploadAvatar($request,$user);}
            $this->add($user);
            if($request->password){$user->password = $password;}
            $user->save();
            if(request('permissions')){
                $user->permissions()->sync(json_decode(request('permissions')));
            }
            if(request('roles')){
                $user->roles()->sync(json_decode(request('roles')));
            }
            DB::commit();
            return response()->json(['OK'],200);
        }catch (Exception $e){
            DB::rollback();
            return abort(500);
        }
    }

    /**
     * Save user information.
     *
     * @param $user
     */
    private function add($user)
    {
        $user->name = request('name');
        $user->username = request('username');
        $user->phone = request('phone');
        $user->status = request('status');
    }

    /**
     * Destroy user.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id)
    {
        // Delete user
        DB::beginTransaction();
        try{
            $password = null;
            $user = User::findOrFail($id);
            $temp = $user;
            $user->permissions()->detach();
            $user->delete();
            DB::commit();
            return response()->json(['OK'],200);
        }catch (Exception $e){
            DB::rollback();
            return abort(500);
        }
    }

    /**
     * Validate user data.
     *
     * @param Request $request
     * @param bool $id
     * @return mixed
     */
    private function validateUser(Request $request,$id = null)
    {
        $rules =  ['name' => 'required'];
        if ($id){
            $rules['username'] = 'required|unique:users,username,'.$id;
            $rules['phone'] = 'required|unique:users,phone,'.$id;
        }else{
            $rules['username'] = 'required|unique:users';
            $rules['phone'] = 'required|unique:users';
        }
        return $request->validate($rules);
    }

    /**
     * Upload avatar.
     *
     * @param Request $request
     * @param string $user
     * @return string
     */
    private function uploadAvatar(Request $request,$user = null)
    {
        $avatar = $request->file('avatar');
        $path = $avatar->hashName('avatars');
        $image = Image::make($avatar);
        $image->fit(128, 128, function ($constraint) {$constraint->aspectRatio();});
        Storage::disk('public')->put($path, (string) $image->encode());
        if ($user && Storage::disk('public')->exists($user->avatar)){Storage::delete($user->avatar);}
        return $path;
    }
}
