@extends('layouts.app')
@section('title', 'Create Project')
@section('content')
&lt;h1&gt;Create New Project&lt;/h1&gt;
&lt;form action="{{ route('projects.store') }}" method="POST"&gt;
    @csrf
    &lt;div class="mb-3"&gt;
        &lt;label for="program_ID" class="form-label"&gt;Program ID&lt;/label&gt;
        &lt;input type="number" class="form-control" id="program_ID" name="program_ID" required&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="facility_ID" class="form-label"&gt;Facility ID&lt;/label&gt;
        &lt;input type="number" class="form-control" id="facility_ID" name="facility_ID" required&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="title" class="form-label"&gt;Title&lt;/label&gt;
        &lt;input type="text" class="form-control" id="title" name="title" required&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="nature_of_project" class="form-label"&gt;Nature of Project&lt;/label&gt;
        &lt;textarea class="form-control" id="nature_of_project" name="nature_of_project" required&gt;&lt;/textarea&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="description" class="form-label"&gt;Description&lt;/label&gt;
        &lt;textarea class="form-control" id="description" name="description" required&gt;&lt;/textarea&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="innovation_focus" class="form-label"&gt;Innovation Focus&lt;/label&gt;
        &lt;textarea class="form-control" id="innovation_focus" name="innovation_focus" required&gt;&lt;/textarea&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="prototype_stage" class="form-label"&gt;Prototype Stage&lt;/label&gt;
        &lt;input type="text" class="form-control" id="prototype_stage" name="prototype_stage" required&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="testing_requirements" class="form-label"&gt;Testing Requirements&lt;/label&gt;
        &lt;textarea class="form-control" id="testing_requirements" name="testing_requirements" required&gt;&lt;/textarea&gt;
    &lt;/div&gt;
    &lt;div class="mb-3"&gt;
        &lt;label for="commercialization_plan" class="form-label"&gt;Commercialization Plan&lt;/label&gt;
        &lt;textarea class="form-control" id="commercialization_plan" name="commercialization_plan" required&gt;&lt;/textarea&gt;
    &lt;/div&gt;
    &lt;button type="submit" class="btn btn-primary"&gt;Create Project&lt;/button&gt;
    &lt;a href="{{ route('projects.index') }}" class="btn btn-secondary"&gt;Cancel&lt;/a&gt;
&lt;/form&gt;
@endsection