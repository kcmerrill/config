<h1>Config</h1>
A super simple but powerful configuration class.

Organize settings in multiple ways. External files, or via array access. 

```php
//.config file within /config/
[db]
user_name = 'root';
password = 'password'
// ...


$app_config = new kcmerrill\utility\config(__DIR__ . '/config/');
$app_config->set('php.hello.world', 'hello_world!');
echo $app_config['db']['user_name'] . ' is my db username configuration!';
echo $app_config->c('whatever.you.set') . ' is my configuration!';
echo $app_config->c('php.hello.world') . is my configuration!';
```