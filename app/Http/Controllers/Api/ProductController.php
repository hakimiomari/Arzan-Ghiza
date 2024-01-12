<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateSingleProduct;
use App\Models\Bussiness;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Stripe;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Illuminate\Support\Facades\Session;
use App\Mail\PurchesMail;
use App\Mail\SaleMail;



class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->all();
            $bussiness_id = $data['bussiness_id'];
            $title = $data['title'];
            $description = $data['description'];
            $price = $data['price'];
            $discount_price = $data['discount_price'];
            $quantity = $data['quantity'];
            $expires_date = $data['expires_date'];
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $newName = time() . '_' . $imageName;
            $image->move(public_path('images/products'), $newName);
            $produt = Product::create([
                'bussinesses_id' => $bussiness_id,
                'tittle' => $title,
                'description' => $description,
                'price' => $price,
                'discount_price' => $discount_price,
                'image' => $newName,
                'quantity' => $quantity,
                'expires_date' => $expires_date,
            ]);
            return response()->json(([
                'produt' => $produt,
                'message' => "Image saved successfully",
                'imageUrl' => asset('images/products/' . $newName)
            ]));
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // get all products
    public function products()
    {
        try {
            $currentDate = Carbon::now()->setTimezone('Asia/Kabul');
            $products = Product::with('address', 'user')->where('expires_date', '>', $currentDate)->where('quantity', '>', 0)->orderBy('id', 'desc')->paginate(15);
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
    // single product
    public function userProduct($id)
    {
        try {
            $products = Product::with('address', 'user')->where('bussinesses_id', $id)->get();
            $i = 0;
            foreach ($products as $item) {
                if ($item->image) {
                    $products[$i]['imgUrl'] = asset('images/products/' . $item->image);
                    $products[$i]['description'] = Str::limit($item->description, 80);
                    $i++;
                }
            }
            $products;
            return response()->json([
                'products' => $products,
                'message' => 'all products'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // get single product
    public function getSingleProduct($id)
    {
        $product = Product::findOrFail($id);
        $product['image'] = asset('images/products/' . $product->image);
        return response()->json([
            'product' => $product,
            'message' => "single product"
        ]);
    }

    // update a single product
    public function updateSingleProduct(UpdateSingleProduct $request)
    {
        try {
            $allData = $request->all();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $newName = time() . '_' . $imageName;
                $product = Product::findOrFail($request->input('id'));
                if ($product) {
                    $oldImage = public_path('images/products/' . $product->image);
                    if ($product->image) {
                        unlink($oldImage);
                    }
                    $image->move(public_path('images/products'), $newName);
                    $product->update([
                        'tittle' => $request->tittle,
                        'price' => $request->price,
                        'discount_price' => $request->discount_price,
                        'quantity' => $request->quantity,
                        'description' => $request->description,
                        'expires_date' => $request->expires_date,
                        'image' => $newName
                    ]);
                }
                return response()->json(['message' => 'Post Successfully Apdated'], 200);
            } else {

                $product = Product::findOrFail($request->input('id'));
                $product->update([
                    'tittle' => $request->tittle,
                    'price' => $request->price,
                    'discount_price' => $request->discount_price,
                    'quantity' => $request->quantity,
                    'description' => $request->description,
                    'expires_date' => $request->expires_date
                ]);
                return response()->json(['message' => 'Post Successfully Apdated'], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // delete Product

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            $productImage = public_path('images/products/' . $product->image);
            if ($product->image) {
                unlink($productImage);
            }
            $product->delete();
            return response()->json([
                "message" => 'successully deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // sigle product get by owns id

    public function singleProductDetails($id)
    {
        try {
            $currentDate = Carbon::now()->setTimezone('Asia/Kabul');
            Cache::forget('product_data');
            $product = Product::with('address', 'user',)->where('id', $id)->where('expires_date', '>', $currentDate)->where('quantity', '>', 0)->get();
            $orders = Order::where('products_id', $product[0]->id)->get();
            $product[0]['image'] = asset('/images/products/' . $product[0]->image);
            return response()->json([
                'product' => $product,
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // get session

    public function getSession(Request $request)
    {
        $value = Cache::get('user');
        $product = Product::findOrFail($request->id);
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $checkout = $stripe->checkout->sessions->create([
            'success_url' => 'http://localhost:3000/#/success',
            'cancel_url' => 'http://localhost:3000/#/cancel',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'USD',
                        'unit_amount' => (int)$product->discount_price * 100,
                        'product_data' => [
                            'name' => $product->tittle,
                        ]
                    ],
                    'quantity' => $request->quantity,
                ],
            ],
            'mode' => 'payment',
        ]);
        $data = [
            'bussiness_id' => $product->bussinesses_id,
            'user_id' => $value['user_id'],
            "product_id" => $product->id,
            'title' => $product->tittle,
            'price' => $product->discount_price * $request->quantity,
            'unitPrice' => $product->discount_price,
            'quantity' => $request->quantity
        ];
        Cache::add('product_data', $data, 60 * 60);
        $bUser = Bussiness::with('address')->where('id', $product->bussinesses_id)->first();
        $user = User::where('email', $value['email'])->first();
        $current = Carbon::now();
        $currentDateTime = $current->toDateTimeString();
        $emailData = [
            'user' => $user->name,
            'user_email' => $user->email,
            'user_phone' => $user->phone,
            'bcity' => $bUser->address->city,
            'bdistrict' => $bUser->address->district,
            'bvalige' => $bUser->address->valige,
            'city' => $user->city,
            'district' => $user->district,
            'valige' => $user->valige,
            'delivery' => $bUser->delivery,
            'phone' => $bUser->phone,
            'bUser_name' => $bUser->bussiness_name,
            'bussiness_user' => $bUser->email,
            'customer_name' => $value['user_name'],
            'order_date' => $currentDateTime,
            'total_amount' => $product->discount_price * $request->quantity,
            'product_name' => $product->tittle,
            'product_quantity' => $request->quantity,
            'price' => $product->discount_price,
        ];
        Cache::add('purchase_data', $emailData, 60 * 60 * 12);
        return response()->json(['checkout' => $checkout]);
    }
    public function successCheckout()
    {
        $data = Cache::get('product_data');
        $order = Order::create([
            'bussiness_id' => $data['bussiness_id'],
            'users_id' => $data['user_id'],
            'products_id' => $data['product_id'],
            'order_quantity' => $data['quantity'],
            'price' => $data['unitPrice']
        ]);
        $payment = Payment::create([
            'orders_id' => $order->id,
            "payment_amount" => $data['price'],
        ]);
        $product = Product::where('id', $data['product_id'])->first();
        $product->quantity = $product->quantity - $data['quantity'];
        $product->save();

        $data = Cache::get('purchase_data');
        $value = Cache::get('user');
        Mail::to($value['email'])->send(new PurchesMail());
        Mail::to($data['bussiness_user'])->send(new SaleMail());

        Cache::forget('product_data');
        Cache::forget('purchase_data');
        return response()->json(["message" => "success"], 200);
    }

    // check quantity
    public function checkQuantity(Request $request)
    {
        try {
            $currentDate = Carbon::now()->setTimezone('Asia/Kabul');
            $product = Product::where('id', $request->id)->first();
            $expires_date = Product::where('id', $request->id)->where('expires_date', '>', $currentDate)->first();
            if ($expires_date) {
            } else {
                return response()->json(['expires_date' => 'The order is no longer exist'], 422);
            }
            // check quantity
            if ($product->quantity >= $request->quantity) {
            } else if ($product->quantity == 0) {
                return response()->json(['quantity' => 'Sorry, The order qunatity is finished. please choose another product'], 422);
            } else {
                return response()->json(['quantity' => 'The order qunatity is greather than that we have in the stock'], 422);
            }
            return response()->json(["message" => "success"], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    public function BSale($id)
    {
        $order = Order::with('payment', 'products')->where('bussiness_id', $id)->paginate(16);
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

    public function chart($id)
    {
        $orders = Order::where('bussiness_id', $id)->get();
        $products = Product::where('bussinesses_id', $id)->get();
        return response()->json(['orders' => $orders, 'products' => $products], 200);
    }

    public function search(Request $request)
    {
        $searchTerm = 'Et vitae quia totam';

        $results = DB::table('products')
            ->join('addresses', 'products.bussinesses_id', '=', 'addresses.bussinesses_id')
            ->where('products.tittle', 'like', '%' . $request->search . '%')
            ->orWhere('addresses.city', 'like', '%' . $request->search . '%')
            ->paginate(10);
        $i = 0;
        if (count($results) < 1) {
            return response()->json(['message' => 'No Records Found'], 422);
        }
        foreach ($results as $result) {
            $result->image = asset('images/products/' . $result->image);
            $data[$i] = $result;
            $i++;
        }

        return response()->json(['message' => 'success', 'data' => $data]);
    }
}
