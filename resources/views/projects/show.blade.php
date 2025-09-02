@extends('layouts.app')
@section('title', 'Project Details')
@section('content')
&lt;h1&gt;Project Details&lt;/h1&gt;
&lt;div class="card"&gt;
    &lt;div class="card-body"&gt;
        &lt;h5 class="card-title"&gt;{{ $project-&gt;title }}&lt;/h5&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Program ID:&lt;/strong&gt; {{ $project-&gt;program_ID }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Facility ID:&lt;/strong&gt; {{ $project-&gt;facility_ID }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Nature of Project:&lt;/strong&gt; {{ $project-&gt;nature_of_project }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Description:&lt;/strong&gt; {{ $project-&gt;description }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Innovation Focus:&lt;/strong&gt; {{ $project-&gt;innovation_focus }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Prototype Stage:&lt;/strong&gt; {{ $project-&gt;prototype_stage }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Testing Requirements:&lt;/strong&gt; {{ $project-&gt;testing_requirements }}&lt;/p&gt;
        &lt;p class="card-text"&gt;&lt;strong&gt;Commercialization Plan:&lt;/strong&gt; {{ $project-&gt;commercialization_plan }}&lt;/p&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;a href="{{ route('projects.edit', $project-&gt;id) }}" class="btn btn-warning mt-3"&gt;Edit&lt;/a&gt;
&lt;a href="{{ route('projects.index') }}" class="btn btn-secondary mt-3"&gt;Back to List&lt;/a&gt;
@endsection