<?php
namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Libraries\Message;
use App\Http\Services\Cms\LogService;
use App\Models\Cms\Mysql\User;
use App\Models\Cms\Mysql\Role;
use Exception;

class ProfileController extends Controller
{
    protected LogService $logService;
    protected Message $message;

    function __construct(LogService $logService, Message $message)
    {
        $this->message = $message;
        $this->logService = $logService;
    }

    public function updateProfileDetail(Request $req): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('updateUserDetail attempt started');

            $user = User::with(['roles'])
                    ->where('id', $req->route('id'))
                    ->first();

            if (!$user) {
                throw new Exception('user detail not found', 400);
            }

            $validatedData = $req->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    "unique:users,email,{$req->route('id')}",
                ],
                'password' => [
                    'string',
                    'min:8',
                    'confirmed:passwordConfirmation',
                ],
                'roleId' => [
                    'required',
                    'integer',
                    'exists:roles,id',
                ],
                'activeStatus' => [
                    'required',
                    'boolean',
                ],
            ]);

            $userBeforeUpdate = $user->toArray();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->is_active = $validatedData['activeStatus'];

            if (!empty($validatedData['password'])) {
                $user->password = bcrypt($validatedData['password']);
            }

            $role = Role::findOrFail($validatedData['roleId']);
            $user->syncRoles($role);
            $user->save();

            $this->logService
                ->setRequest($req)
                ->withContext([
                    'before_update' => $userBeforeUpdate,
                    'after_update' => $user->toArray()
                ])
                ->info('updateUserDetail success');

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('update User Detail successfully')
                ->toArray();

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $this->logService
                ->setRequest($req)
                ->setValidationException($e)
                ->warning('updateUserDetail failed due to validation');

            $response = $this->message
                ->setCode('error_validation')
                ->setMessageHead('Hmmmm something wrong')
                ->setMessageDetail($e->errors())
                ->toArray();

            return response()->json($response, 400);
        } catch (Exception $e) {
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('updateUserDetail process failed');

            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json(
                $response,
                $e->getCode()
            );
        }
    }

    public function getProfileDetail(Request $req): JsonResponse
    {
        try {
            $user = User::with([
                'roles' => function ($query): void {
                    $query->select(['id', 'name', 'guard_name']);
                }
            ])
                ->where('id', $req->route('id'))
                ->select([
                    'id',
                    'name',
                    'email',
                    'is_active',
                    'created_at',
                    'updated_at'
                ])
                ->first();

            if (!$user) {
                throw new Exception('user detail not found', 400);
            }

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('get users list successfully')
                ->setData($user->toArray())
                ->toArray();

            return response()->json($response, 200);
        } catch (Exception $e) {
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('get all user process failed');

            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json(
                $response,
                $e->getCode()
            );
        }
    }
}