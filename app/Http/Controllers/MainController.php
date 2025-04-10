<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
       //Load user´s notes
       $id = session('user.id');
       $notes = User::find($id)->notes()->WhereNull('deleted_at')->get()->toArray();

       //show home view

       return view('home',['notes' => $notes]);
    }

    public function newNote(){
        return view('new_note');
    }

    public function newNoteSubmit(Request $request){
        //validate request
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000',
            ],
            [
                'text_title.required' => 'O titulo é Obrigatório',
                'text_title.min' => 'O titulo tem que ter no minimo :min caracteres',
                'text_title.max' => 'O titulo não pode ter mais de :max caracteres',

                'text_note.required' => 'A nota é Obrigatório',
                'text_note.min' => 'a nota tem que ter no minimo :min caracteres',
                'text_note.max' => 'a nota não pode ter mais de :max caracteres',
                
            ]
        );

        
        //get user id
        $id = session('user.id');

        //created new note
        $note = new Note();
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;

        $note->save();
        //redirect to home

        return redirect()->route('home');
    }

    public function editNote($id){
        $id = Operations::decryptId($id);

        $note = Note::find($id);

        return view('edit_note',['note'=>$note]);

    }

    public function editNoteSubmit(Request $request){
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000',
            ],
            [
                'text_title.required' => 'O titulo é Obrigatório',
                'text_title.min' => 'O titulo tem que ter no minimo :min caracteres',
                'text_title.max' => 'O titulo não pode ter mais de :max caracteres',

                'text_note.required' => 'A nota é Obrigatório',
                'text_note.min' => 'a nota tem que ter no minimo :min caracteres',
                'text_note.max' => 'a nota não pode ter mais de :max caracteres',
                
            ]
        );

        if($request->note_id == null){
            return redirect()->route('home');
        }

        $id = Operations::decryptId($request->note_id);

        $note = Note::find($id);

        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        return redirect()->route('home');
    }

    public function deletedNote($id){
        $id = Operations::decryptId($id);
        
        $note = Note::find($id);

        return view('deleted_note', ['note' => $note]);

    }

    public function deletedNoteConfirm($id){
        $id = Operations::decryptId($id);
        $note = Note::find($id);

        //Hard delete
        // $note->delete();

        //SoftDeleted

        //$note->deleted_at = date('Y:m:d H:i:s');
        //$note->save();

        //SoftDeleted com Model
        $note->delete();

        return redirect()->route('home');
    }
}
