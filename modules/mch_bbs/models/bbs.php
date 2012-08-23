<?php

class Bbs_Model extends Model {

    public $table_name = 'bbs';
    public $primary_key = 'bbs_id';

    public function __construct() {
        parent::__construct();
    }

    public function get($mptt = FALSE, $page_id = '', $bbs_id = '', $lang_id, $sel = '', $sel_content = '') {
        if ($sel == '')
            $sel = 'bbs.*';
        if ($sel_content == '')
            $sel_content = 'bbs_content.*';

        $this->db->select($sel);
        $this->db->select($sel_content);

        if ($bbs_id != '')
            $this->db->{is_array($bbs_id) ? 'in' : 'where'}('bbs.bbs_id', $bbs_id);

        if ($page_id != '')
            $this->db->{is_array($page_id) ? 'in' : 'where'}('bbs_page_id', $page_id);

        $this->db->join('bbs_content', array('bbs_content.bbs_id' => 'bbs.bbs_id'));

        $this->db->where('bbs_content.languages_id', $lang_id);

        if ($mptt) {
            $this->db->where('bbs_left > ', 1);
            $this->db->orderby('bbs_left');
        } else {
            $this->db->orderby(array('bbs_sort_order' => 'desc'));
            $this->db->orderby(array('bbs_date_created' => 'desc', 'bbs_date_modified' => 'desc'));
        }

        $result = $this->db->get($this->table_name)->result_array(false);

        if (count($result) == 1 && $bbs_id != '')
            return $result[0];
        return $result;
    }

    public function set_limit($limit, $offet = '') {
        if ($offet == '')
            $this->db->limit($limit);
        else
            $this->db->limit($limit, $offet);
    }

    public function set_user_view($username) {
        $this->db->where('bbs_user', $username);
    }

    public function insert($set, $mptt = TRUE, $parent = 'root') {
        $admin_lang = '';
        if (SESSION::get('sess_admin_lang'))
            $admin_lang = SESSION::get('sess_admin_lang');
        else
            return FALSE;

        $set_bbs_content = array(
            'bbs_title' => $set['bbs_title'],
            'bbs_content' => isset($set['bbs_content']) ? $set['bbs_content'] : ''
        );
        unset($set['bbs_title'], $set['bbs_content']);

        if ($mptt) {
            if ($parent === 'root')
                $parent_id = ORM::factory('bbs_mptt')->__get('root')->bbs_id;
            else
                $parent_id = $parent;

            $new_bbs = ORM::factory('bbs_mptt')->insert_as_first_child($parent_id);
            $new_bbs_id = $new_bbs->bbs_id;
            $this->db->update($this->table_name, $set, array($this->primary_key => $new_bbs_id));
        } else {
            $new_bbs = $this->db->insert($this->table_name, $set);
            $new_bbs_id = $new_bbs->insert_id();
        }

        if (empty($new_bbs_id))
            return FALSE;

        $set_bbs_content['bbs_id'] = $new_bbs_id;

        //foreach(ORM::factory('languages')->find_all() as $lang) {
            $set_bbs_content['languages_id'] = $admin_lang; //$lang->languages_id;
            $this->db->insert('bbs_content', $set_bbs_content);
        //}
        return $new_bbs_id;
    }

    public function update($lang_id, $set) {
        $bbs_id = $set['bbs_id'];

        $set_bbs_content = array(
            'bbs_title' => $set['bbs_title'],
            'bbs_content' => isset($set['bbs_content']) ? $set['bbs_content'] : ''
        );
        $this->db->where($this->primary_key, $bbs_id);
        $this->db->where('languages_id', $lang_id);
        $result_bbs = $this->db->update('bbs_content', $set_bbs_content);

        unset($set['bbs_title'], $set['bbs_content'], $set['bbs_id'], $set['bbs_short_title']);

        $this->db->where($this->primary_key, $bbs_id);
        $result_bbs_content = $this->db->update($this->table_name, $set);

        return (count($result_bbs) > 0 || count($result_bbs_content) > 0);
    }

    public function set_status($bbs_id, $status) {
        $this->db->update($this->table_name, array('bbs_status' => $status), array('bbs_id' => $bbs_id));
    }

    public function delete($bbs_id, $mptt) {
        // Delete bbs in bbs_content
        $this->db->where($this->primary_key, $bbs_id);
        $result1 = $this->db->delete('bbs_content');

        // Delete bbs in bbs table
        if ($mptt)
            $result2 = ORM::factory('bbs_mptt', $bbs_id)->delete();
        else
            $result2 = ORM::factory('bbs_orm')->delete($bbs_id);

        //if ($result1->count() == ORM::factory('languages')->count_all())
        if ($result1->count()>0)
            if (empty($result2->bbs_id))
                return TRUE;

        return FALSE;
    }

    public function search($search) {
        if (!empty($search['keyword']) || !empty($search['sel_type'])) {
            $keyword = strtolower($this->db->escape('%' . $search['keyword'] . '%'));

            switch ($search['sel_type']) {
                case "1":
                    $this->db->where('LCASE(bbs_title) LIKE ' . $keyword);
                    break;

                case "2":
                    $this->db->where('LCASE(bbs_author) LIKE ' . $keyword);
                    break;

                case "3":
                    $this->db->where('LCASE(bbs_content) LIKE ' . $keyword);
                    break;

                default:
                    $sql = "(LCASE(bbs_author) LIKE $keyword";
                    $sql.=" OR LCASE(bbs_title) LIKE $keyword";
                    $sql.= " OR LCASE(bbs_content) LIKE $keyword)";
                    $this->db->where($sql);
            }
        }
    }

    public function increase_count($id) {
        $result = ORM::factory('bbs_orm', $id);
        $result->bbs_count = $result->bbs_count + 1;
        $result->save();
    }

}