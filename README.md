# Lazy-write sessions for Laravel 4.2

## Rational

The Laravel's sessions implementation overwrites session data at end of each request, and that produces problems when
executing a lot of requests simultaneously. 

One of possible solutions for that problem is to write session data only when it has been changed. Such an approach greatly 
reduces the risk of conflicting updates of the session data.
 
So, this implementation of sessions writes the session data only when the following conditions are met:
1. either new or old flash data are not empty
2. any attribute in the session data has been changed, added, or removed

### Implications/drawbacks

The session's metadata object will contain wrong information about the session's last used time. Hopefully, that information
is not used in Laravel 4.2.

## Installation

Make these changed to ```composer.json``` file:
```json
    "repositories": [
        //...
        {
            "url": "https://github.com/dfbag7/session-lazy-write.git",
            "type": "git"
        }
    ],
    "require": {
        // ...
        "dfbag7/session-lazy-write": "dev-master"
    },
    //...
```
Then run ```composer update```

In ```app\config\app.php```, replace line 
```php
        'Illuminate\Session\SessionServiceProvider',
``` 
with this: 
```php
        'Dfbag7\SessionLazyWrite\SessionLazyWriteServiceProvider',
```
