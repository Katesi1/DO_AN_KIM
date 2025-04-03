
# Hệ Thống Web Quản Lý Đồ Vật Thất Lạc

## 1. Phạm vi hệ thống

### 1.1 Mô tả hệ thống
Hệ thống web dành cho sinh viên, giảng viên và nhân viên Đại học Phương Đông, giúp quản lý đồ vật thất lạc trong khuôn viên trường. Cung cấp quy trình xác minh và trả đồ cho người mất đồ.

### 1.2 Công nghệ yêu cầu
- **Ngôn ngữ lập trình**: PHP
- **Cơ sở dữ liệu**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Thư viện**: jQuery, AJAX
- **Máy chủ**: Apache/Nginx

---

## 2. Yêu cầu chức năng

### 2.1 Quản lý người dùng
1. **Đăng ký tài khoản** với email trường đại học (`@pdu.edu.vn`)
2. **Xác minh tài khoản** qua email
3. **Đăng nhập/Đăng xuất**
4. **Quên mật khẩu**
5. **Quản lý thông tin cá nhân** (họ tên, số điện thoại, khoa/lớp)
6. **Xem lịch sử đăng bài và hoạt động**
7. **Hệ thống điểm uy tín người dùng**

### 2.2 Quản lý đồ thất lạc
1. **Đăng bài tìm đồ** (người mất đồ)
   - Tiêu đề, mô tả chi tiết, danh mục, ảnh, thời gian mất, địa điểm
   - Thông tin nhận dạng đặc biệt (không hiển thị công khai)
2. **Đăng bài nhặt đồ** (người nhặt được)
   - Tiêu đề, mô tả, danh mục, ảnh, thời gian nhặt, địa điểm
   - Giữ lại một số thông tin nhận dạng đặc biệt (không công khai)
3. **Chỉnh sửa/Xóa bài đăng**
4. **Đánh dấu đã tìm thấy/đã trả**

### 2.3 Tìm kiếm và ghép cặp
1. **Tìm kiếm theo từ khóa**
2. **Lọc theo danh mục, thời gian, địa điểm**
3. **Sắp xếp kết quả** (mới nhất, phổ biến nhất)
4. **Đề xuất các bài đăng tương tự** dựa trên từ khóa

### 2.4 Quy trình xác minh
1. **Hệ thống liên hệ thông qua chat nội bộ**
2. **Mẫu câu hỏi xác minh tự động**
3. **Quy trình đối chiếu thông tin**
   - Cung cấp ít nhất 3 đặc điểm nhận dạng chính xác
   - Xác nhận danh tính qua thẻ sinh viên/thẻ nhân viên
4. **Xác nhận trả đồ thành công**
5. **Đánh giá sau khi hoàn thành**

### 2.5 Thông báo
1. **Thông báo email** khi có bài đăng phù hợp
2. **Thông báo trên web** khi có tin nhắn mới
3. **Thông báo khi có cập nhật trạng thái bài đăng**
4. **Nhắc nhở kiểm tra đồ** trước khi rời lớp học (tùy chọn)

### 2.6 Cải thiện văn hóa học đường
1. **Bảng xếp hạng người dùng tích cực**
2. **Hệ thống điểm thưởng và huy hiệu**
3. **Diễn đàn chia sẻ câu chuyện tích cực**
4. **Thống kê và bản đồ nhiệt** về khu vực hay quên đồ
5. **Các chiến dịch nâng cao ý thức**

### 2.7 Quản trị hệ thống
1. **Quản lý người dùng**
2. **Quản lý bài đăng**
3. **Quản lý danh mục**
4. **Báo cáo thống kê**
5. **Quản lý nội dung diễn đàn**
6. **Quản lý thông báo hệ thống**

---

## 3. Cấu trúc cơ sở dữ liệu

### 3.1 Bảng Users
```sql
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    faculty VARCHAR(255),
    class VARCHAR(255),
    student_id VARCHAR(50),
    role_id INT,
    trust_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 3.2 Bảng Roles
```sql
CREATE TABLE Roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    permissions TEXT
);
```

### 3.3 Bảng Categories
```sql
CREATE TABLE Categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(255)
);
```

### 3.4 Bảng Items
```sql
CREATE TABLE Items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type ENUM('lost', 'found') NOT NULL,
    category_id INT,
    location VARCHAR(255),
    lost_found_date TIMESTAMP,
    status ENUM('active', 'resolved') DEFAULT 'active',
    user_id INT,
    private_info TEXT,
    expiry_date TIMESTAMP,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES Categories(id),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
