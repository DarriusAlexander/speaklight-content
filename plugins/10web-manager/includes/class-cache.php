<?php


namespace Tenweb_Manager;


class TenwebCache
{
    protected static $instance = null;


    private function __construct()
    {
    }

    public function register_hooks()
    {
        add_action('post_updated', array($this, 'on_post_updated_purge'), 10, 3);
        add_action('transition_post_status', array($this, 'on_transition_post_purge'), 10, 3);
        add_action('wp_insert_comment', array($this, 'on_wp_insert_comment_purge'), 10, 2);
        add_action('transition_comment_status', array($this, 'on_transition_comment_purge'), 10, 3);
        add_action('edit_comment', array($this, 'on_edit_comment_purge'), 10, 2);
        add_action('wp_ajax_' . TENWEB_PREFIX . '_cache_purge_all', array($this, 'purge_all_caches'));

    }

    public function on_post_updated_purge($post_id, $post_after, $post_before)
    {

        if ($post_after->post_status == 'publish' || $post_before->post_status == 'publish') {
            $this->purge_cache($post_id);
        }

    }

    public function on_transition_post_purge($new_status, $old_status, $post)
    {
        if ($new_status == 'publish' || $old_status == 'future') {
            $this->purge_cache($post->ID);
        }

    }

    public function on_wp_insert_comment_purge($id, $comment)
    {
        if ((int)$comment->comment_approved == 1) {
            $this->purge_cache($comment->comment_post_ID);
        }

    }

    public function on_transition_comment_purge($new_status, $old_status, $comment)
    {
        if ($new_status == 'approved' || $old_status == 'approved') {
            $this->purge_cache($comment->comment_post_ID);
        }
    }

    public function on_edit_comment_purge($id, $comment)
    {
        if ((int)$comment->comment_approved == 1) {
            $this->purge_cache($comment->comment_post_ID);
        }
    }


    public function purge_cache($post_id)
    {
        $post = get_post($post_id);
        if (!$post) {
            return false;
        }
        $public_post_types = get_post_types(array('public' => true));
        $post_type = get_post_type($post_id);
        if (!in_array($post_type, $public_post_types)) {
            return false;
        }
        $url = get_permalink($post_id);
        if ($url) {
            $this->purge_cache_via_url($url);
        }

        return true;
    }

    public function purge_cache_via_url($url)
    {
        if (!$url || empty($url)) {
            return false;
        }
        $hash = $this->get_cache_file_hash($url);
        if ($hash) {
            return $this->flush_cache($hash);
        }

        return false;
    }

    private function get_cache_file_hash($url)
    {
        if (!$url || empty($url)) {
            return false;
        }
        $url = esc_url($url);
        $url = parse_url($url);
        $hash = md5($url['scheme'] . 'GET' . $url['host'] . $url['path']);

        return $hash;
    }


    private function get_tenweb_cache_purge_endpoint()
    {
        if (defined('TENWEB_ENV')) {
            return  TENWEB_ENV;
        }

        return 'live';
    }

    private function flush_cache($hash)
    {

        $resp = wp_remote_get('http://127.0.0.1/purge/' . $this->get_tenweb_cache_purge_endpoint() . '/' . $hash);

        if (is_wp_error($resp) || 200 !== wp_remote_retrieve_response_code($resp)) {
            return false;
        }

        return true;
    }

    public function purge_all_caches()
    {
        $return_resp = array();
        $return_resp['status'] = "ok";
        $return_resp['message'] = "Cache Successfully purged";

        if ($response = $this->flush_cache('all') === false) {
            $return_resp['status'] = "error";
            $return_resp['error'] = "Something went wrong";
        }


        echo json_encode($return_resp);
        exit;
    }

    public static function get_instance()
    {
        if (null == self::$instance) {

            self::$instance = new self;
        }

        return self::$instance;
    }


}