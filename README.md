<div align="center">
<h1>Booking Room API</h4>
</div>

## Deskripsi Singkat
RoomBooking API adalah REST API untuk mengelola data ruang/kamar dan transaksi booking (pengajuan, pembatalan, persetujuan admin) dengan autentikasi JWT agar akses endpoint aman.​

## Requirement

Pastikan Anda telah menginstal hal-hal berikut pada sistem Anda:

```
Git (2.51.2 atau lebih baru)
Composer (2.9.1 atau lebih baru)
```

## Cara Menjalankan Sistem

1. Clone Repo

```
git clone https://github.com/Booking-Room-PWS/Booking-Room.git
cd Portofolio-Website
```

2. Install dependency:

```
composer install
```

3. Setup Invironment:

```
Ubah .env.example -> .env
Atur Koneksi DB & MYSQL (DB_DATABASE, dll jika diperlukan)
```

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

6. Jalankan Local Development Server:

```
php artisan serve
```

**Informasi Akun Uji Coba**
- Admin: admin@example.com
- User: user@example.com  

## Dokumentasi API
- Postman Collection: Kosong untuk saat ini.

## TODO
- [x] middleware
  - IsAdmin.php
- [x] seeders
  - DatabaseSeeder.php (Admin dan User Biasa)
- [x] migration
  - xxxx_xx_xx_xxxxx_create_rooms_table.php
  - xxxx_xx_xx_xxxxx_create_bookings_table.php
  - xxxx_xx_xx_xxxxx_add_is_admin_to_users_table.php
- [x] models
  - Booking.php
  - Room.php
  - User.php
- [] routes
  - api.php
- [] controller + request class
  - RoomController
  - BookingController
  - StoreRoomRequest
  - UpdateRoomRequest
  - StoreBookingRequest
- [] Testing API + Endpoint
- [] Postman Collection