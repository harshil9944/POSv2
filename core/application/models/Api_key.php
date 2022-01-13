<?php
class Api_key extends CI_Model {

    protected $methods = array(
        'index_put' => array('level' => 10, 'limit' => 10),
        'index_delete' => array('level' => 10),
        'level_post' => array('level' => 10),
        'regenerate_post' => array('level' => 10),
    );

    /**
     * Insert a key into the database
     *
     * @access public
     * @return void
     */
    public function generate($user_id)
    {
        $existing = $this->_get_user_key($user_id);

        if($existing) {
            $key_column = config_item('rest_key_column');
            $this->_delete_key($existing->{$key_column});
        }

        // Build a new key
        $key = $this->_generate_key();

        // If no key level provided, provide a generic key
        $level = 10;//$this->put('level') ? $this->put('level') : 1;
        $ignore_limits = 10;//ctype_digit($this->put('ignore_limits')) ? (int) $this->put('ignore_limits') : 1;

        // Insert the new key
        if ($this->_insert_key($key, array('level' => $level, 'ignore_limits' => $ignore_limits, 'user_id' => $user_id)))
        {
            return $key;
        }
        else
        {
            return false;
        }
    }

    public function verify($key,$user_id) {

        $result = $this->_get_key($key);

        if($result) {

            if($result->user_id==$user_id) {
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }

    }

    /**
     * Remove a key from the database to stop it working
     *
     * @access public
     * @return void
     */
    public function remove($key)
    {
        //$key = $this->delete('key');

        // Does this key exist?
        if (!$this->_key_exists($key))
        {
            return false;
        }

        // Destroy it
        $this->_delete_key($key);

        // Respond that the key was destroyed
        return true;
    }

    /**
     * Change the level
     *
     * @access public
     * @return void
     */
    /*public function level_post()
    {
        $key = $this->post('key');
        $new_level = $this->post('level');

        // Does this key exist?
        if (!$this->_key_exists($key))
        {
            // It doesn't appear the key exists
            $this->response(array(
                'status' => FALSE,
                'message' => 'Invalid API key'
            ), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Update the key level
        if ($this->_update_key($key, array('level' => $new_level)))
        {
            $this->response(array(
                'status' => TRUE,
                'message' => 'API key was updated'
            ), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->response(array(
                'status' => FALSE,
                'message' => 'Could not update the key level'
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR); // INTERNAL_SERVER_ERROR (500) being the HTTP response code
        }
    }*/

    /**
     * Suspend a key
     *
     * @access public
     * @return void
     */
    public function suspend($key)
    {
        //$key = $this->post('key');

        // Does this key exist?
        if (!$this->_key_exists($key))
        {
            // It doesn't appear the key exists
            return false;
        }

        // Update the key level
        if ($this->_update_key($key, array('level' => 0)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Regenerate a key
     *
     * @access public
     * @return void
     */
    public function regenerate($old_key)
    {
        //$old_key = $this->post('key');
        $key_details = $this->_get_key($old_key);

        // Does this key exist?
        if (!$key_details)
        {
            // It doesn't appear the key exists
            return false;
        }

        // Build a new key
        $new_key = $this->_generate_key();

        // Insert the new key
        if ($this->_insert_key($new_key, array('level' => $key_details->level, 'ignore_limits' => $key_details->ignore_limits)))
        {
            // Suspend old key
            $this->_update_key($old_key, array('level' => 0));

           return $new_key;
        }
        else
        {
            return false;
        }
    }

    /* Helper Methods */

    private function _generate_key()
    {
        do
        {
            // Generate a random salt
            $salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);

            // If an error occurred, then fall back to the previous method
            if ($salt === FALSE)
            {
                $salt = hash('sha256', time() . mt_rand());
            }

            $new_key = substr($salt, 0, config_item('rest_key_length'));
        }
        while ($this->_key_exists($new_key));

        return $new_key;
    }

    /* Private Data Methods */

    private function _get_user_key($user_id) {

        return $this->db
            ->where('user_id', $user_id)
            ->get(config_item('rest_keys_table'))
            ->row();
    }

    private function _get_key($key)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->get(config_item('rest_keys_table'))
            ->row();
    }

    private function _key_exists($key)
    {
        return $this->db
                ->where(config_item('rest_key_column'), $key)
                ->count_all_results(config_item('rest_keys_table')) > 0;
    }

    private function _insert_key($key, $data)
    {
        $data[config_item('rest_key_column')] = $key;
        $data['date_created'] = function_exists('now') ? now() : time();

        return $this->db
            ->set($data)
            ->insert(config_item('rest_keys_table'));
    }

    private function _update_key($key, $data)
    {
        return $this->rest->db
            ->where(config_item('rest_key_column'), $key)
            ->update(config_item('rest_keys_table'), $data);
    }

    private function _delete_key($key)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->delete(config_item('rest_keys_table'));
    }

}
