<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $user = auth()->user();
        return view('accounts.view')->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounts.create')->with('options', self::dropdownOptions());
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

        return redirect('/account')->with('success', 'Account created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        return view('accounts.edit')->with('user', $user)->with('options', self::dropdownOptions());
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

        return redirect('/account')->with('success', 'Account updated');    }

    protected static $genderDropdowns = [
        'Male',
        'Female',
        'Non-Binary'
    ];

    protected static $schoolDropdowns = [
        'UMD',
        'GC',
        'AMU'
    ];

    protected static $majorDropdowns = [
        'Computer Science',
        'Biology',
        'Electrical Engineering',
        'History'
    ];

    protected static $yearDropdowns = [
        'Undergraduate Freshman',
        'Undergraduate Sophomore',
        'Undergraduate Junior',
        'Undergraduate Senior',
        'Graduate Freshman',
        'Other'
    ];

    /**
     * Get a validation rules for an incoming request.
     *
     * @param  array  $request
     * @return array
     */
    protected function validationRules($request)
    {
        return [
            'gender' => ['required', 'in:'.implode(',', Self::$genderDropdowns)],
            'school' => ['required', 'in:'.implode(',', Self::$schoolDropdowns)],
            'major' =>  ['required', 'in:'.implode(',', Self::$majorDropdowns)],
            'year' =>   ['required', 'in:'.implode(',', Self::$yearDropdowns)],
        ];
    }

    private static function dropdownOptions() 
    {
        foreach(Self::$genderDropdowns as $gender) {
            $options['gender'][$gender] = $gender;
        }

        foreach(Self::$schoolDropdowns as $school) {
            $options['school'][$school] = $school;
        }

        foreach(Self::$majorDropdowns as $major) {
            $options['major'][$major] = $major;
        }

        foreach(Self::$yearDropdowns as $year) {
            $options['year'][$year] = $year;
        }

        return $options;
    }
}
