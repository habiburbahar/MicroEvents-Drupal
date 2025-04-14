
---

## 📦 What’s Included

### `microevents-tracker/`
This is the backend API service built with PHP. It contains:
- **REST APIs** to serve event and notification data
- **MariaDB integration**
- **`init.sql`** to initialize the database
- Docker setup for easy deployment

### `drupal-microevents/`
This is the frontend built with **Drupal 10**. It contains:
- A **custom module** called `event_api_block`
- Two custom blocks:
  - `EventBlock` – Fetches and displays event data from the backend API
  - `NotificationBlock` – Fetches and displays notification data
- Uses **Guzzle** HTTP client to make API calls
- Twig templating planned for better frontend rendering (coming soon)

---

## 🧠 Why Separate Folders?

- `microevents-tracker/` is the backend microservice responsible for handling and serving event data.
- `drupal-microevents/` is the Drupal site where the data is consumed and displayed.
- Keeping them separate follows **microservice architecture**, allowing each part to be developed, tested, and deployed independently.

---

## 🚀 How It Works

1. The backend REST API returns JSON data about events and notifications.
2. The Drupal custom module uses Guzzle to fetch this data.
3. Two blocks (`EventBlock` and `NotificationBlock`) display the data on the Drupal site.
4. The backend and frontend communicate via HTTP calls over defined endpoints.

---

## ✅ Features Implemented

- ✅ Custom Drupal module
- ✅ External REST API integration using Guzzle
- ✅ Service class structure (`EventApiClient`, `NotificationApiClient`)
- ✅ Clean module service registration (`.services.yml`)
- ✅ Docker-ready setup for the backend API

---

## 🛠️ Future Improvements

- 🔧 Add Twig templating for cleaner frontend output
- 🔧 Add unit tests for service classes
- 🔧 CI/CD integration for automated builds

---

## 🙋‍♂️ Author

**Habibur Rahman Bahar**  
Computer Science Graduate | Full-Stack Developer  
[Portfolio](https://habiburbahar.github.io/portfolio) | [LinkedIn](https://linkedin.com/in/habibur-rahman-bahar)

---

Let me know if you want this in `.md` file format or added to your actual project!
