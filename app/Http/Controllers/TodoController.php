<?php

namespace App\Http\Controllers;

use App\Http\Services\TodoService;
use App\Jobs\MailReport;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct(protected TodoService $todoService)
    {
    }

    public function index()
    {
        return response()->json(Todo::all());
    }

    public function store(Request $request)
    {
        try {
            $todo = $this->todoService->store($request);
            return response()->json([
                'message' => "Todo created successfully!",
                'todo' => $todo
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], responseStatusCode($exception->getCode()));
        }
    }

    public function update(Request $request, Todo $todo)
    {
        try {
            $this->todoService->store($request, $todo);
            return response()->json([
                'message' => "Todo updated successfully!"
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], responseStatusCode($exception->getCode()));
        }
    }

    public function destroy(Todo $todo)
    {
        try {
            $todo->delete();
            return response()->json([
                'message' => "Todo deleted successfully!"
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], responseStatusCode($exception->getCode()));
        }
    }

    public function exportTodos()
    {
        try {
            MailReport::dispatch();
            return response()->json([
                'message' => "Report sent to email successfully!"
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], responseStatusCode($exception->getCode()));
        }
    }
}

