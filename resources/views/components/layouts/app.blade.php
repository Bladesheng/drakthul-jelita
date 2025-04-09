<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		@isset($title)
			<title>{{ $title }} | Drak'thul (J)elita</title>
		@else
			<title>Drak'thul (J)elita</title>
		@endisset

		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link
			href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
			rel="stylesheet"
		/>

		@vite(['resources/css/app.css', 'resources/js/app.ts'])

		@stack('scripts')
	</head>

	<body class="flex min-h-screen flex-col">
		@if (request()->attributes->get('isAdmin'))
			<div class="navbar shadow">
				<div class="flex grow">
					<img src="/favicon.svg" alt="favicon" class="h-10 pe-2" />
					<a class="btn btn-ghost text-xl" href="{{ route('screenshots.index') }}">Jelita</a>
					<a class="btn btn-ghost text-xl" href="{{ route('screenshots.create') }}">Upload</a>
				</div>

				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button type="submit" class="btn btn-square btn-ghost">
						<span class="icon-[heroicons--arrow-right-on-rectangle] h-5 w-5"></span>
					</button>
				</form>

				<div></div>
			</div>
		@endif

		{{ $slot }}

		<footer class="flex justify-end p-1">
			<a href="https://github.com/Bladesheng/drakthul-jelita" target="_blank">
				<img src="/github-mark-white.svg" class="h-5" alt="github-mark" />
			</a>
		</footer>
	</body>
</html>
