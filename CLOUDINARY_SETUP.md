# Cloudinary Setup Guide

## Cấu hình Cloudinary cho Lana Shop

### 1. Tạo tài khoản Cloudinary

1. Truy cập [Cloudinary](https://cloudinary.com/) và đăng ký tài khoản miễn phí
2. Sau khi đăng ký, bạn sẽ nhận được thông tin cấu hình trong Dashboard

### 2. Cấu hình Environment Variables

Thêm các biến môi trường sau vào file `.env`:

```env
# Cloudinary Configuration
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
CLOUDINARY_SECURE=true
CLOUDINARY_UPLOAD_PRESET=ml_default
CLOUDINARY_FOLDER=lana_shop
```

### 3. Lấy thông tin cấu hình từ Cloudinary Dashboard

1. Đăng nhập vào Cloudinary Dashboard
2. Vào phần **Settings** > **Access Keys**
3. Copy các thông tin sau:
    - **Cloud Name**: Tên cloud của bạn
    - **API Key**: Khóa API
    - **API Secret**: Bí mật API

### 4. Cấu hình Upload Preset (Tùy chọn)

1. Vào **Settings** > **Upload**
2. Tạo một upload preset mới hoặc sử dụng preset mặc định
3. Cấu hình preset để cho phép upload không cần xác thực (nếu cần)

### 5. Kiểm tra cấu hình

Sau khi cấu hình xong, bạn có thể test upload ảnh bằng cách:

1. Chạy server Laravel: `php artisan serve`
2. Truy cập trang thêm sản phẩm trong admin
3. Upload một ảnh và kiểm tra xem ảnh có được upload lên Cloudinary không

### 6. Tính năng đã được tích hợp

-   ✅ Upload ảnh sản phẩm lên Cloudinary
-   ✅ Tự động resize ảnh (800x800px)
-   ✅ Tối ưu hóa chất lượng ảnh
-   ✅ Lưu URL ảnh vào database
-   ✅ Validation cho file ảnh
-   ✅ Hỗ trợ các định dạng: JPEG, PNG, JPG, GIF, WebP
-   ✅ Giới hạn kích thước file: 2MB

### 7. Cấu trúc thư mục trên Cloudinary

Ảnh sản phẩm sẽ được lưu trong thư mục: `lana_shop/products/`

### 8. Troubleshooting

Nếu gặp lỗi upload:

1. Kiểm tra thông tin cấu hình trong `.env`
2. Đảm bảo Cloudinary account có đủ quota
3. Kiểm tra log Laravel: `storage/logs/laravel.log`
4. Đảm bảo thư mục `storage` có quyền ghi

### 9. Bảo mật

-   Không commit file `.env` lên git
-   Sử dụng biến môi trường cho production
-   Cấu hình CORS trong Cloudinary nếu cần
-   Sử dụng signed uploads cho bảo mật cao hơn
