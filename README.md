
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
git clone [https://github.com/your-username/unicrib.git](https://github.com/your-username/unicrib.git)
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

# Meta / Gemini AI Integration
META_API_KEY=your_google_gemini_api_key
META_API_BASE_URL=[https://generativelanguage.googleapis.com/v1beta/openai/](https://generativelanguage.googleapis.com/v1beta/openai/)
META_MODEL_NAME=gemini-2.5-flash

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

## 🧪 Testing the AI Agents Locally (via cURL)

You can test your WhatsApp and Telegram AI tool gateway locally without needing active external webhook configurations.

### Test Telegram Simulation Endpoint:

```bash
curl -X POST [http://127.0.0.1:8000/api/telegram/simulate](http://127.0.0.1:8000/api/telegram/simulate) \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "chat_id": "123456789",
    "message": "Find a bedsitter in Kutus under 10000 KSh"
  }'

```

*(Note: Ensure you have linked your test user's `telegram_chat_id` to `"123456789"` in your database, or use the `/link <phone>` command).*

### Test WhatsApp Simulation Endpoint:

```bash
curl -X POST [http://127.0.0.1:8000/whatsapp/simulate](http://127.0.0.1:8000/whatsapp/simulate) \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phone": "254704969006",
    "message": "Find a bedsitter in Kutus under 10000 KSh"
  }'

```

---

## 🛡️ License

This project is proprietary software developed for student housing management in Kenya. All rights reserved.

```

```