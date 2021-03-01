<?php

namespace App\Http\Controllers;


use App\Http\Resources\Osaas\RoleCollection;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
//80708679
class RoleController extends Controller
{
    /**
     * Manage roles.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Gestion des roles';
        $permissions = Permission::all();
        if(request()->wantsJson()){return new RoleCollection(Role::index());}
        return view('user.role',['title' => $title, 'user_permissions' => $permissions]);
    }

    /**
     * Store new role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function store(Request $request)
    {
        // Validate data
        $this->validateRole($request);
        // Store role
        DB::beginTransaction();
        try{
            $role = new Role();
            $this->add($role);
            $role->save();
            $role->permissions()->sync(json_decode(request('permissions')));
            DB::commit();
            return response()->json(['OK'],200);
        }catch (Exception $e){
            DB::rollback();
            return abort(500);
        }
    }

    /**
     * Update  role.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function update(Request $request, $id)
    {
        // Validate data
        $this->validateRole($request,$id);
        // Update role
        DB::beginTransaction();
        try{
            $role = Role::findOrFail($id);
            $this->add($role);
            $role->save();
            $role->permissions()->sync(json_decode(request('permissions')));
            DB::commit();
            return response()->json(['OK'],200);
        }catch (Exception $e){
            DB::rollback();
            return abort(500);
        }
    }

    /**
     * Save role information.
     *
     * @param $role
     */
    private function add($role)
    {
        $role->name = request('name');
        $role->slug = Str::slug(request('name'));
    }

    /**
     * Destroy role.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id)
    {
        // Delete role
        DB::beginTransaction();
        try{
            $password = null;
            $role = Role::findOrFail($id);
            $role->delete();
            DB::commit();
            return response()->json(['OK'],200);
        }catch (Exception $e){
            DB::rollback();
            return abort(500);
        }
    }


    /**
     * Validate role data.
     *
     * @param Request $request
     * @param bool $id
     * @return mixed
     */
    private function validateRole(Request $request,$id = null)
    {
        $rules =  ['permissions' => 'required'];
        /*if ($id){
            $rules['name'] = 'required|unique:roles,'.$id;
        }else{
            $rules['name'] = 'required|unique:roles';
        }*/
        return $request->validate($rules);
    }
}
