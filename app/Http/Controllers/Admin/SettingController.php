<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    /**
     * Menyimpan perubahan pengaturan.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name'        => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'logo'             => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
            'favicon'          => 'nullable|image|mimes:png,ico|max:512',
            'remove_logo'      => 'nullable',
            'remove_favicon'   => 'nullable',
        ]);

        // Simpan teks
        Setting::setValue('site_name', $validated['site_name']);
        Setting::setValue('site_description', $validated['site_description'] ?? '');

        // Logo
        if ($request->hasFile('logo')) {
            // Hapus lama
            if ($old = Setting::getValue('logo_url')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $old));
            }
            $path = $request->file('logo')->store('settings', 'public');
            Setting::setValue('logo_url', '/storage/' . $path);
        } elseif ($request->has('remove_logo')) {
            if ($old = Setting::getValue('logo_url')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $old));
            }
            Setting::setValue('logo_url', null);
        }

        // Favicon
        if ($request->hasFile('favicon')) {
            if ($old = Setting::getValue('favicon_url')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $old));
            }
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::setValue('favicon_url', '/storage/' . $path);
        } elseif ($request->has('remove_favicon')) {
            if ($old = Setting::getValue('favicon_url')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $old));
            }
            Setting::setValue('favicon_url', null);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}