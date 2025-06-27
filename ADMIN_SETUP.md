# Admin Panel Setup - Lana Shop

## Tổng quan

Hệ thống admin panel đã được tạo với các tính năng cơ bản để quản lý e-commerce website.

## Cấu trúc đã tạo

### 1. Middleware

-   `AdminMiddleware`: Kiểm tra quyền admin trước khi cho phép truy cập

### 2. Controllers

-   `AdminController`: Xử lý các chức năng quản lý admin

### 3. Models

-   `User`: Đã cập nhật với relationship orders và method isAdmin()
-   `Category`: Model quản lý danh mục sản phẩm
-   `Product`: Model quản lý sản phẩm
-   `Order`: Model quản lý đơn hàng

### 4. Views

-   `layouts/admin.blade.php`: Layout chung cho admin panel
-   `admin/dashboard.blade.php`: Trang dashboard chính
-   `admin/users/index.blade.php`: Quản lý người dùng
-   `admin/products/index.blade.php`: Quản lý sản phẩm
-   `admin/orders/index.blade.php`: Quản lý đơn hàng
-   `admin/categories/index.blade.php`: Quản lý danh mục

### 5. Routes

-   `/admin`: Dashboard chính
-   `/admin/users`: Quản lý người dùng
-   `/admin/products`: Quản lý sản phẩm
-   `/admin/orders`: Quản lý đơn hàng
-   `/admin/categories`: Quản lý danh mục

## Cài đặt và sử dụng

### 1. Chạy migration và seeder

```bash
php artisan migrate:fresh --seed
```

### 2. Thông tin đăng nhập admin

-   **Email**: admin@lanashop.com
-   **Password**: password

### 3. Truy cập admin panel

Sau khi đăng nhập với tài khoản admin, truy cập:

```
http://localhost:8000/admin
```

## Tính năng đã implement

### Dashboard

-   Thống kê tổng quan (users, products, orders, categories)
-   Danh sách đơn hàng gần đây
-   Danh sách người dùng gần đây
-   Quick actions để truy cập nhanh các trang quản lý

### Quản lý Users

-   Hiển thị danh sách tất cả người dùng
-   Phân biệt role admin/customer
-   Pagination
-   Actions: Edit, View, Delete (không thể xóa chính mình)

### Quản lý Products

-   Hiển thị danh sách sản phẩm với hình ảnh
-   Thông tin category, giá, trạng thái
-   Pagination
-   Actions: Edit, View, Delete

### Quản lý Orders

-   Hiển thị danh sách đơn hàng
-   Thông tin khách hàng, tổng tiền, trạng thái
-   Phân loại theo status và payment status
-   Actions: Edit, View, Mark as Completed, Cancel

### Quản lý Categories

-   Hiển thị danh sách danh mục
-   Thống kê số sản phẩm trong mỗi danh mục
-   Không cho phép xóa danh mục có sản phẩm
-   Actions: Edit, View Products, Delete

## Bảo mật

-   Middleware `admin` kiểm tra quyền trước khi cho phép truy cập
-   Chỉ user có role = 'admin' mới có thể truy cập admin panel
-   CSRF protection được bật
-   Validation cho tất cả input

## Giao diện

-   Sử dụng Bootstrap 5
-   Responsive design
-   Font Awesome icons
-   Modern admin dashboard layout
-   Sidebar navigation
-   Color-coded status badges

## Các bước tiếp theo

1. **Thêm chức năng CRUD đầy đủ**:

    - Create/Edit/Delete cho tất cả entities
    - Form validation
    - File upload cho product images

2. **Thêm tính năng nâng cao**:

    - Search và filter
    - Export data (Excel, PDF)
    - Bulk actions
    - Activity logs

3. **Cải thiện UX**:

    - AJAX loading
    - Real-time notifications
    - Advanced charts và analytics

4. **Bảo mật nâng cao**:
    - Role-based permissions
    - Activity monitoring
    - Two-factor authentication

## Troubleshooting

### Lỗi 403 Unauthorized

-   Đảm bảo user đã đăng nhập
-   Kiểm tra role của user có phải là 'admin'
-   Kiểm tra middleware đã được đăng ký đúng

### Lỗi database

-   Chạy lại migration: `php artisan migrate:fresh --seed`
-   Kiểm tra kết nối database trong `.env`

### Lỗi view

-   Kiểm tra file view có tồn tại trong thư mục `resources/views/admin/`
-   Kiểm tra syntax Blade template
