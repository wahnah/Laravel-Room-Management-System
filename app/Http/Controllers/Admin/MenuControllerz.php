<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;

class MenuControllerz extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();
        return view('admin.menu.index', compact('menuItems'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload image and store menu item
        $imagePath = $request->file('image')->store('menu_images', 'public');

        MenuItem::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'image' => $imagePath,
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu item created successfully.');
    }

    public function edit($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        return view('admin.menu.edit', compact('menuItem'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $menuItem = MenuItem::findOrFail($id);

        if ($request->hasFile('image')) {
            // Upload new image and update menu item
            $imagePath = $request->file('image')->store('menu_images', 'public');
            $menuItem->update([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'image' => $imagePath,
            ]);
        } else {
            // Update menu item without changing the image
            $menuItem->update([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
            ]);
        }

        return redirect()->route('menu.index')->with('success', 'Menu item updated successfully.');
    }

    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $menuItem->delete();

        return redirect()->route('menu.index')->with('success', 'Menu item deleted successfully.');
    }
}
