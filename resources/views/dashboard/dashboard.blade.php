@foreach ($users as $user )
    <li>{{$user->user_name}}</li>
    <li>Orders</li>
    @foreach ($user->orders as $order)
    <ul>
        <li>{{$order->products}}</li>
        {{-- <li>{{$order->products->bUser->id}}</li> --}}
         @foreach ($order->products->bUser as $user)
        {{-- @foreach ($order->products as $product)
        <li>{{$product->bUser}}</li>

        @endforeach --}}
        {{-- <li>{{$order->products->product_tittle }}</li> --}}
        {{-- @foreach ($order->products as $product)
        <li>{{$product->product_tittle}}</li> --}}

        @endforeach
    </ul>

    @endforeach
@endforeach
<h1>hello</h1>
