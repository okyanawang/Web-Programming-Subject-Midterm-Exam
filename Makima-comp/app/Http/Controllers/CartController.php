<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Category;

class CartController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $orders_id = DB::table('orders')
            ->where('a_id_customer', $user->a_id)
            ->where('transaction_status', 0)
            ->select('o_id')
            ->pluck('o_id')->first();
        $cartItems = DB::table('order_details')->get()
            // ->where('users_id', $user->id)
            ->where('o_id', $orders_id);
        $lastpls = DB::table('orders')
        ->where('a_id_customer', $user->a_id)
        ->where('transaction_status', 0)
        ->value('o_id');
        // $dataImg = DB::table('order_details')   
        //     ->join('component', 'order_details.c_id', '=', 'component.c_id')   
        //     ->select('component.c_img')
        //     ->where('orders.a_id_customer', $user->a_id)
        //     ->where('orders.transaction_status', '1')
        //     ->get();
        $dataOrders = DB::table('orders')      
            ->select('*')
            ->where('orders.a_id_customer', $user->a_id)
            ->where('orders.transaction_status', '0')
            ->get();
        // dd($dataOrders);
        $dataComponent = DB::table('component') 
            ->select('component.c_id', 'component.c_img', 'component.c_price', 'component.c_description')
            ->get();
        // dd($dataComponent);
        $dataOrderDetails = DB::table('order_details')   
            ->join('orders', 'order_details.o_id', '=', 'orders.o_id')   
            ->select('order_details.*')
            ->where('orders.a_id_customer', $user->a_id)
            ->where('orders.transaction_status', '0')
            ->orderBy('order_details.c_id', 'ASC')
            ->get();
        // dd($dataOrderDetails);
        $sums = DB::table('order_details')
            // ->where('users_id', $user->id)
            ->where('o_id', $lastpls);
        // dd($sums);
        return view('carts.index', compact('cartItems', 'user', 'sums', 'orders_id', 'dataComponent', 'dataOrderDetails', 'dataOrders'));
    }

    public function store(Request $request){
        $banyak = $request->quantity;
        $harga = $request->price;
        $res = $harga * $banyak;
        if($banyak < 1){
            DB::table('order_details')
                ->where('o_id', $request->id)
                ->where('c_id', $request->cid)
                ->delete();
        }else{
            $affected = DB::table('order_details')
                ->where('o_id', $request->id)
                ->where('c_id', $request->cid)
                ->update(['od_qty' => $request->quantity]);
        }
        // dd($res);

        return redirect('/cart');
    }
}

