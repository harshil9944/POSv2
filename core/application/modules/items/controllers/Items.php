<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Items extends MY_Controller {

    public $module = 'items';
    public $model = 'item';
    public $singular = 'Item';
    public $plural = 'Items';
    public $language = 'items/items';
    public $edit_form = '';
    public $form_xtemplate = 'items_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);

        $params = [
            'migration_path' => ITEM_MIGRATION_PATH,
            'migration_table' => ITEM_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()	{

        _language('items');
        _library('table');
        _model('item_category','category');

        $filter_dropdown_value = _input('filterDropdown');
        _set_js_var('exportUrl',base_url('items/exports'));

        $table = $this->{$this->model}->table;
        $offset = (_input('offset') && is_int((int)_input('offset')))?(int)_input('offset'):0;
        $searchString = trim(_input('search'));
        if(!$searchString) {
            $search_in_vendors = false;
        }
        $searchFields = [$table.'.title'];

        $filters = [];
        $filters['filter'] = ['type'=>'product','parent'=>0,'is_addon' =>0];
        if($filter_dropdown_value) {
            $filters['filter']['category_id'] = $filter_dropdown_value;
        }
        if($searchString) {
            $or_likes = [];
            foreach ($searchFields as $field) {
                $or_likes[$field] = $searchString;
            }
            $filters['or_likes'] = $or_likes;
            _set_js_var('searchString',$searchString);
        }
        $filters['orders'] = [['order_by'=>$table.'.title','order'=>'ASC']];
        $filters['offset'] = $offset;
        $filters['limit'] = true;
        $results = $this->{$this->model}->get_list($filters);

        $total_items = $this->{$this->model}->get_list_count($filters);

        $total_rows = ($total_items)?$total_items['total_rows']:0;
        $per_page = (int)_get_setting('global_limit',50);
        $paginate_url = base_url($this->module);

        $body = [];
        if($results) {
            foreach ($results as $result) {
                $action = _edit_link(base_url($this->module.'/edit/'.$result['id'])) . _vue_delete_link('handleRemove('.$result['id'].')');
                $action_cell = [
                    'class' =>  'text-center',
                    'data'  =>  $action
                ];
                $category_id = $result['category_id'];
                $result['categoryTitle'] = '';
                if($category_id) {
                    $category = $this->category->single(['id'=>$category_id]);
                    $result['categoryTitle'] = (@$category['title'])?$category['title']:'Not Selected';
                }

                $item_name = _vue_text_link($result['title'],'handleViewItem('.$result['id'].')','View Item');

                $rate = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format($result['rate'])
                ];
                $body[] = [
                    $item_name,
                    $result['variant_count'] > 0 ? 'With Variant' : 'Single',
                    //$this->{$this->model}->getItemTypeAttribute($result['id']),
                    $result['categoryTitle'],
                    $rate,
                    $action_cell
                ];
            }
        }

        $heading = [
            $this->singular . ' Name',
            'Type',
            'Category',
            ['data'=>'Price','class'=>'text-right no-sort w-110p'],
            ['data'=>'Action','class'=>'text-center no-sort w-110p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $filter_dropdown = true;
        if($filter_dropdown) {
            $this->category->order_by('title','ASC');
            $result = $this->category->search();
            $categories = [];
            if($result) {
                foreach ($result as $item) {
                    $categories[] = [
                        'id'    =>  $item['id'],
                        'value' =>  $item['title'],
                    ];
                }
            }
            $default_value = '';
            $filter_dropdown_values = [
                'value'         =>  ($filter_dropdown_value)?$filter_dropdown_value:$default_value,
                'defaultValue'  =>  $default_value,
                'defaultTitle'  =>  'All Categories',
                'values'        =>  $categories
            ];
            _set_js_var('filterDropdown',$filter_dropdown_values,'j');
        }

        $search = true;
        if($search) {
            _set_js_var('searchUrl',base_url($this->module));
        }

        $page = [
            'singular'          =>  $this->singular,
            'plural'            =>  $this->plural,
            'add_url'           =>  base_url($this->module.'/add'),
            'vue_add_url'       =>  '',
            'table'             =>  $table,
            'search'            =>  $search,
            'filter_dropdown'   =>  true,
            'edit_form'         =>  '',
            'total_rows'        =>  $total_rows,
            'per_page'          =>  $per_page,
            'paginate_url'      =>  $paginate_url
        ];
        _vars('page_data',$page);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_layout_type('wide');
        _set_page_heading($this->plural);
        _set_additional_component('items_detail_view');
        _set_additional_component('items_import_export_tag');
        _set_additional_component('items_xtemplates','outside');
        _set_layout(LIST_VIEW_PATH);

    }

    public function add() {

        $this->_add();

    }

    public function edit($id) {

        $this->_edit($id);

    }

    public function _get_menu() {

        $menus = [];

        $items = [];
        $items[] = array(
            'name'	    =>  'Items',
            'class'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'items',
            'module'    =>  'items',
            'children'  =>  []
        );


        $items[] = array(
            'name'	    =>  'Categories',
            'class'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'items/categories',
            'module'    =>  'items',
            'children'  =>  []
        );


        $items[] = array(
            'name'	    =>  'Icons',
            'class'     =>  '',
            'icon'      =>  '',
            'path'      =>  'items/icons',
            'module'    =>  'items',
            'children'  =>  []
        );

        $menus[] = array(
            'id'        => 'menu-items',
            'class'     => '',
            'icon'      => 'si si-basket-loaded',
            'group'     => 'module',
            'name'      => 'Items',
            'path'      => '',
            'module'    => 'items',
            'priority'  => 2,
            'children'  => $items
        );

        return $menus;

    }

    public function _populate_get() {

        _model('item_category','category');
        _model('item_icon','icon');

        $this->{$this->model}->order_by('title','ASC');
        $this->{$this->model}->select('id,title');

        $params['include_select'] = true;
        $units = _get_module('core/units','_get_select_data',$params);
        $categories = $this->category->get_list();
        $categories = get_select_array($categories,'id','title',true,'','Select Category');

        $icons = [];
        $this->icon->order_by('title');
        $result = $this->icon->search();
        if($result) {
            $this->_exclude_keys($result,$this->icon->exclude_keys,true);
            foreach($result as $row) {
                $icons[] = [
                    'id'    =>  $row['id'],
                    'value' =>  $row['title']
                ];
            }
        }

        $printLocations = [['id'=>'default', 'value'=>'No Printout'],['id'=>'kitchen', 'value'=>'Kitchen']];
        if(KITCHEN_PRINTERS) {
            $printLocations = KITCHEN_PRINTERS;
        }

        _response_data('categories',$categories);
        _response_data('units',$units);
        _response_data('icons',$icons);
        _response_data('printLocations',$printLocations);
        return true;

    }

    public function _action_put() {

        _clear_cache('item');
        _model('items/item_note','note');

        $obj = _input('obj');
        if($obj) {
            $obj = (array) json_decode($obj,true);
        }

        $this->_prep_obj($obj);

        $item = $obj['item_table'];
        if(isset($_FILES['image']) && $_FILES['image']) {
            $image = $this->_upload_image();
            $item['image'] = $image;

        }
        $item['outlet_id'] = _get_setting('default_warehouse',1);
        $item['code'] = _get_ref('ITM',3,6);
        $item['created_by'] = _get_user_id();
        $item['added'] = sql_now_datetime();


        if($this->{$this->model}->insert($item)) {
            $item_id = $this->{$this->model}->insert_id();
            $item['code'] = _update_ref('ITM');
            if(@$obj['notes_table']) {
                foreach ($obj['notes_table'] as $note) {
                    $note['item_id'] = $item_id;
                    $note['added'] = sql_now_datetime();
                    $this->note->insert($note);
                }
            }
            if(@$obj['variants_table']){
                foreach ($obj['variants_table'] as &$v){
                    if($v['removed'] === false){
                        $record = $this->{$this->model}->single(['id'=>$v['id']]);
                        $v['id'] = !empty($record) ? $v['id'] : null;
                        $v['parent'] = $item_id;
                        $v['outlet_id'] = _get_setting('default_warehouse',1);
                        $v['code'] = _get_ref('ITM',3,6);
                        $v['created_by'] = _get_user_id();
                        $v['added'] = sql_now_datetime();
                        unset($v['removed']);
                        $this->{$this->model}->insert($v);
                        _update_ref('ITM');
                    }
                }
            }
            if(@$obj['addons_table']){
                foreach ($obj['addons_table'] as &$v){
                    if($v['removed'] === false){
                        $record = $this->{$this->model}->single(['id'=>$v['id']]);
                        $v['id'] = !empty($record) ? $v['id'] : null;
                        $v['parent'] = $item_id;
                        $v['outlet_id'] = _get_setting('default_warehouse',1);
                        $v['code'] = _get_ref('ITM',3,6);
                        $v['created_by'] = _get_user_id();
                        $v['added'] = sql_now_datetime();
                        unset($v['removed']);
                        $this->{$this->model}->insert($v);
                        _update_ref('ITM');
                    }
                }
            }

            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;

    }

    public function _action_post() {

        _clear_cache('item');

        _model('items/item_note','note');

        $obj = _input('obj');
        if($obj) {
            $obj = (array) json_decode($obj,true);
        }
        $deletedVariant = _input('deletedVariant');
        if($deletedVariant) {
            $deletedVariant = (array) json_decode($deletedVariant,true);
        }
        $this->_prep_obj($obj);
        $item = $obj['item_table'];

        if(isset($_FILES['image']) && $_FILES['image']) {
            $image = $this->_upload_image();
            $item['image'] = $image;

        }
        $item['outlet_id'] = _get_setting('default_warehouse',1);
        $item['created_by'] = _get_user_id();
        $item['added'] = sql_now_datetime();
        $item_id = $item['id'];
        unset($item['id']);

        $filter=[
            'id'    =>  $item_id
        ];
        if($this->{$this->model}->update($item,$filter)) {
            if(@$obj['variants_table']){
                foreach ($obj['variants_table'] as &$v){
                    if($v['removed'] === false){
                        $record = $this->{$this->model}->single(['id'=>$v['id']]);
                        $v['id'] = !empty($record) ? $v['id'] : null;
                        if($v['id'] != null){
                            unset($v['removed'],$v['spiceLevel']);
                            $this->{$this->model}->update($v,['id'=>$v['id']]);
                        }else{
                            $v['outlet_id'] = _get_setting('default_warehouse',1);
                            $v['parent'] = $item_id;
                            $v['code'] = _get_ref('itm',3,6);
                            $v['created_by'] = _get_user_id();
                            $v['added'] = sql_now_datetime();
                            unset($v['removed'],$v['spiceLevel']);
                            $this->{$this->model}->insert($v);
                            _update_ref('itm');
                        }
                    }

                }
            }
            if(@$obj['addons_table']){
                foreach ($obj['addons_table'] as &$v){
                    if($v['removed'] === false){
                        $record = $this->{$this->model}->single(['id'=>$v['id']]);
                        $v['id'] = !empty($record) ? $v['id'] : null;
                        if($v['id'] != null){
                            unset($v['removed'],$v['quantity'],$v['enabled']);
                            $this->{$this->model}->update($v,['id'=>$v['id']]);
                        }else{
                            $v['parent'] = $item_id;
                            $v['outlet_id'] = _get_setting('default_warehouse',1);
                            $v['code'] = _get_ref('itm',3,6);
                            $v['created_by'] = _get_user_id();
                            $v['added'] = sql_now_datetime();
                            unset($v['removed'],$v['quantity'],$v['enabled']);
                            $this->{$this->model}->insert($v);
                            _update_ref('itm');
                        }
                    }

                }
            }
            $ignore = [];
            if(@$obj['notes_table']) {
                foreach ($obj['notes_table'] as $note) {
                    if(@$note['id']!='') {
                        $this->note->update($note,['id'=>$note['id']]);
                        $ignore[] = $note['id'];
                    }else {
                        $note['item_id'] = $item_id;
                        $note['added'] = sql_now_datetime();
                        $this->note->insert($note);
                        $ignore[] = $this->note->insert_id();
                    }
                }
            }
            $this->_clear_notes($item_id,$ignore);
            if(@$deletedVariant){
               /*  foreach($deletedVariant as $d){

                    $this->_clear_variants($d['id']);
                } */
                $deletedVariantIds = array_column($deletedVariant , 'id');
                $this->_clear_variants($deletedVariantIds);
            }

            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_delete() {

        _clear_cache('item');

        _model('items/item_note','note');

        $ignore_list = [];

        $item_id = _input('id');

        if(!in_array($item_id,$ignore_list)) {
            $result = $this->{$this->model}->single(['id' => $item_id]);
            if ($result) {
                $this->_clear_notes($item_id,[]);
               // $this->_clear_addons($item_id,[]);
                $affected_rows = $this->{$this->model}->delete(['id' => $item_id]);
                if ($affected_rows) {
                    _response_data('redirect', base_url($this->module));
                    return true;
                } else {
                    return false;
                }

            } else {
                _response_data('message', _line('error_delete_item_not_found'));
                return false;
            }
        }else{
            _response_data('message', _line('error_cannot_delete_protected_item'));
            return false;
        }
    }

    private function _prep_obj(&$obj) {
        $item_keys = $this->{$this->model}->keys;
        $item_notes_keys = $this->note->keys;

        $obj['item_table'] = [];
        foreach ($item_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['item_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }

        $obj['variants_table'] = [];
        if(count($obj['variations']) > 0){
            foreach ($obj['variations'] as $single){
                foreach ($item_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $obj['variants_table'][] = $single;
            }
            unset($obj['variations']);
        }

        $obj['addons_table'] = [];
        if(count($obj['addons']) > 0){
            foreach ($obj['addons'] as $single){
                foreach ($item_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $obj['addons_table'][] = $single;
            }
            unset($obj['addons']);
        }

        if(isset($obj['notes'])) {
            foreach ($obj['notes'] as $single) {
                foreach ($item_notes_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $obj['notes_table'][] = $single;
            }
            unset($obj['notes']);
        }

    }

    private function _clear_variants($ids) {
        $id_string = implode(',',$ids);
        $sql = "DELETE FROM itm_item WHERE id IN ($id_string)";
        _db_query($sql);
    }

    private function _clear_notes($item_id,$ignore_ids=[]) {
        if($ignore_ids) {
            $this->db->where_not_in('id',$ignore_ids);
        }
        $this->note->delete(['item_id'=>$item_id]);
    }

    private function _clear_addons($item_id,$ignore_ids=[]) {
        if($ignore_ids) {
            $this->db->where_not_in('id',$ignore_ids);
        }
        $this->{$this->model}->delete(['id'=>$item_id]);
    }

    private function _upload_image() {
        $image = (isset($_FILES['image']) && $_FILES['image']) ? 'image' : null;

        if ($image) {


            $item_image_dir = 'items/';
            $upload_path = _get_config('global_upload_path') . $item_image_dir;


            if (!file_exists( $upload_path)) {
                mkdir( $upload_path, 0777, true);
            }

            $config['upload_path'] =  $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|svg|gif';
            $config['detect_mime'] = false;
            $config['max_size'] = '10240';
            $config['encrypt_name'] = true;
            $config['file_ext_tolower'] = true;

            _library('upload', $config);

            $this->upload->file_type = $_FILES['image']['type'];
            $upload_data = $this->upload->do_upload($image);

            if ($upload_data) {
                $uploaded = $this->upload->data();
                return $item_image_dir . $uploaded['file_name'];
            }


        }
    }

    public function _single_get() {

        $id = _input('id');

        $params = [
            'id'    =>  $id
        ];

        $result = $this->_single($params);

        //Temporary Change
        $result['hasSpiceLevel'] = ($result['hasSpiceLevel'])?'1':'0';

        if($result) {
            _response_data('obj',$result);
        }else{
            _set_message('The requested details could not be found.','warning');
            _response_data('redirect',base_url($this->module));
        }
        return true;
    }

    public function _single($params=[]) {


        $id = $params['id'];

        $meta = (@$params['meta'])?$params['meta']:false;
        $result = (@$params['item'])?$params['item']:false;

        _model('items/item_note','note');

        $warehouse_id = _get_setting('default_warehouse',1);
        $filter = ['id'=>$id];
        if(!$result) {
            $result = $this->{$this->model}->single($filter);

        }

        if($result) {
            $result['imageUrl'] = '';
            if($result['image']) {
                $result['imageUrl'] = _get_config('global_upload_url') . $result['image'];
            }

            $item_id = $result['id'];

            $notes = $addons = $variations =[];
            if($meta) {
                $notes = array_values(array_filter((@$meta['notes'])?$meta['notes']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));
                $addons =  array_values(array_filter((@$meta['addons'])?$meta['addons']:[],function($single) use ($item_id) {
                    return $item_id == $single['parent'] && $single['type']==='optional';
                }));
                $variations =  array_values(array_filter((@$meta['variations'])?$meta['variations']:[],function($single) use ($item_id) {
                    return $item_id == $single['parent'] && $single['type']==='variant';
                }));
            }
            $this->_exclude_keys($result);
            $this->_sql_to_vue($result);

            $result['hasSpiceLevel'] = $result['hasSpiceLevel']==='1';
            $result['spiceLevel'] = DEFAULT_SPICE_LEVEL;


            $result['notes'] = [];
            $result['selectedNotes'] = [];
            if(!$meta && !$notes) {
                $notes = $this->note->search(['item_id' => $item_id]);
            }
            if($notes) {
                $this->_exclude_keys($notes,array_merge($this->note->exclude_keys),true);
                $this->_sql_to_vue($notes,$this->note->keys,true);
                $result['notes'] = $notes;
            }

            $result['addons'] = [];
            if(!$addons && !$meta ){
                $addons = $this->{$this->model}->getOptionalVariants($result['id']);
            }
            if(@$addons){
                foreach ($addons as &$v){
                    $this->_exclude_keys($v);
                    $this->_sql_to_vue($v);
                    $v['removed'] = false;
                    $v['quantity'] = 1;
                    $v['enabled'] = false;
                }
            }
            $result['addons'] = $addons;
            $result['variations'] = [];
            if(!$meta && !$variations){
                $variations = $this->{$this->model}->getVariants($result['id']);
            }
            if(@$variations){
                foreach ($variations as &$v){
                    $this->_exclude_keys($v);
                    $this->_sql_to_vue($v);
                    $v['removed'] = false;
                    $v['hasSpiceLevel'] = $v['hasSpiceLevel']==='1';
                    $v['spiceLevel'] = DEFAULT_SPICE_LEVEL;
                }
            }
            $result['variations'] = $variations;
            return $result;

        }else{
            return false;
        }
    }

    public function _get_items_meta($params) {

        _model('items/item_note','note');

        $ids = (@$params['ids'])?$params['ids']:false;

        // Variations, Notes, Addons
        if($ids) {
            $this->{$this->model}->where_in('parent',$ids);
            $this->{$this->model}->where('type',ITEM_TYPE_VARIANT);
        }
        if(SORT_VARIATION_BY_NAME) {
            $this->{$this->model}->order_by('title', 'ASC');
        }
        $variations = $this->{$this->model}->search();

        if($ids) {
            $this->{$this->model}->where_in('parent',$ids);
            $this->{$this->model}->where('type',ITEM_TYPE_VARIANT_OPTIONAL);
        }
        $addons = $this->{$this->model}->search();

        if($ids) {
            $this->note->where_in('item_id',$ids);
        }
        $notes = $this->note->search();


        return [
            'variations'       => $variations,
            'notes'     =>  $notes,
            'addons'    =>  $addons,
        ];

    }

    public function _get_item_variations($params){
        $filter = $params['filter'];
        $limit = (isset($params['limit']) && is_int($params['limit']))?$params['limit']:_get_setting('global_limit',9999);
        $offset = (isset($params['offset']) && is_int($params['offset']))?$params['offset']:0;
        $orders = (isset($params['orders']) && is_array($params['orders']))?$params['orders']:[];
        $exclude = false;
        $convert = false;

        if(isset($params['exclude'])) {
            if(is_array($params['exclude'])) {
                $exclude = $params['exclude'];
            }elseif ($params['exclude']===true) {
                $exclude = $this->{$this->model}->exclude_keys;
            }
        }
        if(isset($params['convert'])) {
            if(is_array($params['convert'])) {
                $convert = $params['convert'];
            }elseif ($params['convert']===true) {
                $convert = $this->{$this->model}->keys;
            }
        }
        if($orders) {
            foreach ($orders as $order) {
                $this->{$this->model}->order_by($order['order_by'],$order['order']);
            }
        }
        $records = $this->{$this->model}->search($filter,$limit,$offset);

        if($records) {
            $temp = [];
            foreach ($records as $single) {
                if($exclude) {
                    $this->_exclude_keys($single, $exclude);
                }
                if($convert) {
                    $this->_sql_to_vue($single, $convert);
                }
                $temp[] = $single;
            }
            $records = $temp;
        }
        return $records;
    }

    public function _find_note($params) {

        _model('item_note');

        $note_id = $params['note_id'];

        return $this->item_note->single(['id'=>$note_id]);

    }

    public function _search_addons($params) {

        $this->{$this->model}->where('type',ITEM_TYPE_VARIANT_OPTIONAL);
        $result = $this->{$this->model}->search(['parent'=>$params['parent_id']]);

        if($result) {
            $temp = [];
            foreach($result as &$a){
                $addon = [
                    'itemId'=>$a['id'],
                    'title'=>$a['title'],
                    'rate'=>$a['rate'],
                    'type'=>$a['type'],
                    'quantity'=>1,
                    'enabled'=>false,
                    'parent'=>$a['parent']
                ];
                $temp[] = $addon;

            }
            $result = $temp;
            return $result;
        }
        return false;

    }

    public function _search_notes($params) {

        _model('item_note');

        $item_id = $params['item_id'];
        $convert = (@$params['convert'])?true:false;
        $exclude = (@$params['exclude'])?true:false;

        $result = $this->item_note->search(['item_id'=>$item_id]);

        if($result) {
            if($exclude) {
                $this->_exclude_keys($result,$this->item_note->exclude_keys,true);
            }
            if($convert) {
                $this->_sql_to_vue($result,$this->item_note->keys,true);
            }
            return $result;
        }
        return false;

    }

    public function _get_single_pos($params=[]) {


        $id = $params['id'];
        $warehouse_id = _get_setting('default_warehouse',1);
        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);


        if($result) {
            $item_keys = $this->{$this->model}->keys;
            foreach ($item_keys as $new=>$old) {
                change_array_key($old,$new,$result);
            }
            $result = array_merge($result);
            return $result;

        }else{
            return false;
        }
    }

    public function _single_item_get() {

        _model('items/item_note','note');
        _model('items/item_category','item_category');
        _model('item_icon','icon');

        $id = _input('id');

        if($id) {

            $filter = ['id'=>$id];
            $result = $this->_single($filter);



            if ($result) {

                $item_id = $result['id'];

                $category_id = $result['categoryId'];

                $result['categoryTitle'] = '';
                if($category_id) {
                    $category = $this->item_category->single(['id'=>$category_id]);
                    $result['categoryTitle'] = (@$category['title'])?$category['title']:'';
                }


                $icon_id = $result['icon'];

                $result['iconTitle'] = '';
                if($icon_id) {
                    $icon = $this->icon->single(['id'=>$icon_id]);
                    $result['iconTitle'] = (@$icon['title'])?$icon['title']:'';
                }
                $action =base_url($this->module.'/edit/'.$result['id']);

                $result['edit'] = $action;
                _response_data('obj', $result);

            } else {
                _set_message('The requested details could not be found.', 'warning');
                _response_data('redirect', base_url($this->module));
            }
        }else{
            _set_message('The requested details could not be found.', 'warning');
            _response_data('redirect', base_url($this->module));
        }
        return true;

    }



    //Search in Database
    public function _query_po_get() {


        $query_string = _input('query');

        $items = [];
        $result = $this->{$this->model}->query($query_string);

        foreach ($result as $row) {
            $value = $row['title'] ;

            //TODO below statement will be different for group items
            $display_title = $row['title'];

            $items[] = [
                'id'            =>  '',
                'itemId'        =>  $row['id'],
                'baseUnitId'    =>  $row['unit_id'],
                'purchaseUnitId'=>  $row['purchase_unit_id'],
                'unit'          =>  '',
                'purchaseUnit'  =>  '',
                'value'         =>  $value,
                'title'         =>  $display_title,
                'quantity'      =>  0,
                'rate'          =>  0,
                'unitRate'      =>  0,
                'unitQuantity'  =>  1,
            ];
        }

        _response_data('items',$items);
        return true;

    }

    //Search in Database
    public function _query_so_get() {


        $query_string = _input('query');

        $items = [];
        $result = $this->{$this->model}->query($query_string);

        foreach ($result as $row) {
            $value = $row['title'] ;

            //TODO below statement will be different for group items
            $display_title = $row['title'];

            $items[] = [
                'id'            =>  '',
                'itemId'        =>  $row['id'],
                'baseUnitId'    =>  $row['unit_id'],
                'saleUnitId'    =>  $row['sale_unit_id'],
                'unit'          =>  '',
                'saleUnit'      =>  '',
                'soId'          =>  '',
                'value'         =>  $value,
                'title'         =>  $display_title,
                'quantity'      =>  0,
                'rate'          =>  0,
                'unitRate'      =>  0,
                'unitQuantity'  =>  1,
            ];
        }

        _response_data('items',$items);
        return true;

    }

    //Search in Database
    public function _query_to_get() {

        $query_string = _input('query');

        $items = [];
        $result = $this->{$this->model}->query($query_string);

        foreach ($result as $row) {
            $value = $row['title'] ;
            $items[] = [
                'id'            =>  '',
                'itemId'        =>  $row['id'],
                'toId'          =>  '',
                'value'         =>  $value,
                'title'         =>  ($row['display_title'])?$row['display_title']:$row['title'],
                'quantity'      =>  1,
            ];
        }

        _response_data('items',$items);
        return true;

    }


    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $tours = $this->_get_select_data($params);

        _response_data('tours',$tours);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $tours = $this->{$this->model}->get_active_list();
        if($tours) {
            $tours = get_select_array($tours,'id','title',$include_select,'','Select '.$this->singular);
            return $tours;
        }else{
            return [];
        }

    }

    public function _add_category_put() {

        _model('item_category','category');
        $obj = _input('obj');

        $insert = [
            'title' =>  $obj['title'],
            'added' =>  sql_now_datetime()
        ];
        $this->category->insert($insert);
        $id = $this->category->insert_id();
        $response_obj = [
            'id'    =>  $id,
            'title' =>  $obj['title']
        ];
        _response_data('obj',$response_obj);
        return true;

    }

    public function _add_category_post() {

        _model('item_category','category');
        $obj = _input('obj');

        $update = [
            'id'    =>  $obj['id'],
            'title' =>  $obj['title'],
            'added' =>  sql_now_datetime()
        ];
        $this->category->update($update);
        $response_obj = [
            'id'    =>  $obj['id'],
            'title' =>  $obj['title']
        ];
        _response_data('obj',$response_obj);
        return true;

    }

    public function _add_category_delete() {

        _model('item_category','category');
        $obj = _input('obj');

        $delete = [
            'id'    =>  $obj['id']
        ];
        $this->category->delete($delete);
        _response_data('message','Deleted Successfully');
        return true;

    }

    public function _get_categories($params=[]) {

        _model('item_category','category');

        $order = (@$params['order'])?$params['order']:['order_by'=>'title','order'=>'ASC'];

        $filter = (@$params['filter'] && is_array($params['filter']))?$params['filter']:[];

        $this->category->order_by($order['order_by'],$order['order']);
        $categories = $this->category->search($filter);

        $include_select = true;
        if(isset($params['include_select']) && $params['include_select']===false) {
            $include_select = false;
        }

        if(isset($params['select_data'])) {
            $categories = get_select_array($categories,'id','title',$include_select,'','All');
        }

        return $categories;

    }
    public function _get_web_categories($params=[]) {

        _model('item_category','category');

        $order = (@$params['order'])?$params['order']:['order_by'=>'title','order'=>'ASC'];

        $filter = (@$params['filter'] && is_array($params['filter']))?$params['filter']:[];

        $this->category->order_by($order['order_by'],$order['order']);
        $categories = $this->category->search($filter);

        if($categories){
            foreach($categories as &$c){
                $temp[] = [
                    'id'    =>  $c['id'],
                    'title' =>  $c['title'],
                    'type'  =>  $c['type']
                ];
            }
            $categories = $temp;
        }

        return $categories;

    }

    public function _get_category_items($params=[]) {

        _model('item_category','category');
        _helper('zebra');

        $filter = (@$params['filter'] && is_array($params['filter']))?$params['filter']:[];
        if(isset($params['category_id']) && $params['category_id']) {
            $filter['category_id'] = $params['category_id'];
        }

        $this->{$this->model}->order_by('title');
        $items = $this->{$this->model}->search($filter);

        $this->_sql_to_vue($items,[],true);

        return $items;

    }



    public function _get_category($category_id){
        _model('item_category','category');
        $category = $this->category->single(['id'=>$category_id]);
        if($category){
           $title = $category['title'];
           return $title;
        }
    }
    public function _get_unit($unit_id){
        $filter = [];
        $filter['id'] = $unit_id;

        $unit = _get_module('core/units','_find',['filter'=>$filter]);
        if($unit){
            $code = $unit['code'];
            return $code;
        }
    }
    public function pdf(){}
    public function csv(){}

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    public function import_items(){
        _model('item_category');
        _model('item');
        _model('item_note');

       $categories= json_decode(file_get_contents('itm_category.json'),true);
       if($categories){
           foreach($categories as &$c){
            $this->item_category->insert($c);
           }
       }
       $items= json_decode(file_get_contents('items_saffron.json'),true);
       if($items){
            foreach($items as &$i){
                $item = $i['item'];
                $item['code'] =  _get_ref('ITM',3,6);
                $item['created_by'] =_get_user_id();
                $item['added'] =sql_now_datetime();
                $this->item->insert($item);
                $item_id = $this->item->insert_id();
                _update_ref('ITM');

                if(@$i['variant']){
                    foreach($i['variant'] as &$v){
                        $v['parent'] =  $item_id;
                        $v['code'] =  _get_ref('ITM',3,6);
                        $v['created_by'] =_get_user_id();
                        $action['added'] =sql_now_datetime();
                        $this->item->insert($v);
                        _update_ref('ITM');
                    }
                }

                if(@$i['addons']){
                    foreach($i['addons'] as &$a){
                        $a['parent'] =  $item_id;
                        $a['code'] =  _get_ref('ITM',3,6);
                        $a['created_by'] =_get_user_id();
                        $action['added'] =sql_now_datetime();
                        $this->item->insert($a);
                        _update_ref('ITM');
                    }
                }
                if(@$i['notes']){
                    foreach($i['notes'] as &$n){
                       $note = [
                           'item_id'=>$item_id,
                           'title'=> $n,
                           'added'=>sql_now_datetime()
                       ];
                        $this->item_note->insert($note);
                    }
                }
            }
        }
        redirect(base_url($this->module));

    }

    protected function _load_files() {

        if(_get_method()=='index') {
            _load_plugin(['dt']);
        }

        if(_get_method()=='add' || _get_method()=='edit') {
            _load_plugin(['vue_multiselect']);
            _helper('control');
            $this->layout = 'items_form_view';
            _page_script_override('items/items-form');
        }
        _set_js_var('defaultTaxable',_get_setting('default_taxable',1),'s');

    }
}
