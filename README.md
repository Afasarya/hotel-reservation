# AoraGrand Hotel Booking API

Sistem pemesanan kamar hotel AoraGrand yang dibangun dengan Laravel 12 dan RESTful API dengan integrasi Midtrans untuk pembayaran.

## 🏨 Fitur Utama

### User Features
- **Authentication**: Register, Login, Logout dengan Laravel Sanctum
- **Profile Management**: Update profil pengguna
- **Room Browsing**: Melihat tipe kamar yang tersedia dengan filter
- **Booking System**: Membuat reservasi kamar dengan validasi ketersediaan
- **Payment Integration**: Pembayaran melalui Midtrans dengan berbagai metode
- **Booking Management**: Melihat, update, dan cancel booking
- **Receipt**: Menerima receipt pembayaran

### Admin Features
- **Dashboard**: Overview statistik hotel dan booking
- **Room Type Management**: CRUD tipe kamar dengan fasilitas dan gambar
- **Booking Management**: Kelola semua booking (confirm, check-in, check-out)
- **Payment Monitoring**: Monitor semua pembayaran dan receipt
- **User Management**: Kelola status user
- **Analytics**: Grafik tren booking, revenue, dan popularitas kamar

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Authentication**: Laravel Sanctum (API Tokens)
- **Database**: MySQL
- **Payment Gateway**: Midtrans
- **API Documentation**: Swagger/OpenAPI
- **Image Processing**: Intervention Image
- **Permissions**: Spatie Laravel Permission
- **Frontend Styling**: Tailwind CSS
- **Font**: Inter

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM
- Midtrans Account (Sandbox/Production)

## 🚀 Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd aora-hotel
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aora_hotel
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Midtrans Configuration
Add to `.env`:
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 6. Run Migrations & Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 7. Generate API Documentation
```bash
php artisan l5-swagger:generate
```

### 8. Build Assets
```bash
npm run build
```

### 9. Start Development Server
```bash
php artisan serve
```

## 📚 API Documentation

API documentation tersedia di: `http://localhost:8000/api/documentation`

### Authentication
Semua endpoint yang memerlukan authentication menggunakan Bearer Token:
```
Authorization: Bearer {your_token}
```

### Default Accounts
- **Admin**: admin@aoragrand.com / password123
- **User**: user@example.com / password123

## 🏗️ Database Schema

### Users
- Authentication dan profile data
- Role-based access (admin/user)

### Room Types
- Tipe kamar dengan fasilitas dan harga
- Gambar dan deskripsi kamar

### Rooms
- Kamar individual dengan nomor dan status
- Relasi ke room type

### Bookings
- Reservasi dengan tanggal dan status
- Relasi ke user, room type, dan room

### Payments
- Integrasi Midtrans dengan status pembayaran
- Receipt dan transaction tracking

## 🔗 API Endpoints

### Public Endpoints
```
POST /api/register          - User registration
POST /api/login             - User login
GET  /api/room-types        - Get available room types
GET  /api/room-types/{id}   - Get specific room type
POST /api/payment/webhook   - Midtrans webhook
```

### Protected Endpoints (User)
```
POST /api/logout            - Logout
GET  /api/profile           - Get user profile
PUT  /api/profile           - Update profile
GET  /api/bookings          - Get user bookings
POST /api/bookings          - Create booking
GET  /api/bookings/{id}     - Get booking details
PUT  /api/bookings/{id}     - Update booking
DELETE /api/bookings/{id}   - Cancel booking
POST /api/payments/create   - Create payment
GET  /api/payments/{id}     - Get payment details
```

### Admin Endpoints
```
GET  /api/admin/dashboard           - Dashboard overview
GET  /api/admin/dashboard/stats     - Detailed statistics
POST /api/admin/room-types          - Create room type
PUT  /api/admin/room-types/{id}     - Update room type
DELETE /api/admin/room-types/{id}   - Delete room type
GET  /api/admin/bookings            - Get all bookings
PUT  /api/admin/bookings/{id}/confirm - Confirm booking
PUT  /api/admin/bookings/{id}/check-in - Check-in
PUT  /api/admin/bookings/{id}/check-out - Check-out
GET  /api/admin/payments            - Get all payments
GET  /api/admin/payments/{id}/receipt - Get receipt
```

## 💳 Payment Flow

1. User membuat booking
2. System generate booking dengan status 'pending'
3. User request payment creation
4. System create payment record dan Midtrans transaction
5. User redirect ke Midtrans payment page
6. Setelah payment, Midtrans send webhook notification
7. System update payment status dan booking status
8. User receive receipt dan booking confirmation

## 🎨 Frontend Design

- **Color Scheme**: Putih dan Ungu elegan
- **Typography**: Inter font family
- **Framework**: Tailwind CSS
- **Responsive**: Mobile-first design
- **Interactive**: Smooth animations dan transitions

## 🔒 Security Features

- API Token authentication
- Role-based access control
- Input validation dan sanitization
- CSRF protection
- SQL injection prevention
- XSS protection

## 📊 Room Types

1. **Standard Room** - Rp 500,000/malam
   - Kapasitas: 2 orang
   - Fasilitas: AC, TV, WiFi, dll

2. **Deluxe Room** - Rp 750,000/malam
   - Kapasitas: 2 orang
   - Fasilitas: Premium amenities

3. **Executive Suite** - Rp 1,200,000/malam
   - Kapasitas: 3 orang
   - Fasilitas: Ruang tamu terpisah

4. **Presidential Suite** - Rp 2,500,000/malam
   - Kapasitas: 4 orang
   - Fasilitas: Luxury amenities

5. **Family Room** - Rp 900,000/malam
   - Kapasitas: 4 orang
   - Fasilitas: Family-friendly

## 🧪 Testing

### API Testing
Gunakan Postman atau tools serupa untuk test API endpoints:

1. Import Swagger documentation
2. Set environment variables
3. Test authentication flow
4. Test booking flow
5. Test payment flow

### Example API Calls

**Login:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@aoragrand.com","password":"password123"}'
```

**Get Room Types:**
```bash
curl -X GET http://localhost:8000/api/room-types \
  -H "Accept: application/json"
```

**Create Booking:**
```bash
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "room_type_id": 1,
    "check_in_date": "2024-01-01",
    "check_out_date": "2024-01-03",
    "guests": 2
  }'
```

## 🚀 Deployment

### Production Setup
1. Set environment to production
2. Configure production database
3. Set Midtrans to production mode
4. Configure web server (Nginx/Apache)
5. Set up SSL certificate
6. Configure cron jobs for scheduled tasks

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
MIDTRANS_IS_PRODUCTION=true
```

## 🤝 Contributing

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📝 License

This project is licensed under the MIT License.

## 📞 Support

Untuk support dan pertanyaan:
- Email: admin@aoragrand.com
- Documentation: http://localhost:8000/api/documentation

## 🔄 Version History

- **v1.0.0** - Initial release dengan fitur lengkap
  - User authentication
  - Room booking system
  - Midtrans payment integration
  - Admin dashboard
  - API documentation

---

**AoraGrand Hotel** - Luxury accommodation with modern booking system
