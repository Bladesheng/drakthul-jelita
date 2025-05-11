<x-layouts.app>
	@push('scripts')
		@vite(['resources/js/search.ts'])
	@endpush

	<div class="m-4 grow">
		@if (session('status') === 'screenshot-updated')
			<x-alert-success class="mb-4">Screenshot updated successfully</x-alert-success>
		@endif

		@if (session('status') === 'screenshot-deleted')
			<x-alert-success class="mb-4">Screenshot deleted successfully</x-alert-success>
		@endif

		<div class="flex flex-col gap-6">
			<div class="flex items-center justify-between">
				<label class="input">
					<span class="icon-[heroicons--magnifying-glass]"></span>
					<input type="search" id="search" placeholder="Search for names..." />
				</label>

				<div>
					<span>
						{{ $screenshots->sum(fn ($screenshots) => $screenshots->count()) }} screenshots
					</span>

					(
					<span class="inline-flex gap-2">
						@foreach ($screenshots->sortByDesc(fn ($group) => $group->count()) as $wowClass => $classScreenshots)
							<span style="color: {{ $classScreenshots[0]->wowClass->color }}">
								{{ $classScreenshots->count() }}
							</span>
						@endforeach
					</span>
					)
				</div>
			</div>

			<div class="grid gap-1" style="grid-template-columns: repeat(13, minmax(135px, 1fr))">
				@foreach ($wowClasses as $wowClass)
					<div>
						<div
							class="mb-4 text-center text-lg font-bold capitalize"
							style="color: {{ $wowClass->color }}"
						>
							{{ $wowClass->name }}
						</div>

						<div class="flex flex-col gap-1">
							@foreach ($screenshots[$wowClass->id] ?? [] as $screenshot)
								@if (request()->attributes->get('isAdmin'))
									<a
										href="{{ route('screenshots.edit', $screenshot) }}"
										class="screenshot"
										data-wow-name="{{ $screenshot->wow_name }}"
									>
										<img
											loading="lazy"
											src="{{ config('filesystems.cdn_url') }}/{{ $screenshot->path }}"
											width="{{ $screenshot->width }}"
											height="{{ $screenshot->height }}"
											alt="{{ $screenshot->wow_name }}"
											class="w-full"
										/>
									</a>
								@else
									<img
										loading="lazy"
										src="{{ config('filesystems.cdn_url') }}/{{ $screenshot->path }}"
										width="{{ $screenshot->width }}"
										height="{{ $screenshot->height }}"
										alt="{{ $screenshot->wow_name }}"
										class="screenshot w-full"
										data-wow-name="{{ $screenshot->wow_name }}"
									/>
								@endif
							@endforeach
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</x-layouts.app>
