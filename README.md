# BravePay

**BravePay** adalah sistem pembayaran dan pendaftaran lomba berbasis web yang dikembangkan untuk mendukung proses registrasi peserta, pembayaran menggunakan QRIS, serta distribusi E-Ticket secara otomatis melalui Email dan WhatsApp.

Sistem dirancang dengan konsep **tanpa login untuk peserta**, sehingga pengguna dapat langsung memilih lomba, mengisi data pendaftaran, melakukan pembayaran, dan menerima E-Ticket.

---

## 1. Overview

BravePay menyediakan alur sederhana:

```text
User
  │
  ▼
Pilih Lomba
  │
  ▼
Isi Data Peserta
  │
  ├── Nama
  ├── Email
  ├── No. Telepon
  ├── Tanggal Lahir
  └── Jumlah Peserta
  │
  ▼
Review Pendaftaran
  │
  ▼
Payment
  │
  ▼
QRIS
  │
  ▼
Pembayaran Berhasil
  │
  ├── E-Ticket di Website
  ├── PDF E-Ticket → Email
  └── E-Ticket → WhatsApp
```

---

# 2. Tujuan Sistem

BravePay dibuat untuk:

* Mempermudah pendaftaran lomba.
* Menghilangkan kebutuhan login bagi peserta.
* Menyediakan pembayaran berbasis QRIS.
* Mengotomatisasi konfirmasi pembayaran.
* Menghasilkan E-Ticket secara otomatis.
* Mengirim E-Ticket melalui Email.
* Menyediakan fitur bantuan pengiriman ulang E-Ticket melalui WhatsApp.
* Mempermudah panitia dalam mengelola peserta dan pembayaran.

---

# 3. Konsep Utama

## Guest Registration

Peserta **tidak perlu membuat akun atau login**.

User cukup:

1. Membuka website BravePay.
2. Memilih lomba.
3. Mengisi data peserta.
4. Memilih jumlah orang.
5. Melakukan pembayaran.
6. Mendapatkan E-Ticket.

Konsep ini dibuat agar proses pendaftaran sesingkat mungkin.

---

# 4. Fitur Utama

## 4.1. Competition List

User dapat melihat daftar lomba yang tersedia.

Informasi lomba minimal:

* Nama lomba
* Deskripsi
* Poster
* Kategori
* Harga pendaftaran
* Tanggal lomba
* Lokasi
* Status pendaftaran

Contoh:

```text
GEN FEST 2026

Basket Competition
Rp50.000 / Peserta

[ DAFTAR SEKARANG ]
```

---

## 4.2. Registration

User mengisi data pendaftaran.

Data utama:

| Field          | Keterangan                    |
| -------------- | ----------------------------- |
| Nama           | Nama peserta                  |
| Email          | Email penerima E-Ticket       |
| No. Telepon    | Nomor WhatsApp                |
| Tanggal Lahir  | Tanggal lahir peserta         |
| Jumlah Peserta | Jumlah orang yang didaftarkan |
| Competition    | Lomba yang dipilih            |

---

# 5. Multiple Participant

User dapat mendaftarkan lebih dari satu orang dalam satu transaksi.

Contoh:

```text
Lomba:
Basket Competition

Jumlah Peserta:
3 Orang

Harga:
Rp50.000 / orang

Total:
Rp150.000
```

Untuk setiap peserta dapat disimpan data:

```text
Participant 1
- Nama
- Tanggal Lahir

Participant 2
- Nama
- Tanggal Lahir

Participant 3
- Nama
- Tanggal Lahir
```

Data kontak utama transaksi:

```text
Email
No. Telepon
```

digunakan sebagai kontak utama untuk pengiriman E-Ticket.

---

# 6. Order System

Setelah user mengisi data, sistem membuat sebuah **Order**.

Contoh:

```text
Order ID:
BRV-20260911-0001

Competition:
Basket Competition

Participants:
3

Total:
Rp150.000

Status:
Pending Payment
```

Status order:

```text
PENDING
PAID
EXPIRED
CANCELLED
```

---

# 7. Payment System

BravePay menggunakan **Payment Gateway dengan metode QRIS**.

Flow:

```text
Registration
     │
     ▼
Create Order
     │
     ▼
Create Payment
     │
     ▼
Generate QRIS
     │
     ▼
User Scan QRIS
     │
     ▼
Payment Gateway
     │
     ▼
Payment Success
     │
     ▼
Webhook BravePay
     │
     ▼
Order = PAID
```

