<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: enzob
 * Date: 26/10/2017
 * Time: 16:26
 */

class Notification_model extends CI_Model {

    /**
     * Return the notifications of an user.
     * @param int $userId The id of the user
     * @return array The notifications of the user
     */
    public function get($userId) {
        /*
        return = $this->db->where('idUser', $userId)
            ->get('Notification')
            ->result();
        */
        return array();
    }

    /**
     * Creates a notification.
     * @param string $content
     * @param string $type
     * @param string $icon
     * @param int $userId The id of the user to send it to
     * @return int The id of the created notification
     */
    public function create($content, $type, $icon, $userId) {
        $data = array(
            'content' => $content,
            'type' => $type,
            'icon' => $icon
        );

        /*
        $this->db->insert('Notification', $data);
        return $this->db->insert_id();
        */
        return 0;
    }

    /**
     * Deletes a notification.
     * @param int $id The id of the notification.
     * @return bool Whether the operation was successful or not
     */
    public function delete($id) {
        // return $this->db->delete('Notification', array('idNotification', $id));
        return true;
    }

}
