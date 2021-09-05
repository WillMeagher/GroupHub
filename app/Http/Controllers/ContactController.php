<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guest()) {
            $request->merge(array('email' => auth()->user()->email, 'name' => auth()->user()->name));
        }

        $this::validator($request->all())->validate();

        $contact = new Contact;

        $contact->name      = $request->input('name');
        $contact->email     = $request->input('email');
        $contact->message   = $request->input('message');

        $contact->save();

        return redirect('/')->with('success', 'We have received your message. Thank you for the input.');
    }

    /**
     * Get a validator for an incoming create request.
     * 
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            $rules = [
                'name' =>       ['required', 'max:255'],
                'email' =>      ['required', 'max:255'],
                'message' =>    ['required', 'max:1024'],
            ], 
            $messages = []
        );
    }
}
