@extends('store.layout')

@section('content')
    <div class="dashboard-container">
        <div class="container">
            <div class="row">
                @include('store.content.dashboard.sidebar')

                <!-- Main Content -->
                <div class="col-lg-9 col-md-8">
                    <div class="main-content" data-aos="fade-up">
                        <!-- Dashboard Tab -->
                        <div id="orders" class="tab-content">
                            <h2 class="section-title">
                                My Orders
                            </h2>

                            <div class="recent-orders">
                                @if ($orders->isEmpty())
                                    <h5 class="text-center">Belum Ada Pesanan</h5>
                                @else
                                    @foreach ($orders as $order)
                                        @php
                                            $items = json_decode($order->items, false);
                                        @endphp
                                        <div class="order-item">
                                            <div class="order-info">
                                                <h6><a href="{{ url('order-details/'.$order->no_invoice) }}">Order #{{ $order->no_invoice }}</a></h6>
                                                <p>Tgl Pesanan : {{ \App\Helpers\WabiHelper::formatDate($order->created_at) }}</p>
                                                <p>Items: {{ count($items) }} | Total: Rp <b>{{ number_format($order->total, 0, ',', '.') }}</b></p>
                                            </div>
                                            @if ($order->status >= 0 && $order->status <= 1)
                                                <span class="order-status-badge status-pending">Pending</span>
                                            @elseif ($order->status > 1 && $order->status < 4)
                                                <span class="order-status-badge status-processing">Processing</span>
                                            @elseif ($order->status == 4)
                                                <div class="order-status-badge status-shipped">Dikirim</div>
                                            @elseif ($order->status == 5)
                                                <div class="order-status-badge status-completed">Diambil</div>
                                            @elseif ($order->status == 404)
                                                <div class="order-status-badge status-canceled">Canceled</div>
                                            @else
                                                <span class="order-status-badge status-unknown">Unknown</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection