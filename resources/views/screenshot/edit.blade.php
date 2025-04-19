<x-layouts.app>
	<main class="relative flex grow flex-col items-center justify-center gap-8 md:flex-row">
		<img
			src="{{ config('filesystems.cdn_url') }}/{{ $screenshot->path }}"
			alt="{{ $screenshot->wow_name }}"
			class="screenshot w-64 rounded shadow"
		/>

		<div class="card bg-base-200 shadow">
			<div class="card-body">
				<h1 class="card-title">Edit screenshot</h1>

				<form
					method="POST"
					action="{{ route('screenshots.update', $screenshot) }}"
					class="flex flex-col gap-4"
				>
					@csrf
					@method('patch')

					<label class="input" for="wowName">
						<span class="label">Name</span>
						<input
							type="text"
							name="wowName"
							id="wowName"
							required
							value="{{ old('wowName', $screenshot->wow_name) }}"
						/>
					</label>

					<x-validation-error field="wowName" />

					<div class="flex flex-col gap-1.5">
						@foreach ($wowClasses as $wowClass)
							<div class="flex items-center gap-1" style="color: {{ $wowClass->color }}">
								<input
									type="radio"
									name="wowClassId"
									id="wowClassId-{{ $wowClass->id }}"
									value="{{ $wowClass->id }}"
									required
									{{ old('wowClassId', $screenshot->wowClass->id) === $wowClass->id ? 'checked' : '' }}
									class="radio"
								/>

								<label for="wowClassId-{{ $wowClass->id }}" class="capitalize">
									{{ $wowClass->name }}
								</label>
							</div>
						@endforeach

						<x-validation-error field="wowClassId" />
					</div>

					<div class="flex justify-between gap-2">
						<button type="submit" class="btn btn-primary flex-1">Save</button>
						<button type="submit" class="btn btn-error flex-1" form="deleteScreenshot">
							Delete
						</button>
					</div>
				</form>

				<form
					method="POST"
					action="{{ route('screenshots.destroy', $screenshot) }}"
					id="deleteScreenshot"
				>
					@csrf
					@method('delete')
				</form>
			</div>
		</div>
	</main>
</x-layouts.app>