### Important

Status pembayaran **tidak boleh hanya bergantung pada halaman yang dibuka user**.

Payment Gateway harus memberikan konfirmasi melalui:

```text
Webhook / Callback
```

Contoh:

```text
Payment Gateway
      │
      │ POST /api/payment/webhook
      ▼
BravePay
      │
      ▼
Verify Transaction
      │
      ▼
Update Payment
      │
      ▼
Generate E-Ticket
```

---

# 8. Payment Status

Sistem menggunakan status pembayaran:

```text
PENDING
PAID
FAILED
EXPIRED
REFUNDED
```

Contoh:

```text
Order:
BRV-20260911-0001

Payment:
QRIS

Amount:
Rp150.000

Status:
PAID
```

---

# 9. E-Ticket

Setelah pembayaran berhasil, sistem secara otomatis membuat E-Ticket.

E-Ticket memiliki informasi:

* BravePay
* Nama event/lomba
* Nama peserta
* Nomor ticket
* Order ID
* Kategori lomba
* Tanggal lomba
* Lokasi
* QR Code / Barcode
* Status ticket

Contoh:

```text
┌─────────────────────────────────┐
│          BRAVEPAY               │
│                                 │
│       BASKET COMPETITION        │
│                                 │
│  Participant: Wahyu Perwira     │
│  Ticket: BRV-TKT-00001          │
│  Category: Student              │
│                                 │
│          [ QR CODE ]            │
│                                 │
│  20 September 2026              │
│  Batam                          │
└─────────────────────────────────┘
```

QR Code nantinya dapat digunakan panitia untuk melakukan:

```text
Scan Ticket
      │
      ▼
Validate Ticket
      │
      ▼
Check-in
      │
      ▼
Ticket = USED
```

---

# 10. E-Ticket PDF

Setelah pembayaran berhasil:

```text
Payment Success
      │
      ▼
Generate E-Ticket
      │
      ▼
Generate PDF
      │
      ├──────────────► Email
      │
      └──────────────► Website
```

PDF E-Ticket dikirim ke email yang digunakan saat pendaftaran.

Email berisi:

```text
Subject:
E-Ticket BravePay - [Nama Event]

Attachment:
BRAVEPAY-E-TICKET-XXXX.pdf
```

---

# 11. Email Notification

Email digunakan untuk:

### Registration

```text
Pendaftaran berhasil dibuat.

Order ID:
BRV-XXXX

Status:
Menunggu pembayaran.
```

### Payment Success

```text
Pembayaran berhasil.

Order ID:
BRV-XXXX

Total:
RpXXX.XXX

E-Ticket terlampir.
```

### Ticket Delivery

E-Ticket PDF dilampirkan pada email.

---

# 12. WhatsApp E-Ticket

BravePay memiliki fitur bantuan untuk user yang tidak menemukan E-Ticket di email.

Pada website tersedia icon bantuan:

```text
        ?
     Help / Bantuan
```

User dapat memasukkan nomor WhatsApp yang digunakan saat pendaftaran.

Contoh:

```text
┌─────────────────────────────┐
│       Bantuan E-Ticket      │
│                             │
│ Nomor WhatsApp              │
│ [ 08xxxxxxxxxx ]            │
│                             │
│ [ KIRIM E-TICKET ]          │
└─────────────────────────────┘
```

Sistem kemudian:

```text
Input No. WhatsApp
        │
        ▼
Search Order
        │
        ▼
Check Payment
        │
        ▼
Check E-Ticket
        │
        ▼
Send via WhatsApp
```

---

# 13. WhatsApp Delivery

Nomor WhatsApp digunakan untuk mencari transaksi peserta.

Contoh:

```text
08xxxxxxxxxx
       │
       ▼
Find Registration
       │
       ▼
Order Found
       │
       ▼
Payment = PAID
       │
       ▼
Find E-Ticket
       │
       ▼
Send WhatsApp
```

WhatsApp dapat mengirim:

```text
Halo Wahyu,

Berikut E-Ticket BravePay Anda.

Event:
Basket Competition

Ticket:
BRV-TKT-00001

Silakan gunakan E-Ticket tersebut saat melakukan check-in.

Terima kasih.
```

E-Ticket dapat dikirim sebagai:

* PDF
* Image
* Link E-Ticket

