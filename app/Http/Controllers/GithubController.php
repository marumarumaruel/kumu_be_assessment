<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Models\GithubUser;
use App\Models\UserLog;

class GithubController extends Controller
{
    /**
     * Get the Github user.
     */
    function getUser(string $user, $request)
    {
        $redisUser = Redis::hgetall('github_user:'.$user);
        if($redisUser) {
            UserLog::create([
                'user' => $request->user()->id,
                'github_login' => $redisUser['login'],
                'source' => 'redis'
            ]);
            return $redisUser;
        } else {
            $githubUser = GithubUser::where('login', $user)->first();
            if(!empty($githubUser)) {
                UserLog::create([
                    'user' => $request->user()->id,
                    'github_login' => $githubUser['login'],
                    'source' => 'db'
                ]);
                return $githubUser->toArray();
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

                    GithubUser::create($githubUser);

                    UserLog::create([
                        'user' => $request->user()->id,
                        'github_login' => $githubUser['login'],
                        'source' => 'github'
                    ]);
                    return $githubUser;
                }
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
        // $githubUsernames = $request->all();
        $githubUsernames = $request->input("github_username");
        $ghUs = [];
        
        foreach($githubUsernames as $githubUsername){
            if(!empty($githubUsername)) {
                $ghU = $this->getUser($githubUsername,$request);
                if(!empty($ghU)) {
                    $ghUs[] = $ghU;
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
