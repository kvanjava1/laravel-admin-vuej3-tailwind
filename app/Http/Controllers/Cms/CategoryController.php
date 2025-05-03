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

    public function updateCategory(Request $req, int $id): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('updateCategory attempt started for ID: ' . $id);

            $category = Category::find($id);

            if (!$category) {
                throw new Exception('Category not found', 400);
            }

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
                    function ($attribute, $value, $fail) use ($id) {
                        if ($value == $id) {
                            $fail('A category cannot be its own parent.');
                        }
                    }
                ],
                'isActive' => [
                    'required',
                    'boolean',
                ]
            ]);

            $categoryBeforeUpdate = $category->toArray();

            DB::beginTransaction();

            $category->update([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'parent_id' => $validatedData['parentId'] ?? null,
                'is_active' => $validatedData['isActive'],
            ]);

            $category->refresh();

            DB::commit();

            $this->logService
                ->setRequest($req)
                ->withContext([
                    'before_update' => $categoryBeforeUpdate,
                    'after_update' => $category->toArray()
                ])
                ->info('updateCategory success for ID: ' . $id);

            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Category updated successfully for ' . $category->name)
                ->toArray();

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $this->logService
                ->setRequest($req)
                ->setValidationException($e)
                ->warning('updateCategory failed due to validation for ID: ' . $id);

            $response = $this->message
                ->setCode('error_validation')
                ->setMessageHead('Validation error')
                ->setMessageDetail($e->errors())
                ->toArray();

            return response()->json($response, 400);
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('updateCategory process failed for ID: ' . $id);

            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Update failed')
                ->setMessageDetail([$e->getMessage()])
                ->toArray();

            return response()->json(
                $response,
                $e->getCode() ? $e->getCode() : 500
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

    public function deleteCategory(string $id, Request $req): JsonResponse
    {
        try {
            $this->logService
                ->setRequest($req)
                ->debug('deleteCategory attempt started');
    
            $category = Category::with('children')->find($id);
    
            if (!$category) {
                $this->logService
                    ->setRequest($req)
                    ->withContext([
                        'error' => ['Category ' . $id . ' not found']
                    ])
                    ->error('deleteCategory failed');
    
                $response = $this->message
                    ->setCode('error_system')
                    ->setMessageHead('Category not found')
                    ->setMessageDetail(['Category with ID ' . $id . ' does not exist'])
                    ->toArray();
    
                return response()->json($response, 404);
            }
    
            $categoryBeforeDelete = $category->toArray();
    
            DB::beginTransaction();

            if ($category->children()->exists()) {
                $this->logService
                    ->setRequest($req)
                    ->withContext([
                        'error' => ['Cannot delete category with child categories']
                    ])
                    ->error('deleteCategory failed');

                throw new Exception('Cannot delete category with child categories', 422);
            }

            $category->delete();
    
            DB::commit();
    
            $this->logService
                ->setRequest($req)
                ->withContext([
                    'deleted_category' => $categoryBeforeDelete,
                    'deleted_at' => now()->toDateTimeString(),
                    'children_deleted' => $category->children->count()
                ])
                ->info('deleteCategory successful');
    
            $response = $this->message
                ->setCode('success')
                ->setMessageHead('Delete Category successfully')
                ->setMessageDetail([
                    'Category ' . $categoryBeforeDelete['name'] . ' has been deleted',
                ])
                ->toArray();
    
            return response()->json($response, 200);
        } catch (Exception $e) {
            DB::rollBack();
    
            $this->logService
                ->setRequest($req)
                ->setException($e)
                ->error('deleteCategory failed due to system error');
    
            $statusCode = $e->getCode() ?: 500;
            $errorDetails = [$e->getMessage()];
            
            if ($statusCode === 422) {
                $errorDetails[] = 'Please delete or reassign child categories first';
            }
    
            $response = $this->message
                ->setCode('error_system')
                ->setMessageHead('Delete failed')
                ->setMessageDetail($errorDetails)
                ->toArray();
    
            return response()->json($response, $statusCode);
        }
    }
}