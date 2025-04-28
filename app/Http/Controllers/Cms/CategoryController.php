<?php
namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Exception;
use DB;

use App\Http\Libraries\Message;
use App\Models\Cms\Mysql\Category;
use App\Http\Services\Cms\LogService;

class CategoryController extends Controller
{
    protected LogService $logService;
    protected Message $message;

    function __construct(LogService $logService, Message $message)
    {
        $this->message = $message;
        $this->logService = $logService;
    }

    public function addCategory(Request $req): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('addCategory attempt started');

            $validatedData = $req->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'parentId' => [
                    'nullable',
                    'integer',
                    'exists:categories,id',
                ],
                'isActive' => [
                    'required',
                    'boolean',
                ]
            ]);

            DB::beginTransaction();

            $category = Category::create([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'parent_id' => $validatedData['parentId'] ?? null,
                'is_active' => $validatedData['isActive'],
            ]);

            DB::commit();

            $this->logService
                ->setRequest($req)
                ->info('addCategory success');

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Category added successfully for ' . $category->name)
                ->toArray();

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $this->logService
                ->setRequest($req)
                ->setValidationException($e)
                ->warning('addCategory failed due to validation');

            $response = $this->message
                ->setCode('error_validation')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail($e->errors())
                ->toArray();

            return response()->json($response, 400);
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('addCategory process failed');

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

    public function getAllCategory(Request $req)
    {
        try {
            $categories = Category::with('recursiveChildren')
                ->whereNull('parent_id')
                ->get();

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Get all Category successfully')
                ->setData($categories->toArray())
                ->toArray();

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $this->logService
                ->setRequest($req)
                ->setValidationException($e)
                ->warning('getAllCategory failed due to validation');

            $response = $this->message
                ->setCode('error_validation')
                ->setMessageHead('Hmmm something wrong')
                ->setMessageDetail($e->errors())
                ->toArray();

            return response()->json($response, 400);
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('getAllCategory process failed');

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