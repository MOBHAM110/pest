<?php

class Page_Model extends Model {

    protected $table_name = 'page';
    protected $primary_key = 'page_id';
    protected $status_key = 'page_status';

    public function get_page_lang($lang_id, $id = '', $sel_p = '', $sel_pt = '', $sel_pd = '', $order_key = '', $order_type = 'asc') {
        if ($sel_p === '')
            $sel_p = 'page.*';
        if ($sel_pt === '')
            $sel_pt = 'pt.*';
        if ($sel_pd === '')
            $sel_pd = 'pd.*';

        $this->db->select($sel_p);
        $this->db->select($sel_pt);
        $this->db->select($sel_pd);

        if ($id != '') {
            $this->db->{is_array($id) ? 'in' : 'where'}('page.page_id', $id);
        }

        $this->db->join('page_description as pd', array('page.page_id' => 'pd.page_id'));
        $this->db->join('page_type as pt', array('page.page_type_id' => 'pt.page_type_id'));
        $this->db->where('pd.languages_id', $lang_id);

        $this->db->orderby(empty($order_key) ? 'page.page_left' : "page.$order_key", $order_type);

        $result = $this->db->get('page')->result_array(false);

        if (isset($result[0]) && !empty($id) && !is_array($id))
            return isset($result[0]) ? $result[0] : false;

        return $result;
    }

    public function get_page_type($type) {
        $pt = ORM::factory('page_type_orm')->{is_array($type) ? 'in' : 'where'}('page_type_name', $type)->find_all();

        if ($pt->count() > 1)
            foreach ($pt as $key => $value)
                $arr[$key] = $value->page_type_id;
        elseif (empty($pt[0]->page_type_id))
            return array();
        else
            $arr = $pt[0]->page_type_id;

        $this->db->{is_array($type) ? 'in' : 'where'}('page_type_id', $arr);
        $result = arr::rotate($this->db->get($this->table_name)->result_array(false));

        if (count($result) == 0)
            return array();

        return $result['page_id'];
    }

    public function update_page_layout($page_id, $set) {
        $this->db->where('pl.page_id', $page_id);
        $this->db->update('page_layout as pl', $set);
    }

    public function update($lang_id, $set) {
        /* ------------------------------------------ page TABLE ---------------------------------------------------- */
        // update page parent
        $new_parent = ORM::factory('page_mptt', $set['page_parent']);
        $cur_parent = ORM::factory('page_mptt', $set['page_id'])->__get('parent');
        if ($new_parent->page_id != $cur_parent->page_id)
            ORM::factory('page_mptt', $set['page_id'])->move_to_last_child($new_parent);

        $result_up_page = array();
        // init array set for update page table
        $set_page = array(
            'page_title_seo' => $set['page_title_seo'],
            'page_keyword' => $set['page_keyword'],            
            'page_description' => $set['page_description'],
            'page_target' => isset($set['page_target']) ? $set['page_target'] : '',
            'page_status' => $set['page_status']
        );
        if (!empty($set['page_type_id']))
            $set_page['page_type_id'] = $set['page_type_id'];

        // update table page
        $this->db->where($this->primary_key, $set['page_id']);
        $result_up_page = $this->db->update($this->table_name, $set_page);

        /* ----------------------------------------- page description TABLE ----------------------------------------- */

        $result_up_pd = array();
        if (isset($set['page_title'])) {
            // init array set for update page description table
            $set_page_des = array(
                'page_title' => $set['page_title'],
                'page_content' => $set['page_content']
            );

            // update table page description (languages)
            $this->db->where($this->primary_key, $set['page_id']);
            $this->db->where('languages_id', $lang_id);
            $result_up_pd = $this->db->update('page_description', $set_page_des);
        }

        /* ------------------------------------------------------------------------------------------------------------ */
        return (count($result_up_page) || count($result_up_pd));
    }

    public function insert($set) {
        // create new page with properties empty
        $new_page = ORM::factory('page_mptt')->insert_as_last_child($set['page_parent']);
        $new_page->page_type_id = $set['page_type_id'];
        $new_page->page_title_seo = $set['page_title_seo'];
        $new_page->page_keyword = $set['page_keyword'];
        $new_page->page_description = $set['page_description'];
        $new_page->page_target = isset($set['page_target']) ? $set['page_target'] : '';
        $new_page->page_status = $set['page_status'];
        $new_page->save();
        unset($set['page_type_id']);

        // create header for new page 
        // create languages for new page in page description talbe
        $set_pd = array(
            'page_id' => $new_page->page_id,
            'languages_id' => '',
            'page_title' => $set['page_title'],
            'page_content' => $set['page_content']
        );
        foreach (ORM::factory('languages')->find_all() as $lang) {
            $set_pd['languages_id'] = $lang->languages_id;
            $new_pd = $this->db->insert('page_description', $set_pd);
        }
        unset($set['page_title'], $set['page_content']);

        return (count($new_page) || count($new_pd)); // || count($new_pl));
    }

    public function is_page_special($id) { // id = page_id
        $this->db->select('pt.*');
        $this->db->join('page_type as pt', array('page.page_type_id' => 'pt.page_type_id'));
        $this->db->where('page.page_id', $id);

        $result = $this->db->get('page')->result_array(false);

        return ($result[0]['page_type_special'] == 1) ? TRUE : FALSE;
    }

    public function delete($id) { // page id
        // Delete page follow MPTT Structure
        $result1 = ORM::factory('page_mptt', $id)->delete();

        // Delete info language in page_description table
        $result2 = $this->db->where('page_id', $id)->delete('page_description');

        if (empty($result1->page_id))
            if ($result2->count() == ORM::factory('languages')->count_all())
                return TRUE;
        return FALSE;
    }

    public function check_type($id, $type) {
        $page = ORM::factory('page_orm')->find($id);
        $ptd = ORM::factory('page_type_orm')->find($page->page_type_id);

        if ($ptd->page_type_name == $type)
            return TRUE;
        return FALSE;
    }

    public function type_exist($page_type) {
        $this->db->where('page_type_id', $page_type);

        $result = $this->db->count_records('page');

        if ($result > 0)
            return TRUE;
        return FALSE;
    }

    public function get_frm() {
        $form = array
            (
            'txt_title' => '',
            'txt_title_seo' => '',
            'txt_keyword' => '',
            'txt_description' => '',
            'txt_content' => '',
            'txt_content_url' => '',
            'txt_content_form' => '',
            'sel_type' => '',
            'sel_parent' => '',
            'sel_target' => '',
            'sel_status' => ''
        );
        return $form;
    }
    
    public function set_frm($frm)
    {
        $frm = array();
        return $frm;
    }
    
}

?>