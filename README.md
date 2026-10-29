
```markdown
# 🏡 Unicrib - Student Housing, Storage & Transport Platform

Unicrib is a robust, multi-channel student accommodation and logistics platform built with **Laravel**, integrated with **Gemini AI** (via OpenAI-compatible proxy endpoints), and supporting multi-channel AI agents on **WhatsApp** and **Telegram**.

---

## 🚀 Key Features
* **Multi-Channel AI Agents**: Fully functional AI agents running on both WhatsApp and Telegram, capable of tool calling and multimodal image interpretation.
* **Intelligent Tool Gateway**: Maps AI intents automatically to local controllers for housing search, transport status tracking, and storage management.
* **Multimodal Vision Analysis**: Analyzes property photos sent via chat to extract rent estimates, bedroom counts, architectural types, and student suitability.
* **Role-Based Workflows**: Tailored experiences for Students (booking, verification, payments) and Landlords (listing management, booking approvals).
* **Automated Routing & Maps**: Integrated Geoapify/Leaflet routing for transport dispatch.

---

## 🛠️ Tech Stack
* **Backend**: PHP 8.2+, Laravel Framework
* **Frontend**: Blade templates, Livewire, Tailwind CSS
* **AI Engine**: Gemini 2.5 Flash / Pro (via OpenAI-compatible endpoints & Google GenAI SDK)
* **Databases**: PostgreSQL / MySQL
* **Messaging APIs**: WhatsApp Cloud API & Telegram Bot API

---

## 📦 Local Setup & Installation

Follow these steps to set up and run Unicrib on a new computer:

### 1. Clone the Repository
```bash
git clone [https://github.comfloppy-piece/unicrib.git]
cd unicrib

```

### 2. Install PHP Dependencies

```bash
composer install

```

### 3. Install Node.js Dependencies & Build Assets

```bash
npm install
npm run build

```

### 4. Configure Environment Variables

Copy the example environment file and generate your application key:

```bash
cp .env.example .env
php artisan key:generate

```

Open your `.env` file and configure your database and AI credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=unicrib
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Meta AI Integration
META_API_KEY=your_meta_api_key
META_API_BASE_URL=[https://generativelanguage.googleapis.com/v1beta/openai/]
META_MODEL_NAME=muse-spark

# Telegram Bot Integration
TELEGRAM_BOT_TOKEN=your_telegram_bot_token
TELEGRAM_SECRET_TOKEN=your_telegram_webhook_secret

# WhatsApp Cloud API Integration
WHATSAPP_TOKEN=your_whatsapp_access_token
WHATSAPP_PHONE_NUMBER_ID=your_whatsapp_phone_id
WHATSAPP_WEBHOOK_VERIFY_TOKEN=your_webhook_verify_token

```

### 5. Run Database Migrations & Seeders

```bash
php artisan migrate --seed

```

### 6. Start the Local Development Server

```bash
php artisan serve

```

Your app will be available at `http://127.0.0.1:8000`.

---


## 🛡️ License

This project is proprietary software developed for student housing management in Kenya. All rights reserved.

```

```