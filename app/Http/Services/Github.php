<?php


namespace App\Http\Services;


use Carbon\Carbon;
use Httpful\Exception\ConnectionErrorException;
use Httpful\Request;

class Github
{
    /**
     * A function that search repositories in github
     * @param $topCount
     * @param $date
     * @param $langs
     * @param $searchTerm
     * @return array|object|string
     */
    public function searchRepos($topCount, $date, $langs, $searchTerm)
    {
        try {
            $username = config('github.username');
            $token = config('github.token');

            $query = $this->createQuery($date, $langs, $searchTerm);
            $queryString = "sort=stars&order=desc&per_page=$topCount&q=$query";

            $httpfulRequest = Request::get("https://api.github.com/search/repositories?$queryString");

            // put authentication if exists
            if (isset($username) && isset($token)) {
                $httpfulRequest->authenticateWith($username, $token);
            }

            $response = $httpfulRequest->send();
            return response()->json($response->body);
        } catch (ConnectionErrorException $e) {
            return response()->json([
                'message' => 'Could not connect to github api'
            ], 503);
        }
    }

    /**
     * make the Q field query using all the subqueries
     * @param $date
     * @param $langs
     * @param $searchTerm
     * @return string
     */
    public function createQuery($date, $langs, $searchTerm)
    {
        $langQuery = $this->getLangQuery($langs);
        $dateQuery = $this->getDateString($date);
        $query = "$searchTerm$langQuery$dateQuery";
        if ($query == "")
            return 'e';

        return $query;
    }

    /**
     * makes the language part of the query
     * @param $languages
     * @return string
     */
    public function getLangQuery($languages)
    {
        if ($languages == '')
            return '';

        $languages = explode(',', $languages);
        $langString = '';
        foreach ($languages as $lang) {
            $langString .= '+language:' . $lang;
        }

        return $langString;
    }

    /**
     * makes the date query
     * @param $date
     * @return string
     */
    public function getDateString($date)
    {
        if ($date == null)
            return '';

        $date = Carbon::parse($date);
        $dateFormatted = $date->format('Y-m-d');
        return "+created:>$dateFormatted";
    }
}
