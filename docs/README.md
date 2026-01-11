<div align="center">
<h2>Dokumentasi Booking Room API</h2>
</div>

## AUTH
1) Register

Method: POST <br> URL: api/auth/register <br> Body (form data):

```
{
    "name": "rudi"
    "email": "rudi@example.com"
    "password": "123456"
    "password_confirmation": "123456"
}
```


Response sukses:
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

Method: POST <br> URL: api/auth/login <br> Body (form data):

```
{
    "name": "rudi"
    "email": "emailuser@example.com   
}
```

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

Method: POST <br> URL: api/auth/me <br> Headers: Authorization (bearer token)
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

Method: POST <br> URL: {{base_url}}/auth/logout <br> Headers: Authorization (bearer token)
Response:

```
{
    "message": "Logged out"
}
```

5) Refresh token

Method: POST <br> URL: api/auth/refresh <br> Headers: Authorization (bearer token)
Response:

```
{ "access_token": "newtoken...", "token_type":"bearer", "expires_in":3600 }
```

## Room
6) List rooms

Method: GET <br> URL: api/rooms <br> Headers: Authorization (bearer token) <br> Response:

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

Method: GET <br> URL: api/rooms/1 <br> Headers: Authorization (bearer token) <br> Response:

```
{
    "id":5,"name":"Ruang Meeting E","location":"Lantai 5","capacity":24,"is_active":true,"created_at":"2026-01-02T06:21:36.000000Z","updated_at":"2026-01-02T07:16:33.000000Z"
}
```

8) Create room (admin only)

Method: POST <br> URL: api/rooms <br> Headers: Authorization (bearer token) <br> Body (form data):

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

Method: PUT <br> URL: api/rooms/3 <br> Headers: Authorization (bearer token) <br> Body (x-www-form-urlencoded):

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

Method: PATCH <br> URL: api/rooms/1 <br> Headers: Authorization (Bearer Token) <br> Body (x-www-form-urlencoded) <br> contoh:

```
{ "capacity": 12 }
```

11) Delete room (admin only)

Method: DELETE <br> URL: api/rooms/1 <br> Headers: Authorization (bearer token) <br> Response:

```
{ "message": "Room succesfully deleted" }
```

## Booking
11) List bookings

Method: GET <br> URL: api/bookings <br> Headers: Authorization (bearer token) <br> Response:

```
{
    "current_page": 1,
    "data": [],
    "first_page_url": "http://127.0.0.1:8000/api/bookings?page=1",
    "from": null,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/bookings?page=1",
    "links": [
        {
        "url": null,
        "label": "&laquo; Previous",
        "page": null,
        "active": false
        },
        {
        "url": "http://127.0.0.1:8000/api/bookings?page=1",
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
    "path": "http://127.0.0.1:8000/api/bookings",
    "per_page": 15,
    "prev_page_url": null,
    "to": null,
    "total": 0
}
```

12) Show booking

Method: GET <br> URL: api/bookings/1 <br> Headers: Authorization (bearer token) <br> Response:

```
{
    "id": 1,
    "room_id": 1,
    "user_id": 1,
    "start_at": "2026-01-11T15:20:00.000000Z",
    "end_at": "2026-01-30T15:20:00.000000Z",
    "purpose": "Presentasi UAS",
    "status": "pending",
    "created_at": "2026-01-11T07:22:49.000000Z",
    "updated_at": "2026-01-11T07:22:49.000000Z",
    "room": {
        "id": 1,
        "name": "Ruang Meeting A",
        "location": "Lantai 1",
        "capacity": 10,
        "is_active": true,
        "created_at": "2026-01-10T06:18:59.000000Z",
        "updated_at": "2026-01-10T06:18:59.000000Z"
    },
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com",
        "email_verified_at": null,
        "is_admin": true,
        "created_at": "2026-01-10T06:18:59.000000Z",
        "updated_at": "2026-01-10T06:18:59.000000Z"
    }
}
```

13) Create booking (user)

Method: POST <br> URL: api/bookings <br> Headers: Authorization (bearer token) <br> Body (form data):
```
{
  "room_id": 1,
  "start_at": "2026-01-11 15:20:00",
  "end_at": "2026-01-30 15:20:00",
  "purpose": "Presentasi UAS"
}
```

Response sukses: status booking pending (default pending).

```
{
    "room_id": "1",
    "user_id": 1,
    "start_at": "2026-01-11T15:20:00.000000Z",
    "end_at": "2026-01-30T15:20:00.000000Z",
    "purpose": "Presentasi UAS",
    "status": "pending",
    "updated_at": "2026-01-11T07:22:49.000000Z",
    "created_at": "2026-01-11T07:22:49.000000Z",
    "id": 1
}
```

14) Cancel booking (user)

Method: PATCH <br> URL: api/bookings/1/cancel <br> Headers: Authorization (bearer token) <br>
Response: booking status: "cancelled":
```
{
    id": 1,
    "room_id": 1,
    "user_id": 1,
    "start_at": "2026-01-11T15:20:00.000000Z",
    "end_at": "2026-01-30T15:20:00.000000Z",
    "purpose": "Presentasi UAS",
    "status": "cancelled",
    "created_at": "2026-01-11T07:22:49.000000Z",
    "updated_at": "2026-01-11T07:31:52.000000Z"
}
```

15) Approve booking (admin)

Method: PATCH <br> URL: api/bookings/1/approve <br> Headers: Authorization (bearer token)
Response: booking status: "approved".
```
{
    id": 1,
    "room_id": 1,
    "user_id": 1,
    "start_at": "2026-01-11T15:20:00.000000Z",
    "end_at": "2026-01-30T15:20:00.000000Z",
    "purpose": "Presentasi UAS",
    "status": "approved",
    "created_at": "2026-01-11T07:22:49.000000Z",
    "updated_at": "2026-01-11T07:31:52.000000Z"
}
```

16) Reject booking (admin)

Method: PATCH <br> URL: api/bookings/1/reject <br> Headers: Authorization (bearer token)
Response: booking status: "rejected":
```
{
    id": 1,
    "room_id": 1,
    "user_id": 1,
    "start_at": "2026-01-11T15:20:00.000000Z",
    "end_at": "2026-01-30T15:20:00.000000Z",
    "purpose": "Presentasi UAS",
    "status": "rejected",
    "created_at": "2026-01-11T07:22:49.000000Z",
    "updated_at": "2026-01-11T07:31:52.000000Z"
}
```