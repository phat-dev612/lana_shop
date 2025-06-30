<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\OrderItem;
use App\Services\CloudinaryService;
use App\Http\Requests\CreateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Display admin dashboard
     */
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display users management page
     */
    public function users(): View
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display products management page
     */
    public function products(): View
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }
    public function showProduct($id): View
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }
    public function addProduct(): View
    {
        $categories = Category::all();
        return view('admin.products.add', compact('categories'));
    }
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'is_preorder' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle image upload to Cloudinary
        if ($request->hasFile('image')) {
            $uploadResult = $this->cloudinaryService->uploadImage(
                $request->file('image'),
                'lana_shop/products'
            );

            if ($uploadResult) {
                $data['image_url'] = $uploadResult['url'];
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to upload image. Please try again.');
            }
        }

        // Set default values for boolean fields
        $data['is_active'] = $request->has('is_active');
        $data['is_preorder'] = $request->has('is_preorder');
        $data['is_featured'] = $request->has('is_featured');
        $data['sold'] = 0;
        Product::create($data);

        return redirect()->route('admin.products')->with('success', 'Product created successfully');
    }
    public function editProduct($id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'is_preorder' => 'boolean',
            'is_featured' => 'boolean',
        ]);
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $product = Product::findOrFail($id);
        if ($request->hasFile('image')) {
            $uploadResult = $this->cloudinaryService->uploadImage(
                $request->file('image'),
                'lana_shop/products'
            );
            if ($uploadResult) {
                $data['image_url'] = $uploadResult['url'];
                $this->cloudinaryService->deleteImage($product->image_url);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to upload image. Please try again.');
            }
        }
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['is_preorder'] = $request->has('is_preorder');
        $product->update($data);
        return redirect()->route('admin.products')->with('success', 'Product updated successfully');
    }
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->cloudinaryService->deleteImage($product->image_url);
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
    }


    /**
     * Display orders management page
     */
    public function orders(): View
    {
        $orders = Order::with('user')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display categories management page
     */
    public function categories(): View
    {
        $categories = Category::with('products')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories')->with('error', 'Cannot delete category that has products');
        }
        
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully');
    }

    /**
     * Display order detail page
     */
    public function showOrder($id): View
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($id);
        
        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'notes' => $request->notes,
        ]);

        // Log status change
        $statusChange = [];
        if ($oldStatus !== $request->status) {
            $statusChange[] = "Order status changed from " . ucfirst($oldStatus) . " to " . ucfirst($request->status);
        }
        if ($oldPaymentStatus !== $request->payment_status) {
            $statusChange[] = "Payment status changed from " . ucfirst($oldPaymentStatus) . " to " . ucfirst($request->payment_status);
        }

        $message = 'Order updated successfully';
        if (!empty($statusChange)) {
            $message .= '. ' . implode(', ', $statusChange);
        }

        return redirect()->route('admin.orders.show', $id)->with('success', $message);
    }

    /**
     * Display create order form
     */
    public function createOrder(): View
    {
        $users = User::where('role', 'customer')->get();
        $products = Product::where('is_active', true)->get();
        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Store new order
     */
    public function storeOrder(CreateOrderRequest $request)
    {
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Calculate totals
        $subtotal = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal += $product->price * $item['quantity'];
        }
        $total = $subtotal + $request->shipping_fee;

        // Create order
        $order = Order::create([
            'user_id' => $request->user_id,
            'order_number' => $orderNumber,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'shipping_name' => $request->shipping_name,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'subtotal' => $subtotal,
            'shipping_fee' => $request->shipping_fee,
            'total_amount' => $total,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order created successfully with order number: ' . $orderNumber);
    }
} 