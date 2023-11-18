<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Revolution\Mastodon\Facades\Mastodon;

class SiteController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function timeline()
    {
        $user = session('mastodon_user');
        $domain = session('mastodon_domain');

        $toots = [];

        $toots = array_values(array_filter(Mastodon::domain($domain)
            ->token($user->token)
            ->get('/timelines/home', [
                'exclude_replies' => true,
                'exclude_reblogs' => true,
                'limit' => 40,
            ]), function ($toot) {
                return is_null($toot['reblog']);
            }));


        return view('timeline', compact('toots'));
    }
}
