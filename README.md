# Laravel Scout `pgvector` Demo

A demonstration of semantic similarity search in Laravel using pgvector, OpenAI embeddings, and Laravel Scout.

## 🚀 Features

- Semantic search powered by pgvector and OpenAI embeddings
- Full Laravel Scout integration
- Restaurant search demo with intelligent matching
- Docker-ready with Laravel Sail
- An API Platform interface for easy testing

## ⚡️ Quick Start

1. Clone the repository
2. Copy configuration file:
   ```sh
   cp .env.example .env
   ```

3. Add your OpenAI API key to .env:
   ```
   OPENAI_API_KEY=your-key-here
   ```

4. Start the containers:
   ```sh
   ./vendor/bin/sail up -d
   ```

5. Initialize the application:
   ```sh
   sail artisan key:generate
   sail artisan migrate:fresh --seed
   ```

6. Start the queue worker:
   ```sh
   sail artisan queue:work --queue=scout,default
   ```

7. Import the restaurant data:
   ```sh
   sail artisan scout:import "App\Models\Restaurant"
   ```

## 🔍 Demo Examples

Start a Tinker session:
```sh
sail artisan tinker
```

Try these semantic searches:

```php
// Find sushi restaurants by searching for a sushi type
App\Models\Restaurant::search('Nigiri')->get();

// Find Chicago restaurants using the city's nickname
App\Models\Restaurant::search('Windy City')->get();
```

The searches will return relevant results even when the exact terms don't appear in the restaurant names, thanks to semantic matching via OpenAI's embeddings.

## 🔌 API Endpoints

Once the application is running, you can access the API Platform interface at `http://laravel.test/api` to try out these endpoints:

### Search Restaurants
`POST /api/restaurants/search`
```json
{
  "query": "Italian"
}
```
This endpoint lets you perform semantic searches for restaurants based on cuisine, city, or other attributes.

### Chat with Restaurant data
`POST /api/restaurants/chat`
```json
{
  "message": "Give me a sushi restaurant suggestion"
}
```

This endpoint provides an AI-powered restaurant chatbot (provided you supply an OpenAI key) that can give personalised recommendations.

Additional endpoints:
- `GET /api/restaurants`: List all restaurants
- `GET /api/restaurants/{id}`: Get details of a specific restaurant

All endpoints are fully documented in the API Platform interface with example requests and responses.

## 🙏 Acknowledgments

Special thanks to Ben Bjurstrom for creating the [pgvector-scout](https://github.com/pgvector/pgvector-scout) package that powers this demo.

## 📝 Requirements

- Docker
- OpenAI API key

## 📖 How It Works

The application uses OpenAI's API to generate vector embeddings for restaurant data. These embeddings are stored using PostgreSQL's pgvector extension, enabling semantic similarity searches through Laravel Scout.

When you search, the query text is converted to a vector embedding and pgvector finds the closest matches in the database, allowing for intelligent, meaning-based searches rather than simple text matching.