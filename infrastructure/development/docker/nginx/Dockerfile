FROM almasry/nginx-stable:latest

COPY site.conf /etc/nginx/conf.d/default.conf


EXPOSE 14900 443

CMD ["nginx", "-g", "daemon off;"]