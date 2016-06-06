@extends('default')
@section('subtitle', "Home sub page")

@section('body')
	<h1>{{$title}}</h1>
	<p>
		A simple php framework using MVC structure and components of Symfony and Illuminate.
	</p>
	<hr>
	<a href="/" class="btn btn-success">Go to Home</a>
@stop