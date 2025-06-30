@extends('layouts.admin')

@section('title', 'Order Details - Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order Details</h1>
        <div>
            <a href="{{ route('admin.orders') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Orders
            </a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-print fa-sm text-white-50"></i> Print Invoice
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                    <div>
                        <span class="badge bg-{{ 
                            $order->status === 'completed' ? 'success' : 
                            ($order->status === 'pending' ? 'warning' : 
                            ($order->status === 'cancelled' ? 'danger' : 
                            ($order->status === 'processing' ? 'info' : 
                            ($order->status === 'shipped' ? 'primary' : 'secondary')))) 
                        }} fs-6">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Order Details</h6>
                            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                            <p><strong>Payment Status:</strong> 
                                <span class="badge bg-{{ 
                                    $order->payment_status === 'paid' ? 'success' : 
                                    ($order->payment_status === 'pending' ? 'warning' : 'danger') 
                                }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Customer Information</h6>
                            <p><strong>Name:</strong> {{ $order->name }}</p>
                            <p><strong>Phone:</strong> {{ $order->phone }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $order->address }}</p>
                        </div>
                    </div>

                    @if($order->shipping_name || $order->shipping_phone || $order->shipping_address)
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="font-weight-bold">Shipping Information</h6>
                            <p><strong>Name:</strong> {{ $order->shipping_name ?? 'Same as customer' }}</p>
                            <p><strong>Phone:</strong> {{ $order->shipping_phone ?? 'Same as customer' }}</p>
                            <p><strong>Address:</strong> {{ $order->shipping_address ?? 'Same as customer' }}</p>
                        </div>
                    </div>
                    @endif

                    @if($order->note)
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="font-weight-bold">Customer Note</h6>
                            <p class="text-muted">{{ $order->note }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr >
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" 
                                                     class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.products.show', $item->product_id) }}"><strong>{{ $item->product_name }}</strong></a>
                                                
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->price) }} VND</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ number_format($item->subtotal) }} VND</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Status Update -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>{{ number_format($order->subtotal) }} VND</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping Fee:</span>
                        <span>{{ number_format($order->shipping_fee) }} VND</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total:</strong>
                        <strong class="text-primary fs-5">{{ number_format($order->total_amount) }} VND</strong>
                    </div>
                </div>
            </div>

            <!-- Status Update -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Order Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any internal notes about this order...">{{ $order->notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Update Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-envelope"></i> Send Email to Customer
                        </a>
                        <a href="#" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit Order
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Delete Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        // Add delete functionality here
        alert('Delete functionality will be implemented here');
    }
}
</script>
@endsection
