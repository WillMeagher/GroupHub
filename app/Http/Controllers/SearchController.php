<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Options;
use App\Models\Group;
use App\Models\User;
use PhpSpellcheck\SpellChecker\Aspell;
use App\Helpers\Inflect;
use \Validator;

class SearchController extends Controller
{
    /**
     * Display the search groups page.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return view('search.search')->with('options', Options::groups());
    }

    /**
     * Returns a search with results baised on the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function results(Request $request)
    {
        $validator = $this::validator($request->all());

        if ($validator->fails()) {
            return redirect('/search')->withErrors($validator)->withInput();
        }

        if ($request->searchfor == "Groups") {

            $groups = Group::allListed();

            $aspell = Aspell::create('C:\Program Files (x86)\Aspell\bin\aspell.exe');
    
            // get each word from search query, set them to lowercase, split on " ", "-", "/", and remove and empty results
            foreach (array_filter(preg_split('/( |-|\/)/', strtolower($request->search)), 'strlen') as $word) {
                $misspelling = $aspell->check($word, ['en_US'], ['from_example'])->current();
                if ($misspelling != Null) {
                    // get first 5 suggestions plus initial element at max
                    $searchWords[] = array_slice(array_merge(array($word), $misspelling->getSuggestions()), 0, 5);
                } else {
                    $searchWords[] = array($word);
                }
            }
    
            foreach ($groups as $group) {
                $group['score'] = 0;
                $nameLength = count(preg_split('/( )/', $group->name));
    
                foreach ($searchWords as $words) {
                    foreach ($words as $word) {
                        if (str_contains(strtolower($group->name), Inflect::singularize($word)) || 
                            str_contains(strtolower($group->name), Inflect::pluralize($word))) {
                            $group['score'] += (720 / $nameLength);
                            continue;
                        }
                    }
                }
    
                if ($request->platform == 'Any' || $group->platform == $request->platform) {
                    $group['score'] += (240 / $nameLength);
                }
    
                if ($request->type == 'Any' || $group->type == $request->type) {
                    $group['score'] += (240 / $nameLength);
                }
    
                if ($request->privacy == 'Any' || $group->privacy == $request->privacy) {
                    $group['score'] += (240 / $nameLength);
                }

                $group['score'] += (min(240, $group->size * 2) / $nameLength);

                $ageDays = round((time() - strtotime($group->created_at)) / (60 * 60 * 24));
                $group['score'] -= (min(240, $ageDays / 4) / $nameLength);

            }
    
            return view('search.results')->with('results', $groups->sortByDesc('score'))->with('options', Options::groups($request))->with('request', $request);
        } else {
            
            $users = User::all();

            $searchWords = array_filter(preg_split('/( |-|\/)/', strtolower($request->search)), 'strlen');
    
            foreach ($users as $user) {
                $user['score'] = 0;
    
                foreach ($searchWords as $word) {
                    if (str_contains(strtolower($user->name), $word)) {
                        $user['score'] += (1680 / count(preg_split('/( )/', $user->name)));
                        continue;
                    }
                }
            }
    
            return view('search.results')->with('results', $users->sortByDesc('score'))->with('options', Options::groups($request))->with('request', $request);
        }
    }

    /**
     * Get a validator for an incoming create request.
     * 
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $options = Options::groups();
        return Validator::make(
            $data,
            $rules = [
                'search' =>         ['required', 'max:128'],
                'searchfor' =>      ['required', 'in:'.implode(',', $options['searchfor'])],
                'platform' =>       ['required', 'in:Any,'.implode(',', $options['platform'])],
                'type' =>           ['required', 'in:Any,'.implode(',', $options['type'])],
                'privacy' =>        ['required', 'in:Any,'.implode(',', $options['privacy'])]
            ], 
            $messages = []
        );
    }
}
