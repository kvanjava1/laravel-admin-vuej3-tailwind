<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;
use App\Models\Cms\Mysql\Role;
use App\Http\Libraries\Message;
use App\Http\Services\Cms\LogService;
use Exception;
use DB;

class RoleController extends Controller
{
    protected Message $message;
    protected LogService $logService;
    
    function __construct(Message $message, LogService $logService)
    {
        $this->message = $message;
        $this->logService = $logService;
    }

    public function roleDetail(Request $req): JsonResponse
    {
        try {
            $roles = Role::select(['id', 'name', 'guard_name'])
                ->where('guard_name', 'api')
                ->with([
                    'permissions' => function ($query) {
                        $query->select(['id', 'name', 'guard_name']);
                    }
                ])
                ->where('id', $req->route('id'))
                ->first();

            if (!$roles) {
                $response = $this->message->setCode('error_system')
                    ->setMessageHead('Hmmm something wrong')
                    ->setMessageDetail(['role detail was not found'])
                    ->toArray();

                return response()->json($response, 400);
            }

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Success')
                ->setData($roles->toArray())
                ->toArray();

            return response()->json($response, 200);
        } catch (Exception $e) {
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->warning('roleDetail failed');

            $response = $this->message->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json($response, 200);
        }
    }

    public function getRole(Request $req): JsonResponse
    {
        try {
            $roles = Role::select(['id', 'name', 'guard_name', 'created_at', 'updated_at'])
                ->where('guard_name', 'api')
                ->orderBy('id', 'desc');

            if ($req->get('name')) 
            {
                $roles = $roles->where('name', 'like', '%' . $req->get('name') . '%');
            }

            if ($req->get('paginate') == true) 
            {
                $roles = $roles
                    ->paginate($req->get('per_page') ?? 1);
            } 
            else 
            {
                $roles = $roles->get();
            }

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Success')
                ->setData($roles->toArray())
                ->toArray();

            return response()->json($response, 200);
        } catch (Exception $e) {
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('roles failed');

            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json($response, 500);
        }
    }

    public function getPermission(Request $req): JsonResponse
    {
        try {
            $permission = Permission::where('guard_name', 'api')
                ->get()
                ->mapToGroups(function ($permission) {
                    $group = explode('.', $permission->name)[0];
                    return [
                        $group => [
                            'id' => $permission->id,
                            'name' => $permission->name,
                        ]
                    ];
                })->toArray();

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Success')
                ->setData($permission)
                ->toArray();

            return response()->json($response, 200);
        } catch (Exception $e) {
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('permission failed');

            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json($response, 500);
        }
    }

    public function addRole(Request $req): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('addRole attempt started');

            $validated = $req->validate([
                'roleName' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:roles,name',
                ],
                'selectedPermission' => [
                    'nullable',
                    'array',
                ],
                'selectedPermission.*' => [
                    'string',
                    'exists:permissions,name',
                ],
            ]);

            DB::beginTransaction();

            $role = Role::create([
                'name' => $validated['roleName'],
                'guard_name' => 'api',
            ]);

            if (!empty($validated['selectedPermission'])) {
                $role->syncPermissions($validated['selectedPermission']);
            }

            DB::commit();

            $this->logService
                ->setRequest($req)
                ->info('addRole is successfully');

            $response = $this->message->setCode('success')
                ->setMessageHead('Success add role ' . $validated['roleName'])
                ->toArray();

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $this->logService
                ->setRequest($req)
                ->setValidationException($e)
                ->warning('addRole failed due to validation');

            $response = $this->message
                ->setCode('error_validation')
                ->setMessageHead('Hmmmm something wrong')
                ->setMessageDetail($e->errors())
                ->toArray();

            return response()->json($response, 400);
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('addRole failed due to system error');

            $response = $this->message->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json($response, 500);
        }
    }

    public function updateRole($id, Request $req): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('updateRole attempt started');

            $req->validate([
                'roleName' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'selectedPermission' => [
                    'nullable',
                    'array',
                ],
                'selectedPermission.*' => [
                    'string',
                    'exists:permissions,name',
                ],
            ]);

            $role = Role::with('permissions')
                ->find($id);

            if (!$role) {
                $this->logService
                    ->setRequest($req)
                    ->withContext(['errors' => 'Role id not found'])
                    ->warning('updateRole failed due to validation');

                $response = $this->message
                    ->setCode('error_validation')
                    ->setMessageHead('Hmmmm something wrong')
                    ->setMessageDetail(['Role id not found'])
                    ->toArray();

                return response()->json($response, 400);
            }

            $beforeUpdate = $role->toArray();

            DB::beginTransaction();

            $role->name = $req->get('roleName');

            if (!empty($req->get('selectedPermission'))) {
                $role->syncPermissions($req->get('selectedPermission'));
            }

            $role->save();

            DB::commit();

            $afterUpdate = $role->toArray();

            $this->logService
                ->setRequest($req)
                ->withContext([
                    'before_update' => $beforeUpdate,
                    'after_update' => $afterUpdate,
                ])
                ->info('updateRole is successfully');

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Success update role')
                ->setData($afterUpdate)
                ->toArray();

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $this->logService
                ->setRequest($req)
                ->setValidationException($e)
                ->warning('updateRole failed due to validation');

            $response = $this->message
                ->setCode('error_validation')
                ->setMessageHead('Hmmmm something wrong')
                ->setMessageDetail($e->errors())
                ->toArray();

            return response()->json($response, 400);
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('updateRole failed due to system error');

            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json($response, 500);
        }
    }

    public function deleteRole(string $id, Request $req): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('delete role attempt started');

            $role = Role::find($id);

            if (!$role) {
                $this->logService
                    ->setRequest($req)
                    ->withContext([
                        'error' => ['Role ' . $id . ' not found']
                    ])
                    ->error('DeleteRole failed');

                $response = $this->message
                    ->setCode('error_system')
                    ->setMessageHead('Role not found')
                    ->setMessageDetail(['Role with ID ' . $id . ' does not exist'])
                    ->toArray();

                return response()->json($response, 404);
            }

            $roleWillBeDeleted = $role->toArray();

            DB::beginTransaction();

            $role->delete();

            DB::commit();

            $this->logService
                ->setRequest($req)
                ->withContext([
                    'deleted_role' => $roleWillBeDeleted
                ])
                ->info('DeleteRole successfully');

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Success delete role ' . $roleWillBeDeleted['name'])
                ->toArray();

            return response()->json($response, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('deleteRole failed due to system error');

            $response = $this->message->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json($response, 500);
        }
    }
}