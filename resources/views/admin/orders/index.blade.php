@extends('layouts.admin')

@section('title', 'Manage Orders - Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Orders</h1>
        <div>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm me-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Orders
            </a>
            <a href="{{ route('admin.orders.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Create Order
            </a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Orders</h6>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>STT</th>
                                <th>Order Code</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Payment Method</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $key => $order)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $order->order_number }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $order->name }}</strong><br>
                                        <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                                    </div>
                                </td>   
                                <td>{{ number_format($order->total_amount) }} VND</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ 
                                        $order->status === 'completed' ? 'success' : 
                                        ($order->status === 'pending' ? 'warning' : 
                                        ($order->status === 'cancelled' ? 'danger' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ 
                                        $order->payment_status === 'paid' ? 'success' : 
                                        ($order->payment_status === 'pending' ? 'warning' : 'danger') 
                                    }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ ucfirst($order->payment_method) }}</span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">No orders found.</p>
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Order
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 