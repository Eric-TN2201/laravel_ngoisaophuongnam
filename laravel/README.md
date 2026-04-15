# Thuốc Trừ Sâu Long An - Website

Website giới thiệu sản phẩm và dịch vụ của Công Ty TNHH MTV Bảo Vệ Thực Vật Long An.

## Yêu cầu hệ thống

- PHP >= 8.1
- MySQL
- Composer
- Node.js & NPM

## Cài đặt

```bash
# Cài đặt dependencies
composer install
npm install

# Sao chép file cấu hình
cp .env.example .env

# Tạo application key
php artisan key:generate

# Cấu hình database trong file .env
# DB_DATABASE=thuoc_tru_sau_db
# DB_USERNAME=root
# DB_PASSWORD=

# Chạy migration và seed dữ liệu
php artisan migrate
php artisan db:seed

# Tạo symbolic link cho storage
php artisan storage:link

# Build assets
npm run build
```

## Chạy ứng dụng

```bash
# Chạy server
php artisan serve

# Chạy Vite dev server (khi phát triển)
npm run dev
```

## Cấu trúc dự án

```
app/
├── Http/Controllers/
│   ├── Admin/          # Controllers quản trị (Sản phẩm, Danh mục, Tin tức, Banner, ...)
│   └── Client/         # Controllers trang khách (Trang chủ, Sản phẩm, Tin tức, Liên hệ)
├── Models/             # Eloquent models (Product, Category, News, Banner, Setting, ...)
└── helpers.php         # Helper function: setting()

resources/views/
├── admin/              # Giao diện quản trị (AdminLTE)
├── client/             # Giao diện trang khách
├── components/         # Blade components (navbar, footer, product-card, ...)
├── layouts/            # Layout files (client, admin)
└── errors/             # Trang lỗi (404, 500)
```

## Chức năng

### Trang khách
- **Trang chủ**: Banner slider, sản phẩm xu hướng, danh mục sản phẩm, tin nổi bật, giới thiệu công ty
- **Sản phẩm**: Danh sách sản phẩm theo danh mục / danh mục phụ, chi tiết sản phẩm, quick view
- **Tin tức**: Danh sách tin tức, chi tiết bài viết
- **Giới thiệu**: Lời giới thiệu công ty
- **Liên hệ**: Thông tin liên hệ, bản đồ Google Maps

### Quản trị (`/tts/admin`)
- Quản lý sản phẩm, danh mục, danh mục phụ
- Quản lý tin tức / sự kiện
- Quản lý dịch vụ
- Quản lý banner
- Lời giới thiệu
- **Thông tin công ty**: Tên, địa chỉ, SĐT, email, Facebook, Zalo (lưu trong database, thay đổi trực tiếp từ admin)
- Cài đặt tài khoản

## Cấu hình môi trường (.env)

| Biến | Mô tả |
|------|-------|
| `APP_NAME` | Tên ứng dụng |
| `DB_DATABASE` | Tên database |
| `DB_USERNAME` | Username database |
| `DB_PASSWORD` | Mật khẩu database |
| `ADMIN_ACCOUNT_NAME` | Tên tài khoản admin mặc định |
| `ADMIN_ACCOUNT_EMAIL` | Email admin mặc định |
| `ADMIN_ACCOUNT_PASSWORD` | Mật khẩu admin mặc định |
| `COMPANY_NAME` | Tên công ty (giá trị khởi tạo, sau đó quản lý qua admin) |
| `COMPANY_ADDRESS` | Địa chỉ công ty |
| `COMPANY_PHONE` | Số điện thoại |
| `COMPANY_ZALO` | Link Zalo |

## Công nghệ sử dụng

- **Framework**: Laravel 10
- **Admin Panel**: AdminLTE 3
- **Frontend**: Bootstrap, Swiper.js, Font Awesome
- **Build Tool**: Vite
- **Database**: MySQL

## Triển khai Production

### 1. Yêu cầu server

- PHP >= 8.1 (với extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML)
- MySQL >= 5.7
- Composer
- Node.js >= 16 & NPM
- Nginx hoặc Apache

### 2. Cài đặt

```bash
# Clone source code
git clone <repo-url> /var/www/thuoctrusau
cd /var/www/thuoctrusau

# Cài đặt dependencies (không cài dev packages)
composer install --optimize-autoloader --no-dev

# Cài đặt và build frontend assets
npm install
npm run build

# Sao chép và cấu hình .env
cp .env.example .env
php artisan key:generate
```

### 3. Cấu hình .env cho production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thuoc_tru_sau_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

CACHE_DRIVER=file
SESSION_DRIVER=file
```

### 4. Khởi tạo database và dữ liệu

```bash
# Chạy migration
php artisan migrate --force

# Seed dữ liệu mặc định (tài khoản admin, thông tin công ty)
php artisan db:seed --force

# Tạo symbolic link cho storage
php artisan storage:link
```

### 5. Tối ưu hóa

```bash
# Cache config, route, view
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Tối ưu autoload
composer dump-autoload --optimize
```

### 6. Cấu hình Nginx (ví dụ)

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/thuoctrusau/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 7. Phân quyền thư mục

```bash
# Đảm bảo web server có quyền ghi vào storage và cache
chown -R www-data:www-data /var/www/thuoctrusau
chmod -R 755 /var/www/thuoctrusau
chmod -R 775 /var/www/thuoctrusau/storage
chmod -R 775 /var/www/thuoctrusau/bootstrap/cache
```

### 8. Cập nhật khi deploy mới

```bash
# Pull code mới
git pull origin main

# Cài đặt dependencies
composer install --optimize-autoloader --no-dev

# Chạy migration
php artisan migrate --force

# Build assets
npm install
npm run build

# Xóa cache cũ và tạo lại
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
