[pwa]: https://developers.google.com/web/progressive-web-apps/
[service-worker]: service-worker.js
[atom-editor]: https://atom.io/
[apc-stats]: https://web.senorcontento.com/apc/
[issues-tab]: https://github.com/bgbrandongomez/self-hosted-blog/issues
[PHPMyAdmin-fails]: https://web.senorcontento.com/test/failed_pma/
[material-design]: https://material.io/

# What Is This?

This is the server side code to my website. I am making my code available to the public so any potential future employers can see my work. I am currently developing for CSCI 3000 (Web Development) class at University of North Georgia in Dahlonega, Georgia. The class is individual work only, so you are only going to see commits from me as I am not allowed to work in a team, but, it will still be a fun project to work on. I am also adding features which are outside of the scope of the class such as having a [Javascript Service Worker][service-worker] so I can make a [Progressive Web App][pwa]. A current idea I am working on, after I finish the CSS related projects for my teacher, is changing my site's theme to use [Material Design][material-design]. It will help me make my website look more like an app which will be awesome!!!

This repository is auto-updated everytime I push a commit from my editor ([Atom][atom-editor]) to my git repository stored on the server itself. The git repository check's out the latest commit to the webserver root and then pushes a copy of its commits to the Github repo (which I manually added an origin for over ssh).

## Git Hook (post-receive):

```bash
#!/bin/sh
GIT_WORK_TREE=/var/www/class git checkout -f
git push
```

The server runs on a Raspberry Pi 3. It is connected to an APC UPS which can display [battery stats][apc-stats] so the server knows when it needs to shutdown.

### Security Flaws?

Now, I know what some people are thinking... Wouldn't posting the server code make it easier to hack the server? Technically, yes, but at the same time, a good samaritan could find a security flaw that I didn't notice. That good samaritan could then post the flaw to the [issues tab][issues-tab] and I would know to fix the flaw right away.

Even then, security by obscurity is just simply not security, so, I shouldn't avoid posting the code because I think hiding the code will somehow make me more secure. You can see an example of where a bot tried to hack my [PHPMyAdmin installation][PHPMyAdmin-fails]. It failed because I disabled the root account on MySQL.

Obviously, when I start using MySQL, I am not storing the username or password in the source code itself or anywhere in webroot, but it will be loaded via config, probably through the fastcgi_param option (unless security or something else dictates otherwise). This is to prevent accidentally leaking data which could weaken the security of my website.

I have decided to put a section of my NGinx config so others can setup a copy of the site in a similar fashion to how mine is set up. The php config only has the user and group changed to the web user and group.

## NGinx Config as of January 28th, 2019:

```nginx
charset utf-8;

error_page  401 /errors/401/index.php;
error_page  403 /errors/403/index.php;
error_page  404 /errors/404/index.php;
error_page  451 /errors/451/index.php;

error_page  500 /errors/500/index.php;
error_page  503 /errors/503/index.php;

location / {
	# First, attempt to serve request as file, then as a directory,
	# then fall back to displaying a 404 error.
	try_files $uri $uri/ =404;
}

location ~ /(server-data) {
	deny all;
	return 404;
}

location ~ ^/ReadMe.md$ {
	deny all;
	return 404;
}

location ~ ^/LICENSE.md$ {
	deny all;
	return 404;
}

# pass PHP scripts to FastCGI server
location ~ \.php$ {
	fastcgi_param alex.server.type "production";
	fastcgi_param alex.server.name "rover-class";
	fastcgi_param alex.server.host "https://web.senorcontento.com";

	include snippets/fastcgi-php.conf;
	fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
}
```