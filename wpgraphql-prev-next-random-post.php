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
        $prev = get_adjacent_post(true, '', true, 'language');
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
        $next = get_adjacent_post(true, '', false, 'language');
        wp_reset_postdata();
        if (!$next) {
            return null;
        }

        return DataSource::resolve_post_object($next->ID, $context);
    },
]);

// Random Post

register_graphql_field('RootQuery', 'randomPost', [
    'type' => 'Post',
    'description' => __('Random Post by Language'),
    'args' => [
        'language' => [
            'type' => 'LanguageCodeEnum',
            'description' => __('Language'),
        ],
    ],
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