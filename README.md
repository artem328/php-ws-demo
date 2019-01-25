PHP Websocket Demo
==================

First build docker container
```bash
docker build --tag=ws .
```

You might need to specify `--build-arg=HOST_USER_ID=${UID}` 
while build container to have synchronized file permissions with your local machine

Then run
```bash
docker run -v $(pwd):/var/www/html -p 8080:8080 -it ws
```

In container you can run
```bash
bin/console list | grep server:run
```
to see available server commands

After you run one of the servers, open `public/index.html` in your browser
