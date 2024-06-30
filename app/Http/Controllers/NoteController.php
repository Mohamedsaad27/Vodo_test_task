<?php
namespace App\Http\Controllers;

use App\Models\Note;
use App\Traits\HandleApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    use HandleApiResponse;
    public function index()
    {
        try {
            $user = Auth::user();
            $notes = $user->notes()->get();
            if ($notes->isEmpty()){
                return $this->errorResponse('No notes founded',404);
            }
            return $this->successResponse($notes,'Notes retrieved successfully',200);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);
        }
    }
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }
            $validatedData = $validator->validated();
            $note = $user->notes()->create([
                'content' => $validatedData['content'],
            ]);
            return $this->successResponse($note, 'Note created Successfully', 201);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }
    public function show(Note $note)
    {
        try {
            if ($note->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized',403);
            }
            return $this->successResponse($note,'Note retrieved successfully',200);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);
        }
    }
    public function update(Request $request, Note $note)
    {
        try {
            if ($note->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized',403);
            }
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }
            $validatedData = $validator->validated();
            $note->update([
                'content' => $validatedData['content'],
            ]);
            return $this->successResponse($note,'Note updated successfully',200);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);
        }
    }
    public function destroy($id)
    {
        try {
            $note = Note::find($id);
            if (!$note){
                return $this->errorResponse('Note not found',404);
            }
            if ($note->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized',403);
            }
            $note->delete();
            return response()->json(['success' => true, 'message' => 'Note deleted successfully']);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);
        }
    }
}
