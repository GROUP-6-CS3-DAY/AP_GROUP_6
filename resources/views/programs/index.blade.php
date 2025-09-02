@extends('layouts.app')
@section('title', 'Programs')
@section('content')
&lt;h1&gt;Programs&lt;/h1&gt;
&lt;a href="{{ route('programs.create') }}" class="btn btn-primary mb-3"&gt;Create New Program&lt;/a&gt;
&lt;table class="table table-striped"&gt;
    &lt;thead&gt;
        &lt;tr&gt;
            &lt;th&gt;ID&lt;/th&gt;
            &lt;th&gt;Name&lt;/th&gt;
            &lt;th&gt;Description&lt;/th&gt;
            &lt;th&gt;Actions&lt;/th&gt;
        &lt;/tr&gt;
    &lt;/thead&gt;
    &lt;tbody&gt;
        @foreach($programs as $program)
            &lt;tr&gt;
                &lt;td&gt;{{ $program-&gt;program_ID }}&lt;/td&gt;
                &lt;td&gt;{{ $program-&gt;name }}&lt;/td&gt;
                &lt;td&gt;{{ $program-&gt;description }}&lt;/td&gt;
                &lt;td&gt;
                    &lt;a href="{{ route('programs.show', $program-&gt;program_ID) }}" class="btn btn-info btn-sm"&gt;View&lt;/a&gt;
                    &lt;a href="{{ route('programs.edit', $program-&gt;program_ID) }}" class="btn btn-warning btn-sm"&gt;Edit&lt;/a&gt;
                    &lt;form action="{{ route('programs.destroy', $program-&gt;program_ID) }}" method="POST" style="display:inline;"&gt;
                        @csrf @method('DELETE')
                        &lt;button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"&gt;Delete&lt;/button&gt;
                    &lt;/form&gt;
                &lt;/td&gt;
            &lt;/tr&gt;
        @endforeach
    &lt;/tbody&gt;
&lt;/table&gt;
@endsection