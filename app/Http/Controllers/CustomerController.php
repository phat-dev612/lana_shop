<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // Trang chủ khách hàng
    public function index()
    {
        // TODO: Hiển thị trang chủ hoặc danh sách sản phẩm nổi bật
        $featuredProducts = Product::where('is_featured', true)->get();
        $categories = Category::all();
        return view('home', compact('featuredProducts', 'categories'));
    }

    // Danh sách sản phẩm
    public function products(Request $request)
    {
        $title = 'Sản phẩm';
        $categories = Category::all();
        $products = Product::query()->where('is_active', true);
        // tìm kiếm
        if ($request->has('search')) {
            $products->where('name', 'like', '%' . $request->search . '%');
        }
        // lọc theo giá
        if ($request->has('min_price')) {
            $products->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $products->where('price', '<=', $request->max_price);
        }
        // sắp xếp
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $products->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $products->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $products->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                default:
                    $products->orderBy('created_at', 'desc');
                    break;
            }
        }
        $products = $products->paginate(12);

        return view('customers.products', compact('products', 'title', 'categories'));
    }

    // Xem sản phẩm theo danh mục
    public function category(Category $category)
    {
        $title = 'Danh mục: ' . $category->name;
        $products = Product::where('category_id', $category->id)->paginate(12);
        $categories = Category::all();

        return view('customers.products', compact('products', 'title', 'category', 'categories'));
    }

    // Xem chi tiết sản phẩm
    public function product($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $title = $product->name;
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('is_active', true)
                                 ->limit(4)
                                 ->get();

        return view('customers.product', compact('product', 'title', 'relatedProducts'));
    }

    // Tìm kiếm sản phẩm
    public function search(Request $request)
    {
        $title = 'Kết quả tìm kiếm: ' . $request->q;
        $query = $request->q;
        
        $products = Product::where('is_active', true)
                          ->where(function($q) use ($query) {
                              $q->where('name', 'like', '%' . $query . '%')
                                ->orWhere('description', 'like', '%' . $query . '%');
                          })
                          ->with('category')
                          ->paginate(12);
        
        $categories = Category::all();
        
        return view('customers.products', compact('products', 'title', 'categories', 'query'));
    }

    // Hiển thị giỏ hàng
    public function cart()
    {
        $title = 'Giỏ hàng';
        $cartItems = Cart::with(['product.category'])->where('user_id', Auth::user()->id)->get();
        
        // Chuyển đổi dữ liệu để phù hợp với view
        $formattedCartItems = $cartItems->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->product->name,
                'image_url' => $item->product->image_url,
                'price' => $item->product->price,
                'category' => $item->product->category->name,
                'quantity' => $item->quantity,
                'product_id' => $item->product_id,
                'is_buy' => $item->is_buy
            ];
        });
        
        // Tính tổng tiền
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });
        
        return view('customers.cart', compact('formattedCartItems', 'total', 'title'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        // nhận dữ liệu từ fetch json
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
        }
        // cập nhật số lượng sản phẩm trong giỏ hàng
        $cart = Cart::where('product_id', $product->id)->where('user_id', Auth::user()->id)->first();
        if (!$cart) {
            $cart = new Cart();
            $cart->product_id = $product->id;
            $cart->user_id = Auth::user()->id;
            $cart->quantity = $request->quantity;
            $cart->is_buy = true; // Mặc định là true khi thêm vào giỏ hàng
            $cart->save();
        }
        else {
            $cart->quantity += $request->quantity;
            $cart->save();
        }
        return response()->json(['success' => 'Sản phẩm đã được thêm vào giỏ hàng']);
    }

    // Cập nhật giỏ hàng
    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('id', $request->cart_id)
                   ->where('user_id', Auth::user()->id)
                   ->first();

        if (!$cart) {
            return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json([
            'success' => 'Cập nhật giỏ hàng thành công',
            'quantity' => $cart->quantity,
            'total' => $cart->quantity * $cart->product->price
        ]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id'
        ]);

        $cart = Cart::where('id', $request->cart_id)
                   ->where('user_id', Auth::user()->id)
                   ->first();

        if (!$cart) {
            return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
        }

        $cart->delete();

        return response()->json(['success' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
    }
    // cập nhật is_buy của sản phẩm trong giỏ hàng
    public function updateIsBuy(Request $request)
    {
        // nhận dữ liệu từ fetch json danh sách ids của cart và is_buy: true/false
        $request->validate([
            'cart_ids' => 'required|array',
            'is_buy' => 'required|boolean'
        ]);
        $cartIds = $request->cart_ids;
        $isBuy = $request->is_buy;
        foreach ($cartIds as $cartId) {
            $cart = Cart::where('id', $cartId)
                        ->where('user_id', Auth::user()->id)
                        ->first();
            if (!$cart) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }
            $cart->is_buy = $isBuy;
            $cart->save();
        }
        return response()->json(['success' => 'Cập nhật is_buy thành công']);
    }
    // Thông tin cá nhân
    public function profile()
    {
        $title = 'Thông tin cá nhân';
        $user = Auth::user();
        return view('customers.profile', compact('user', 'title'));
    }

    // Cập nhật thông tin cá nhân
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
    }

    // Trang thanh toán
    public function checkout()
    {
        $title = 'Thanh toán';
        $user = Auth::user();
        
        // Lấy các sản phẩm trong giỏ hàng có is_buy = true
        $cartItems = Cart::with(['product.category'])
                        ->where('user_id', $user->id)
                        ->where('is_buy', true)
                        ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Không có sản phẩm nào được chọn để thanh toán');
        }
        
        // Chuyển đổi dữ liệu để phù hợp với view
        $formattedCartItems = $cartItems->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->product->name,
                'image_url' => $item->product->image_url,
                'price' => $item->product->price,
                'category' => $item->product->category->name,
                'quantity' => $item->quantity,
                'product_id' => $item->product_id,
                'subtotal' => $item->quantity * $item->product->price
            ];
        });
        
        // Tính tổng tiền
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });
        
        // Phí vận chuyển (có thể thay đổi theo logic nghiệp vụ)
        $shippingFee = 30000; // 30,000 VND
        
        // Tổng cộng
        $total = $subtotal + $shippingFee;
        
        return view('customers.checkout', compact('formattedCartItems', 'subtotal', 'shippingFee', 'total', 'title', 'user'));
    }

    // Xử lý đặt hàng
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash,cod,bank_transfer',
            'notes' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        
        // Lấy các sản phẩm trong giỏ hàng có is_buy = true
        $cartItems = Cart::with(['product'])
                        ->where('user_id', $user->id)
                        ->where('is_buy', true)
                        ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Không có sản phẩm nào được chọn để thanh toán');
        }
        
        // Tính tổng tiền
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });
        
        $shippingFee = 30000;
        $total = $subtotal + $shippingFee;
        
        // Tạo đơn hàng
        $order = new \App\Models\Order();
        $order->user_id = $user->id;
        $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        $order->status = 'pending';
        $order->subtotal = $subtotal;
        $order->shipping_fee = $shippingFee;
        $order->total_amount = $total;
        $order->name = $request->shipping_name;
        $order->phone = $request->shipping_phone;
        $order->address = $request->shipping_address;
        $order->payment_method = $request->payment_method;
        $order->note = $request->notes;
        $order->save();
        
        // Tạo chi tiết đơn hàng
        foreach ($cartItems as $cartItem) {
            $orderItem = new \App\Models\OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $cartItem->product_id;
            $orderItem->product_name = $cartItem->product->name;
            $orderItem->price = $cartItem->product->price;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->total_price = $cartItem->quantity * $cartItem->product->price;
            $orderItem->save();
        }
        
        // Xóa các sản phẩm đã mua khỏi giỏ hàng
        $cartItems->each(function($item) {
            $item->delete();
        });
        
        return redirect()->route('customer.orders')->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number);
    }

    // Danh sách đơn hàng
    public function orders()
    {
        $title = 'Đơn hàng của tôi';
        $orders = \App\Models\Order::where('user_id', Auth::user()->id)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(10);
        
        return view('customers.orders', compact('orders', 'title'));
    }

    // Chi tiết đơn hàng
    public function orderDetail($id)
    {
        $order = \App\Models\Order::with(['orderItems.product'])
                                 ->where('id', $id)
                                 ->where('user_id', Auth::user()->id)
                                 ->firstOrFail();
        
        $title = 'Chi tiết đơn hàng: ' . $order->order_number;
        
        return view('customers.order-detail', compact('order', 'title'));
    }
} 