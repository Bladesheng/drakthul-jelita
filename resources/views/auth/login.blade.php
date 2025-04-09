<x-layouts.app>
	<main class="flex grow items-center justify-center">
		<form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
			@csrf

			<label for="password" class="input">
				<span class="label">
					<span class="icon-[heroicons--lock-closed]"></span>
				</span>

				<input type="password" name="password" id="password" required />
			</label>

			<button type="submit" class="btn btn-primary">Login</button>
		</form>
	</main>
</x-layouts.app>
