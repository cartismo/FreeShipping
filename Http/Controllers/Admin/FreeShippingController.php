<?php

namespace Modules\FreeShipping\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstalledModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FreeShippingController extends Controller
{
    /**
     * Show shipping method settings
     */
    public function index(): Response
    {
        $module = InstalledModule::where('slug', 'free-shipping')->firstOrFail();

        $defaultSettings = [
            'enabled' => true,
            'title' => 'Free Shipping',
            'description' => 'Free standard shipping',
            'minimum_order_amount' => null,
            'sort_order' => 0,
        ];

        $settings = array_replace_recursive($defaultSettings, $module->settings ?? []);

        return Inertia::render('FreeShipping::Admin/Settings', [
            'module' => $module,
            'settings' => $settings,
            'defaultSettings' => $defaultSettings,
        ]);
    }

    /**
     * Update shipping method settings
     */
    public function update(Request $request): RedirectResponse
    {
        $module = InstalledModule::where('slug', 'free-shipping')->firstOrFail();

        $validated = $request->validate([
            'settings.enabled' => 'boolean',
            'settings.title' => 'required|string|max:255',
            'settings.description' => 'nullable|string|max:1000',
            'settings.minimum_order_amount' => 'nullable|numeric|min:0',
            'settings.sort_order' => 'integer|min:0',
        ]);

        $module->update([
            'settings' => $validated['settings'],
        ]);

        return back()->with('success', 'Shipping settings updated successfully.');
    }
}