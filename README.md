## PHP library for the Wordpress REST API

**CAUTION:** Not yet tested in production!

Currently only supports GET requests.

---

### Installation

Install via composer:

```
composer require maneuver/channel
``` 

And include the autoloader:

```php
require('vendor/autoload.php');
```

Happy times. 🤙

---

### Authentication

#### Basic Authentication

Make sure the [Basic Authentication](https://github.com/WP-API/Basic-Auth) plugin for Wordpress is installed and activated.  
_(should only be used for development purposes, as stated by the repository)_

```php
$channel = new \Maneuver\Channel([
  'uri' => 'http://example.com/wp-json/wp/v2/',
  'username' => 'your-username',
  'password' => 'your-password',
]);
```

#### API Token

Make sure the [Rooftop API Authentication](https://github.com/davidmaneuver/rooftop-api-authentication) plugin is installed and activated.

```php
$channel = new \Maneuver\Channel([
  'uri' => 'http://example.com/wp-json/wp/v2/',
  'token' => 'your-token',
]);
```

#### OAuth

_Currently not implemented._

---

### Usage

#### Posts

Retrieve a list of all posts (where post_type = 'post'):

```php
$posts = $channel->getPosts();

echo count($posts);
```

Retrieve a post by ID:

```php
$post = $channel->getPost(1);

echo $post->excerpt;
```

Using Twig? Fear not:

```twig
<h1>{{ post.title }}</h1>
<p>{{ post.excerpt|raw }}</p>
```



#### Pages

Retrieve a list of all pages:

```php
$pages = $channel->getPages();

foreach ($pages as $page) {
  echo $page->title;
}
```

Retrieve a page by ID:

```php
$page = $channel->getPage(1);

echo $page->content;
```



#### Taxonomies & Terms

Retrieve all existing taxonomies:

```php
$taxonomies = $channel->getTaxonomies();
```

Retrieve one taxonomy by slug:

```php
$taxonomy = $channel->getTaxonomy('category'); // use singular taxonomy name

// Then you can retrieve its terms:
$terms = $taxonomy->terms();
```

Or retrieve the terms in one call using the 'get' method:

```php
$terms = $channel->get('categories'); // use plural taxonomy name
``` 


#### Users

Get all users:

```php
$users = $channel->getUsers();

echo $users[0]->name;
```


#### Media

Get all media:

```php
$media = $channel->getMedia();
```

---

### Slightly more advanced

#### Endpoints

You can actually call any endpoint using the 'get' method:

```php
$post_types = $channel->get('types');
$latest = $channel->get('posts?per_page=5');
```

Read more about all endpoints in the [REST API Handbook](https://developer.wordpress.org/rest-api/)

#### Guzzle

You can pass in more requestOptions for Guzzle:

```php
$latest = $channel->get('posts?per_page=5', [
  'proxy' => 'tcp://localhost:8125',
]);
```

Read more about the Guzzle RequestOptions [here](http://docs.guzzlephp.org/en/latest/request-options.html).


#### Custom Classes

Every call returns an object (or array of objects) extending the '\Maneuver\Models\Base' class. You can define your own classes if needed.

**Note**: Not yet supported for custom post types. Soon.

```php
class MyPost extends \Maneuver\Models\Base {
  public function fullTitle() {
    return 'Post: ' . $this->title;
  }
}

$channel->setCustomClasses([
  // 'type' => 'ClassName'
  // eg: 'user' => 'MyUser'
  // or:
  'post' => 'MyPost',
]);

$post = $channel->getPost(1);

echo $post->fullTitle();

echo get_class($post);
// => 'MyPost'
```

---

### Todo:

- Better custom post types support
- OAuth authentication
- Add WP_Query-like parameters
