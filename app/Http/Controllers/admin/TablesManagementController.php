<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\StoreTableRequest;
use App\Http\Requests\admin\UpdateTableRequest;
use App\Models\Table;
use Exception;

class TablesManagementController extends Controller
{
    /**
     * Tampilkan daftar meja warkop.
     */
    public function index()
    {
        $tables = Table::latest()->get();
        return view('admin.tabel.index', compact('tables'));
    }

    /**
     * Tampilkan formulir tambah meja baru.
     */
    public function create()
    {
        return view('admin.tabel.create');
    }

    /**
     * Simpan meja baru ke database.
     */
    public function store(StoreTableRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Generate token QR secara otomatis dan unik
            $data['qr_token'] = strtoupper(bin2hex(random_bytes(10)));
            $data['is_active'] = true;

            Table::create($data);

            return redirect()->route('admin.tabel.index')->with('success', 'Meja baru berhasil ditambahkan!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store Table Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal menambahkan meja baru karena terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Tampilkan formulir edit meja.
     */
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        return view('admin.tabel.edit', compact('table'));
    }

    /**
     * Perbarui data meja di database.
     */
    public function update(UpdateTableRequest $request, $id)
    {
        try {
            $table = Table::findOrFail($id);
            $data = $request->validated();

            $data['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;

            $table->update($data);

            return redirect()->route('admin.tabel.index')->with('success', 'Data meja berhasil diperbarui!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Table Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal memperbarui meja karena terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Hapus meja dari database.
     */
    public function destroy($id)
    {
        try {
            $table = Table::findOrFail($id);
            $table->delete();

            return redirect()->route('admin.tabel.index')->with('success', 'Meja berhasil dihapus!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Delete Table Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('admin.tabel.index')->with('error', 'Gagal menghapus meja karena terjadi kesalahan sistem.');
        }
    }

    /**
     * Ganti status keaktifan meja secara cepat (toggle).
     */
    public function toggleStatus($id)
    {
        try {
            $table = Table::findOrFail($id);
            $table->update([
                'is_active' => !$table->is_active
            ]);

            return redirect()->route('admin.tabel.index')->with('success', 'Status keaktifan meja berhasil diubah!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Toggle Table Status Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('admin.tabel.index')->with('error', 'Gagal mengubah status meja karena terjadi kesalahan sistem.');
        }
    }
}
