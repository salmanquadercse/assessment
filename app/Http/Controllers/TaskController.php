<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of pending tasks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendingList()
    {
        // Get Pending Tasks
        $pendingTasks = Task::where('is_completed', false)->get();
        return response()->json($pendingTasks);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'is_completed' => 'boolean|sometimes',
            ]);
            $task = Task::create($validatedData);
            return response()->json($task, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }
    /**
     * Display the specified task.
     * (Not explicitly requested, but part of a standard API resource)
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    /**
     * Update the specified task in storage (used for marking as completed).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
    */
    public function update(Request $request, string $id)
    {
        // Mark Task as Completed
        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['message' => 'Task not found'], 404);
            }

            // Only allow updating is_completed for this specific requirement
            $validatedData = $request->validate([
                'is_completed' => 'required|boolean', // 'required' because this is the primary update for this endpoint
            ]);

            $task->update([
                'is_completed' => $validatedData['is_completed']
            ]);

            return response()->json($task);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Task update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified task from storage.
     * (Not explicitly requested, but part of a standard API resource)
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
