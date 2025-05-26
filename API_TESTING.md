# API Testing Guide - AoraGrand Hotel

Panduan lengkap untuk testing semua API endpoints menggunakan curl atau Postman.

## Base URL
```
http://localhost:8000/api
```

## Authentication
Untuk endpoint yang memerlukan authentication, gunakan Bearer Token:
```
Authorization: Bearer {your_token}
```

## 1. Authentication Endpoints

### Register User
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+62812345678",
    "address": "Jl. Contoh No. 123",
    "date_of_birth": "1990-01-01",
    "gender": "male",
    "identity_number": "1234567890123456"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@aoragrand.com",
    "password": "password123"
  }'
```

### Get Profile
```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Update Profile
```bash
curl -X PUT http://localhost:8000/api/profile \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe Updated",
    "phone": "+62812345679"
  }'
```

### Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

## 2. Room Types Endpoints

### Get All Room Types
```bash
curl -X GET http://localhost:8000/api/room-types \
  -H "Accept: application/json"
```

### Get Room Types with Availability Check
```bash
curl -X GET "http://localhost:8000/api/room-types?check_in_date=2024-01-01&check_out_date=2024-01-03&guests=2" \
  -H "Accept: application/json"
```

### Get Specific Room Type
```bash
curl -X GET http://localhost:8000/api/room-types/1 \
  -H "Accept: application/json"
```

### Create Room Type (Admin Only)
```bash
curl -X POST http://localhost:8000/api/admin/room-types \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Luxury Suite",
    "description": "Luxury suite with premium amenities",
    "price_per_night": 3000000,
    "capacity": 4,
    "total_rooms": 5,
    "facilities": ["AC", "TV 65\"", "WiFi", "Jacuzzi", "Butler Service"],
    "images": ["/images/luxury-1.jpg", "/images/luxury-2.jpg"],
    "is_active": true
  }'
```

### Update Room Type (Admin Only)
```bash
curl -X PUT http://localhost:8000/api/admin/room-types/1 \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "price_per_night": 550000
  }'
```

### Delete Room Type (Admin Only)
```bash
curl -X DELETE http://localhost:8000/api/admin/room-types/1 \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

## 3. Booking Endpoints

### Get User Bookings
```bash
curl -X GET http://localhost:8000/api/bookings \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Get User Bookings by Status
```bash
curl -X GET "http://localhost:8000/api/bookings?status=confirmed" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Create Booking
```bash
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "room_type_id": 1,
    "check_in_date": "2024-01-01",
    "check_out_date": "2024-01-03",
    "guests": 2,
    "special_requests": "Late check-in please"
  }'
```

### Get Specific Booking
```bash
curl -X GET http://localhost:8000/api/bookings/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Update Booking
```bash
curl -X PUT http://localhost:8000/api/bookings/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "special_requests": "Early check-in if possible"
  }'
```

### Cancel Booking
```bash
curl -X DELETE http://localhost:8000/api/bookings/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

## 4. Payment Endpoints

### Create Payment
```bash
curl -X POST http://localhost:8000/api/payments/create \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "booking_id": 1
  }'
```

### Get Payment Details
```bash
curl -X GET http://localhost:8000/api/payments/1 \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Check Payment Status
```bash
curl -X GET http://localhost:8000/api/payments/1/status \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Midtrans Webhook (Public)
```bash
curl -X POST http://localhost:8000/api/payment/webhook \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "order_id": "ORDER-20240101123456-ABCD",
    "status_code": "200",
    "gross_amount": "1000000.00",
    "signature_key": "signature_hash",
    "transaction_status": "settlement",
    "transaction_id": "xyz789",
    "payment_type": "credit_card"
  }'
```

## 5. Admin Dashboard Endpoints

### Get Dashboard Overview
```bash
curl -X GET http://localhost:8000/api/admin/dashboard \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

### Get Dashboard Statistics
```bash
curl -X GET "http://localhost:8000/api/admin/dashboard/stats?period=monthly" \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

## 6. Admin Booking Management

### Get All Bookings (Admin)
```bash
curl -X GET http://localhost:8000/api/admin/bookings \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

### Get Bookings by Status (Admin)
```bash
curl -X GET "http://localhost:8000/api/admin/bookings?status=pending" \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

### Confirm Booking (Admin)
```bash
curl -X PUT http://localhost:8000/api/admin/bookings/1/confirm \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

### Check-in Booking (Admin)
```bash
curl -X PUT http://localhost:8000/api/admin/bookings/1/check-in \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

### Check-out Booking (Admin)
```bash
curl -X PUT http://localhost:8000/api/admin/bookings/1/check-out \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

### Cancel Booking (Admin)
```bash
curl -X PUT http://localhost:8000/api/admin/bookings/1/cancel \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

## 7. Admin Payment Management

### Get All Payments (Admin)
```bash
curl -X GET http://localhost:8000/api/admin/payments \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

### Get Payment Receipt (Admin)
```bash
curl -X GET http://localhost:8000/api/admin/payments/1/receipt \
  -H "Authorization: Bearer {admin_token}" \
  -H "Accept: application/json"
```

## Testing Flow

### 1. Complete User Flow
```bash
# 1. Register user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# 2. Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# 3. Get room types
curl -X GET http://localhost:8000/api/room-types

# 4. Create booking
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"room_type_id":1,"check_in_date":"2024-01-01","check_out_date":"2024-01-03","guests":2}'

# 5. Create payment
curl -X POST http://localhost:8000/api/payments/create \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"booking_id":1}'
```

### 2. Complete Admin Flow
```bash
# 1. Login as admin
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@aoragrand.com","password":"password123"}'

# 2. Get dashboard
curl -X GET http://localhost:8000/api/admin/dashboard \
  -H "Authorization: Bearer {admin_token}"

# 3. Get all bookings
curl -X GET http://localhost:8000/api/admin/bookings \
  -H "Authorization: Bearer {admin_token}"

# 4. Confirm booking
curl -X PUT http://localhost:8000/api/admin/bookings/1/confirm \
  -H "Authorization: Bearer {admin_token}"
```

## Expected Response Format

All API responses follow this format:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data
  }
}
```

Error responses:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    // Validation errors (if any)
  }
}
```

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Postman Collection

Import the Swagger documentation into Postman:
1. Open Postman
2. Click Import
3. Enter URL: `http://localhost:8000/api/documentation`
4. Import the collection
5. Set up environment variables for tokens

## Testing Checklist

- [ ] User registration and login
- [ ] Profile management
- [ ] Room type browsing
- [ ] Booking creation and management
- [ ] Payment processing
- [ ] Admin dashboard access
- [ ] Admin booking management
- [ ] Admin payment monitoring
- [ ] Error handling
- [ ] Authentication middleware
- [ ] Admin middleware
- [ ] Input validation 