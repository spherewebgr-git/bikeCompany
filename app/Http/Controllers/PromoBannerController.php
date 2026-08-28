<?php

namespace App\Http\Controllers;

use App\Models\PromoBanner;
use Illuminate\Http\Request;

class PromoBannerController extends Controller
{
    public function index()
    {
        $banners = PromoBanner::orderBy('sort_order')->get();
        return view('staff.promo-banner.index', compact('banners'));
    }

    public function create()
    {
        return view('staff.promo-banner.edit', ['banner' => null]);
    }

    public function edit(PromoBanner $banner)
    {
        return view('staff.promo-banner.edit', compact('banner'));
    }

    public function store(Request $request)
    {
        return $this->save($request, new PromoBanner());
    }

    public function update(Request $request, PromoBanner $banner)
    {
        return $this->save($request, $banner);
    }

    private function save(Request $request, PromoBanner $banner)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'banner_color' => ['required', 'string', 'max:7'],
            'content_color' => ['required', 'string', 'max:7'],
            'button_text' => ['required', 'string', 'max:100'],
            'button_link' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('promo-banners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $banner->fill($validated);
        $banner->save();

        return redirect()->route('dashboard.promo-banner.index')
            ->with('success', 'Promo banner saved successfully.');
    }

    public function destroy(PromoBanner $banner)
    {
        $banner->delete();
        return back()->with('success', 'Promo banner deleted.');
    }

    // Public JSON endpoint — το καλεί το React component
    public function apiShow()
    {
        $banners = PromoBanner::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($banner) => [
                'title' => $banner->title,
                'description' => $banner->description,
                'image' => $banner->image ? asset('storage/' . $banner->image) : null,
                'bannerColor' => $banner->banner_color,
                'contentColor' => $banner->content_color,
                'buttonText' => $banner->button_text,
                'buttonLink' => $banner->button_link,
            ]);

        return response()->json($banners);
    }
}
