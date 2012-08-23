<?php

class User_Model extends Model {

    protected $table_name = 'user';
    protected $primary_key = 'user_id';

    public function get($id = '') {
        $this->db->select($this->table_name . '.*');

        if ($id != '')
            $this->db->{is_array($id) ? 'in' : 'where'}($this->table_name . '.' . $this->primary_key, $id);

        $this->db->orderby($this->primary_key, 'desc');
        $result = $this->db->get($this->table_name)->result_array(false);

        if ($id != '' && !is_array($id))
            return isset($result[0]) ? $result[0] : false;

        return $result;
    }

    public function account_exist($username, $pass, $type='') {
        $this->db->where("(user_name = '".$username."' OR user_email = '".$username."')");
        $this->db->where($this->table_name . '_pass', $pass);
        if ($type == 'admin') {
            $this->db->where($this->table_name . '_level<=', 2);
        }
        $result = $this->db->get($this->table_name)->result_array(false);

        return (count($result) > 0 ? $result[0] : FALSE);
    }

    public function set_status($id, $status='') {
        $set = array($this->table_name . '_status' => $status);
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        return count($this->db->update($this->table_name, $set));
    }

    public function delete($id, $type='') {
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }

}

?>