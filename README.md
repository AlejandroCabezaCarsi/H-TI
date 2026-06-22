# Live Poll API

Backend API for a small live voting/poll application. The frontend is intentionally kept separate and can consume this API from another local project.

## Stack

- PHP 8.3+
- Laravel 13
- SQLite by default for local development
- PHPUnit for feature tests

## Architecture

The poll feature follows a clear Laravel application structure:

- `routes/api.php`: public REST API routes.
- `app/Http/Requests/Polls`: request validation and input normalization.
- `app/Http/Controllers/Api`: thin controllers that coordinate requests, actions, and queries.
- `app/Actions/Polls`: write use cases such as creating polls and casting votes.
- `app/Queries/Polls`: read/query response shaping for voting and result views.
- `app/Models`: Eloquent models with constants for table and column names used across migrations and code.

## Local Setup

Install dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

For SQLite local development, create the database file if it does not exist:

```bash
touch database/database.sqlite
```

On Windows PowerShell:

```powershell
New-Item database/database.sqlite -ItemType File -Force
```

Run migrations:

```bash
php artisan migrate
```

Start the API server:

```bash
php artisan serve
```

The API will be available at:

```text
http://127.0.0.1:8000/api
```

## Frontend CORS

For a separate frontend project, configure allowed origins in `.env`:

```env
CORS_ALLOWED_ORIGINS=http://localhost:5173
```

Multiple origins can be comma-separated.

## API Endpoints

### Create Poll

```http
POST /api/polls
```

Request:

```json
{
  "question": "Where should we have lunch?",
  "options": ["Pizza", "Sushi", "Burgers"]
}
```

Response: `201 Created`

```json
{
  "data": {
    "id": "01J...",
    "question": "Where should we have lunch?",
    "options": [
      { "id": "01J...", "text": "Pizza" }
    ]
  }
}
```

### Get Poll For Voting

```http
GET /api/polls/{poll}
```

Returns the poll question and answer options.

### Cast Vote

```http
POST /api/polls/{poll}/votes
```

Request:

```json
{
  "poll_option_id": "01J..."
}
```

Response: `201 Created`, with the updated results payload.

### Get Results

```http
GET /api/polls/{poll}/results
```

Response:

```json
{
  "data": {
    "id": "01J...",
    "question": "Where should we have lunch?",
    "total_votes": 1,
    "options": [
      {
        "id": "01J...",
        "text": "Pizza",
        "votes_count": 1,
        "percentage": 100
      }
    ]
  }
}
```

## Data Model

- `polls`: stores the poll question.
- `poll_options`: stores 2-5 options per poll.
- `votes`: stores each vote event, linked to both poll and option.

The app stores votes as rows instead of incrementing counters directly. This keeps an auditable vote history and lets result counts be derived with queries.

## Validation

Poll creation validates:

- question is required, string, 5-255 characters
- options is required, array, 2-5 items
- each option is required, string, unique, max 120 characters

Vote creation validates:

- `poll_option_id` is required and must be a ULID
- selected option must belong to the poll being voted on

## Tests

Run the test suite:

```bash
php artisan test
```

Current coverage includes the main API flow:

- create poll
- fetch poll for voting
- cast vote
- fetch results
- reject voting with an option from a different poll

## Trade-offs

- No authentication, per challenge requirements.
- Duplicate vote prevention is not implemented yet. A frontend can store a local flag, but robust prevention would need identity, sessions, signed links, or rate limiting.
- Results can be live-updated by the separate frontend with polling against `GET /api/polls/{poll}/results`.
- SQLite is enough for local development. For production or heavier concurrency, PostgreSQL plus transactional writes and indexes would be the next step.
