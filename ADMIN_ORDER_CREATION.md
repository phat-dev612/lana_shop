# Hướng dẫn sử dụng tính năng tạo đơn hàng từ Admin Panel

## Tổng quan

Tính năng này cho phép admin tạo đơn hàng mới trực tiếp từ admin panel mà không cần khách hàng đặt hàng qua website.

## Cách sử dụng

### 1. Truy cập trang tạo đơn hàng

-   Đăng nhập vào admin panel
-   Vào menu "Quản lý đơn hàng" (Orders)
-   Click nút "Create Order" hoặc "Create First Order"

### 2. Điền thông tin đơn hàng

#### Thông tin khách hàng

-   **Chọn khách hàng**: Chọn từ danh sách khách hàng đã đăng ký
-   **Tên người đặt hàng**: Tên người thực hiện đặt hàng
-   **Số điện thoại**: Số điện thoại liên hệ
-   **Địa chỉ**: Địa chỉ của người đặt hàng

#### Thông tin giao hàng

-   **Tên người nhận**: Tên người sẽ nhận hàng (có thể khác người đặt)
-   **Số điện thoại người nhận**: Số điện thoại người nhận hàng
-   **Địa chỉ giao hàng**: Địa chỉ giao hàng
-   **Nút "Copy thông tin khách hàng"**: Tự động copy thông tin từ phần khách hàng

#### Thông tin thanh toán

-   **Phương thức thanh toán**: Chọn từ 3 phương thức:
    -   Tiền mặt
    -   Chuyển khoản
    -   Thanh toán online
-   **Phí vận chuyển**: Nhập phí vận chuyển (mặc định là 0)

#### Danh sách sản phẩm

-   **Thêm sản phẩm**: Click nút "Thêm sản phẩm" để thêm sản phẩm mới
-   **Chọn sản phẩm**: Chọn từ danh sách sản phẩm có sẵn
-   **Số lượng**: Nhập số lượng cần đặt
-   **Đơn giá**: Hiển thị tự động khi chọn sản phẩm
-   **Xóa sản phẩm**: Click nút thùng rác để xóa sản phẩm

### 3. Tính năng tự động

-   **Tính tổng tiền**: Tự động tính tổng tiền hàng và tổng cộng
-   **Validation**: Kiểm tra dữ liệu trước khi submit
-   **Tạo mã đơn hàng**: Tự động tạo mã đơn hàng theo format ORD-YYYYMMDD-XXXXXX

### 4. Lưu đơn hàng

-   Click nút "Tạo đơn hàng" để lưu
-   Hệ thống sẽ chuyển đến trang chi tiết đơn hàng vừa tạo
-   Hiển thị thông báo thành công với mã đơn hàng

## Các trường bắt buộc

Các trường có dấu \* là bắt buộc phải điền:

-   Chọn khách hàng
-   Tên người đặt hàng
-   Số điện thoại
-   Địa chỉ
-   Tên người nhận
-   Số điện thoại người nhận
-   Địa chỉ giao hàng
-   Phương thức thanh toán
-   Phí vận chuyển
-   Ít nhất một sản phẩm

## Lưu ý

-   Đơn hàng được tạo với trạng thái "pending" (chờ xử lý)
-   Trạng thái thanh toán mặc định là "pending" (chờ thanh toán)
-   Admin có thể cập nhật trạng thái đơn hàng sau khi tạo
-   Mã đơn hàng được tạo tự động và không trùng lặp

## Xử lý lỗi

-   Nếu có lỗi validation, form sẽ hiển thị thông báo lỗi
-   Các trường lỗi sẽ được highlight màu đỏ
-   Dữ liệu đã nhập sẽ được giữ lại để không phải nhập lại

## Tính năng bổ sung

-   **Copy thông tin**: Tự động copy thông tin khách hàng sang thông tin giao hàng
-   **Tính toán tự động**: Tự động tính tổng tiền khi thay đổi sản phẩm hoặc số lượng
-   **Validation real-time**: Kiểm tra dữ liệu ngay khi nhập
-   **Responsive design**: Giao diện tương thích với mọi thiết bị
