# Content Scheduler

A powerful social media content scheduling application built with Laravel. Content Scheduler allows users to create, schedule, and publish content across multiple social media platforms from a single dashboard.

## Features

-   **Calendar View**: Visual calendar showing all scheduled posts
-   **List View**: Detailed list of upcoming and past posts
-   **Multi-platform Support**: Connect and publish to various social media platforms
-   **Content Creation**: Easy-to-use post editor with character counter and image uploads
-   **Scheduling**: Set specific dates and times for post publishing
-   **Platform Management**: Connect, disconnect, and manage platform credentials
-   **Auto Publishing**: Automatic publishing of scheduled content
-   **Filtering**: Filter posts by platform, status, or search term

## Screenshots

![Dashboard Calendar View](path/to/calendar-screenshot.png)
![Post Creation](path/to/post-creation-screenshot.png)
![Settings Page](path/to/settings-screenshot.png)

## Tech Stack

-   **Framework**: Laravel 12
-   **Frontend**: Blade templates with Tailwind CSS
-   **JavaScript**: Alpine.js for interactive components
-   **Database**: MySQL
-   **Authentication**: Laravel's built-in authentication

## Installation

### Requirements

-   PHP 8.1+
-   Composer
-   Node.js & NPM
-   MySQL or PostgreSQL

### Setup Steps

1. Clone the repository:

    ```bash
    git clone https://github.com/if12is/Content-Scheduler.git
    cd Content-Scheduler
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install frontend dependencies:

    ```bash
    npm install
    ```

4. Copy the environment file:

    ```bash
    cp .env.example .env
    ```

5. Configure your database in the `.env` file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=content_scheduler
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6. Generate application key:

    ```bash
    php artisan key:generate
    ```

7. Run database migrations:

    ```bash
    php artisan migrate
    ```

8. Compile assets:

    ```bash
    npm run dev
    ```

9. Start the development server:

    ```bash
    php artisan serve
    ```

10. Visit [http://localhost:8000](http://localhost:8000) in your browser

## Usage

### Dashboard

The dashboard provides two views:

-   **Calendar View**: A monthly calendar showing all scheduled posts
-   **List View**: A filterable list of upcoming posts

### Creating Posts

1. Click "Create Post" button from the dashboard
2. Fill in the post details:
    - Title
    - Content
    - Select platform
    - Upload image (optional)
    - Set scheduled date and time
3. Save as draft or schedule for publishing

### Managing Platforms

1. Navigate to the Settings page
2. Connect to platforms using your API credentials
3. Manage existing platform connections

## Database Structure

The application uses the following key models:

-   **User**: Authenticated users
-   **Post**: Content to be published
-   **Platform**: Supported social media platforms
-   **UserPlatform**: Connection between users and platforms
-   **PostPlatform**: Relationship between posts and platforms
-   **ActivityLog**: System activity logging

## API Documentation

A full REST API is available for programmatic access. You can import the latest Postman collection here:

[![Postman Collection](https://img.shields.io/badge/Postman-View%20Collection-orange?logo=postman)](https://ahmed-9464583.postman.co/workspace/Ahmed's-Workspace~c43dcbc3-0fa1-494a-892f-64e670144e40/collection/44910971-9995fdc5-340c-43e6-8196-d5ab8e0f0336?action=share&creator=44910971)

### Main Endpoints

#### Authentication

-   `POST /api/register` — Register a new user
-   `POST /api/login` — Log in and receive an auth token
-   `POST /api/logout` — Log out the authenticated user
-   `GET /api/user` — Get the authenticated user's profile

#### Posts

-   `GET /api/posts` — List posts (with filters)
-   `GET /api/posts/{id}` — Get a single post
-   `POST /api/posts` — Create a post (fields: title, content, platform, scheduled_time, status, image_url)
-   `PUT /api/posts/{id}` — Update a post (partial updates supported)
-   `DELETE /api/posts/{id}` — Delete a post

#### Platforms

-   `GET /api/platforms` — List all available platforms
-   `GET /api/user/platforms` — List platforms connected to the current user
-   `POST /api/platforms/{platform}/toggle` — Toggle a platform's active status for the user

### Example: Create Post

```http
POST /api/posts
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
  "title": "Sample Post",
  "content": "This is a sample post content.",
  "platform": "twitter",
  "scheduled_time": "2025-06-01 12:30:00",
  "status": "scheduled",
  "image_url": <file>
}
```

### Example: Toggle Platform

```http
POST /api/platforms/1/toggle
Authorization: Bearer {token}
```

**Response:**

```json
{
    "message": "Platform Twitter has been activated",
    "is_active": true
}
```

For more details and sample requests, see the [Postman Collection](https://ahmed-9464583.postman.co/workspace/Ahmed's-Workspace~c43dcbc3-0fa1-494a-892f-64e670144e40/collection/44910971-9995fdc5-340c-43e6-8196-d5ab8e0f0336?action=share&creator=44910971).

## More Info

-   **GitHub Repository:** [https://github.com/if12is/Content-Scheduler.git](https://github.com/if12is/Content-Scheduler.git)
-   **API Postman Collection:** [https://ahmed-9464583.postman.co/workspace/Ahmed's-Workspace~c43dcbc3-0fa1-494a-892f-64e670144e40/collection/44910971-9995fdc5-340c-43e6-8196-d5ab8e0f0336?action=share&creator=44910971](https://ahmed-9464583.postman.co/workspace/Ahmed's-Workspace~c43dcbc3-0fa1-494a-892f-64e670144e40/collection/44910971-9995fdc5-340c-43e6-8196-d5ab8e0f0336?action=share&creator=44910971)

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

Developed by [Ahmed](https://github.com/if12is)

## Contact

For questions or feedback, please open an issue on GitHub or contact us at example@example.com.
