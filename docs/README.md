<div align="center">
<h2>Dokumentasi Booking Room API</h2>
</div>

### AUTH
1) Register

Method: POST
URL: api/auth/register
Body (form data):

Response sukses (201):
```
{
  "user": {
    "name": "rudi"
    "email": "rudi@example.com"
    "updated_at": "2025-12-29T01:37:45.000000Z"
    "created_at": "2025-12-29T01:37:45.000000Z"
    "id": 3
  }
  "access_token": "eyJ..."
}
```

2) Login

Method: POST
URL: api/auth/login
Body (form data):

{
    "name": "namauser"
    "email": "emailuser@example.com   
}

Response:
```
{
    "access_token": "eyJ..."
    "token_type": "bearer"
    "expires_in": "3600"
}
```

Catatan: gunakan akun admin dari seeder [DatabaseSeeder.php](../database/seeders/DatabaseSeeder.php) untuk testing admin: admin@example.com / password.

Contoh login admin:

```
{
    "name": "Admin"
    "email": "admin@example.com"
    "password": "password"
}
```

3) me (cek user)

Method: POST
URL: api/auth/me
Headers: Authorization (bearer token)
Response:
```
{
    "id": 3,
    "name": "rudi",
    "email": "rudi@example.com",
    "email_verified_at": null,
    "is_admin": false,
    "created_at": "2025-12-29T01:37:45.000000Z",
    "updated_at": "2025-12-29T01:37:45.000000Z"
}
```

4) Logout

Method: POST
URL: {{base_url}}/auth/logout
Headers: Authorization (bearer token)
Response:

```
{
    "message": "Logged out"
}
```

5) Refresh token (Belum selesai)

Method: POST
URL: {{base_url}}/auth/refresh
Headers: Authorization
Response:

```
{ "access_token": "newtoken...", "token_type":"bearer", "expires_in":3600 }
```

### Room
6) List rooms

Method: GET
URL: api/rooms
Headers: Authorization (bearer token)
Response (paging):

```
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "name": "Ruang Meeting A",
            "location": "Lantai 1",
            "capacity": 10,
            "is_active": true,
            "created_at": "2025-12-29T01:18:59.000000Z",
            "updated_at": "2025-12-29T01:18:59.000000Z"
        },
        {
            "id": 2,
            "name": "Ruang Meeting B",
            "location": "Lantai 2",
            "capacity": 20,
            "is_active": true,
            "created_at": "2025-12-29T01:18:59.000000Z",
            "updated_at": "2025-12-29T01:18:59.000000Z"
        },
        {
            "id": 3,
            "name": "Ruang Meeting C",
            "location": "Lantai 3",
            "capacity": 30,
            "is_active": true,
            "created_at": "2025-12-29T01:18:59.000000Z",
            "updated_at": "2025-12-29T01:18:59.000000Z"
        },
        {
            "id": 4,
            "name": "Ruang Meeting D",
            "location": "Lantai 4",
            "capacity": 25,
            "is_active": true,
            "created_at": "2026-01-02T06:02:01.000000Z",
            "updated_at": "2026-01-02T06:02:01.000000Z"
        },
        {
            "id": 5,
            "name": "Ruang Meeting E",
            "location": "Lantai 5",
            "capacity": 24,
            "is_active": true,
            "created_at": "2026-01-02T06:21:36.000000Z",
            "updated_at": "2026-01-02T07:16:33.000000Z"
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/rooms?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/rooms?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "page": null,
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/rooms?page=1",
            "label": "1",
            "page": 1,
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "page": null,
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http://127.0.0.1:8000/api/rooms",
    "per_page": 10,
    "prev_page_url": null,
    "to": 5,
    "total": 5
}
```

7) Show room (by id)

Method: GET
URL: api/rooms/1
Headers: Authorization (bearer token)
Response:

```
{
    "id":5,"name":"Ruang Meeting E","location":"Lantai 5","capacity":24,"is_active":true,"created_at":"2026-01-02T06:21:36.000000Z","updated_at":"2026-01-02T07:16:33.000000Z"
}
```

8) Create room (admin only)

Method: POST
URL: api/rooms
Headers: Authorization (bearer token)
Body (form data):

```
{
    "name": Ruang Meeting D"
    "location": "Lantai 4"
    "capacity": "20"
}
```

Response:

```
{"name":"Ruang Meeting D","location":"Lantai 4","capacity":"25","updated_at":"2026-01-02T06:21:36.000000Z","created_at":"2026-01-02T06:21:36.000000Z","id":5}
```

Jika bukan admin: 

Status: 403 Forbidden

```
{ "error": "Admin Access Required" }
```

9) Update room (admin only)

Method: PUT
URL: api/rooms/3 (id room)
Headers: Authorization (bearer token)
Body (x-www-form-urlencoded):

```
{
    "name": "Ruang Meeting E"
    "location": "Lantai 5"
    "capacity": "18"
}
```

Response:

```
{
    "id": 5,
    "name": "Ruang Meeting E",
    "location": "Lantai 5",
    "capacity": 18,
    "is_active": true, (defaul true)
    "created_at": "2026-01-02T06:21:36.000000Z",
    "updated_at": "2026-01-02T07:00:17.000000Z"
}
```

10) Patch room (admin only)

Method: PATCH
URL: api/rooms/1 (id room)
Headers: Authorization (Bearer Token)
Body (x-www-form-urlencoded) contoh:

```
{ "capacity": 12 }
```

11) Delete room (admin only)

Method: DELETE
URL: api/rooms/1
Headers: Authorization (bearer token)
Response:

```
{ "message": "Room succesfully deleted" }
```

### Booking (Belum selesai)
11) List bookings

Method: GET
URL: {{base_url}}/bookings
Headers: Authorization
Behavior: jika admin lihat semua; jika user lihat booking miliknya.
Response (paging) contoh:

{
  "data": [
    { "id":1, "room_id":1, "user_id":2, "start_at":"2025-12-30 09:00:00", "end_at":"2025-12-30 10:00:00", "status":"pending", ... },
    ...
  ]
}

12) Show booking

Method: GET
URL: {{base_url}}/bookings/1
Headers: Authorization
Response:

{ "id":1, "room":{...}, "user":{...}, "start_at":"...", "end_at":"...", "status":"pending" }

13) Create booking (user)

Method: POST
URL: {{base_url}}/bookings
Headers: Authorization + Content-Type
Body:

{
  "room_id": 1,
  "start_at": "2025-12-30 09:00:00",
  "end_at": "2025-12-30 10:00:00",
  "purpose": "Presentasi UAS"
}


Response sukses (201): booking object dengan status pending.
Jika waktu bentrok → response 422:

{ "errors": { "time": ["Waktu booking bentrok"] } }


Format tanggal: gunakan YYYY-MM-DD HH:MM:SS sesuai timezone app.

14) Cancel booking (user)

Method: PATCH
URL: {{base_url}}/bookings/1/cancel
Headers: Authorization
Response: booking object dengan status: "cancelled".

15) Approve booking (admin)

Method: PATCH
URL: {{base_url}}/bookings/1/approve
Headers: Authorization
Response: booking object dengan status: "approved".

16) Reject booking (admin)

Method: PATCH
URL: {{base_url}}/bookings/1/reject
Headers: Authorization
Response: booking object dengan status: "rejected".