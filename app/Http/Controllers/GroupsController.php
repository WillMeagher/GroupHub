<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

// for error logging
use Illuminate\Support\Facades\Log;

class GroupsController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('accountCreated', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::getAllListed();

        return view('groups.index')->with('groups', $groups);
    }

    /**
     * Display a listing of all groups the user created.
     *
     * @return \Illuminate\Http\Response
     */
    public function createdGroups()
    {
        $groups = Group::userCreatedGroups(auth()->user()->id);

        return view('groups.index')->with('groups', $groups);
    }

    /**
     * Display a listing of all the groups the user joined.
     *
     * @return \Illuminate\Http\Response
     */
    public function joinedGroups()
    {
        $groups = Group::userJoinedGroups(auth()->user()->id);

        return view('groups.index')->with('groups', $groups);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create')->with('options', self::dropDownOptions());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, self::createValidationRules(null, $request));

        $group = new Group;

        $group->name        = $request->input('name');
        $group->link        = $request->input('link');
        $group->platform    = $request->input('platform');
        $group->type        = $request->input('type');
        $group->privacy     = $request->input('privacy');
        $group->description = $request->input('description') == null ? "" : $request->input('description');
        $group->creator_id  = auth()->user()->id;

        $group->save();

        return redirect('/group/created')->with('success', 'Group created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            return redirect('/group')->with('error', 'Group not found');
        } else if ($group->privacy == 'delisted' && auth()->user()->id !== $group->creator_id) {
            return redirect('/group')->with('error', 'Unauthorized page');
        }

        return view('groups.show')->with('group', $group)->with('size', GroupPermission::size($group->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function join($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            return redirect('/group')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id && !GroupPermission::isMember(auth()->user()->id, $group->id)) {
            return redirect('/group')->with('error', 'Unauthorized page');
        }

        return view('groups.join')->with('group', $group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            return redirect('/group/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/group/created')->with('error', 'Unauthorized page');
        }
        
        return view('groups.edit')->with('group', $group)->with('options', self::dropDownOptions());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            return redirect('/group/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/group/created')->with('error', 'Unauthorized page');
        }

        $this->validate($request, self::createValidationRules($group->id, $request));

        $group->name        = $request->input('name');
        $group->link        = $request->input('link');
        $group->platform    = $request->input('platform');
        $group->type        = $request->input('type');
        $group->privacy     = $request->input('privacy');
        $group->description = $request->input('description') == null ? "" : $request->input('description');

        $group->save();

        return redirect('/group/created')->with('success', 'Group updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            return redirect('/group/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/group/created')->with('error', 'Unauthorized page');
        }

        $group->delete();

        return redirect('/group/created')->with('success', 'Group deleted');
    }

    protected function createValidationRules($group_id, $request) 
    {
        return [
            'name' =>           ['required', Rule::unique('groups')->ignore($group_id)],
            'link' =>           ['required', Rule::unique('groups')->ignore($group_id)],
            'platform' =>       ['required', 'in:'.implode(',', Self::$platformDropdowns)],
            'type' =>           ['required', 'in:'.implode(',', Self::$typeDropdowns)],
            'privacy' =>        ['required', 'in:'.implode(',', Self::$privacyDropdowns)],
            'description' =>    ['max:1024']
        ];
    }
    
    protected static $platformDropdowns = [
        'Discord',
        'Groupme',
        'Instagram',
        'Facebook',
        'Other'
    ];

    protected static $typeDropdowns = [
        'Class',
        'Sports',
        'Club',
        'Other'
    ];

    protected static $privacyDropdowns = [
        'Public',
        'Private',
        'Delisted'
    ];

    private static function dropDownOptions() 
    {
        foreach(Self::$platformDropdowns as $platform) {
            $options['platform'][$platform] = $platform;
        }
        foreach(Self::$typeDropdowns as $type) {
            $options['type'][$type] = $type;
        }
        foreach(Self::$privacyDropdowns as $privacy) {
            $options['privacy'][$privacy] = $privacy;
        }

        return $options;
    }
}
