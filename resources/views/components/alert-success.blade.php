<div
	role="alert"
	{{ $attributes->merge(['class' => 'alert alert-success']) }}
>
	<span class="icon-[heroicons--check-circle] h-6 w-6"></span>
	<span>
		{{ $slot }}
	</span>
</div>
