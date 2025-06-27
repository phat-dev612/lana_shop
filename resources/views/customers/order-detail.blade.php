@extends('layouts.customer')

@section('title', $title)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>{{ $title }}</h1>
                <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
            
            <div class="row">
                <!-- Thông tin đơn hàng -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Sản phẩm đã đặt
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($order->orderItems as $item)
                            <div class="row align-items-center mb-3 pb-3 border-bottom">
                                <div class="col-md-2">
                                    @if($item->product && $item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" 
                                             class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                    @if($item->product && $item->product->category)
                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                    @endif
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="text-muted">Số lượng: {{ $item->quantity }}</span>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong>{{ number_format($item->total_price) }} đ</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Thông tin chi tiết -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Thông tin đơn hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Mã đơn hàng:</strong>
                                <span class="text-primary">{{ $order->order_number }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Ngày đặt:</strong>
                                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Trạng thái:</strong>
                                <div class="mt-1">
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Chờ xử lý</span>
                                            @break
                                        @case('processing')
                                            <span class="badge bg-info">Đang xử lý</span>
                                            @break
                                        @case('shipped')
                                            <span class="badge bg-primary">Đã giao hàng</span>
                                            @break
                                        @case('delivered')
                                            <span class="badge bg-success">Đã nhận hàng</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endswitch
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong>Phương thức thanh toán:</strong>
                                <div class="mt-1">
                                    @switch($order->payment_method)
                                        @case('cod')
                                            <span class="badge bg-secondary">Thanh toán khi nhận hàng</span>
                                            @break
                                        @case('bank_transfer')
                                            <span class="badge bg-info">Chuyển khoản</span>
                                            @break
                                        @case('cash')
                                            <span class="badge bg-success">Tiền mặt</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->payment_method }}</span>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-truck me-2"></i>
                                Thông tin giao hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Người nhận:</strong>
                                <div>{{ $order->name }}</div>
                            </div>
                            <div class="mb-2">
                                <strong>Số điện thoại:</strong>
                                <div>{{ $order->phone }}</div>
                            </div>
                            <div class="mb-2">
                                <strong>Địa chỉ:</strong>
                                <div>{{ $order->address }}</div>
                            </div>
                            @if($order->note)
                            <div class="mb-2">
                                <strong>Ghi chú:</strong>
                                <div class="text-muted">{{ $order->note }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Tổng tiền
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($order->subtotal ?? 0) }} đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>{{ number_format($order->shipping_fee ?? 0) }} đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-0">
                                <strong>Tổng cộng:</strong>
                                <strong class="text-primary fs-5">{{ number_format($order->total_amount) }} đ</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 