
# URL Shortener

To get setup pull the repo and copy the .env.example file and customize then run make start. Check the Makefile to find out what the make commands are doing before running them.
Database is set to NULL in the .env file as it's persisted in memory using redis.

The lifetime of cache can be configured with environment variable `CODE_CACHE_EXPIRATION` which is currently set to 1 day. The length of the code returned can also be configured with environment variable `SHORTENED_CODE_LENGTH` which is defaulted to 6 which allows for billions of possible codes. The lowest I'd recommend is 4 which is still millions of possible codes but a higher number is safer.

Clone and go into directory:
```bash
git clone git@github.com:Brennii96/url-shortener-api.git && cd url-shortener-api
```
Copy example env to own:
```bash
cp .env.example .env
```
Run helper docker up command:
```bash
make start
```
Run composer install and generate key
```aiignore
make composer-install && make generate-key
```

## Authors

- [@brennii96](https://www.github.com/brennii96)


---

## Endpoints

For ease, I've included an example Postman collection which can be imported and run. Found in `Encode-Decode.postman_collection.json`

To run the Unit Tests simply start the container:
```bash
make start
```
Then run the tests:
```bash
make run-tests
```

### 1. Encode URL

- **URL**: `/api/encode`
- **Method**: `POST`
- **Description**: Accepts a URL and returns a shortened version of the URL.

#### Request Example:
```bash
POST /api/shorten
Content-Type: application/json

{
  "url": "https://example.com"
}
```

#### Response Example (Success):
```json
{
  "success": true,
  "shortUrl": "http:/localhost:8000/abc123",
  "originalUrl": "https://example.com"
}
```
#### Response Example (Success):
```json
{
  "success": true,
  "shortUrl": "http:/localhost:8000/abc123",
  "originalUrl": "https://example.com"
}
```
#### Response Example (Validation Error)
```json
{
  "message": "The url field is required."
}
```
- **HTTP Status Code**: 201 Created (on success), 422 Unprocessable Entity (on validation error)

### 2. Decode URL

- **URL**: `/api/decode`
- **Method**: `POST`
- **Description**: Accepts a code and returns the original URL.
#### Request Example:
```bash
POST /api/decode
Content-Type: application/json

{
  "key": "https://example.com"
}
```
#### Response Example (Success):
```json
{
  "success": true,
  "originalUrl": "https://example.com"
}
```
#### Response Example (Not Found)
```json
{
  "success": false,
  "message": "Short URL not found."
}
```
- **HTTP Status Code**: 200 OK (on success), 404 Not Found (if the shortened code doesn't exist)
