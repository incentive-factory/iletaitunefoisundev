FROM node:lts-alpine
ARG NODE_DOCKER_PORT

RUN useradd -d /home/node_user -m -s /bin/bash node_user
USER node_user

HEALTHCHECK CMD curl --fail http://localhost:${NODE_DOCKER_PORT}/ || exit 1


WORKDIR /var/www/client
COPY client/package*.json /var/www/client/

RUN npm install -g npm@latest
RUN npm uninstall -g @angular/cli
RUN npm cache clean --force
RUN npm install -g @angular/cli@latest
RUN npm ci

RUN apk add chromium
RUN apk add chromium-chromedriver
ENV CHROME_BIN=/usr/bin/chromium-browser

EXPOSE ${NODE_DOCKER_PORT}
CMD ["ng", "serve", "--host", "0.0.0.0"]
