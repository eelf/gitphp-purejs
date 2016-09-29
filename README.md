# GitPHP
A web based git repository browser written in PHP as a Single Page Application

#### example of nginx config
```
worker_processes  1;

events {
    worker_connections  1024;
}

http {
    include            mime.types;
    default_type       application/octet-stream;
    sendfile           on;
    keepalive_timeout  65;

    server {
        listen      80;
        server_name git.example.com;
        root        /sites/git.example.com/www;

        location / {
            try_files $uri /index.html;
        }
        location /app/ {
            fastcgi_pass  unix:///tmp/php-fpm.sock;
            fastcgi_pass  127.0.0.1:9000;
            include       fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root/app.php;
            fastcgi_intercept_errors on;
        }
    }
}
```

## TODO
Review:
reply to comments (threaded comments)
comment on deleted or previously existing lines
format your comment
save individual comments
mark a comment as a just a suggestion or a defect
perform incremental reviews (just the delta that has changed)
add file specific review (comment for whole file, not specific line) resolution

Code formatting:
Ease of use when copying code to clipboard
syntax highlight
chose formatting schemes

Gitosis-admin:
manage pre-commit hooks

Unknown:
Ability to appoint most suitable peer
Ability to identify review requester
Reasonable speed to index whole repository
Reasonable speed to index individual commit
Ability to aggregate overview for dashboard
Stability of branchdiff (JS)
Visibility of review distribution
Abiltiy to request someone else's opinion (other than committer and reviewer)
