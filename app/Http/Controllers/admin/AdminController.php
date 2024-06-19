<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $startDate = Carbon::now()->subWeek();
        $datesArray = [];
        $series = [];
        $penjualan = [];
        $ratingChart = [];

        foreach ($products as $product) {
            $salesData = [];
            $date = $startDate->copy();
            for ($i = 0; $i < 7; $i++) {
                $date = $date->addDay();
                $sales = Cart::where('product_id', $product->id)->where('pembayaran', true)->whereDate('created_at', $date)->sum('jumlah');
                $salesData[] = $sales? $sales: null;
            }

            $nonNullValues = array_filter($salesData, function($value) {
                return !is_null($value);
            });
            
            // Cek apakah array yang difilter kosong
            if (!empty($nonNullValues)) {
                $series[] = [
                    'name' => $product->name,
                    'data' => $salesData
                ];
            }

            $totaljual = Cart::where('product_id', $product->id)->where('pembayaran', true)->where('created_at', '>=', $startDate)->sum('jumlah');

            if($totaljual > 0) {
                $ratingChart[] = ['name' => $product->name, 'penjualan' => $totaljual];
                $penjualan[] = $totaljual;
            }
        }

        $maxJual = max($penjualan);

        foreach ($ratingChart as $key => $item) {
            $ratingChart[$key]['rating'] = $this->calculateRating($item['penjualan'], $maxJual);
        }

        usort($ratingChart, function($a, $b) {
            return $b['rating'] <=> $a['rating'];
        });
        
        foreach ($ratingChart as $item) {
            $data['ratingChart']['name'][] = $item['name'];
            $ratingJual[] = $item['rating'];
        }

        // memvbatasi dan menghapus decimal jika bilangan bulat
        $ratingJual = array_map(function($num) {
            $num = $this->formatNumber($num);
            return $num;
        }, $ratingJual);

        $data['ratingChart']['series'][] = ['name' => 'Rating', 'data' => $ratingJual];

        // return $data['ratingChart']['name'];
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDay(); // Tambahkan hari ke tanggal mulai
            $formattedDate = $date->format('d M'); // Format tanggal menjadi '12 Feb'
            $datesArray[] = $formattedDate;
        }

        $data['datesArr'] = $datesArray;
        $data['series'] = $series;
        $data['cekstok'] = Product::where('jumlah', 0)->get();
        $data['habis'] = Product::where('jumlah', '<=', 5)->get();

        // dd($data);
        // return($series);
        return view('admin.dashboard', $data);
    }


    private function calculateRating($total_penjualan, $maxPenjualan)
    {
        // Jika maxPenjualan adalah 0, atur rating ke 0 untuk menghindari pembagian oleh nol
        if ($maxPenjualan == 0) {
            return 0;
        }
        
        // Hitung persentase
        return ($total_penjualan / $maxPenjualan) * 100;
    }
    
    private function formatNumber($num) {
        $rounded = round($num, 1);
        // Jika hasil pembulatan adalah bilangan bulat, kembalikan sebagai integer
        return ($rounded == intval($rounded)) ? intval($rounded) : $rounded;
    }
}
