<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INF 653 Midterm API - Autumn Wertz</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        code { background: #eee; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
        h1 { border-bottom: 2px solid #005a8d; padding-bottom: 10px; color: #005a8d; }
        .endpoint { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
        .method { font-weight: bold; color: #28a745; }
        a { color: #005a8d; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>INF 653 Midterm: Quotes API</h1>
    <p>This is a RESTful API serving quotes, authors, and categories from a PostgreSQL database hosted on Render.</p>

    <h2>Available Endpoints</h2>

    <div class="endpoint">
        <p><span class="method">GET</span> <a href="/api/quotes/"><code>/api/quotes/</code></a> - List all quotes</p>
        <p><span class="method">GET</span> <a href="/api/quotes/?random=true"><code>/api/quotes/?random=true</code></a> - <strong>Extra Credit:</strong> Get a random quote</p>
    </div>

    <div class="endpoint">
        <p><span class="method">GET</span> <a href="/api/authors/"><code>/api/authors/</code></a> - List all authors</p>
        <p><span class="method">GET</span> <a href="/api/categories/"><code>/api/categories/</code></a> - List all categories</p>
    </div>

    <p><small>Note: If the API is slow to respond, it is likely spinning up from a cold start on Render's free tier.</small></p>
</body>
</html>
