FROM nginx:alpine
ARG NGINX_DOCKER_PORT


RUN useradd -d /home/nginx_user -m -s /bin/bash nginx_user
USER nginx_user

HEALTHCHECK CMD curl --fail http://localhost:${NGINX_DOCKER_PORT}/ || exit 1

RUN apk update \
    && apk add git curl vim wget bash acl


COPY nginx.conf /etc/nginx/
RUN rm /etc/nginx/conf.d/default.conf

RUN HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1) \
    && setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var \
    && setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