Implementasi WhatsApp akan menggunakan **WhatsApp API / WhatsApp Business Provider** yang dipilih pada tahap pengembangan.

---

# 14. Help Center

BravePay menyediakan floating help button.

Contoh:

```text
                         ┌─────┐
                         │  ?  │
                         └─────┘
```

Fungsi:

* Kirim ulang E-Ticket
* Cek status pembayaran
* Bantuan pendaftaran
* Bantuan pembayaran

Untuk MVP, fitur utama:

```text
[ Kirim Ulang E-Ticket ]
```

---

# 15. User Flow

## Complete User Flow

```text
START
  │
  ▼
Homepage
  │
  ▼
Competition List
  │
  ▼
Select Competition
  │
  ▼
Registration Form
  │
  ├── Name
  ├── Email
  ├── Phone
  ├── Date of Birth
  └── Number of Participants
  │
  ▼
Review Data
  │
  ▼
Create Order
  │
  ▼
Payment Page
  │
  ▼
QRIS
  │
  ▼
Scan & Pay
  │
  ▼
Payment Gateway
  │
  ▼
Payment Confirmation
  │
  ▼
Payment Success
  │
  ├──────────────┐
  ▼              ▼
Generate       Generate
E-Ticket       PDF
  │              │
  ▼              ▼
Website        Email
                 │
                 ▼
              E-Ticket
                 
Optional:
     │
     ▼
WhatsApp Help
     │
     ▼
Send E-Ticket
```

---

# 16. Admin System

Meskipun peserta tidak membutuhkan login, BravePay menyediakan **Admin Dashboard** untuk panitia.

Admin login diperlukan untuk mengelola sistem.

## Admin Features

### Dashboard

Menampilkan:

* Total pendaftaran
* Total peserta
* Total transaksi
* Total pembayaran berhasil
* Total pembayaran pending
* Total revenue
* Ticket check-in

---

## Competition Management

Admin dapat:

* Membuat lomba
* Mengubah lomba
* Menghapus lomba
* Mengaktifkan/nonaktifkan pendaftaran
* Mengatur harga
* Mengatur kuota
* Mengatur tanggal lomba
* Mengatur lokasi

---

## Registration Management

Admin dapat melihat:

```text
Order ID
Nama
Email
No. Telepon
Lomba
Jumlah Peserta
Total
Payment Status
Ticket Status
Tanggal Daftar
```

---

## Payment Management

Admin dapat melihat:

```text
Order ID
Payment ID
Amount
Payment Method
Payment Status
Paid At
```

---

## Ticket Management

Admin dapat:

* Melihat E-Ticket
* Download E-Ticket
* Resend E-Ticket
* Validasi ticket
* Check-in peserta

---

# 17. Ticket Check-in

Setiap E-Ticket memiliki QR Code unik.

Flow:

```text
Admin
  │
  ▼
Scan QR Code
  │
  ▼
BravePay
  │
  ▼
Validate Ticket
  │
  ├── Valid ──► Check-in
  │
  ├── Used ───► Already Checked-in
  │
  └── Invalid ─► Reject
```

Status ticket:

```text
ACTIVE
USED
CANCELLED
```

---

# 18. Database Concept

Database utama menggunakan relational database.

Entitas utama:

```text
USERS
COMPETITIONS
REGISTRATIONS
PARTICIPANTS
PAYMENTS
TICKETS
TICKET_CHECKINS
```

Relasi:

```text
COMPETITIONS
      │
      │ 1:N
      ▼
REGISTRATIONS
      │
      ├──────── 1:N ────────► PARTICIPANTS
      │
      ├──────── 1:1 ────────► PAYMENTS
      │
      └──────── 1:N ────────► TICKETS
                                  │
                                  │
                                  ▼
                           TICKET_CHECKINS
```

---

# 19. Recommended Database Structure

## competitions

```text
id
name
slug
description
poster
category
price
quota
event_date
location
status
created_at
updated_at
```

## registrations

```text
id
order_code
competition_id
email
phone
total_participants
total_amount
status
created_at
updated_at
```

## participants

```text
id
registration_id
name
date_of_birth
created_at
updated_at
```

## payments

```text
id
registration_id
payment_gateway
payment_reference
payment_method
amount
status
paid_at
expired_at
created_at
updated_at
```

## tickets

```text
id
registration_id
participant_id
ticket_code
qr_code
status
pdf_path
created_at
updated_at
```

## ticket_checkins

