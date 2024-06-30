<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->notes;
        return view('notes.index', compact('notes'));
    }
    public function store(NoteRequest $request)
    {
        auth()->user()->notes()->create($request->validated());
        return redirect()->route('notes.index')->with('successCreate', 'Note added successfully');
    }
    public function update(NoteRequest $request, Note $note)
    {
        $note->update($request->validated());
        return redirect()->route('notes.index')->with('successUpdate', 'Note updated successfully');
    }
    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->route('notes.index')->with('successDelete', 'Note deleted successfully');
    }
}
