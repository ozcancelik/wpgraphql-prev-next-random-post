/**
* Plugin Name: WpGraphQL Previous, Next and Random Post Plugin
* Plugin Description: This plugin adds previous, next and random post to the WPGraphQL schema for posts.
* Author: @ozcancelik
* Author URI: https://github.com/ozcancelik
* Version: 0.0.1
*/

// Previous Post
register_graphql_field('Post', 'previousPost', [
'type' => 'Post',
'description' => __(
'Previous Post'
),
'resolve' => function (Post $post, array $args, AppContext $context) {
global $post;
$post = get_post($post->ID, OBJECT);
setup_postdata($post);
if ( is_plugin_active( 'polylang/polylang.php' ) ) {
$prev = get_adjacent_post(true, '', true, 'language');
} else {
$prev = get_adjacent_post(true, '', true);
}
wp_reset_postdata();
if (!$prev) {
return null;
}

return DataSource::resolve_post_object($prev->ID, $context);
},
]);
// Next Post
register_graphql_field('Post', 'nextPost', [
'type' => 'Post',
'description' => __(
'Next Post'
),
'resolve' => function (Post $post, array $args, AppContext $context) {
global $post;
$post = get_post($post->ID, OBJECT);
setup_postdata($post);
if ( is_plugin_active( 'polylang/polylang.php' ) ) {
$next = get_adjacent_post(true, '', false, 'language');
} else {
$next = get_adjacent_post(true, '', false);
}
wp_reset_postdata();
if (!$next) {
return null;
}

return DataSource::resolve_post_object($next->ID, $context);
},
]);

// Random Post
register_graphql_field('Post', 'randomPost', [
'type' => 'Post',
'description' => __(
'Random Post'
),
'resolve' => function (Post $post, array $args, AppContext $context) {
global $post;
$post = get_post($post->ID, OBJECT);
setup_postdata($post);
$random = get_posts([
'post_type' => 'post',
'orderby' => 'rand',
'posts_per_page' => 1,

]);
wp_reset_postdata();
if (!$random) {
return null;
}

return DataSource::resolve_post_object($random[0]->ID, $context);
},
]);