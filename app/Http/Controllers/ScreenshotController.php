<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class ScreenshotController extends Controller
{
	private const WOW_CLASSES = [
		['name' => 'death knight', 'color' => '#C41E3A'],
		['name' => 'demon hunter', 'color' => '#A330C9'],
		['name' => 'druid', 'color' => '#FF7C0A'],
		['name' => 'evoker', 'color' => '#33937F'],
		['name' => 'hunter', 'color' => '#AAD372'],
		['name' => 'mage', 'color' => '#3FC7EB'],
		['name' => 'monk', 'color' => '#00FF98'],
		['name' => 'paladin', 'color' => '#F48CBA'],
		['name' => 'priest', 'color' => '#FFFFFF'],
		['name' => 'rogue', 'color' => '#FFF468'],
		['name' => 'shaman', 'color' => '#0070DD'],
		['name' => 'warlock', 'color' => '#8788EE'],
		['name' => 'warrior', 'color' => '#C69B6D'],
	];

	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		$screenshots = Screenshot::select('id', 'path', 'wow_name', 'wow_class')
			->orderBy('wow_name')
			->get()
			->groupBy('wow_class')
			->sortKeys();

		return view('screenshot.index', [
			'screenshots' => $screenshots,
			'wowClasses' => self::WOW_CLASSES,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('screenshot.create', [
			'wowClasses' => self::WOW_CLASSES,
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
			'wowClass' => 'required',
			'screenshot' => ['required', File::image()->max('1mb')],
		]);

		$wowName = $validated['wowName'];
		$wowClass = $validated['wowClass'];

		$exists = Screenshot::where('wow_name', $wowName)->where('wow_class', $wowClass)->exists();
		if ($exists) {
			return back()
				->withErrors(['wowName' => 'Screenshot with that name and class already exists'])
				->withInput();
		}

		$file = $validated['screenshot'];
		$path = Storage::disk('s3')->putFile($file);

		$screenshot = new Screenshot();
		$screenshot->path = $path;
		$screenshot->mime_type = $file->getClientMimeType();
		$screenshot->size = $file->getSize();
		$screenshot->wow_name = $wowName;
		$screenshot->wow_class = $wowClass;
		$screenshot->save();

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
			'wowClasses' => self::WOW_CLASSES,
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
			'wowClass' => 'required',
		]);

		$wowName = $validated['wowName'];
		$wowClass = $validated['wowClass'];

		$exists = Screenshot::where('wow_name', $wowName)->where('wow_class', $wowClass)->exists();
		if ($exists) {
			return back()
				->withErrors(['wowName' => 'Screenshot with that name and class already exists'])
				->withInput();
		}

		$screenshot->wow_name = $wowName;
		$screenshot->wow_class = $wowClass;
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
			->where('wow_class', $request->query('wowClass'))
			->get();

		return response()->json($screenshots);
	}
}
