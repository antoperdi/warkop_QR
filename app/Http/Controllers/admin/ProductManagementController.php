<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\StoreProductRequest;
use App\Http\Requests\admin\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProductManagementController extends Controller
{
    /**
     * Tampilkan daftar menu warkop.
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Tampilkan formulir tambah menu baru.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Simpan menu baru ke database.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Set default is_available ke true jika tidak diinput
            $data['is_available'] = $request->has('is_available') ? (bool)$request->is_available : true;

            if ($request->hasFile('image')) {
                // Simpan berkas gambar ke storage/app/public/products
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            Product::create($data);

            return redirect()->route('admin.products.index')->with('success', 'Menu baru berhasil ditambahkan!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store Product Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal menambahkan menu baru karena terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Tampilkan formulir edit menu.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Perbarui data menu di database.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $data = $request->validated();

            $data['is_available'] = $request->has('is_available') ? (bool)$request->is_available : false;

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                // Simpan gambar baru
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            return redirect()->route('admin.products.index')->with('success', 'Data menu berhasil diperbarui!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Product Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal memperbarui menu karena terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Hapus menu dari database.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Menu berhasil dihapus!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Delete Product Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('admin.products.index')->with('error', 'Gagal menghapus menu karena terjadi kesalahan sistem.');
        }
    }

    /**
     * Ganti status ketersediaan menu secara cepat (toggle).
     */
    public function toggleStatus($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update([
                'is_available' => !$product->is_available
            ]);

            return redirect()->route('admin.products.index')->with('success', 'Status ketersediaan menu berhasil diubah!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Toggle Product Status Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('admin.products.index')->with('error', 'Gagal mengubah status menu karena terjadi kesalahan sistem.');
        }
    }
}
