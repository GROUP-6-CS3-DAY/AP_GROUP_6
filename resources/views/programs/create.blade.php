@extends('layouts.app')
@section('title', 'Create Program')
@section('content')
&lt;h1&gt;Create New Program&lt;/h1&gt;
&lt;form action="{{ route('programs.store') }}" method="POST"&gt;
    @csrf
    &lt;div class="mb-3"&gt;
        &lt;label for="name" class="form-label"&gt;Name&lt;/label&gt;
        &lt;input type="text" class="form-control" id="name" name="name" required&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="description" class="form-label"&gt;Description&lt;/label&gt;
        &lt;textarea class="form-control" id="description" name="description" required&gt;&lt;/textarea&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="national_alignment" class="form-label"&gt;National Alignment&lt;/label&gt;
        &lt;input type="text" class="form-control" id="national_alignment" name="national_alignment" required&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="focus_areas" class="form-label"&gt;Focus Areas&lt;/label&gt;
        &lt;input type="text" class="form-control" id="focus_areas" name="focus_areas" required&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="phases" class="form-label"&gt;Phases&lt;/label&gt;
        &lt;input type="text" class="form-control" id="phases" name="phases" required&gt;
    &lt;/div&gt;
    &lt;button type="submit" class="btn btn-primary"&gt;Create Program&lt;/button&gt;
    &lt;a href="{{ route('programs.index') }}" class="btn btn-secondary"&gt;Cancel&lt;/a&gt;
&lt;/form&gt;
@endsection