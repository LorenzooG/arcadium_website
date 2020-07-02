FROM ubuntu:latest

RUN apt-get install node npm

WORKDIR /var/www

COPY * ./

RUN npm install

RUN npm run echo start
