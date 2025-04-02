<?php
namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Libraries\Message;
use App\Http\Services\Cms\LogService;
use Exception;

class ExampleController extends Controller
{
    protected LogService $logService;
    protected Message $message;

    function __construct(LogService $logService, Message $message)
    {
        $this->message = $message;
        $this->logService = $logService;
    }

    public function detail(Request $req): JsonResponse
    {
        try {
            $response = $this->message
                ->setCode('success')
                ->setMessageHead('get users list successfully')
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
                $response, 500
            );
        }
    }

    public function store(Request $req): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('addUser attempt started');

            // logic here

            $this->logService
                ->setRequest($req)
                ->info('addUser success');

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('addUser successfully')
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