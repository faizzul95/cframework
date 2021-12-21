<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <title> {{ $title }} | {{ $_ENV['APP_NAME'] }} </title>
    <meta charset="utf-8" />
    <meta name="description" content="Canthink Framework by Canthink Solution" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('framework/favicon.ico') }}" />
</head>
<!--end::Head-->
<!--begin::Body-->

<h1>{{ $title }}</h1>
<h3 class="mb-10" style="color: #A3A3C7">Seems there is nothing here</h3>
<a href="{{ url('user') }}" class="btn btn-primary">Return</a>