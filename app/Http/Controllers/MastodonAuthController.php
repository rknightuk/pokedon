<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Revolution\Mastodon\Facades\Mastodon;

class MastodonAuthController extends Controller
{
    public function login(Request $request)
    {
        session(['mastodon_user' => null]);

        $domain = $request->input('domain');

        $server = Server::where('domain', $domain)->first();

        if (empty($server)) {
            $info = Mastodon::domain($domain)
                ->createApp(env('APP_NAME'), config('services.mastodon.redirect'), 'read');

            $server = Server::create([
                'domain'        => $domain,
                'client_id'     => $info['client_id'],
                'client_secret' => $info['client_secret'],
            ]);
        }

        config(['services.mastodon.domain' => $domain]);
        config(['services.mastodon.client_id' => $server->client_id]);
        config(['services.mastodon.client_secret' => $server->client_secret]);

        session(['mastodon_domain' => $domain]);
        session(['mastodon_server' => $server]);

        return Socialite::driver('mastodon')->redirect();
    }

    public function callback()
    {
        $domain = session('mastodon_domain');
        $server = session('mastodon_server');

        config(['services.mastodon.domain' => $domain]);
        config(['services.mastodon.client_id' => $server->client_id]);
        config(['services.mastodon.client_secret' => $server->client_secret]);

        $user = Socialite::driver('mastodon')->user();

        session(['mastodon_user' => $user]);

        return redirect()->route('timeline');
    }

    public function logout()
    {
        session(['mastodon_user' => null]);

        return redirect()->route('welcome');
    }
}
