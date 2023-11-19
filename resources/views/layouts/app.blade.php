<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="/css/app.css" type="text/css" media="screen" title="no title" charset="utf-8">

        <title>Pokédon</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=vt323:400|atkinson-hyperlegible:400,400i,700,700i" rel="stylesheet" />

        <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
        <link rel="manifest" href="/icons/site.webmanifest">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <meta property="og:image" content="/icons/preview.png">
        <meta property="description" content="Browse the 'don on a Pokédex!" />
		<meta property="og:description" content="Browse the 'don on a Pokédex!" />

        <script src="https://cdn.usefathom.com/script.js" data-site="SSRZTBHH" defer></script>

    </head>
    <body>

    <header>
        <img src="/logo.png" alt="Pokédon" />
    </header>

    @yield('content')

    <footer>
        <p>
            A hackathon project by <a href="https://rknight.me">Robb Knight</a> • <a href="https://rknight.me/coffee">Buy Me a Coffee</a> • <a href="https://www.streamlinehq.com/icons/pixel">Pixel Icons</a> @if (session('mastodon_user')) • <a href="/auth/logout">Logout</a> @endif
        </p>
    </footer>

    </body>
</html>
