<div align="center">
<h1>Booking Room API</h4>
</div>

## Deskripsi Singkat
Booking Room API adalah REST API untuk mengelola data ruang/kamar dan transaksi booking (pengajuan, pembatalan, persetujuan admin) dengan autentikasi JWT agar akses endpoint aman.​

## Requirement

Pastikan Anda telah menginstal hal-hal berikut pada sistem Anda:

```
Git (2.51.2 atau lebih baru)
Composer (2.9.1 atau lebih baru)
XAMPP (8.2.12)
```

## Cara Menjalankan Sistem

1. Clone Repo

```
git clone https://github.com/Booking-Room-PWS/Booking-Room.git
cd Booking-Room-API
```

2. Install dependency:

```
composer install
```

3. Setup Environment:

```
Ubah .env.example -> .env
Atur Koneksi DB & MYSQL (DB_DATABASE, dll jika diperlukan)
```
> [!NOTE]
> Klik dibawah ini untuk apa saja yang diubah pada .env
<details>
<summary>Klik disini!</summary>

```
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=roombooking
# DB_USERNAME=root
# DB_PASSWORD=''

Ubah menjadi seperti ini:
DB_CONNECTION=mysql # ubah sqlite ke mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=roombooking # sesuaikan dengan database,
DB_USERNAME=root
DB_PASSWORD=''
```

</details>

4. Migration:

```
php artisan migrate
```

5. Setup JWT (tymon/jwt-auth):

```
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider" (membuat config/jwt.php)
php artisan jwt:secret (mengisi JWT_SECRET di .env)
```

6. Generate secure key:

```
php artisan key:generate (membuat 32 lebih random karakter key dan set value APP_KEY=(awalnya kosong) di file .env)
```

7. Jalankan Local Development Server:

```
php artisan serve
```
> [!IMPORTANT]
> Uji Coba Akun
- Admin:
  - name: Admin
  - email: admin@example.com
  - password: password
- User:
  - name: namabaru
  - email: namabaru@example.com
  - password: abc123
  - password_confirmation: abc123

## Dokumentasi API
- Dokumentasi API Saat ini: [Klik Disini!](docs/README.md)
- Postman Collection (publish): https://documenter.getpostman.com/view/38706978/2sBXVeGY1u (Note/catatan: masih belum complete) 

## TODO
- [x] middleware
  - IsAdmin.php
  - ForceJsonHeaders.php
  - LogAPI.php
- [x] seeders
  - DatabaseSeeder.php (Admin dan User Biasa)
- [x] migration
  - xxxx_xx_xx_xxxxx_create_rooms_table.php
  - xxxx_xx_xx_xxxxx_create_bookings_table.php
  - xxxx_xx_xx_xxxxx_add_is_admin_to_users_table.php
  - xxxx_xx_xx_xxxxx_create_log_table.php
- [x] models
  - Booking.php
  - Room.php
  - LogModel.php
  - User.php
- [x] helpers
  - ApiFormatter.php
- [x] routes
  - api.php
- [x] bootstrap / konfigurasi (routing, middleware alias, exception handler dll)
  - bootstrap/app.php (laravel 12+)
- [x] controller + request class
  - AuthController.php (refresh belum selesai)
  - RoomController.php
  - BookingController.php (belum selesai)
- [] Testing API + Endpoint
- [] Postman Collection