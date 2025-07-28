<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $itemsByLatestActivity = Item::orderBy('updated_at', 'desc')
                                     ->take(7)
                                     ->get();
        $totalItemCount = Item::count();

        $recentStockIns = StockIn::orderBy('created_at', 'desc')
                                 ->take(5)
                                 ->get();

        $recentStockOuts = StockOut::orderBy('created_at', 'desc')
                                  ->take(5)
                                  ->get();

        $dashboardLastRefreshed = Carbon::now();

        return view('dashboard', compact(
            'itemsByLatestActivity',
            'totalItemCount',
            'recentStockIns',
            'recentStockOuts',
            'dashboardLastRefreshed'
        ));
    }
}