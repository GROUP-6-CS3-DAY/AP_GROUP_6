&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;@yield('title', 'InnoTrack')&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;nav class="navbar navbar-expand-lg navbar-dark bg-primary"&gt;
        &lt;div class="container"&gt;
            &lt;a class="navbar-brand" href="/"&gt;InnoTrack&lt;/a&gt;
            &lt;div class="navbar-nav"&gt;
                &lt;a class="nav-link" href="{{ route('programs.index') }}"&gt;Programs&lt;/a&gt;
                &lt;a class="nav-link" href="{{ route('projects.index') }}"&gt;Projects&lt;/a&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/nav&gt;
    &lt;div class="container mt-4"&gt;
        @if(session('success'))
            &lt;div class="alert alert-success"&gt;{{ session('success') }}&lt;/div&gt;
        @endif
        @yield('content')
    &lt;/div&gt;
    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;