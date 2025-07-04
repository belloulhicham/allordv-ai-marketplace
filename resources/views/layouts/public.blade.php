<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'fr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat IA - AllOrdv</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8f9fa; }
        .chat-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .chat-header { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .chat-content { background: #fff; border-radius: 10px; min-height: 600px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; }
        .navbar-simple { background: #fff; border-bottom: 1px solid #e9ecef; padding: 10px 0; }
        .navbar-simple .navbar-brand { font-weight: bold; color: #007bff; }
    </style>
</head>
<body>
    <nav class="navbar navbar-simple">
        <div class="container">
            <a class="navbar-brand" href="{{ route('frontend.index') }}">AllOrdv Chat IA</a>
            <div class="d-flex">
                @auth
                    <span class="navbar-text me-3">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Inscription</a>
                @endauth
            </div>
        </div>
    </nav>
    <div class="chat-container">
        <div class="chat-header">
            <h3><i class="fas fa-robot text-primary me-2"></i>Chat IA Assistant</h3>
            <p class="mb-0 text-muted">Posez vos questions à notre assistant intelligent</p>
        </div>
        <div class="chat-content">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
