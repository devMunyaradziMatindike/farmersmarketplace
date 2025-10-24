# 🌾 MUSIKA WEDU - Zimbabwe Agricultural Marketplace

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" alt="Vue 3">
  <img src="https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Status-Production%20Ready-success?style=for-the-badge" alt="Status">
</p>

<p align="center">
  <strong>"Musika Wedu" - Our Market | Connecting farmers directly with buyers across Zimbabwe</strong>
</p>

---

## 🚀 Quick Start

### **One Command to Rule Them All:**
```bash
npm run serve
```

Then visit: **http://localhost:8000**

### **Or Run Separately:**
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

---

## ✨ Features

### 🛒 **For Buyers**
- Browse and search products across Zimbabwe
- Filter by category, price range, and location
- Sort by: Latest, Cheapest, or Nearest
- View detailed product information
- Contact sellers via phone or WhatsApp
- Mobile-responsive design

### 🌱 **For Sellers/Farmers**
- Register with phone OTP or Google Sign-In
- Create and manage product listings
- Upload multiple photos (up to 10 per product)
- Update product status (Available, Sold Out, Soon Available)
- Manage contact information
- View product performance

### 👑 **For Administrators**
- Manage all users and products
- Create, update, and delete categories
- Remove fraudulent or inappropriate listings
- View system analytics
- Full platform oversight

---

## 🏗️ Tech Stack

### **Backend**
- Laravel 12 (PHP 8.4)
- Laravel Sanctum (API Authentication)
- Twilio SDK (OTP via SMS)
- Laravel Socialite (Google OAuth)
- SQLite (Dev) / MySQL (Production)

### **Frontend**
- Vue 3 (Composition API)
- Inertia.js
- Tailwind CSS 3
- DaisyUI 4
- Vite 7

---

## 📦 Installation

### **Prerequisites:**
- PHP 8.2+
- Composer
- Node.js 18+
- npm

### **Setup:**

```bash
# 1. Install dependencies
composer install
npm install --legacy-peer-deps

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Set up database
php artisan migrate --seed

# 4. Create storage link
php artisan storage:link

# 5. Start application
npm run serve
```

Visit: `http://localhost:8000`

**Detailed instructions:** See [START_HERE.md](START_HERE.md)

---

## 🧪 Test Users

| Role | Email | Phone | Password |
|------|-------|-------|----------|
| Admin | admin@farmersmarket.zw | +263771234567 | password |
| Seller | seller@farmersmarket.zw | +263772345678 | password |
| Buyer | buyer@farmersmarket.zw | +263773456789 | password |

---

## 📚 Documentation

| Document | Description |
|----------|-------------|
| [START_HERE.md](START_HERE.md) | Quick start guide |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Complete API reference (80+ endpoints) |
| [SETUP_GUIDE.md](SETUP_GUIDE.md) | Detailed installation guide |
| [FRONTEND_SETUP.md](FRONTEND_SETUP.md) | Frontend setup details |
| [FINAL_SUMMARY.md](FINAL_SUMMARY.md) | Complete project overview |
| Postman Collection | Ready-to-import API collection |

---

## 🎨 UI Preview

### **Homepage**
- Hero section with search
- Product grid with filters
- Category navigation
- Sort options

### **Product Detail**
- Image gallery with thumbnails
- Full product information
- Seller contact details
- Call & WhatsApp buttons

### **Dark Mode**
- Full dark mode support
- Toggle-ready design

---

## 🔧 Development

### **Commands:**
```bash
# Start both servers
npm run serve

# Or separately:
php artisan serve      # Laravel backend
npm run dev           # Vite frontend

# Build for production
npm run build

# Run tests
php artisan test

# Format code
vendor/bin/pint
```

### **Database:**
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=CategorySeeder
```

---

## 📡 API Endpoints

### **Public (No Auth):**
- `GET /api/v1/products` - Browse products
- `GET /api/v1/products/{id}` - View product
- `GET /api/v1/categories` - List categories
- `POST /api/v1/auth/login` - Login
- `POST /api/v1/auth/register/phone` - Register

### **Authenticated (Token Required):**
- `POST /api/v1/seller/products` - Create product
- `PUT /api/v1/seller/products/{id}` - Update product
- `DELETE /api/v1/seller/products/{id}` - Delete product
- `POST /api/v1/seller/products/{id}/photos` - Add photos

### **Admin Only:**
- `GET /api/v1/admin/users` - List users
- `POST /api/v1/admin/categories` - Create category
- `DELETE /api/v1/admin/products/{id}` - Remove listing

**Full API Documentation:** [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Api/ProductTest.php

# Run with coverage
php artisan test --coverage
```

