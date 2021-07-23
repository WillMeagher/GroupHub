<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupPermission;
use Illuminate\Support\Facades\Auth;

// for error logging
use Illuminate\Support\Facades\Log;

class GroupPermissionsController extends Controller
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
        // show user all requests to all their groups
        $groupPermissions = GroupPermission::getAll(auth()->user()->id);

        return view('permissions.index')->with('requests', $groupPermissions);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications()
    {
        $newNotifications = GroupPermission::getNewNotifications(auth()->user()->id);
        $oldNotifications = GroupPermission::getOldNotifications(auth()->user()->id);

        GroupPermission::notificationsViewed(auth()->user()->id);

        return view('permissions.notifications')->with('newNotifications', $newNotifications)->with('oldNotifications', $oldNotifications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($group_id)
    {
        $group = Group::find($group_id);

        if (empty($group) || $group->privacy == 'Delisted') {
            return redirect('/group')->with('error', 'Group not found');
        } else if (GroupPermission::isMember(auth()->user()->id, $group_id)) {
            return redirect('/group/'.$group_id.'/join');
        } else if (GroupPermission::exists(auth()->user()->id, $group_id)) {
            return redirect('/group/'.$group_id)->with('error', 'You already have an entry in our database for this group');
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

        $group = Group::find($request->input('group_id'));

        if (empty($group) || $group->privacy == 'Delisted') {
            return redirect('/group')->with('error', 'Group not found');
        } else if (GroupPermission::exists(auth()->user()->id, $request->input('group_id'))) {
            return redirect('/group/'.$request->input('group_id'))->with('error', 'You already have already requested to join this group');
        }

        $groupPermissions = new GroupPermission;

        $groupPermissions->user_id = auth()->user()->id;
        $groupPermissions->group_id = $request->input('group_id');

        if ($group->privacy == 'Public') {
            $groupPermissions->message = '';
            $groupPermissions->status = 'Accepted';
            $groupPermissions->notify = 0;
            $groupPermissions->save();
            return redirect('/group/'.$request->input('group_id').'/join');
        } else {
            $groupPermissions->message = $request->input('message');
            $groupPermissions->status = 'Pending';
            $groupPermissions->notify = 1;
            $groupPermissions->save();
            return redirect('/group/'.$request->input('group_id'))->with('success', 'Request Sent');
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
        $request = GroupPermission::find($id);

        if (empty($request)) {
            return redirect('/permissions')->with('error', 'Request not found');
        } else if (auth()->user()->id !== GroupPermission::owner($id)) {
            return redirect('/permissions')->with('error', 'Unauthorized page');
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

        $groupPermissions = GroupPermission::find($id);

        if (empty($groupPermissions)) {
            return redirect('/permissions')->with('error', 'Request not found');
        } else if (auth()->user()->id !== $groupPermissions->group_creator_id) {
            return redirect('/permissions')->with('error', 'Unauthorized page');
        }

        $groupPermissions->notify = 1;
        $groupPermissions->status = $request->status;

        $groupPermissions->save();

        return redirect('/permissions')->with('success', 'Request '.$request->status);
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateNotify(Request $request, $id)
    {
        // allow group creator to accept or deny and update the pending field
        // redirect to group premissions create on success
        $this->validate($request, self::updateViewedValidationRules($request));

        $groupPermissions = GroupPermission::find($id);

        if (empty($groupPermissions)) {
            return redirect('/permissions')->with('error', 'Request not found');
        } else if ((auth()->user()->id !== $groupPermissions->group_creator_id || $groupPermissions->status != 'Pending') &&
                    (auth()->user()->id !== $groupPermissions->user_id || $groupPermissions->status == 'Pending')) {
            return redirect('/permissions')->with('error', 'Unauthorized page');
        }

        $groupPermissions->notify = 0;
        $groupPermissions->timestamps = false;

        $groupPermissions->save();
    }

    /**
     * Delete all permissions to a group.
     *
     * @param  int  $group_id
     */
    public static function delete($group_id)
    {
        GroupPermission::deleteGroup($group_id);
    }

    private function createValidationRules($request) 
    {
        return [
            'message'  => [],
            'group_id' => ['required', 'integer']
        ];
    }

    private function updateValidationRules($request) 
    {
        return [
            'group_id' => ['required', 'integer'],
            'status' => ['in:Accepted,Denied']
        ];
    }

    private function updateViewedValidationRules($request) 
    {
        return [
            'group_id' => ['required', 'integer'],
        ];
    }
    
}
