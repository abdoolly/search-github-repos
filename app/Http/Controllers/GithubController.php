<?php

namespace App\Http\Controllers;


use App\Http\Services\Github;
use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function getPopularRepos(Request $request, Github $github)
    {
        $this->validate($request, [
            'top' => 'integer',
            'langs' => 'string|regex:/^[a-zA-Z0-9,_]+$/',
            'date' => 'date'
        ]);

        // making searchTerm required if language specified and no date
        if (isset($request->langs) && !isset($request->date)) {
            $this->validate($request, [
                'searchTerm' => 'required'
            ]);
        }

        $langs = $request->get('langs', '');
        $date = $request->get('date', null);
        $topCount = $request->get('top', 10);
        $searchTerm = $request->get('searchTerm', '');

        return $github->searchRepos($topCount, $date, $langs, $searchTerm);
    }
}