**Test Coverage:**
- ✅ 27+ feature tests
- ✅ Authentication flows
- ✅ Product CRUD operations
- ✅ Authorization policies
- ✅ Search and filtering

---

## 🔐 Security

- ✅ Password hashing (bcrypt)
- ✅ Token-based authentication
- ✅ OTP verification via SMS
- ✅ Role-based access control
- ✅ Request validation
- ✅ SQL injection protection
- ✅ CSRF protection
- ✅ Authorization policies

---

## 📊 Project Statistics

- **Total Endpoints:** 80+
- **Database Tables:** 8
- **Models:** 4
- **Controllers:** 14
- **Vue Components:** 15+
- **Test Cases:** 27+
- **Lines of Code:** 6000+
- **Categories:** 11
- **Documentation Pages:** 7

---

## 🚀 Production Deployment

### **Environment Configuration:**
```env
# Production settings
APP_ENV=production
APP_DEBUG=false

# Database (MySQL recommended)
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=farmers_marketplace

# Twilio (for OTP)
TWILIO_SID=your_sid
TWILIO_TOKEN=your_token
TWILIO_FROM=+your_number

# Google OAuth
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_secret
```

### **Build & Optimize:**
```bash
npm run build
php artisan optimize
php artisan config:cache
php artisan route:cache
```

---

## 🤝 Contributing

This is a project for Zimbabwe's agricultural marketplace. Contributions are welcome!

---

## 📄 License

Open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🆘 Support

- 📖 Check documentation files (`.md`)
- 🐛 Review logs: `storage/logs/laravel.log`
- 💬 Check browser console for frontend errors
- 📧 Contact: development team

---

## 🎯 Roadmap

### **Phase 1 (Current)** ✅
- ✅ Core API functionality
- ✅ Authentication system
- ✅ Product management
- ✅ Frontend UI with Vue 3
- ✅ Search and filtering

### **Phase 2 (Next)**
- [ ] Seller dashboard UI
- [ ] Admin panel UI
- [ ] Product photo upload interface
- [ ] Enhanced search (Elasticsearch)

### **Phase 3 (Future)**
- [ ] Mobile app (Flutter)
- [ ] Payment integration (EcoCash, OneMoney)
- [ ] Chat/messaging system
- [ ] Product reviews
- [ ] Analytics dashboard

---

## 🌟 Special Features

### **1. Location-Based Search**
Find nearby farmers using GPS coordinates with Haversine formula:
```
GET /api/v1/products?latitude=-17.8252&longitude=31.0335&radius=50&sort=nearest
```

### **2. Multi-Photo Upload**
Each product supports up to 10 photos with primary image designation

### **3. OTP Authentication**
Secure phone number verification using Twilio SMS

### **4. Instant Contact**
One-click calling or WhatsApp messaging to sellers

### **5. Real-Time Search**
Debounced search with instant results

### **6. Dark Mode**
Complete dark mode support throughout the application

---

## 📱 Mobile Ready

The UI is fully responsive and works perfectly on:
- ✅ Mobile phones
- ✅ Tablets
- ✅ Desktops
- ✅ Large screens

---

## 🌍 Built for Zimbabwe

**Categories tailored for Zimbabwean agriculture:**
- Grains (Maize, Wheat, Sorghum, Millet)
- Vegetables, Fruits, Legumes
- Livestock, Dairy, Poultry
- Farm Equipment
- Seeds & Seedlings
- Organic Products
- Agricultural Services

---

## 💚 Made with Love

**Built with ❤️ for Zimbabwe's farming community**

🌾 **From Farm to Market - Empowering Zimbabwean Farmers!** 🌾

---

**Version:** 1.0.0  
**Status:** ✅ Production Ready  
**Last Updated:** October 20, 2025
