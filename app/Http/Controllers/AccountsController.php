<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\Options;

class AccountsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        $this->validate($request, self::validationRules($request));

        $user = auth()->user();

        $user->gender = $request->input('gender');
        $user->school = $request->input('school');
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
        $this->validate($request, self::validationRules($request));

        $user = auth()->user();

        $user->gender = $request->input('gender');
        $user->school = $request->input('school');
        $user->major = $request->input('major');
        $user->year = $request->input('year');

        $user->save();

        return redirect('/account/'.str_replace(" ", "_", auth()->user()->name))->with('success', 'Account updated');
    }

    /**
     * Get a validation rules for an incoming request.
     *
     * @param  array  $request
     * @return array
     */
    protected function validationRules($request)
    {
        $options = Options::accounts();

        return [
            'gender' => ['required', 'in:'.implode(',', $options['gender'])],
            'school' => ['required', 'in:'.implode(',', $options['school'])],
            'major' =>  ['required', 'in:'.implode(',', $options['major'])],
            'year' =>   ['required', 'in:'.implode(',', $options['year'])],
        ];
    }
}
