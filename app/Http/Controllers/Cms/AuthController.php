<?php

namespace App\Http\Controllers\Cms;

use Exception; // Added this import
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Libraries\Message;
use App\Http\Services\Cms\LogService;
use App\Models\Cms\Mysql\User;

class AuthController extends Controller
{  
    protected LogService $logService; // Added property declaration
    protected Message $message; // Added property declaration

    function __construct(LogService $logService, Message $message)
    {
        $this->message = $message;
        $this->logService = $logService;
    }
    
    public function login(Request $req): JsonResponse
    {
       try {
            $req->validate([
                'email' => [
                    'required', 'email'
                ],
                'password' => [
                    'required'
                ],
            ]);

            $user = User::where('email', $req->email)
                    ->first();
    
            if (!$user || !Hash::check($req->password, $user->password)) {
                throw new Exception('Username or password did not match any record', 400);
            }
            
            $abilities = $user->getAllPermissions()
                        ->pluck('name')
                        ->toArray();

            $token = $user->createToken('cms_auth_token', $abilities);

            $response = $this->message
                            ->setCode('success')
                            ->setMessageHead('Success login')
                            ->setData([
                                'token' => $token->plainTextToken,
                                'abilities' => $abilities,
                                'profile' => [
                                    'name' => $user->name
                                ]
                            ])
                            ->toArray();

            return response()->json(
                $response, 200
            );
            
        } catch (ValidationException $e) {
            $response = $this->message
                            ->setCode('error_validation')
                            ->setMessageHead('Hmmmm something wrong')
                            ->setMessageDetail($e->errors())
                            ->toArray();

            return response()->json(
                $response, 400
            );
        } catch (Exception $e) {
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('Login process failed');                        
            
            $response = $this->message
                        ->setCode('error_system')
                        ->setMessageHead('Hmmm something wrong')
                        ->setMessageDetail([$e->getMessage()])
                        ->toArray();
            
            return response()->json(
                $response, $e->getCode()
            );
        }
    }

    public function check(Request $req): JsonResponse
    {
        try {
            $user = $req->user();
            $token = PersonalAccessToken::findToken($req->bearerToken());

            if (!$user || !$token) {
                throw new Exception('Invalid user or token');
            }

            $response = $this->message
                            ->setCode('success')
                            ->setMessageHead('Success login')
                            ->setData([
                                'token' => $req->bearerToken(),
                                'abilities' => $token->abilities, 
                                'profile' => [
                                    'name' => $user->name
                                ]
                            ])
                            ->toArray();

            return response()->json(
                $response, 200
            );

        } catch (Exception $e) {
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('Check auth process failed');

            $response = $this->message
                        ->setCode('error_system')
                        ->setMessageHead('Hmmm something wrong')
                        ->setMessageDetail([$e->getMessage()])
                        ->toArray();
            
            return response()->json(
                $response, 500
            );
        }
    }
}