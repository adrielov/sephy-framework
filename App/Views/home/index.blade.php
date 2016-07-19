@extends('default')
@section('subtitle', "Home simple page")

@section('body')
	<h1>Sephy - Simple PHP Framework</h1>
	<p>
		A simple php framework using MVC structure and components of Symfony and Illuminate.
	</p>
	<hr>
	<a href="/subpage" class="btn btn-success">Go to Sub Page</a>
	<a href="/middleware" class="btn btn-success">Test Middleware</a>
@stop