```text
id
ticket_id
checked_in_at
checked_in_by
created_at
updated_at
```

---

# 20. Technology Stack

## Backend

```text
Laravel
PHP
Laravel API
Laravel Queue
Laravel Mail
```

## Frontend

Untuk tahap awal dapat menggunakan:

```text
Laravel Blade
Tailwind CSS
Alpine.js
```

Frontend dapat dikembangkan menggunakan React pada tahap berikutnya apabila diperlukan.

## Database

```text
MySQL / MariaDB
```

## Payment

```text
Payment Gateway
QRIS
Webhook
```

## Email

```text
SMTP / Transactional Email Provider
```

## WhatsApp

```text
WhatsApp Business API
atau
WhatsApp API Provider
```

## PDF

Laravel akan menggunakan library PDF generator untuk membuat E-Ticket.

## QR Code

QR Code digunakan untuk:

```text
Ticket Identification
Ticket Validation
Check-in
```

---

# 21. Laravel Architecture

Recommended structure:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── CompetitionController.php
│   │   ├── RegistrationController.php
│   │   ├── PaymentController.php
│   │   ├── TicketController.php
│   │   └── WebhookController.php
│   │
│   └── Requests/
│
├── Models/
│   ├── Competition.php
│   ├── Registration.php
│   ├── Participant.php
│   ├── Payment.php
│   ├── Ticket.php
│   └── TicketCheckin.php
│
├── Services/
│   ├── PaymentService.php
│   ├── TicketService.php
│   ├── EmailService.php
│   └── WhatsAppService.php
│
└── Jobs/
    ├── GenerateTicket.php
    ├── SendTicketEmail.php
    └── SendTicketWhatsApp.php
```

---

# 22. API Concept

Endpoint utama:

```text
GET
/competitions
```

Menampilkan daftar lomba.

```text
GET
/competitions/{slug}
```

Menampilkan detail lomba.

```text
POST
/registrations
```

Membuat pendaftaran.

```text
POST
/payments
```

Membuat transaksi pembayaran.

```text
POST
/payment/webhook
```

Menerima notifikasi dari payment gateway.

```text
GET
/ticket/{ticket_code}
```

Menampilkan E-Ticket.

```text
POST
/help/resend-ticket
```

Mengirim ulang E-Ticket.

```text
POST
/tickets/check
```

Validasi ticket.

```text
POST
/tickets/{ticket}/check-in
```

Melakukan check-in.

---

# 23. Security

Karena peserta tidak menggunakan login, sistem harus memberikan perhatian khusus pada keamanan transaksi.

Implementasi:

* CSRF Protection
* Input Validation
* Rate Limiting
* Server-side Validation
* Webhook Signature Verification
* Unique Order Code
* Unique Ticket Code
* QR Code Validation
* Payment Status Verification
* Expired Payment Handling
* File Access Protection
* Admin Authentication

Nomor telepon tidak boleh menjadi satu-satunya kredensial untuk mengakses data sensitif.

Untuk fitur resend ticket, sebaiknya ditambahkan verifikasi tambahan seperti:

```text
Phone Number
+
Order Code / OTP
```

untuk mencegah orang lain mengambil E-Ticket hanya dengan mengetahui nomor WhatsApp.

---

# 24. Payment Security

Jangan menentukan:

```text
payment = PAID
```

hanya karena user kembali ke halaman:

```text
/payment/success
```

Status pembayaran harus diverifikasi melalui payment gateway.

Flow yang benar:

```text
User Payment
     │
     ▼
Payment Gateway
     │
     ▼
Webhook
     │
     ▼
BravePay
     │
     ▼
Verify Signature
     │
     ▼
Verify Amount
     │
     ▼
Update Payment
     │
     ▼
Generate Ticket
```

---

# 25. Order Code

Setiap transaksi memiliki kode unik.

Format yang direkomendasikan:

```text
BRV-YYYYMMDD-XXXX
```

Contoh:

```text
BRV-20260911-0001
BRV-20260911-0002
BRV-20260911-0003
```

---

# 26. Ticket Code

Setiap peserta mendapatkan ticket code unik.

Contoh:

```text
BRV-TKT-A8F92K
BRV-TKT-X92KD1
BRV-TKT-P7A21M
```

QR Code hanya menyimpan identifier yang diperlukan untuk validasi, bukan seluruh data pribadi peserta.

---

# 27. Queue & Background Jobs

Pengiriman E-Ticket sebaiknya tidak membuat user menunggu terlalu lama.

Setelah payment berhasil:

```text
Payment Success
      │
      ▼
