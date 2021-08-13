<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Options;
use App\Models\Group;
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
        $validator = Validator::make($request->all(), self::searchValidation($request));

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
    
            return view('search.results')->with('groups', $groups->sortByDesc('score'))->with('options', Options::groups($request))->with('request', $request);
        } else {

        }

    }

    /**
     * Get a validation rules for an incoming request.
     *
     * @param  array  $request
     * @return array
     */
    protected function searchValidation($request) 
    {
        $options = Options::groups();
        return [
            'search' =>         ['required', 'max:128'],
            'searchfor' =>      ['required', 'in:'.implode(',', $options['searchfor'])],
            'platform' =>       ['required', 'in:Any,'.implode(',', $options['platform'])],
            'type' =>           ['required', 'in:Any,'.implode(',', $options['type'])],
            'privacy' =>        ['required', 'in:Any,'.implode(',', $options['privacy'])]
        ];
    }
}
