<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\Options;
use Illuminate\Support\Facades\Validator;

class AccountsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verified');
        $this->middleware('accountCreated', ['except' => ['create', 'store']]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        
        return view('accounts.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounts.create')->with('options', Options::accounts());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this::storeValidator($request->all())->validate();

        $user = auth()->user();

        $user->name = $request->input('name');
        $user->gender = $request->input('gender');
        $user->school = 'UMD';
        $user->major = $request->input('major');
        $user->year = $request->input('year');
        $user->account_created = 1;

        $user->save();

        return redirect('/account/'.str_replace(" ", "_", auth()->user()->name))->with('success', 'Account created');
    }

    /**
     * Display the specified resource.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function show($name = null)
    {
        $user = User::find($name == null ? auth()->user()->name : $name);

        if (empty($user)) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name))->with('error', 'User not found');
        }

        return view('accounts.view')->with('user', $user);
    }

    /**
     * Show the form for editing the default resource.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        $user = User::find($name);

        if (empty($user)) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name))->with('error', 'User not found');
        } else if (auth()->user()->id !== $user->id) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name))->with('error', 'Unauthorized page');
        }

        return view('accounts.edit')->with('user', $user)->with('options', Options::accounts());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this::updateValidator($request->all())->validate();

        $user = auth()->user();

        $user->gender = $request->input('gender');
        $user->major = $request->input('major');
        $user->year = $request->input('year');

        $user->save();

        return redirect('/account/'.str_replace(" ", "_", auth()->user()->name))->with('success', 'Account updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $user = User::find($name);

        if (empty($user)) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name))->with('error', 'User not found');
        } else if (auth()->user()->name !== $name) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name))->with('error', 'Unauthorized request');
        }

        $user->delete();
        return redirect('/login')->with('success', 'Account deleted');
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function storeValidator(array $data)
    {
        $options = Options::accounts();

        return Validator::make(
            $data,
            $rules = [
                'name' => ['required', 'string', 'regex:/^[ a-zA-Z0-9.-]*$/', 'max:255', 'unique:users'],
                'gender' => ['required', 'in:'.implode(',', $options['gender'])],
                'major' =>  ['required', 'in:'.implode(',', $options['major'])],
                'year' =>   ['required', 'in:'.implode(',', $options['year'])],
            ], 
            $messages = [
                'name.regex'    => 'Your name can only contain letters, spaces, numbers, periods, and dashes.',
            ]
        );
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function updateValidator(array $data)
    {
        $options = Options::accounts();

        return Validator::make(
            $data,
            $rules = [
                'gender' => ['required', 'in:'.implode(',', $options['gender'])],
                'major' =>  ['required', 'in:'.implode(',', $options['major'])],
                'year' =>   ['required', 'in:'.implode(',', $options['year'])],
            ], 
            $messages = []
        );
    }
}
