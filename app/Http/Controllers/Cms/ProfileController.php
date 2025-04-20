<?php
namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Hash;
use DB;

use App\Http\Libraries\Message;
use App\Http\Services\Cms\LogService;

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

            if (!$req->user()) 
            {
                throw new Exception('No User Detail was found', 400);
            }

            $validatedData = $req->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'oldPassword' => [
                    'string',
                    'min:8'
                ],
                'password' => [
                    'string',
                    'min:8',
                    'confirmed:passwordConfirmation'
                ]
            ]);

            $userBeforeUpdate = $req->user()->toArray();

            DB::beginTransaction();

            $req->user()->name = $validatedData['name'];

            if (!empty($validatedData['password'])) {
                if (!Hash::check($req->get('oldPassword'), $req->user()->password)) {
                    throw new Exception('Invalid Password', 400);
                }

                $req->user()->password = bcrypt($validatedData['password']);
            }

            $req->user()->save();

            DB::commit();

            $this->logService
                ->setRequest($req)
                ->withContext([
                    'before_update' => $userBeforeUpdate,
                    'after_update' => $req->user()->toArray()
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
            DB::rollBack();

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
                $e->getCode() ? $e->getCode() : 500
            );
        }
    }

    public function getProfileDetail(Request $req): JsonResponse
    {
        try {
            $response = $this->message
                ->setCode('success')
                ->setMessageHead('get profile detail successfully')
                ->setData($req->user() ? $req->user()->toArray() : [])
                ->toArray();

            return response()->json($response, 200);
        } catch (Exception $e) {
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('getProfileDetail process failed');

            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json(
                $response,
                $e->getCode() ? $e->getCode() : 500
            );
        }
    }
}