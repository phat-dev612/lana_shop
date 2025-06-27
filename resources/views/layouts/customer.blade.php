<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lana Shop')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Vite assets (nếu có) -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        .navbar-brand {
            font-size: 1.8rem;
            color: #2c3e50 !important;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .navbar-brand:hover {
            color: #3498db !important;
        }
        .nav-link {
            font-weight: 500;
            color: #34495e !important;
            transition: color 0.3s ease;
            position: relative;
        }
        .nav-link:hover {
            color: #3498db !important;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #3498db;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .search-form {
            position: relative;
        }
        .search-form .form-control {
            border-radius: 25px;
            padding-left: 45px;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }
        .search-form .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .search-form .btn {
            border-radius: 25px;
            padding: 8px 20px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            transition: all 0.3s ease;
        }
        .search-form .btn:hover {
            background: linear-gradient(135deg, #2980b9, #1f5f8b);
            transform: translateY(-2px);
        }
        .cart-icon {
            position: relative;
            font-size: 1.2rem;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            min-width: 200px;
        }
        .dropdown-item {
            padding: 10px 20px;
            transition: background-color 0.3s ease;
            border: none;
            background: transparent;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .dropdown-toggle::after {
            margin-left: 8px;
        }
        .top-bar {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 8px 0;
            font-size: 0.9rem;
        }
        .top-bar a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .top-bar a:hover {
            color: white;
        }
    </style>
</head>
<body class="bg-light min-vh-100 d-flex flex-column">

    <!-- Main Header -->
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top mb-4 py-2">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="/">
                LANA Shop
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                
                
                <!-- Search Box -->
                <div class="d-flex justify-content-center flex-grow-1 mx-4">
                    <form action="{{ route('customer.products') }}" method="GET" class="search-form d-flex gap-2" style="max-width: 400px;">
                        <div class="position-relative flex-grow-1">
                            <i class="fas fa-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..." 
                                    value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- User Actions -->
                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.products') }}">
                            <i class="fas fa-store me-1"></i>Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-info-circle me-1"></i>Hướng dẫn đặt hàng
                        </a>
                    </li>
                    <!-- Cart -->
                    <li class="nav-item me-3">
                        <a class="nav-link cart-icon" href="{{ route('customer.cart') }}" title="Giỏ hàng">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge">{{ $cartCount }}</span>
                        </a>
                    </li>
                    
                    <!-- User Menu -->
                    @if(Auth::check())
                        <li class="nav-item dropdown">
                            <button class=" dropdown-toggle d-flex align-items-center border-0 bg-transparent" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar me-2">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('customer.profile') }}"><i class="fas fa-user me-2"></i>Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.orders') }}"><i class="fas fa-shopping-bag me-2"></i>Đơn hàng</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </header>
    
    <main class="flex-fill">
        @yield('content')
    </main>
    
    <footer class="bg-white text-center py-3 mt-auto border-top">
        <small>&copy; {{ date('Y') }} Lana Shop. All rights reserved.</small>
    </footer>

</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript for dropdown functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    // Ensure dropdowns work on mobile
    document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            var dropdown = bootstrap.Dropdown.getInstance(this);
            if (dropdown) {
                dropdown.toggle();
            }
        });
    });
});
</script>

@stack('scripts')
</html> 