Create Ticket
      │
      ▼
Queue Job
      │
      ├── Generate PDF
      │
      ├── Send Email
      │
      └── Send WhatsApp
```

Laravel Queue dapat digunakan untuk pekerjaan tersebut.

---

# 28. Error Handling

Contoh kondisi:

### Payment Pending

```text
Pembayaran masih menunggu konfirmasi.
```

### Payment Expired

```text
Pembayaran telah kedaluwarsa.
Silakan melakukan pendaftaran kembali.
```

### Payment Failed

```text
Pembayaran gagal.
Silakan coba kembali.
```

### Ticket Not Found

```text
E-Ticket tidak ditemukan.
Silakan periksa kembali data Anda.
```

### WhatsApp Not Found

```text
Tidak ditemukan E-Ticket
dengan nomor tersebut.
```

---

# 29. MVP Development

Pengembangan BravePay dilakukan bertahap.

## Phase 1 — Laravel Foundation

* Laravel setup
* Database
* Migration
* Model
* Seeder
* Basic frontend
* Competition CRUD

## Phase 2 — Registration

* Competition detail
* Registration form
* Multiple participants
* Order generation
* Validation

## Phase 3 — Payment

* Payment gateway
* QRIS
* Payment creation
* Webhook
* Payment status

## Phase 4 — E-Ticket

* Ticket generation
* QR Code
* PDF generation
* Ticket page

## Phase 5 — Notification

* Email notification
* PDF attachment
* WhatsApp integration
* Resend ticket

## Phase 6 — Admin

* Admin authentication
* Dashboard
* Registration management
* Payment management
* Ticket management
* Check-in scanner

## Phase 7 — Security & Deployment

* Rate limiting
* Webhook security
* File security
* Queue
* Logging
* Production environment
* Backup
* Monitoring

---

# 30. Final System Flow

```text
                         BRAVEPAY
                            │
                            ▼
                       Landing Page
                            │
                            ▼
                    Pilih Competition
                            │
                            ▼
                    Registration Form
                            │
              ┌─────────────┴─────────────┐
              │                           │
           Contact                    Participants
              │                           │
              ├── Email                   ├── Name
              └── Phone                   └── Date of Birth
                            │
                            ▼
                       Review Order
                            │
                            ▼
                      Create Order
                            │
                            ▼
                      Payment Page
                            │
                            ▼
                          QRIS
                            │
                            ▼
                     Payment Gateway
                            │
                            ▼
                         Webhook
                            │
                            ▼
                    Payment Verified
                            │
                            ▼
                       PAID
                            │
                            ▼
                    Generate E-Ticket
                            │
              ┌─────────────┼─────────────┐
              │             │             │
              ▼             ▼             ▼
           Website        Email       WhatsApp
                            │
                            ▼
                       PDF E-Ticket
                            │
                            ▼
                         Event Day
                            │
                            ▼
                       Scan QR Code
                            │
                            ▼
                      Validate Ticket
                            │
                    ┌───────┴───────┐
                    │               │
                  VALID           INVALID
                    │
                    ▼
                 CHECK-IN
```

---

# 31. Project Goal

BravePay diharapkan menjadi platform pembayaran dan pendaftaran lomba yang:

* **Simple** — peserta tidak perlu login.
* **Fast** — proses pendaftaran singkat.
* **Secure** — pembayaran diverifikasi melalui webhook.
* **Automated** — E-Ticket dibuat otomatis.
* **Accessible** — E-Ticket tersedia melalui website, email, dan WhatsApp.
* **Scalable** — dapat digunakan untuk banyak event dan lomba.
* **Manageable** — panitia memiliki dashboard untuk mengelola pendaftaran, pembayaran, dan check-in.

---

## Project Identity

**Project Name:** BravePay

**Type:** Event Registration & Payment Platform

**Main Function:** Registration, QRIS Payment & E-Ticket

**Primary Users:**

* Participant
* Admin / Event Organizer

**Core Flow:**

```text
Register
→ Pay
→ Verify
→ Generate Ticket
→ Deliver Ticket
→ Check-in
```

---

## Status

```text
Project Status: Development
Version: 0.1.0
```

BravePay dikembangkan secara bertahap dengan prioritas pada **registration → payment → E-Ticket → notification → check-in**.