```

### 3.5 Bảng Images
```sql
CREATE TABLE Images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES Items(id)
);
```

### 3.6 Bảng Claims
```sql
CREATE TABLE Claims (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT,
    claimer_id INT,
    owner_id INT,
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verification_score INT DEFAULT 0,
    meeting_location VARCHAR(255),
    meeting_time TIMESTAMP,
    completed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES Items(id),
    FOREIGN KEY (claimer_id) REFERENCES Users(id),
    FOREIGN KEY (owner_id) REFERENCES Users(id)
);
```

### 3.7 Bảng Messages
```sql
CREATE TABLE Messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    claim_id INT,
    sender_id INT,
    content TEXT NOT NULL,
    read_status BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (claim_id) REFERENCES Claims(id),
    FOREIGN KEY (sender_id) REFERENCES Users(id)
);
```

### 3.8 Bảng Ratings
```sql
CREATE TABLE Ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    claim_id INT,
    rater_id INT,
    rated_id INT,
    rating INT CHECK(rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (claim_id) REFERENCES Claims(id),
    FOREIGN KEY (rater_id) REFERENCES Users(id),
    FOREIGN KEY (rated_id) REFERENCES Users(id)
);
```

### 3.9 Bảng Forums
```sql
CREATE TABLE Forums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id INT,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
```

### 3.10 Bảng Comments
```sql
CREATE TABLE Comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT,
    user_id INT,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (forum_id) REFERENCES Forums(id),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
```

### 3.11 Bảng Notifications
```sql
CREATE TABLE Notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    type VARCHAR(50),
    content TEXT NOT NULL,
    read_status BOOLEAN DEFAULT FALSE,
    reference_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
```

---

## 4. Cách thức liên kết giữa các bảng trong cơ sở dữ liệu

1. **Users** có **Roles** để xác định quyền hạn của người dùng (quản lý, sinh viên, giảng viên).
2. **Items** có **Categories** để phân loại đồ vật.
3. **Claims** liên kết giữa người **claiming** và người **đăng bài** về đồ thất lạc.
4. **Messages** để gửi thông điệp giữa người mất đồ và người nhặt được.
5. **Ratings** để đánh giá người dùng sau khi giao dịch hoàn tất.
6. **Forums** và **Comments** để tạo môi trường chia sẻ câu chuyện tích cực.

---

## 5. Giao diện người dùng

Sử dụng **Bootstrap 5** để thiết kế giao diện:

### 5.1 Trang chủ
- Banner giới thiệu
- Thống kê tổng quan (số đồ đã tìm thấy, số người dùng)
- Danh sách đồ thất lạc/tìm thấy mới nhất
- Tìm kiếm nhanh
- Danh mục phổ biến

### 5.2 Trang đăng bài
- Form với các trường bắt buộc và tùy chọn
- Upload nhiều ảnh
- Bản đồ chọn vị trí
- Hướng dẫn mô tả chi tiết

### 5.3 Trang chi tiết đồ vật
- Ảnh và thông tin đầy đủ
- Nút liên hệ/yêu cầu nhận lại
- Bản đồ vị trí
- Đồ vật tương tự

### 5.4 Trang tìm kiếm
- Bộ lọc đa tiêu chí
- Sắp xếp kết quả
- Hiển thị dạng lưới/danh sách

### 5.5 Trang cá nhân
- Thông tin người dùng
- Huy hiệu và điểm thưởng
- Danh sách bài đăng
- Lịch sử hoạt động

### 5.6 Trang tin nhắn
- Danh sách cuộc trò chuyện
- Giao diện chat
- Hiển thị trạng thái online

### 5.7 Trang diễn đàn
- Danh sách bài viết
- Form đăng bài
- Phần bình luận

---

## 6. MVC Mô Hình

Dựa trên mô hình MVC, bạn có thể chia hệ thống thành các phần:

- **Model**: Quản lý cơ sở dữ liệu (Users, Items, Claims, etc.)
- **View**: Các trang HTML sử dụng Bootstrap 5 để hiển thị dữ liệu cho người dùng.
- **Controller**: Điều phối các hành động người dùng, xử lý yêu cầu và trả về phản hồi.

Chúc bạn phát triển dự án thành công!
