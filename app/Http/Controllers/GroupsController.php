<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use \Validator;

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
     * Display a listing of the listed groups.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::allListed();

        return view('groups.index')->with('groups', $groups);
    }

    /**
     * Display a listing of all groups the user created.
     *
     * @return \Illuminate\Http\Response
     */
    public function created()
    {
        $groups = Group::created(auth()->user()->id);

        return view('groups.index')->with('groups', $groups)->with('title', 'Your Created Groups');
    }

    /**
     * Display a listing of all the groups a user has joined.
     *
     * @return \Illuminate\Http\Response
     */
    public function joined()
    {
        $groups = Group::joined(auth()->user()->id);

        return view('groups.index')->with('groups', $groups)->with('title', 'Your Joined Groups');
    }
    
    /**
     * Display the search groups page.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return view('groups.search')->with('options', self::dropdownOptions());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create')->with('options', self::dropdownOptions());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, self::createValidation(null, $request));

        $group = new Group;

        $group->name        = $request->input('name');
        $group->link        = $request->input('link');
        $group->platform    = $request->input('platform');
        $group->type        = $request->input('type');
        $group->privacy     = $request->input('privacy');
        $group->description = $request->input('description') == null ? "" : $request->input('description');
        $group->creator_id  = auth()->user()->id;
        $group->size  = 1;

        $group->save();

        return redirect('/group/created')->with('success', 'Group created');
    }

    /**
     * Returns a search with results baised on the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function results(Request $request)
    {
        $validator = Validator::make($request->all(), self::searchValidation($request));

        if ($validator->fails()) {
            return redirect('/group/search')->withErrors($validator)->withInput();
        }

        $groups = [];

        return view('groups.index')->with('groups', $groups)->with('title', 'Results');
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

        return view('groups.show')->with('group', $group);
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
        } else if (auth()->user()->id !== $group->creator_id && !Permission::isMember(auth()->user()->id, $group->id)) {
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
        
        return view('groups.edit')->with('group', $group)->with('options', self::dropdownOptions());
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

        $this->validate($request, self::createValidation($group->id, $request));

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
    
    protected static $sortbyDropdowns = [
        'Most Popular',
        'Newest',
        'Trending'
    ];

    /**
     * Get a validation rules for an incoming request.
     *
     * @param  int  $group_id
     * @param  array  $request
     * @return array
     */
    protected function createValidation($group_id, $request) 
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

    /**
     * Get a validation rules for an incoming request.
     *
     * @param  int  $group_id
     * @param  array  $request
     * @return array
     */
    protected function searchValidation($request) 
    {
        return [
            'search' =>         ['required', 'max:128'],
            'platform' =>       ['required', 'in:Any,'.implode(',', Self::$platformDropdowns)],
            'type' =>           ['required', 'in:Any,'.implode(',', Self::$typeDropdowns)],
            'privacy' =>        ['required', 'in:Any,'.implode(',', Self::$privacyDropdowns)],
            'sortby' =>         ['required', 'in:'.implode(',', Self::$sortbyDropdowns)]
        ];
    }

    /**
     * Get dropdown options for
     *
     * @param  int  $group_id
     * @param  array  $request
     * @return array
     */
    protected static function dropdownOptions() 
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
        foreach(Self::$sortbyDropdowns as $sortby) {
            $options['sortby'][$sortby] = $sortby;
        }

        return $options;
    }
}
