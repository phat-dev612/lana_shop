@extends('layouts.customer')

@section('title', $title)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">{{ $title }}</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">Chưa có đơn hàng nào</h3>
                    <p class="text-muted">Bạn chưa có đơn hàng nào trong hệ thống.</p>
                    <a href="{{ route('customer.products') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                    </a>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Phương thức thanh toán</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ number_format($order->total_amount) }} đ</td>
                                        <td>
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
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.order.detail', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Phân trang -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 