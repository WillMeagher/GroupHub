<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use \Validator;
use PhpSpellcheck\SpellChecker\Aspell;
use App\Helpers\Inflect;

//use Illuminate\Support\Facades\Log;

// TODO look into filtering out old groups
// TODO search by user - convert search to own controller
// TODO add no created, no joined, and no found text
// TODO convert group ids in links to names

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
            return redirect('/group/search')->with('error', 'User not found');
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
     * Display the search groups page.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return view('groups.search')->with('options', self::dropdownOptions());
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

        $groups = Group::allListed();

        $aspell = Aspell::create('C:\Program Files (x86)\Aspell\bin\aspell.exe');

        // get each word from search query, set them to lowercase, split on " ", "-", "/", and remove and empty results
        foreach (array_filter(preg_split('/( |-|\/)/', strtolower($request->search)), 'strlen') as $word) {
            $misspelling = $aspell->check($word, ['en_US'], ['from_example'])->current();
            if ($misspelling != Null) {
                // get first 9 suggestions plus initial element at max
                $searchWords[] = array_slice(array_merge(array($word), $misspelling->getSuggestions()), 0, 10);
            } else {
                $searchWords[] = array($word);
            }
        }

        foreach ($groups as $group) {
            $group['score'] = 0;

            foreach ($searchWords as $words) {
                foreach ($words as $word) {
                    if (in_array(Inflect::singularize($word), preg_split('/( |-|\/)/', strtolower($group->name))) || 
                        in_array(Inflect::pluralize($word), preg_split('/( |-|\/)/', strtolower($group->name)))) {
                        $group['score'] += (20 / count($searchWords));
                        continue;
                    }
                }
            }

            if ($request->platform == 'Any' || $group->platform == $request->platform) {
                $group['score'] += 5;
            }

            if ($request->type == 'Any' || $group->type == $request->type) {
                $group['score'] += 5;
            }

            if ($request->privacy == 'Any' || $group->privacy == $request->privacy) {
                $group['score'] += 3;
            }
        }

        return view('groups.results')->with('groups', $groups->sortByDesc('score'))->with('options', self::dropdownOptions())->with('request', $request);
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
        $group->size        = 1;

        $group->save();

        return redirect('/account/'.auth()->user()->name.'/created')->with('success', 'Group created');
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
            return redirect('/group/search')->with('error', 'Group not found');
        } else if ($group->privacy == 'Delisted' && auth()->user()->id !== $group->creator_id) {
            return redirect('/group/search')->with('error', 'Unauthorized page');
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
            return redirect('/group/search')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id && !Permission::isMember(auth()->user()->id, $group->id)) {
            return redirect('/group/search')->with('error', 'Unauthorized page');
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
            return redirect('/account/'.auth()->user()->name.'/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/account/'.auth()->user()->name.'/created')->with('error', 'Unauthorized page');
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
            return redirect('/account/'.auth()->user()->name.'/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/account/'.auth()->user()->name.'/created')->with('error', 'Unauthorized page');
        }

        $this->validate($request, self::createValidation($group->id, $request));

        $group->name        = $request->input('name');
        $group->link        = $request->input('link');
        $group->platform    = $request->input('platform');
        $group->type        = $request->input('type');
        $group->privacy     = $request->input('privacy');
        $group->description = $request->input('description') == null ? "" : $request->input('description');

        $group->save();

        return redirect('/account/'.auth()->user()->name.'/created')->with('success', 'Group updated');
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
            return redirect('/account/'.auth()->user()->name.'/created')->with('error', 'Group not found');
        } else if (auth()->user()->id !== $group->creator_id) {
            return redirect('/account/'.auth()->user()->name.'/created')->with('error', 'Unauthorized page');
        }

        $group->delete();

        return redirect('/account/'.auth()->user()->name.'/created')->with('success', 'Group deleted');
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
        'Clubs',
        'Intermurals',
        'Greek Life',
        'Univerity Sponsored',
        'Other'
    ];

    protected static $privacyDropdowns = [
        'Public',
        'Private',
        'Delisted'
    ];
    
    protected static $sortByDropdowns = [
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
            'sortby' =>         ['required', 'in:'.implode(',', Self::$sortByDropdowns)]
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
        foreach(Self::$sortByDropdowns as $sortBy) {
            $options['sortby'][$sortBy] = $sortBy;
        }

        return $options;
    }
}
