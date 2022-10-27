@extends('layouts.master');

@section('content')
<section class="cart_area padding_top">
  <div class="container">
    <div class="cart_inner">
      @php
            $count = 0
      @endphp
      <div class="table-responsive">
      @forelse ($dataOrders as $key => $item) 
      <h2> Cart </h2>
        <table class="table">
          <thead>
            <tr>
              <th class="col-6" scope="col">Product</th>
              <th class="col-2" scope="col">Price</th>
              <th class="col-2" scope="col">Quantity</th>
              <th class="col-2" scope="col">Total</th>
            </tr>
          </thead>
          <tbody>
              @foreach ($dataOrderDetails as $key => $itemDua)   
                  @if($itemDua->o_id == $item->o_id) 
                    @foreach ($dataComponent as $key => $itemTiga)
                      @if($itemTiga->c_id == $itemDua->c_id)
                      <tr>
                          <td>
                          <div class="media">
                              <div class="d-flex">
                              @if (Str::contains($itemTiga->c_img, 'https:/'))
                                  <img src="{{$itemTiga->c_img}}" alt="image" height="200px">
                              @else
                                  <img src="{{ asset('images/product/'.$itemTiga->c_img)}}" alt="image" height="200px">
                              @endif
                              </div>
                              <div class="media-body">
                              <p>{{ $itemTiga->c_description }}</p>
                              </div>
                          </div>
                          </td>
                          <td>
                          <h5>{{ $itemTiga->c_price }}</h5>
                          </td>
                          <td>
                          <div class="product_count">
                            <form action="cart" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ Auth::user()->id }}" name="users_id">
                                <input type="hidden" value="{{ $itemDua->o_id }}" name="id">
                                <input type="hidden" value="{{ $itemDua->c_id }}" name="cid">
                                {{-- <input type="hidden" value="{{ $item->name }}" name="name"> --}}
                                <input type="hidden" value="{{ $itemTiga->c_price }}" name="price">
                                <input type="hidden" value="{{ $itemDua->od_qty - 1 }}" name="quantity">
                                {{-- <input type="hidden" value="dec" name="idcnt"> --}}
                                <button class="input-number-decrement"><i class="ti-angle-down"></i></button>
                            </form>
                            {{-- <span class="input-number-decrement"> <i class="ti-angle-down"></i></span> --}}
                            <input class="input-number" type="text" value="{{ $itemDua->od_qty }}" name="counter" min="0" max="10">
                            <form action="cart" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ Auth::user()->id }}" name="users_id">
                                <input type="hidden" value="{{ $itemDua->o_id }}" name="id">
                                <input type="hidden" value="{{ $itemDua->c_id }}" name="cid">
                                {{-- <input type="hidden" value="{{ $item->name }}" name="name"> --}}
                                <input type="hidden" value="{{ $itemTiga->c_price }}" name="price">
                                <input type="hidden" value="{{ $itemDua->od_qty + 1 }}" name="quantity">
                                {{-- <input type="hidden" value="add" name="idcnt"> --}}
                                <button class="input-number-increment"><i class="ti-angle-up"></i></button>
                            </form>
                            {{-- <span class="input-number-increment"> <i class="ti-angle-up"></i></span> --}}
                        </div>
                      </td>
                      <td>
                          <h5>{{ $itemTiga->c_price * $itemDua->od_qty }}</h5>
                          @php
                              $count = $count + $itemTiga->c_price * $itemDua->od_qty
                          @endphp
                      </td>
                  </tr>
                      @endif
                    @endforeach
                  @endif
              @endforeach
            <tr>
              <td></td>
              <td></td>
              <td>
                  <h5>Subtotal</h5>
              </td>
              <td>
                  <h5>Rp {{ $count }}</h5>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="checkout_btn_inner float-right">
          <a class="btn_1" href="/shop">Continue Shopping</a>
          <a class="btn_1 checkout_btn_1" href="/order/{{$orders_id}}/edit">Proceed to checkout</a>
        </div>

      @empty
      <h4>There is no item</h4>
      @endforelse
      </div>
    </div>
  </div>
  </section>
@endsection