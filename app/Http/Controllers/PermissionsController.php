<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

use App\Models\Permission;

// for error logging
use Illuminate\Support\Facades\Log;

class PermissionsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('accountCreated');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // show user all requests to all their groups and status of all their requests
        $newNotifications = Permission::getNewNotifications(auth()->user()->id);
        $oldNotifications = Permission::getOldNotifications(auth()->user()->id);

        Permission::notificationsViewed(auth()->user()->id);

        return view('permissions.notifications')->with('newNotifications', $newNotifications)->with('oldNotifications', $oldNotifications);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @param  int  $group_id
     * @return \Illuminate\Http\Response
     */
    public function create($name)
    {
        $group = Group::find($name);

        if (empty($group) || $group->privacy == 'Delisted') {
            return redirect('/search')->with('error', 'Group not found');
        } else if (Permission::isMember(auth()->user()->id, $group->id)) {
            return redirect('/group/'.str_replace(" ", "_", $name).'/join');
        } else if (Permission::exists(auth()->user()->id, $group->id)) {
            return redirect('/group/'.str_replace(" ", "_", $name))->with('error', 'You already have an entry in our database for this group');
        } else if ($group->privacy == 'Public') {
            $request = new \Illuminate\Http\Request;
            $request->setMethod('POST');
            $request->request->add(['name' => $group->name]);
            return $this->store($request);
        }

        return view('permissions.create')->with('group', $group);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // store a request created by a person looking to join the gorup
        // check to see if they have already tried to join and throw error if they have
        
        $this->validate($request, self::createValidationRules($request));

        $group = Group::find($request->input('name'));

        if (empty($group) || $group->privacy == 'Delisted') {
            return redirect('/group')->with('error', 'Group not found');
        } else if (Permission::exists(auth()->user()->id, $group->id)) {
            return redirect('/group/'.str_replace(" ", "_", $group->name))->with('error', 'You already have already requested to join this group');
        }

        $permissions = new Permission;

        $permissions->user_id = auth()->user()->id;
        $permissions->group_id = $group->id;
        $permissions->notify_user = $group->creator_id;
        $permissions->viewed = 0;

        if ($group->privacy == 'Public') {
            $permissions->message = null;
            $permissions->status = 'Accepted';

            $permissions->save();

            Group::incrementSize($permissions->group_id);

            return redirect('/group/'.str_replace(" ", "_", $group->name).'/join');
        } else {
            $permissions->message = $request->input('message');
            $permissions->status = 'Pending';

            $permissions->save();

            return redirect('/group/'.str_replace(" ", "_", $group->name))->with('success', 'Request Sent');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // show a single request to the owner of the group
        $request = Permission::find($id);

        if (empty($request)) {
            return redirect('/notifications')->with('error', 'Request not found');
        } else if (auth()->user()->id !== Permission::owner($id)) {
            return redirect('/notifications')->with('error', 'Unauthorized page');
        }

        return view('permissions.show')->with('request', $request);
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
        // allow group creator to accept or deny and update the pending field
        // redirect to group premissions create on success
        $this->validate($request, self::updateValidationRules($request));

        $permission = Permission::find($id);

        if (empty($permission)) {
            return redirect('/notifications')->with('error', 'Request not found');
        } else if (auth()->user()->id !== $permission->group_creator_id) {
            return redirect('/notifications')->with('error', 'Unauthorized page');
        } else if ($permission->status != 'Pending') {
            return redirect('/notifications')->with('error', 'You have already '.$permission->status.' this request.');
        }

        $permission->notify_user = $permission->user_id;
        $permission->viewed = 0;
        $permission->status = $request->status;

        $permission->save();

        if ($permission->status == 'Accepted') {
            Group::incrementSize($permission->group_id);
        }

        return redirect('/notifications')->with('success', 'Request '.$request->status);
    }

    /**
     * Get a validation rules for an incoming create request.
     *
     * @param  array  $request
     * @return array
     */
    protected function createValidationRules($request) 
    {
        return [
            'name' => ['required'],
            'message'  => ['max:1024']
        ];
    }

    /**
     * Get a validation rules for an incoming update request.
     *
     * @param  array  $request
     * @return array
     */
    protected function updateValidationRules($request) 
    {
        return [
            'name' => ['required'],
            'status' => ['required', 'in:Accepted,Denied']
        ];
    }    
}
