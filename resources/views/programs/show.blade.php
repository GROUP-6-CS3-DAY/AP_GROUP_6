@extends('layouts.app')
@section('title', 'Program Details')
@section('content')
&lt;h1&gt;Program Details&lt;/h1&gt;
&lt;div class="card"&gt;
    &lt;div class="card-body"&gt;
        &lt;h5 class="card-title"&gt;{{ $program-&gt;name }}&lt;/h5&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Description:&lt;/strong&gt; {{ $program-&gt;description }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;National Alignment:&lt;/strong&gt; {{ $program-&gt;national_alignment }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Focus Areas:&lt;/strong&gt; {{ $program-&gt;focus_areas }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Phases:&lt;/strong&gt; {{ $program-&gt;phases }}&lt;/p&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;a href="{{ route('programs.edit', $program-&gt;program_ID) }}" class="btn btn-warning mt-3"&gt;Edit&lt;/a&gt;
&lt;a href="{{ route('programs.index') }}" class="btn btn-secondary mt-3"&gt;Back to List&lt;/a&gt;
@endsection