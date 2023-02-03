<?php
use WPGraphQL\AppContext;
use WPGraphQL\Data\DataSource;
use WPGraphQL\Model\Post;

/**
 * Plugin Name: WpGraphQL Previous, Next and Random Post Plugin
 * Plugin Description: This plugin adds previous, next and random post to the WPGraphQL schema for posts.
 * Author: @ozcancelik
 * Author URI: https://github.com/ozcancelik
 * Version: 0.0.1
 */

add_action('graphql_register_types', 'register_post_object_fields');
function register_post_object_fields()
{
    // Previous Post 
    register_graphql_field('Post', 'previous', [
        'type' => 'Post',
        'description' => __(
            'Previous Post'
        ),

        'resolve' => function (Post $post, array $args, AppContext $context) {
            global $post;
            $post = get_post($post->ID, OBJECT);
            setup_postdata($post);
            $prev = get_adjacent_post(true, '', true);
            wp_reset_postdata();
            if (!$prev) {
                return null;
            }

            return DataSource::resolve_post_object($prev->ID, $context);
        },
    ]);

    // Next Post 
    register_graphql_field('Post', 'next', [
        'type' => 'Post',
        'description' => __(
            'Next Post'
        ),
        'resolve' => function (Post $post, array $args, AppContext $context) {
            global $post;
            $post = get_post($post->ID, OBJECT);
            setup_postdata($post);
            $next = get_adjacent_post(true, '', false);
            wp_reset_postdata();
            if (!$next) {
                return null;
            }

            return DataSource::resolve_post_object($next->ID, $context);
        },
    ]);
    // Random Post 
    register_graphql_field('rootQuery', 'randomPost', [
        'type' => 'Post',
        'description' => __('A random post', 'wpgraphql-random-post'),
        'resolve' => function ($root, $args, $context, $info) {
            $args = [
                'post_type' => 'post',
                'orderby' => 'rand',
                'posts_per_page' => 1,
                'lang' => $args['language'],
            ];
            $query = new \WP_Query($args);
            $posts = $query->get_posts();
            $post = $posts[0];
            return DataSource::resolve_post_object($post->ID, $context);
        },
    ]);
}