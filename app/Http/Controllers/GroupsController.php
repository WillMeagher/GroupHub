<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Group;
use App\Models\Permission;
use App\Models\User;
use App\Helpers\Options;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Support\Facades\Log;

// TODO college specific groups
// TODO turn on confirm email

class GroupsController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'search', 'results']]);
        $this->middleware('accountCreated', ['except' => ['show', 'search', 'results']]);
    }

    /**
     * Display a listing of all groups the user created.
     *
     * @return \Illuminate\Http\Response
     */
    public function created($username)
    {
        if (empty(User::find($username))) {
            return redirect('/search')->with('error', 'User not found');
        }

        if (auth()->user()->name == str_replace("_", " ", $username)) {
            return view('groups.index')->with('groups', Group::allCreated($username))->with('title', 'Your Created Groups');
        } else {
            return view('groups.index')->with('groups', Group::listedCreated($username))->with('title', str_replace("_", " ", $username)."'s Created Groups");
        }
    }

    /**
     * Display a listing of all the groups a user has joined.
     *
     * @return \Illuminate\Http\Response
     */
    public function joined($username)
    {
        if (empty(User::find($username))) {
            return redirect('/search')->with('error', 'User not found');
        }

        $groups = Group::joined($username);

        if (auth()->user()->name == str_replace("_", " ", $username)) {
            return view('groups.index')->with('groups', $groups)->with('title', 'Your Joined Groups');
        } else {
            return view('groups.index')->with('groups', $groups)->with('title', str_replace("_", " ", $username)."'s Joined Groups");
        }
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
        $this::storeValidator(null, $request->all())->validate();

        $group = new Group;

        $group->name        = $request->input('name');
        $group->link        = $request->input('link');
        $group->platform    = self::getPlatform($request->input('link'));
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

        $this::updateValidator($group->id, $request->all())->validate();

        $group->name        = $request->input('name');
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
     * Get a validator for an incoming store request.
     * @param int $group_id
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function storeValidator($group_id, array $data)
    {
        $options = Options::groups();
        return Validator::make(
            $data,
            $rules = [
                'name' =>           ['required', 'regex:/^[A-Za-z0-9 ]*[A-Za-z]+[A-Za-z0-9 ]*$/', Rule::unique('groups')->ignore($group_id)],
                'link' =>           ['required', Rule::unique('groups')->ignore($group_id)],
                'type' =>           ['required', 'in:'.implode(',', $options['type'])],
                'privacy' =>        ['required', 'in:'.implode(',', $options['privacy'])],
                'description' =>    ['max:1024']
            ], 
            $messages = [
                'regex' => 'Your group name can only contain letters, numbers, and spaces.  It also must contain at least one letter.'
            ]
        );
    }

    /**
     * Get a validator for an incoming update request.
     * @param int $group_id
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function updateValidator($group_id, array $data)
    {
        $options = Options::groups();
        return Validator::make(
            $data,
            $rules = [
                'name' =>           ['required', 'regex:/^[A-Za-z0-9 ]*[A-Za-z]+[A-Za-z0-9 ]*$/', Rule::unique('groups')->ignore($group_id)],
                'type' =>           ['required', 'in:'.implode(',', $options['type'])],
                'privacy' =>        ['required', 'in:'.implode(',', $options['privacy'])],
                'description' =>    ['max:1024']
            ], 
            $messages = [
                'regex' => 'Your group name can only contain letters, numbers, and spaces. It also must contain at least one letter.'
            ]
        );
    }

    /**
     * Get the corresponding platform to a link.
     * @param string  $link
     * @return string
     */
    protected function getPlatform($link) {
        $platforms = Options::platformDomains();
        $link = str_replace(["https://", "http://"], ["", ""], $link);

        foreach($platforms as $domain => $platform) {
            if (str_starts_with($link, $domain)) {
                return $platform;
            }
        }

        return "Other";
    }
}
