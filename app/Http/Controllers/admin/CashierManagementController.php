<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\StoreCashierRequest;
use App\Http\Requests\admin\UpdateCashierRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class CashierManagementController extends Controller
{
    /**
     * Tampilkan daftar staf kasir.
     */
    public function index()
    {
        $cashiers = User::where('role', 'cashier')->latest()->get();
        return view('admin.cashiers.index', compact('cashiers'));
    }

    /**
     * Tampilkan formulir tambah akun kasir baru.
     */
    public function create()
    {
        return view('admin.cashiers.create');
    }

    /**
     * Simpan akun kasir baru ke database.
     */
    public function store(StoreCashierRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Hash password secara aman dan set role kasir
            $data['password'] = Hash::make($data['password']);
            $data['role'] = 'cashier';

            User::create($data);

            return redirect()->route('admin.cashiers.index')->with('success', 'Akun kasir baru berhasil didaftarkan!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store Cashier Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal mendaftarkan kasir karena terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Tampilkan formulir edit akun kasir.
     */
    public function edit($id)
    {
        $cashier = User::findOrFail($id);
        return view('admin.cashiers.edit', compact('cashier'));
    }

    /**
     * Perbarui data kasir di database.
     */
    public function update(UpdateCashierRequest $request, $id)
    {
        try {
            $cashier = User::findOrFail($id);
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $cashier->update($data);

            return redirect()->route('admin.cashiers.index')->with('success', 'Data akun kasir berhasil diperbarui!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Cashier Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal memperbarui data kasir karena terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Hapus akun kasir dari database.
     */
    public function destroy($id)
    {
        try {
            $cashier = User::findOrFail($id);
            $cashier->delete();

            return redirect()->route('admin.cashiers.index')->with('success', 'Akun kasir berhasil dihapus!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Delete Cashier Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('admin.cashiers.index')->with('error', 'Gagal menghapus akun kasir karena terjadi kesalahan sistem.');
        }
    }
}
