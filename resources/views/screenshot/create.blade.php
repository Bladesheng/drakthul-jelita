<x-layouts.app>
	@push('scripts')
		@vite(['resources/js/upload.ts'])
	@endpush

	<main class="relative flex grow flex-col items-center justify-center gap-8 md:flex-row">
		<img src="" alt="" class="screenshot w-64 rounded shadow" />

		<div>
			@if (session('status') === 'screenshot-created')
				<x-alert-success class="mb-4">Screenshot created successfully</x-alert-success>
			@endif

			<form
				method="POST"
				action="{{ route('screenshots.store') }}"
				enctype="multipart/form-data"
				class="uploadForm card bg-base-200 shadow"
			>
				@csrf

				<div class="card-body gap-4">
					<h1 class="card-title">Upload new screenshot</h1>

					<label class="input" for="wowName">
						<span class="label">Name</span>
						<input
							type="text"
							name="wowName"
							id="wowName"
							required
							value="{{ old('wowName') }}"
							minlength="2"
						/>
					</label>

					<x-validation-error field="wowName" />

					<div class="flex flex-col gap-1.5">
						@foreach ($wowClasses as $wowClass)
							<div class="flex items-center gap-1" style="color: {{ $wowClass['color'] }}">
								<input
									type="radio"
									name="wowClass"
									id="wowClass-{{ $wowClass['color'] }}"
									value="{{ $wowClass['name'] }}"
									required
									{{ old('wowClass') === $wowClass['name'] ? 'checked' : '' }}
									class="radio"
								/>

								<label for="wowClass-{{ $wowClass['color'] }}" class="capitalize">
									{{ $wowClass['name'] }}
								</label>
							</div>
						@endforeach

						<x-validation-error field="wowClass" />
					</div>

					<input
						type="file"
						name="screenshot"
						id="screenshot"
						required
						class="file-input file-input-ghos"
					/>

					<x-validation-error field="screenshot" />

					<div class="card-actions">
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</div>
			</form>
		</div>
	</main>
</x-layouts.app>
