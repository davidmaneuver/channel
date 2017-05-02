## PHP library for the Wordpress REST API

**Important:** Not yet tested in production!

Currently only supports GET requests.

---

### Authentication

#### Basic Authentication

Make sure the [Basic Authentication](https://github.com/WP-API/Basic-Auth) plugin for Wordpress is installed and activiated.  
_(should only be used for development purposes)_

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

### Extra

You can call any endpoint using the 'get' method:

```php
$post_types = $channel->get('types');
```

Read more in the [REST API Handbook](https://developer.wordpress.org/rest-api/)

---

### Todo:

- OAuth authentication
- Add WP_Query-like parameters
