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

class UserController extends Controller
{
    protected LogService $logService;
    protected Message $message;

    function __construct(LogService $logService, Message $message)
    {
        $this->message = $message;
        $this->logService = $logService;
    }

    public function getUser(Request $req): JsonResponse
    {
        try {
            $user = User::with([
                'roles' => function ($query): void{
                    $query->select(['id','name','guard_name']);
                }
            ])
            ->select([
                'id', 'name', 'email', 'is_active', 'created_at', 'updated_at'
            ])
            ->orderBy('id', 'desc');

            if ($req->get("name")) 
            {
                $user->where('name', 'like', '%' . $req->get("name") . '%');
            }

            if ($req->get("email")) 
            {
                $user->where('email', 'like', '%' . $req->get("email") . '%');
            }

            if ($req->get("roleId")) 
            {
                $roleId = $req->get("roleId");
                $user->whereHas('roles', function ($q) use ($roleId): void {
                    $q->where('id', $roleId);
                });
            }

            if ($req->get("activeStatus")) 
            {
                $activeStatus = $req->get("activeStatus");
                $user->where('is_active', filter_var($activeStatus, FILTER_VALIDATE_BOOLEAN));
            }

            if ($req->get('paginate') == true) 
            {
                $user = $user->paginate($req->get('perPage') ?? 1);
            }
            else
            {
                $user = $user->get();
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
                500
            );
        }
    }

    public function addUser(Request $req): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('addUser attempt started');

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
                    'unique:users,email',
                ],
                'password' => [
                    'required',
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

            $newUser = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'is_active' => $validatedData['activeStatus'],
            ]);

            $role = Role::findOrFail($validatedData['roleId']);
            $newUser->assignRole($role);

            $this->logService
                ->setRequest($req)
                ->info('addUser success');

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('add user successfully for ' . $newUser->name)
                ->toArray();

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $this->logService
                ->setRequest($req)
                ->setValidationException($e)
                ->warning('addUser failed due to validation');

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
                ->error('addUser process failed');

            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json(
                $response,
                500
            );
        }
    }
}