<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Info(
 *     title="Api Docs",
 *     version="1.0.0"
 * ),
 * @OA\PathItem(
 *     path="/api/"
 * )
 */

class NoteController extends Controller
{

    /**
     * @OA\Schema(
     *     schema="Note",
     *     required={"full_name", "phone_number", "email"},
     *     @OA\Property(property="full_name", type="string", example="Example Name"),
     *     @OA\Property(property="company", type="string", example="Company"),
     *     @OA\Property(property="phone_number", type="string", example="1234567890"),
     *     @OA\Property(property="email", type="string", example="example.name@example.com"),
     *     @OA\Property(property="date_birth", type="string", format="date", example="1990-11-10"),
     *     @OA\Property(property="path_to_photo", type="string", format="binary")
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/v1/notebook",
     *     summary="Get all notes",
     *     tags={"Notes"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="full_name", type="string"),
     *                 @OA\Property(property="company", type="string"),
     *                 @OA\Property(property="phone_number", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="date_birth", type="string", format="date"),
     *                 @OA\Property(property="path_to_photo", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             )
     *         )
     *     )
     * )
     */
    public function getAllNotes()
    {
        $notes = Note::all();
        return response()->json($notes, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/notebook",
     *     summary="Create a new note",
     *     tags={"Notes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name", "phone_number", "email"},
     *             @OA\Property(property="full_name", type="string", example="Example Name"),
     *             @OA\Property(property="company", type="string", example="Company"),
     *             @OA\Property(property="phone_number", type="string", example="1234567890"),
     *             @OA\Property(property="email", type="string", example="example.name@example.com"),
     *             @OA\Property(property="date_birth", type="string", format="date", example="1990-12-26"),
     *             @OA\Property(property="path_to_photo", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note has been created",
     *         @OA\JsonContent(
     *             @OA\Property(property="Success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Note has been created"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="full_name", type="string"),
     *                 @OA\Property(property="company", type="string"),
     *                 @OA\Property(property="phone_number", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="date_birth", type="string", format="date"),
     *                 @OA\Property(property="path_to_photo", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             )
     *         )
     *     )
     * )
     */
    public function createNote(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone_number' => 'required|string|digits_between:10,12',
            'email' => 'required|string|email|max:255',
            'date_birth' => 'nullable|date',
            'path_to_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('path_to_photo')) {
            $imagePath = $request->file('path_to_photo')->store('public/images');
            $data['path_to_photo'] = url(\Storage::url($imagePath));
        }

        $note = Note::create($data);

        if (!$note) {
            abort(500, 'Failed to create note');
        }

        return response()->json([
            'Success' => true,
            'message' => 'Note has been created',
            'data' => $note,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/notebook/{id}",
     *     summary="Get a note by ID",
     *     tags={"Notes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="full_name", type="string"),
     *             @OA\Property(property="company", type="string"),
     *             @OA\Property(property="phone_number", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="date_birth", type="string", format="date"),
     *             @OA\Property(property="path_to_photo", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found"
     *     )
     * )
     */
    public function getOneNote($id)
    {
        $note = Note::findOrFail($id);

        if (!$note) {
            return response()->json([
                "success" => false,
                "message" => "Note not found",
            ], 404);
        }

        return response()->json($note);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/notebook/{id}",
     *     summary="Update a note",
     *     tags={"Notes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name", "phone_number", "email"},
     *             @OA\Property(property="full_name", type="string", example="Example Name"),
     *             @OA\Property(property="company", type="string", example="Company"),
     *             @OA\Property(property="phone_number", type="string", example="1234567890"),
     *             @OA\Property(property="email", type="string", example="example.name@example.com"),
     *             @OA\Property(property="date_birth", type="string", format="date", example="1990-11-26"),
     *             @OA\Property(property="path_to_photo", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note has been updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="Success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Note has been updated"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="full_name", type="string"),
     *                 @OA\Property(property="company", type="string"),
     *                 @OA\Property(property="phone_number", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="date_birth", type="string", format="date"),
     *                 @OA\Property(property="path_to_photo", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found"
     *     )
     * )
     */
    public function updateNote($id, Request $request)
    {
        $note = Note::findOrFail($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note not found',
            ], 404);
        }

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone_number' => 'required|string|digits_between:10,12',
            'email' => 'required|string|email|max:255',
            'date_birth' => 'nullable|date',
            'path_to_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('path_to_photo')) {
            $imagePath = $request->file('path_to_photo')->store('public/images');
            $data['path_to_photo'] = url(\Storage::url($imagePath));
        }

        $note->update($data);

        if (!$note) {
            abort(500, 'Failed to update note');
        }

        return response()->json([
            'Success' => true,
            'message' => 'Note has been updated',
            'data' => $note,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/notebook/{id}",
     *     summary="Delete a note",
     *     tags={"Notes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note has been deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found"
     *     )
     * )
     */
    public function destroyNote($id)
    {
        $note = Note::findOrFail($id);
        $imagePath = str_replace('http://localhost:8000/storage/', 'public/', $note->path_to_photo); //http://localhost:8000/storage/ заменяется на адресс при публикации на хост, но вот это остаётся /storage/
        Storage::delete($imagePath);
        $note->delete();
        return response()->json([
            'Success' => true,
            'message' => 'Note has been deleted successfully'
        ], 200);
    }
}
