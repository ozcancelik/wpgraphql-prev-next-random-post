# WpGraphQL Previous, Next and Random Post.

This is a WordPress plugin that adds a new field to the GraphQL schema that allows you to query for the previous, next and random post of a given post type.

## Installation

Download the plugin as a zip file and install it via the WordPress admin panel. Or add the code your theme's `functions.php` file.

or

Install via git clone:

```bash
git clone https://github.com/ozcancelik/wpgraphql-prev-next-random-post.git
```

## Usage

### Previous and Next Post

```graphql
query MyQuery {
  post(id: "cG9zdDox") {
    id
    title
    previous {
      id
      title
    }
    next {
      id
      title
    }
  }
}
```

### Random Post

```graphql
query MyQuery {
  randomPost {
    id
    title
  }
}
```
