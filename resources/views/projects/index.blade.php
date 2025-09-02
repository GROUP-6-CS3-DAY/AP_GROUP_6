@extends('layouts.app')
@section('title', 'Projects')
@section('content')
&lt;h1&gt;Projects&lt;/h1&gt;
&lt;a href="{{ route('projects.create') }}" class="btn btn-primary mb-3"&gt;Create New Project&lt;/a&gt;
&lt;table class="table table-striped"&gt;
    &lt;thead&gt;
        &lt;tr&gt;
            &lt;th&gt;ID&lt;/th&gt;
            &lt;th&gt;Title&lt;/th&gt;
            &lt;th&gt;Description&lt;/th&gt;
            &lt;th&gt;Actions&lt;/th&gt;
        &lt;/tr&gt;
    &lt;/thead&gt;
    &lt;tbody&gt;
        @foreach($projects as $project)
            &lt;tr&gt;
                &lt;td&gt;{{ $project-&gt;id }}&lt;/td&gt;
                &lt;td&gt;{{ $project-&gt;title }}&lt;/td&gt;
                &lt;td&gt;{{ $project-&gt;description }}&lt;/td&gt;
                &lt;td&gt;
                    &lt;a href="{{ route('projects.show', $project-&gt;id) }}" class="btn btn-info btn-sm"&gt;View&lt;/a&gt;
                    &lt;a href="{{ route('projects.edit', $project-&gt;id) }}" class="btn btn-warning btn-sm"&gt;Edit&lt;/a&gt;
                    &lt;form action="{{ route('projects.destroy', $project-&gt;id) }}" method="POST" style="display:inline;"&gt;
                        @csrf @method('DELETE')
                        &lt;button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"&gt;Delete&lt;/button&gt;
                    &lt;/form&gt;
                &lt;/td&gt;
            &lt;/tr&gt;
        @endforeach
    &lt;/tbody&gt;
&lt;/table&gt;
@endsection