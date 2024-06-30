<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->notes;
        return view('notes.index', compact('notes'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);
        auth()->user()->notes()->create([
            'content' => $request->content,
        ]);
        return redirect()->route('notes.index')->with('success', 'Note added successfully');
    }
    public function update(Request $request, Note $note)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);
        $note->update([
            'content' => $request->input('content'),
        ]);
        return redirect()->route('notes.index')->with('success', 'Note updated successfully');
    }
    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Note deleted successfully');
    }
}
