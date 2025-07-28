<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function list()
    {
        $items = Item::orderBy('created_at', 'desc')->get();
        return view('item.index', compact('items'));
    }

    public function detail($id)
    {
        $item = Item::with('stockOuts')->findOrFail($id);
        $lastStockOut = null;
        if ($item->volume == 0 && $item->stockOuts->isNotEmpty()) {
            $lastStockOut = $item->stockOuts->sortByDesc('created_at')->first();
        }
        return response()->json([
            'item' => $item,
            'last_stock_out' => $lastStockOut ? [
                'date' => $lastStockOut->created_at->format('d M Y, H:i') . ' WITA',
                'recipient' => $lastStockOut->recipient,
                'keterangan' => $lastStockOut->keterangan
            ] : null
        ]);
    }

    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'volume' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:100',
            'serialnumber' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'referensi' => 'nullable|string',
        ]);

        $dataToStore = $validatedData;
        $dataToStore['keterangan'] = (isset($dataToStore['keterangan']) && trim($dataToStore['keterangan']) !== '') ? trim($dataToStore['keterangan']) : '-';
        $dataToStore['referensi'] = (isset($dataToStore['referensi']) && trim($dataToStore['referensi']) !== '') ? trim($dataToStore['referensi']) : '-';
        if (isset($dataToStore['type']) && trim($dataToStore['type']) === '') $dataToStore['type'] = null;
        if (isset($dataToStore['spesifikasi']) && trim($dataToStore['spesifikasi']) === '') $dataToStore['spesifikasi'] = null;
        if (isset($dataToStore['satuan']) && trim($dataToStore['satuan']) === '') $dataToStore['satuan'] = null;
        if (isset($dataToStore['serialnumber']) && trim($dataToStore['serialnumber']) === '') $dataToStore['serialnumber'] = null;

        DB::beginTransaction();
        try {
            $serialNumber = $dataToStore['serialnumber'];
            $query = Item::where('nama_perangkat', $dataToStore['nama_perangkat'])->where(function ($q) use ($dataToStore) {
                if (is_null($dataToStore['type'])) $q->whereNull('type')->orWhere('type', '');
                else $q->where('type', $dataToStore['type']);
            })->where(function ($q) use ($dataToStore) {
                if (is_null($dataToStore['spesifikasi'])) $q->whereNull('spesifikasi')->orWhere('spesifikasi', '');
                else $q->where('spesifikasi', $dataToStore['spesifikasi']);
            });
            if (is_null($serialNumber)) $query->whereNull('serialnumber')->orWhere('serialnumber', '');
            else $query->where('serialnumber', $serialNumber);

            $item = $query->first();
            $keteranganStockIn = 'Barang baru ditambahkan';
            $isExisting = false;

            if ($item) {
                $item->volume += $dataToStore['volume'];
                $item->satuan = $dataToStore['satuan'] ?? $item->satuan;
                $item->keterangan = $dataToStore['keterangan'];
                $item->referensi = $dataToStore['referensi'];
                $item->save();
                $keteranganStockIn = 'Stok tambahan dicatat untuk barang yang sudah ada';
                $isExisting = true;
            } else {
                $item = Item::create($dataToStore);
            }

            if ($dataToStore['volume'] > 0) {
                StockIn::create([
                    'itemid' => $item->itemid,
                    'volume' => $dataToStore['volume'],
                    'keterangan' => $keteranganStockIn,
                ]);
            }

            DB::commit();
            $redirectRoute = 'stock.list';
            return redirect()->route($redirectRoute, ['highlight_item_id' => $item->itemid])->with('success', $isExisting ? 'Jumlah item berhasil diperbarui. Stok Masuk dicatat.' : 'Item berhasil ditambahkan. Stok Masuk dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing item: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return redirect()->back()->withInput()->with('error', 'Gagal menambah/memperbarui item. Silakan coba lagi. ' . $e->getMessage());
        }
    }

    public function renew(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'volume' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:100',
            'serialnumber' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'referensi' => 'nullable|string',
        ]);

        $dataToUpdate = $validatedData;
        $dataToUpdate['keterangan'] = (isset($dataToUpdate['keterangan']) && trim($dataToUpdate['keterangan']) !== '') ? trim($dataToUpdate['keterangan']) : '-';
        $dataToUpdate['referensi'] = (isset($dataToUpdate['referensi']) && trim($dataToUpdate['referensi']) !== '') ? trim($dataToUpdate['referensi']) : '-';
        if (isset($dataToUpdate['type']) && trim($dataToUpdate['type']) === '') $dataToUpdate['type'] = null;
        if (isset($dataToUpdate['spesifikasi']) && trim($dataToUpdate['spesifikasi']) === '') $dataToUpdate['spesifikasi'] = null;
        if (isset($dataToUpdate['satuan']) && trim($dataToUpdate['satuan']) === '') $dataToUpdate['satuan'] = null;
        if (isset($dataToUpdate['serialnumber']) && trim($dataToUpdate['serialnumber']) === '') $dataToUpdate['serialnumber'] = null;

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($id);
            $oldVolume = $item->volume;
            $item->update($dataToUpdate);
            $newVolume = $item->volume;
            $volumeDifference = $newVolume - $oldVolume;
            $keteranganStockMovement = $request->input('keterangan_stock_movement', ($volumeDifference > 0 ? 'Stok bertambah melalui edit' : ($volumeDifference < 0 ? 'Stok berkurang melalui edit' : 'Volume tidak berubah')));

            if ($volumeDifference > 0) {
                StockIn::create([
                    'itemid' => $item->itemid,
                    'volume' => $volumeDifference,
                    'keterangan' => $keteranganStockMovement,
                ]);
            } elseif ($volumeDifference < 0) {
                StockOut::create([
                    'itemid' => $item->itemid,
                    'volume' => abs($volumeDifference),
                    'keterangan' => $keteranganStockMovement,
                    'recipient' => $request->input('recipient_on_edit', 'Penyesuaian Sistem'),
                ]);
            }

            DB::commit();
            return redirect()->route('item.list', ['highlight_item_id' => $item->itemid])->with('success', 'Item berhasil diperbarui. Pergerakan stok dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating item: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui item. Silakan coba lagi. ' . $e->getMessage());
        }
    }

    public function remove($id)
    {
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($id);
            $currentVolume = $item->volume;
            $keteranganPenghapusan = 'Volume item diatur ke nol';

            if ($currentVolume > 0) {
                StockOut::create([
                    'itemid' => $item->itemid,
                    'volume' => $currentVolume,
                    'keterangan' => $keteranganPenghapusan,
                    'recipient' => 'Tindakan Sistem (Pengaturan ke Nol)',
                ]);
            }

            $item->volume = 0;
            $item->save();

            DB::commit();
            return redirect()->route('stock.list', ['highlight_item_id' => $item->itemid])->with('success', 'Volume item berhasil diatur ke 0. Stok Keluar dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error zeroing out item: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return redirect()->route('stock.list')->with('error', 'Gagal mengatur volume item ke nol. Silakan coba lagi. ' . $e->getMessage());
        }
    }
}