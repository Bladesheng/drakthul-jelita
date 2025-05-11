<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use App\Models\WowClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class ScreenshotController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		$screenshots = Screenshot::with('wowClass')
			->select('id', 'path', 'wow_name', 'wow_class', 'wow_class_id', 'width', 'height')
			->orderBy('wow_name')
			->get()
			->groupBy('wow_class_id')
			->sortKeys();

		return view('screenshot.index', [
			'screenshots' => $screenshots,
			'wowClasses' => WowClass::all(),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('screenshot.create', [
			'wowClasses' => WowClass::all(),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$request->merge([
			'wowName' => ucfirst($request->input('wowName')),
		]);

		$validated = $request->validate([
			'wowName' => 'required|min:2|max:16',
			'wowClassId' => 'required|exists:wow_classes,id',
			'screenshot' => ['required', File::image()->max('1mb')],
		]);

		$wowName = $validated['wowName'];
		$wowClassId = $validated['wowClassId'];

		$exists = Screenshot::where('wow_name', $wowName)->where('wow_class_id', $wowClassId)->exists();
		if ($exists) {
			return back()
				->withErrors(['wowName' => 'Screenshot with that name and class already exists'])
				->withInput();
		}

		$file = $validated['screenshot'];

		$mimeType = $file->getClientMimeType();
		$tempPath = $file->getRealPath();

		$img = match ($mimeType) {
			'image/png' => imagecreatefrompng($tempPath),
			'image/jpeg' => imagecreatefromjpeg($tempPath),
			'image/webp' => imagecreatefromwebp($tempPath),
			'image/avif' => imagecreatefromavif($tempPath),
			default => null,
		};

		if (!$img) {
			return back()
				->withErrors(['screenshot' => 'Unsupported image format'])
				->withInput();
		}

		$avifPath = sys_get_temp_dir() . '/' . Str::uuid() . '.avif';

		/**
		 * AVIF 90 is as close as it gets to the original quality.
		 * Webp 100 has similar size, and sometimes similar quality too, but other times AVIF just
		 * looks better.
		 */
		if (!imageavif($img, $avifPath, 90)) {
			return back()
				->withErrors(['screenshot' => 'Failed to convert image to AVIF'])
				->withInput();
		}

		$avifFile = new \Illuminate\Http\File($avifPath);
		$path = Storage::disk('s3')->putFile($avifFile);

		$screenshot = new Screenshot();
		$screenshot->path = $path;
		$screenshot->mime_type = $avifFile->getMimeType();
		$screenshot->size = $avifFile->getSize();
		$screenshot->wow_name = $wowName;
		$screenshot->wowClass()->associate($wowClassId);
		$screenshot->width = imagesx($img);
		$screenshot->height = imagesy($img);
		$screenshot->save();

		imagedestroy($img);
		unlink($avifPath);

		return to_route('screenshots.create')->with('status', 'screenshot-created');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Screenshot $screenshot)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Screenshot $screenshot): View
	{
		return view('screenshot.edit', [
			'screenshot' => $screenshot,
			'wowClasses' => WowClass::all(),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Screenshot $screenshot): RedirectResponse
	{
		$request->merge([
			'wowName' => ucfirst($request->input('wowName')),
		]);

		$validated = $request->validate([
			'wowName' => 'required|min:2|max:16',
			'wowClassId' => 'required|exists:wow_classes,id',
		]);

		$wowName = $validated['wowName'];
		$wowClassId = $validated['wowClassId'];

		$exists = Screenshot::where('wow_name', $wowName)->where('wow_class_id', $wowClassId)->exists();
		if ($exists) {
			return back()
				->withErrors(['wowName' => 'Screenshot with that name and class already exists'])
				->withInput();
		}

		$screenshot->wow_name = $wowName;
		$screenshot->wowClass()->associate($wowClassId);
		$screenshot->save();

		return to_route('screenshots.index')->with('status', 'screenshot-updated');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Screenshot $screenshot): RedirectResponse
	{
		$screenshot->delete();

		Storage::disk('s3')->delete($screenshot->path);

		return to_route('screenshots.index')->with('status', 'screenshot-deleted');
	}

	public function search(Request $request): JsonResponse
	{
		$screenshots = Screenshot::where('wow_name', $request->query('wowName'))
			->where('wow_class_id', $request->query('wowClassId'))
			->get();

		return response()->json($screenshots);
	}
}
