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
        @php
            $count = $count+1;
        @endphp
        <h2>Order {{ $count }}  </h2>
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
                                {{ $itemDua->od_qty }}
                            </div>
                        </td>
                        <td>
                            <h5>{{ $itemTiga->c_price * $itemDua->od_qty }}</h5>
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
                    <h5>Rp {{ $item->o_total_price }}</h5>
                </td>
              </tr>
            </tbody>
          </table>

        @empty
        <h4>There is no order history</h4>
        @endforelse
        </div>
      </div>
    </div>
  </section>
@endsection