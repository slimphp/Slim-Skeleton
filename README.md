# Dynamo app

### Install the Application

Run following command to install app
```bash
composer install
```

### Start application

Run docker compose in detach mode. 
First time running will take some time, since it will build up the container.
```bash
docker-compose up -d
```
When done, open `http://localhost:8080` in your browser.

### Tests
Run following command for running test
```bash
composer test
```

