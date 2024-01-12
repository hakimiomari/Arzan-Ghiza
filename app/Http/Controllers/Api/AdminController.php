<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bussiness;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('address', 'user')->paginate(16);
            $i = 0;
            foreach ($products as $item) {
                if ($item->image) {
                    $products[$i]['imgUrl'] = asset('images/products/' . $item->image);
                    $products[$i]['description'] = Str::limit($item->description, 80);
                    $i++;
                }
            }

            return response()->json([
                'products' => $products,
                'message' => 'all products'
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    // users
    public function users()
    {
        try {
            $users = User::where('id', '<>', 1)->paginate(20);
            $i = 0;
            foreach ($users as $user) {
                unset($users[$i]['photo']);
                unset($users[$i]['email_verified_at']);
                $i++;
            }
            return response()->json([
                'users' => $users,
                'message' => 'all users'
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function sales()
    {
        $order = Order::with('payment', 'products')->paginate(16);
        $i = 0;
        foreach ($order as $item) {
            $user = User::where('id', $order[$i]['users_id'])->first();
            $bussiness_id = $order[$i]['products']->bussinesses_id;
            $bussiness_user = Bussiness::where('id', $bussiness_id)->first();
            $order[$i]['bussiness_name'] = $bussiness_user->bussiness_name;
            $order[$i]['product_name'] = $order[$i]['products']->tittle;
            $order[$i]['total'] = $order[$i]['payment']->payment_amount;
            $order[$i]['customer'] = $user->name;
            unset($order[$i]['payment']);
            unset($order[$i]['products']);
            unset($order[$i]['users_id']);
            $i++;
        }
        return response()->json(['message' => "success sales", 'order' => $order]);
    }

    // all data for chart
    public function chart()
    {
        $orders = Order::all();
        $products = Product::all();
        return response()->json(['orders' => $orders, 'products' => $products], 200);
    }
}
