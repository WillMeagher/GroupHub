<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Group;
use App\Models\Permission;
use App\Helpers\Options;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Support\Facades\Log;

// TODO look into filtering out old groups
// TODO add notification for user joining public group
// TODO look into what happens to outstanding requests when groups are transitioned form private to public

class GroupsController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'search', 'results']]);
        $this->middleware('accountCreated', ['except' => ['index', 'show', 'search', 'results']]);
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
    public function created($username)
    {
        $groups = auth()->user()->name == $username ? Group::allCreated($username) : Group::listedCreated($username);

        if (empty($groups)) {
            return redirect('/search')->with('error', 'User not found');
        }

        if (auth()->user()->name == $username) {
            return view('groups.index')->with('groups', $groups)->with('title', 'Your Created Groups');
        } else {
            return view('groups.index')->with('groups', $groups)->with('title', $username."'s Created Groups");
        }
    }

    /**
     * Display a listing of all the groups a user has joined.
     *
     * @return \Illuminate\Http\Response
     */
    public function joined($username)
    {
        $groups = Group::joined($username);

        return view('groups.index')->with('groups', $groups)->with('title', 'Your Joined Groups');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create')->with('options', Options::groups());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this::validator(null, $request->all())->validate();

        $group = new Group;

        $group->name        = $request->input('name');
        $group->link        = $request->input('link');
        $group->platform    = $request->input('platform');
        $group->type        = $request->input('type');
        $group->privacy     = $request->input('privacy');
        $group->description = $request->input('description') == null ? "" : $request->input('description');
        $group->creator_id  = auth()->user()->id;
        $group->size        = 1;

        $group->save();

        return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('success', 'Group created');
    }

    /**
     * Display the specified resource.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $group = Group::find($name);

        if (empty($group)) {
            return redirect('/search')->with('error', 'Group not found');
        } else if ($group->privacy == 'Delisted' && auth()->user()->id !== $group->creator_id) {
            return redirect('/search')->with('error', 'Unauthorized page');
        }

        return view('groups.show')->with('group', $group);
    }

    /**
     * Display the specified resource.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function join($name)
    {
        $group = Group::find($name);

        if (empty($group)) {
            return redirect('/search')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id && !Permission::isMember(auth()->user()->id, $group->id)) {
            return redirect('/search')->with('error', 'Unauthorized page');
        }

        return view('groups.join')->with('group', $group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        $group = Group::find($name);

        if (empty($group)) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('error', 'Unauthorized page');
        }
        
        return view('groups.edit')->with('group', $group)->with('options', Options::groups());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        $group = Group::find($name);

        if (empty($group)) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('error', 'Unauthorized page');
        }

        $this::validator($group->id, $request->all())->validate();

        $group->name        = $request->input('name');
        $group->link        = $request->input('link');
        $group->platform    = $request->input('platform');
        $group->type        = $request->input('type');
        $group->privacy     = $request->input('privacy');
        $group->description = $request->input('description') == null ? "" : $request->input('description');

        $group->save();

        if ($group->privacy == 'Public') {
            Permission::acceptPending($group);
        }

        return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('success', 'Group updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $group = Group::find($name);

        if (empty($group)) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('error', 'Unauthorized page');
        }

        $group->delete();

        return redirect('/account/'.str_replace(" ", "_", auth()->user()->name).'/created')->with('success', 'Group deleted');
    }

    /**
     * Get a validator for an incoming create request.
     * @param int $group_id
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator($group_id, array $data)
    {
        $options = Options::groups();
        return Validator::make(
            $data,
            $rules = [
                'name' =>           ['required', 'not_regex:/_/', Rule::unique('groups')->ignore($group_id)],
                'link' =>           ['required', Rule::unique('groups')->ignore($group_id)],
                'platform' =>       ['required', 'in:'.implode(',', $options['platform'])],
                'type' =>           ['required', 'in:'.implode(',', $options['type'])],
                'privacy' =>        ['required', 'in:'.implode(',', $options['privacy'])],
                'description' =>    ['max:1024']
            ], 
            $messages = [
                'not_regex' => 'Your group name cannot contain any underscores'
            ]
        );
    }
}
