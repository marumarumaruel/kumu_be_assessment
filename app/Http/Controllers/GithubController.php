<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class GithubController extends Controller
{
    /**
     * Get the Github user.
     */
    function getUser(string $user)
    {
        $redisUser = Redis::hgetall('github_user:'.$user);
        if($redisUser) {
            return $redisUser;
        } else {
            $response = Http::get("https://api.github.com/users/".$user);
            $response = $response->json();

            if(!empty($response['status']) && $response['status']==404){
                return false;
            } else {
                $githubUser = array(
                    "name" => $response['name'],
                    "login" => $response['login'],
                    "company" => $response['company'],
                    "followers" => $response['followers'],
                    "public_repos" => $response['public_repos'],
                    "average_followers" => $response['followers'] / $response['public_repos'],
                );
                // echo "<pre>";
                // print_r($githubUser);
                // echo "</pre>";
    
                Redis::hSet(
                    'github_user:'.$githubUser['login'],
                    'name', $githubUser['name'],
                    'login', $githubUser['login'],
                    'company', $githubUser['company'],
                    'followers', $githubUser['followers'],
                    'public_repos', $githubUser['public_repos'],
                    'average_followers', $githubUser['average_followers'],
                );
                Redis::expire('github_user:'.$githubUser['login'],120);

                return $githubUser;
            }
        }
    }

    function getUser1()
    {
        $response = Http::get("https://api.github.com/users/marumarumaruel");
        $response = $response->json();
        return $response;
    }

    /**
     * Get the Github user.
     */
    public function getUsers(Request $request)
    {
        // $githubUsernames = ['mojombo','defunkt'];
        $githubUsernames = $request->all();
        $ghUs = [];
        
        foreach($githubUsernames as $githubUsername){
            if(!empty($githubUsername)) {
                if(!empty($this->getUser($githubUsername))) {
                    $ghUs[] = $this->getUser($githubUsername);
                }
            }
        }

        array_multisort(array_column($ghUs, 'name'), SORT_ASC, $ghUs);
        // echo "<pre>";
        // print_r($ghUs);
        // echo "</pre>";
        
        return response()->json($ghUs);
    }
}
