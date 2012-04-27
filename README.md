# Installation

### Step 1: Download / clone the following plugins: 

 * **Aweber** to _Plugin/Aweber_
 * [HttpSocketOauth plugin](https://github.com/ProLoser/http_socket_oauth) (ProLoser fork) to _Plugin/HttpSocketOauth_
 * [Apis plugin](https://github.com/ProLoser/CakePHP-Api-Datasources) to _Plugin/Apis_

### Step 2: Setup your `database.php`

```
var $aweber = array(
	'datasource' => 'Aweber.Aweber',
	'login' => '<aweber api key>',
	'password' => '<aweber api secret>',
);
```
### Setp 3: Use Aweber controllers and models

For Oauth dance in your view
```
echo $this->Html->link('Connect with Aweber', array(  
	'plugin' => 'aweber', 'controller' => 'aweber',  
	'action' => 'connect', bin2hex(serialize(your_cake_url))  
));
```

To fetch data, in use one of Aweber model in your controler
```
$uses = array('Aweber.AweberVideos');  
...  
$videos = $this->AweberVideos->getList();
```
