<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Sephy - @yield('subtitle')</title>
		<!-- Global stylesheets -->
		<link href="/assets/css/core-default.css" rel="stylesheet">
		<!-- /global stylesheets -->
		</head>
	<body>
		@include('layout.header')
			<div id="wrapper">
				@yield('body')
			</div>
		@include('layout.footer')
	</body>
</html>