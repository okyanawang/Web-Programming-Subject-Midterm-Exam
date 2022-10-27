<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Category;

class ShopController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        $products = DB::table('component')->orderBy('c_id')->get();
        $categories = Category::all();
        $user = Auth::user();
        // dd($products);
        return view('shops.index', compact('products', 'user', 'categories'));
    }

    public function store(Request $request){
        // $this->middleware('auth');
        $unc = 0;
        
        $orders = DB::table('orders')
                     ->select('a_id_customer', 'o_id')
                     ->where('a_id_customer', $request["users_id"])
                     ->where('transaction_status', $unc)
                     ->first();

                     
                     if(!$orders){
                         $neworders = DB::table('orders')->insert([
                             "a_id_customer" => $request["users_id"],
                "o_total_price" => 0,
                "o_address" => "none",
                "transaction_status" => $unc
            ]);
        }
        
        $ordersId = DB::table('orders')
        ->select('o_id')
        ->where('a_id_customer', $request["a_id_customer"])
                ->where('transaction_status', $unc)
                ->first();
        
        if(!$ordersId) {
            $apaaa = DB::table('orders')
            ->where('a_id_customer', $request["a_id_customer"])
            ->update(['transaction_status' => 0]);
        }
        
        $lastpls = DB::table('orders')
        ->where('a_id_customer', $request["users_id"])
        ->where('transaction_status', $unc)
        ->value('o_id');
        // ->get();

        // dd($request["users_id"]);
        
        // dd($lastpls);
        // dd(array_values($lastpls));
        // dd($request["id"]);
        
        $ordersDetailId = DB::table('order_details')
        ->select('o_id')
        ->where('c_id', $request["id"])
        ->where('o_id', $lastpls)
        ->first();
        
        // dd($ordersDetailId);

        $ambil_id = DB::table('orders')
        ->select('o_id')
        ->where('a_id_customer', $request["id"])
        ->where('transaction_status', $unc)
        ->first();

        // dd($ambil_id);

        $pernah_order = DB::table('order_details')
            ->select('o_id')
            ->where('c_id', $request["id"])
            ->where('o_id', $lastpls)
            ->first();
        
        if (!$ordersDetailId){
            $query = DB::table('order_details')->insert([
                "c_id" => $request["id"],
                "od_qty" => 0,
                "o_id" => $lastpls
            ]);
        }
        
        // dd($pernah_order);
        // dd($request->price);

        if($pernah_order){
            $banyak_lama = DB::table('order_details')
            ->where('o_id', $lastpls)
            ->where('c_id', $request->id)
            ->value('od_qty');
            $banyak = $banyak_lama + 1;
            $harga = $request->price;
            $res = $harga * $banyak;
            $query = DB::table('order_details')
            ->where('o_id', $lastpls)
            ->where('c_id', $request->id)
            ->update(['od_qty' => $banyak]);
            // dd($query);
            // ->update(['quantity' => $banyak, 'total_price' => $res]);
        }else {
            $query = DB::table('order_details')
            ->where('o_id', $lastpls)
            ->where('c_id', $request->id)
            ->update(['od_qty' => 1]);
        }

        // dd($query);

        // return redirect('/shop')->with('success', 'Product added to cart successfully!');
        Alert::success('Success', 'Product Successfully Added!');
        return redirect('/shop')->with('success', 'Product added to cart successfully!');
    }
}
