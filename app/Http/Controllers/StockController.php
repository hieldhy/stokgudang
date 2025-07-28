<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

class StockController extends Controller
{
    public function list()
    {
        $items = Item::orderBy('nama_perangkat', 'asc')->get();
        $stockIns = StockIn::with('item')->orderBy('created_at', 'desc')->get();
        $stockOuts = StockOut::with('item')->orderBy('created_at', 'desc')->get();
        
        $mappedItemsForJson = $items->map(function($item) {
            return [
                'id' => $item->itemid,
                'nama_perangkat' => $item->nama_perangkat,
                'type' => $item->type ?: '-',
                'serialnumber' => $item->serialnumber ?: '-',
                'volume' => $item->volume,
                'displayTextStockOut' => $item->nama_perangkat . ' (Stok: ' . $item->volume . ', SN: ' . ($item->serialnumber ?: '-') . ')',
                'displayTextStockIn' => $item->nama_perangkat . ' (Tipe: ' . ($item->type ?: '-') . ', SN: ' . ($item->serialnumber ?: '-') . ')'
            ];
        });

        return view('stock.index', compact('items', 'stockIns', 'stockOuts', 'mappedItemsForJson'));
    }

    public function addIn(Request $request)
    {
        $validatedData = $request->validate([
            'itemid' => 'required|exists:items,itemid',
            'volume' => 'required|integer|min:1',
            'serialnumber' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($validatedData['itemid']);
            
            $item->volume += $validatedData['volume'];
            $item->save();

            StockIn::create([
                'itemid' => $item->itemid,
                'volume' => $validatedData['volume'],
                'keterangan' => $validatedData['keterangan'],
            ]);

            DB::commit();

            return redirect()->route('stock.list', ['active_tab' => 'stockIn'])
                ->with('success', 'Stok masuk berhasil dicatat untuk: ' . $item->nama_perangkat);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing stock in: ' . $e->getMessage());
            return redirect()->route('stock.list', ['active_tab' => 'stockIn'])
                ->with('error', 'Gagal mencatat stok masuk. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function addOut(Request $request)
    {
        $validatedData = $request->validate([
            'itemid' => 'required|exists:items,itemid',
            'volume' => 'required|integer|min:1',
            'recipient' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($validatedData['itemid']);

            if ($item->volume < $validatedData['volume']) {
                DB::rollBack();
                return redirect()->route('stock.list', ['active_tab' => 'stockOut'])
                    ->withErrors(['volume' => 'Volume keluar melebihi stok yang tersedia untuk barang: ' . $item->nama_perangkat])
                    ->withInput();
            }

            $item->volume -= $validatedData['volume'];
            $item->save();

            StockOut::create([
                'itemid' => $item->itemid,
                'volume' => $validatedData['volume'],
                'recipient' => $validatedData['recipient'],
                'keterangan' => $validatedData['keterangan'],
            ]);

            DB::commit();

            return redirect()->route('stock.list', ['active_tab' => 'stockOut'])
                ->with('success', 'Stok keluar berhasil dicatat untuk: ' . $item->nama_perangkat);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing stock out: ' . $e->getMessage());
            return redirect()->route('stock.list', ['active_tab' => 'stockOut'])
                ->with('error', 'Gagal mencatat stok keluar. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function download(Request $request)
    {
        $type = $request->query('type', 'allinventory');
        $format = $request->query('format', 'pdf');
        $fileName = 'PLN_UID_KALSELTENG_' . str_replace('_', '-', $type) . '_' . date('d-m-Y_H-i-s') . '.' . $format;
        $title = 'Laporan Stok'; 
        $data = collect();
        $headers = [];

        if ($format === 'pdf') {
            if ($type === 'allinventory') {
                $title = 'Laporan Stok Keseluruhan';
                $headers = ['No.', 'Perangkat', 'Tipe', 'Spesifikasi', 'SN', 'Volume', 'Satuan', 'Keterangan', 'Referensi'];
                $items = Item::orderBy('nama_perangkat', 'asc')->get();
                $data = $items->map(function ($item, $index) {
                    return [
                        'no' => $index + 1,
                        'perangkat' => $item->nama_perangkat ?? 'N/A',
                        'tipe' => $item->type ?? '-',
                        'spesifikasi' => $item->spesifikasi ?? '-',
                        'sn' => $item->serialnumber ?? '-',
                        'volume' => $item->volume ?? 0,
                        'satuan' => $item->satuan ?? 'Unit',
                        'keterangan' => $item->keterangan ?? '-',
                        'referensi' => $item->referensi ?? '-',
                    ];
                });
            } elseif ($type === 'stockinhistory') {
                $title = 'Laporan Riwayat Stok Masuk';
                $headers = ['No.', 'Tanggal Masuk', 'Perangkat', 'Tipe', 'Spesifikasi', 'SN', 'Volume', 'Keterangan'];
                $records = StockIn::with('item')->orderBy('created_at', 'desc')->get();
                $data = $records->map(function ($record, $index) {
                    return [
                        'no' => $index + 1,
                        'tanggal' => $record->created_at->format('d M Y, H:i'),
                        'perangkat' => $record->item->nama_perangkat ?? 'N/A',
                        'tipe' => $record->item->type ?? '-',
                        'spesifikasi' => $record->item->spesifikasi ?? '-',
                        'sn' => $record->item->serialnumber ?? '-',
                        'volume' => '+' . $record->volume,
                        'keterangan' => $record->keterangan ?? '-',
                    ];
                });
            } elseif ($type === 'stockouthistory') {
                $title = 'Laporan Riwayat Stok Keluar';
                $headers = ['No.', 'Tanggal Keluar', 'Perangkat', 'Tipe', 'Spesifikasi', 'SN', 'Volume', 'Pengambil', 'Keterangan'];
                $records = StockOut::with('item')->orderBy('created_at', 'desc')->get();
                $data = $records->map(function ($record, $index) {
                    return [
                        'no' => $index + 1,
                        'tanggal' => $record->created_at->format('d M Y, H:i'),
                        'perangkat' => $record->item->nama_perangkat ?? 'N/A',
                        'tipe' => $record->item->type ?? '-',
                        'spesifikasi' => $record->item->spesifikasi ?? '-',
                        'sn' => $record->item->serialnumber ?? '-',
                        'volume' => '-' . $record->volume,
                        'pengambil' => $record->recipient ?? '-',
                        'keterangan' => $record->keterangan ?? '-',
                    ];
                });
            }
            
            if ($data->isEmpty() && $type !== 'allinventory' && !Item::exists() && $type === 'allinventory') {
                 return redirect()->route('stock.list')->with('info', 'Tidak ada data untuk diekspor jenis laporan: ' . $title);
            }
            
            $pdf = Pdf::loadView('exports.template_pdf', compact('data', 'headers', 'title', 'fileName'))->setPaper('a4', 'landscape');
            return $pdf->download($fileName);

        } elseif ($format === 'csv') {
            $responseHeaders = [
                "Content-type"        => "text/csv; charset=utf-8",
                "Content-Disposition" => "attachment; filename=\"$fileName\"",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use ($type) {
                $file = fopen('php://output', 'w');
                fwrite($file, "\xEF\xBB\xBF"); 
                fwrite($file, "sep=,\n"); 

                if ($type === 'allinventory') {
                    $headers = ['No.', 'Perangkat', 'Tipe', 'Spesifikasi', 'Serial Number', 'Volume', 'Satuan', 'Keterangan', 'Referensi'];
                    fputcsv($file, $headers);
                    Item::orderBy('nama_perangkat', 'asc')->chunk(200, function ($items) use ($file) {
                        static $counter = 1;
                        foreach ($items as $item) {
                            fputcsv($file, [
                                $counter++,
                                $item->nama_perangkat ?? 'N/A',
                                $item->type ?? '-',
                                $item->spesifikasi ?? '-',
                                $item->serialnumber ?? '-',
                                $item->volume ?? 0,
                                $item->satuan ?? 'Unit',
                                $item->keterangan ?? '-',
                                $item->referensi ?? '-',
                            ]);
                        }
                    });
                } elseif ($type === 'stockinhistory') {
                    $headers = ['No.', 'Tanggal Masuk', 'Perangkat', 'Tipe', 'Spesifikasi', 'Serial Number', 'Volume Masuk', 'Keterangan'];
                    fputcsv($file, $headers);
                    StockIn::with('item')->orderBy('created_at', 'desc')->chunk(200, function ($records) use ($file) {
                        static $counter = 1;
                        foreach ($records as $record) {
                            fputcsv($file, [
                                $counter++,
                                $record->created_at->format('d M Y, H:i'),
                                $record->item->nama_perangkat ?? 'N/A',
                                $record->item->type ?? '-',
                                $record->item->spesifikasi ?? '-',
                                $record->item->serialnumber ?? '-',
                                '+' . $record->volume,
                                $record->keterangan ?? '-',
                            ]);
                        }
                    });
                } elseif ($type === 'stockouthistory') {
                    $headers = ['No.', 'Tanggal Keluar', 'Perangkat', 'Tipe', 'Spesifikasi', 'Serial Number', 'Volume Keluar', 'Pengambil', 'Keterangan'];
                    fputcsv($file, $headers);
                    StockOut::with('item')->orderBy('created_at', 'desc')->chunk(200, function ($records) use ($file) {
                        static $counter = 1;
                        foreach ($records as $record) {
                            fputcsv($file, [
                                $counter++,
                                $record->created_at->format('d M Y, H:i'),
                                $record->item->nama_perangkat ?? 'N/A',
                                $record->item->type ?? '-',
                                $record->item->spesifikasi ?? '-',
                                $record->item->serialnumber ?? '-',
                                '-' . $record->volume,
                                $record->recipient ?? '-',
                                $record->keterangan ?? '-',
                            ]);
                        }
                    });
                }
                fclose($file);
            };
            return new StreamedResponse($callback, 200, $responseHeaders);
        }

        $errorMessage = 'Format atau tipe ekspor tidak valid.';
        if (Route::has('stock.list')) return redirect()->route('stock.list')->with('error', $errorMessage);
        return redirect('/')->with('error', $errorMessage);
    }
}