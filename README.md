Shop: Users Service
--
### Description:
This service handles users functionality, including CRUDs, processing and handling all logic related to users.

---

### Installation:

1. Clone the repository:
```shell script
git clone git@gitlab.com:shop_ecommerce/shop_users.git
```

2- Rename the file “.env.example” under “/” to “.env” then change the parameters to match your preferences, example:
```yaml
APP_NAME=Lumen
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC
...
```
And so on for Redis and RabbitMQ ...
>Note: You can use network (marketplace-network) gateway ip instead of providing each container ip

Now, you have two options:

Option 1:
1. Login to docker registry provider, in order to pull this microservice docker image:
```bash
docker login registry.gitlab.com
```
Provide your username and password on gitlab, you should have access to the project, so you can pull the image.

2. Pull the docker image from container registry:
```bash
docker pull registry.gitlab.com/shop_ecommerce/shop_users
```

Option 2:
1. Build a new image (for x64 arch):
```bash
docker build -t registry.gitlab.com/shop_ecommerce/shop_users .
```

3- Run `docker-compose up -d`, This command will create new containers:

1. shop_users_users-sync_1
- This will declare a new queue “users_sync” in RabbitMQ queues list
2. shop_users_users-async_1
- This will declare a new queue “users_async” in RabbitMQ queues list
3. shop_users_users-api_1
- This will start a new application server listening on a specific port specified in `docker-compose.yml` file, you can access it by going to this URL: [http://localhost:port](http://localhost:1003)
- As a default, the port value is 1003.
- You can use Postman with the collections provided to test microservice APIs.
4. shop_users_users-unit-test_1
- This will run the unit test for this microservice.


If you want to scale up the workers (sync / async), you can simply run this command:
```bash
docker-compose up --scale users-{sync/async}=num -d
```

Where “num” is the number of processes to run, {sync/async} is the service which you want to scale up, example:
```bash
docker-compose up --scale users-async=3 -d
```

---
### Unit test
To run the unit test, just run this command:
```bash
docker-compose up users-unit-test
```
