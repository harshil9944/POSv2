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
        _model('items/item_price','price');

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
        $filters['filter'] = ['type'=>'single'];
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
                $item_price = $this->price->single(['item_id' => $result['id']]);
                $item_name = _vue_text_link($result['title'],'handleViewItem('.$result['id'].')','View Item');

                $price = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format($item_price['sale_price'])
                ];
                $body[] = [
                    $item_name,
                    $result['categoryTitle'],
                    $price,
                    $action_cell
                ];
            }
        }

        $heading = [
            $this->singular . ' Name',
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
            'name'	    =>  'Item Groups',
            'class'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'items/item_groups',
            'module'    =>  'items',
            'children'  =>  []
        );

        $items[] = array(
            'name'	    =>  'Addon Items',
            'class'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'items/addon_items',
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

        /*$items[] = array(
            'name'	    =>  'Composite Items',
            'class'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'items/item_composites',
            'module'    =>  'items',
            'children'  =>  []
        );

        $items[] = array(
            'name'	    =>  'Inventory Adjustments',
            'class'     =>  '',
            'icon'      =>  '',
            'path'      =>  'items/adjustments',
            'module'    =>  'items',
            'children'  =>  []
        );*/

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

        _model('item_feature','feature');
        _model('item_category','category');
        _model('item_icon','icon');

        $this->{$this->model}->order_by('title','ASC');
        $this->{$this->model}->select('id,title');
        $items = $this->{$this->model}->search(['is_addon'=>1]);

        $params['include_select'] = true;
        $units = _get_module('core/units','_get_select_data',$params);
        $vendors = _get_module('contacts/vendors','_get_select_data',$params);
        //$vendors = [['id'=>'','value'=>'Select Vendor']];

        $features = $this->feature->get_list();
        $features = get_select_array($features,'id','title',false);

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

        _response_data('addonItems',($items)?$items:[]);
        _response_data('features',$features);
        _response_data('categories',$categories);
        _response_data('units',$units);
        _response_data('vendors',$vendors);
        _response_data('icons',$icons);
        _response_data('printLocations',$printLocations);
        return true;

    }

    public function _action_put() {

        _clear_cache('item');

        _model('items/item_sku','item_sku');
        _model('items/item_price','price');
        _model('items/item_note','note');
        _model('items/item_addon','addon');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('item_feature_value','feature_value');

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
        $item['code'] = _get_ref('SI',3,6);
        $item['created_by'] = _get_user_id();
        $item['added'] = sql_now_datetime();
        $item['type'] = 'single';

        $warehouse_id = _get_setting('default_warehouse',1);

        if($this->{$this->model}->insert($item)) {
            $item_id = $this->{$this->model}->insert_id();
            $item['code'] = _update_ref('SI');

            $item_sku = $obj['item_sku_table'];
            $item_sku['item_id'] = $item_id;

            $this->item_sku->insert($item_sku);
            $sku_id = $this->item_sku->insert_id();

            if($obj['item_price_table']) {
                foreach ($obj['item_price_table'] as $value) {
                    $value['item_id'] = $item_id;
                    $value['sku_id'] = $sku_id;
                    $value['purchase_currency'] = _get_setting('currency_code','INR');
                    $value['sale_currency'] = _get_setting('currency_code','INR');
                    $value ['added']        =  sql_now_datetime();
                    $this->price->insert($value);
                }
            }

            if(@$obj['addons_table']) {
                foreach ($obj['addons_table'] as $addon) {
                    unset($addon['id']);
                    $addon['item_id'] = $item_id;
                    $addon['sku_id'] = 0;
                    $addon['added'] = sql_now_datetime();
                    $this->addon->insert($addon);
                }
            }

            if(@$obj['notes_table']) {
                foreach ($obj['notes_table'] as $note) {
                    $note['item_id'] = $item_id;
                    $note['added'] = sql_now_datetime();
                    $this->note->insert($note);
                }
            }

           /* if($obj['feature_value_table']) {
                foreach ($obj['feature_value_table'] as $value) {
                    $value['item_id'] = $item_id;
                    $this->feature_value->insert($value);
                }
            }*/
            $rate = 0;
            if($obj['item_inventory_table']['quantity'] > 0 ) {
                $rate = (float)$obj['item_inventory_table']['amount'] / (float)$obj['item_inventory_table']['quantity'];
            }else{
                $obj['item_inventory_table']['amount'] = 0;
            }

            $on_hand = $obj['item_inventory_table']['quantity'];
            $amount =$obj['item_inventory_table']['amount'];

            $current_item = [
                'item_id'       =>  $item_id,
                'sku_id'        =>  $sku_id
            ];
            $this->_update_warehouse_opening(['item'=>$current_item]);

            $item_inventory = [
                'item_id'       =>  $item_id,
                'sku_id'        =>  $sku_id,
                'warehouse_id'  =>  $warehouse_id,
                'reason'        =>  'opening',
                'date'          =>  sql_now_datetime(),
                'quantity'      =>  $on_hand,
                'rate'          =>  $rate,
                'amount'        =>  $amount,
                'created_by'    =>  _get_user_id(),
                'added'         =>  sql_now_datetime()
            ];
            $this->_update_opening_inventory(['inventory'=>$item_inventory]);
            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;

    }

    public function _action_post() {

        _clear_cache('item');

        _model('items/item_sku','item_sku');
        _model('items/item_price','price');
        _model('items/item_note','note');
        _model('items/item_addon','addon');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('item_feature_value','feature_value');

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

        $item['created_by'] = _get_user_id();
        $item['added'] = sql_now_datetime();
        $warehouse_id = _get_setting('default_warehouse',1);
        $item_id = $item['id'];
        unset($item['id']);

        $filter=[
            'id'    =>  $item_id
        ];


        if($this->{$this->model}->update($item,$filter)) {
            $item_sku = $obj['item_sku_table'];
            $item_sku['item_id'] = $item_id;
            $sku_id = $item_sku['id'];
            unset($item_sku['id']);

            $this->item_sku->update($item_sku,['id'=>$sku_id]);

            $this->price->delete(['item_id'=>$item_id]);
            if(@$obj['item_price_table']) {
                foreach ($obj['item_price_table'] as $value) {
                    $value['item_id'] = $item_id;
                    $value['sku_id'] = $sku_id;
                    $value['purchase_currency'] = _get_setting('currency_code','INR');
                    $value['sale_currency'] = _get_setting('currency_code','INR');
                    $this->price->insert($value);
                }
            }

            $ignore = [];
            if(@$obj['addons_table']) {
                foreach ($obj['addons_table'] as $addon) {
                    if(@$addon['id']!='') {
                        $this->addon->update($addon,['id'=>$addon['id']]);
                        $ignore[] = $addon['id'];
                    }else {
                        unset($addon['id']);
                        $addon['item_id'] = $item_id;
                        $addon['sku_id'] = 0;
                        $addon['added'] = sql_now_datetime();
                        $this->addon->insert($addon);
                        $ignore[] = $this->addon->insert_id();
                    }
                }
            }
            $this->_clear_addons($item_id,$ignore);

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

            /*$this->feature_value->delete(['item_id'=>$item_id]);
            if($obj['feature_value_table']) {
                foreach ($obj['feature_value_table'] as $value) {
                    $value['item_id'] = $item_id;
                    $this->feature_value->insert($value);
                }
            }*/
           // $inventory = $item_sku['item_inventory_table'];
           // unset($item_sku['item_inventory_table']);
            $rate = 0;
            if($obj['item_inventory_table']['quantity'] > 0 ) {
                $rate = (float)$obj['item_inventory_table']['amount'] / (float)$obj['item_inventory_table']['quantity'];
            }else{
                $obj['item_inventory_table']['amount'] = 0;
            }

            $on_hand = $obj['item_inventory_table']['quantity'];
            $amount =$obj['item_inventory_table']['amount'];

            $current_item = [
                'item_id'       =>  $item_id,
                'sku_id'        =>  $sku_id
            ];
            $this->_update_warehouse_opening(['item'=>$current_item]);

            $item_inventory = [
                'item_id'       =>  $item_id,
                'sku_id'        =>  $sku_id,
                'warehouse_id'  =>  $warehouse_id,
                'reason'        =>  'opening',
                'date'          =>  sql_now_datetime(),
                'quantity'      =>  $on_hand,
                'rate'          =>  $rate,
                'amount'        =>  $amount,
                'created_by'    =>  _get_user_id(),
                'added'         =>  sql_now_datetime()
            ];

            $this->_update_opening_inventory(['inventory'=>$item_inventory]);

            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_delete() {

        _clear_cache('item');

        _model('items/item_sku','item_sku');
        _model('items/item_inventory','inventory');
        _model('items/item_note','note');
        _model('items/item_addon','addon');
        _model('items/item_stock','stock');
        _model('item_feature_value','feature_value');

        $ignore_list = [];

        $item_id = _input('id');

        if(!in_array($item_id,$ignore_list)) {

            $this->inventory->gs();
            $this->inventory->or_where('reason','purchase');
            $this->inventory->or_where('reason','sale');
            $this->inventory->or_where('reason','transferIn');
            $this->inventory->or_where('reason','transferOut');
            $this->inventory->ge();

            $items = $this->inventory->search(['item_id'=>$item_id]);

            if(count($items)==0) {
                $result = $this->{$this->model}->single(['id' => $item_id]);

                if ($result) {

                    //Clear Existing Records
                    $this->inventory->delete(['item_id' => $item_id]);
                    $this->stock->delete(['item_id' => $item_id]);
                    $this->feature_value->delete(['item_id' => $item_id]);

                    $this->_clear_notes($item_id,[]);
                    $this->_clear_addons($item_id,[]);

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
                _response_data('message', _line('error_cannot_delete_item'));
                return false;
            }
        }else{
            _response_data('message', _line('error_cannot_delete_protected_item'));
            return false;
        }
    }

    private function _prep_obj(&$obj) {
        $item_keys = $this->{$this->model}->keys;
        $item_sku_keys = $this->item_sku->keys;
        $item_inventory_keys = $this->inventory->keys;
        $item_feature_value_keys =$this->feature_value->keys;
        $item_price_keys = $this->price->keys;
        $item_notes_keys = $this->note->keys;
        $item_addons_keys = $this->addon->keys;

        $obj['item_table'] = [];
        foreach ($item_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['item_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }
        $obj['item_table']['is_addon'] = 0;
        $obj['item_table']['purchase_unit_id'] = $obj['item_table']['sale_unit_id'] = $obj['item_table']['unit_id'];

        if(isset($obj['prices'])) {
            foreach ($obj['prices'] as $single) {
                foreach ($item_price_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $obj['item_price_table'][] = $single;
            }
            unset($obj['prices']);
        }

        foreach ($item_sku_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['item_sku_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }

        if(isset($obj['features'])) {
            foreach ($obj['features'] as $single) {
                foreach ($item_feature_value_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $obj['feature_value_table'][] = $single;
            }
            unset($obj['features']);
        }

        if(isset($obj['addons'])) {
            foreach ($obj['addons'] as $single) {
                foreach ($item_addons_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $single['addon_item_id'] = $single['addon_item_id']['id'];
                unset($single['quantity'],$single['enabled']);
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

        if($obj['openingStock'] && !is_nan($obj['openingStock'])){
        }else{
            $obj['openingStock'] = 0.00;
        }

        if($obj['openingStockValue'] && !is_nan($obj['openingStockValue'])){
        }else{
            $obj['openingStockValue'] = 0.00;
        }

        foreach ($item_inventory_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['item_inventory_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }
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
        $this->addon->delete(['item_id'=>$item_id]);
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

    public function update_soh($item_id,$sku_id,$warehouse_id) {

        //$sql = "SELECT SUM(ii.quantity) as stock_on_hand FROM itm_inventory ii WHERE ii.item_id=$item_id AND ii.sku_id=$sku_id AND ii.warehouse_id=$warehouse_id";

        $filter = [
            'item_id'       =>  $item_id,
            'sku_id'        =>  $sku_id,
            'warehouse_id'  =>  $warehouse_id
        ];
        $this->select_sum('quantity','stock_on_hand');
        $result = $this->single($filter,ITEM_INVENTORY_TABLE);

        $stock_on_hand = 0.00;
        if($result) {
            $stock_on_hand = $result['stock_on_hand'];
        }
        $stock = [
            'item_id'       =>  $item_id,
            'sku_id'        =>  $sku_id,
            'warehouse_id'  =>  $warehouse_id,
            'on_hand'       =>  $stock_on_hand,
            'modified'      =>  sql_now_datetime()
        ];
        if(!$this->single($filter)) {
            $this->insert($stock);
        }else{
            $this->update($stock,$filter);
        }
        return $sku_id;
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

            if(@$result['addons']) {
                $temp = [];
                foreach ($result['addons'] as $addon) {
                    $addon['addonItemId'] = [
                        'id'    =>  $addon['addonItemId'],
                        'title' =>  ''
                    ];
                    $temp[] = $addon;
                }
                $result['addons'] = $temp;
            }

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

        _model('items/item_sku','item_sku');
        _model('items/item_price','price');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('items/item_addon','addon');
        _model('items/item_note','note');
        _model('item_feature_value','feature_value');

        $warehouse_id = _get_setting('default_warehouse',1);
        $filter = ['id'=>$id];
        if(!$result) {
            $result = $this->{$this->model}->single($filter);
        }
        $item_sku_exclude_fields = $this->item_sku->exclude_keys;
        $item_feature_exclude_fields = $this->feature_value->exclude_keys;
        $item_price_exclude_fields = $this->price->exclude_keys;

        if($result) {
            $result['imageUrl'] = '';
            if($result['image']) {
                $result['imageUrl'] = _get_config('global_upload_url') . $result['image'];
            }

            $item_id = $result['id'];

            $item_sku = $item_prices = $notes = $addons = $features = $inventory = [];
            if($meta) {
                $item_sku = array_values(array_filter((@$meta['sku'])?$meta['sku']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));
                $item_sku = ($item_sku)?$item_sku[0]:[];

                $item_prices = array_values(array_filter((@$meta['prices'])?$meta['prices']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));

                $notes = array_values(array_filter((@$meta['notes'])?$meta['notes']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));

                $addons = array_values(array_filter((@$meta['addons'])?$meta['addons']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));

                if(_get_setting('enable_item_features',false)) {
                    $item_features = array_values(array_filter((@$meta['features'])?$meta['features']:[],function($single) use ($item_id) {
                        return $item_id == $single['item_id'];
                    }));
                }
            }

            $item_sku_keys = $this->item_sku->keys;
            $item_price_keys = $this->price->keys;
            $item_inventory_keys =$this->inventory->keys;
            $item_feature_value_keys =$this->feature_value->keys;

            $this->_exclude_keys($result);
            $this->_sql_to_vue($result);

            $result['hasSpiceLevel'] = $result['hasSpiceLevel']==='1';
            $result['spiceLevel'] = DEFAULT_SPICE_LEVEL;

            if(!$meta && !$item_sku) {
                $item_sku = $this->item_sku->single(['item_id' => $item_id]);
            }
            $sku_id = $item_sku['id'];
            $item_sku = filter_array_keys($item_sku,$item_sku_exclude_fields);

            foreach ($item_sku_keys as $new=>$old) {
                change_array_key($old,$new,$item_sku);
            }

            if(!$meta && !$item_prices) {
                $item_prices = $this->price->search(['item_id' => $item_id]);
            }

            $base_price = [];
            $temp = [];
            foreach ($item_prices as $single) {
                $single = filter_array_keys($single,$item_price_exclude_fields);
                foreach ($item_price_keys as $vue=>$sql) {
                    change_array_key($sql, $vue, $single);
                }
                if($single['unitId']===$result['unit']) {
                    $base_price[] = $single;
                }else {
                    $temp[] = $single;
                }
            }
            $item_prices = array_merge($base_price,$temp);

            $result['prices'] = $item_prices;

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
            //$result['selectedAddons'] = [];
            if(!$meta && !$addons) {
                $addons = $this->addon->search(['item_id' => $item_id]);
            }
            if($addons) {
                $temp = [];
                foreach ($addons as $addon) {
                    $addon['quantity'] = 1;
                    $addon['enabled'] = false;
                    $temp[] = $addon;
                }
                $addons = $temp;
                $this->_exclude_keys($addons,array_merge($this->addon->exclude_keys),true);
                $this->_sql_to_vue($addons,$this->addon->keys,true);
                $result['addons'] = $addons;
            }

            $item_features = [];
            if(_get_setting('enable_item_features',false)) {
                if(!$meta && !$item_features) {
                    $item_features = $this->feature_value->search(['item_id' => $item_id]);
                }
                $temp = [];
                foreach ($item_features as $feature) {
                    $feature = filter_array_keys($feature, $item_feature_exclude_fields);
                    foreach ($item_feature_value_keys as $vue => $sql) {
                        change_array_key($sql, $vue, $feature);
                    }
                    $temp[] = $feature;
                }
                $item_features = $temp;
            }

            $result['features'] = $item_features;

            $item_inventory = [];
            if(1==2) {
                $inventory = $this->inventory->single(['item_id' => $item_id, 'sku_id' => $sku_id, 'warehouse_id' => $warehouse_id, 'reason' => 'opening']);
                if ($inventory) {
                    $item_inventory = [
                        'quantity' => $inventory['quantity'],
                        'amount' => $inventory['amount']
                    ];
                }

                foreach ($item_inventory_keys as $new => $old) {
                    change_array_key($old, $new, $item_inventory);
                }
            }
            if(isset($item_sku['name'])){ unset($item_sku['name']); }
            $result = array_merge($result,$item_sku,$item_inventory);

            return $result;

        }else{
            return false;
        }
    }

    public function _get_items_meta($params) {

        _model('items/item_sku','item_sku');
        _model('items/item_price','price');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('items/item_addon','addon');
        _model('items/item_note','note');
        _model('item_feature_value','feature_value');

        $ids = (@$params['ids'])?$params['ids']:false;

        //SKUs, Prices, Notes, Addons, Features, Inventory
        if($ids) {
            $this->item_sku->where_in('item_id',$ids);
        }
        if(SORT_VARIATION_BY_NAME) {
            $this->item_sku->order_by('title', 'ASC');
        }
        $skus = $this->item_sku->search();

        if($ids) {
            $this->price->where_in('item_id',$ids);
        }
        $prices = $this->price->search();

        if($ids) {
            $this->note->where_in('item_id',$ids);
        }
        $notes = $this->note->search();

        if($ids) {
            $this->addon->where_in('item_id',$ids);
        }
        $this->addon->order_by('id','ASC');
        $addons = $this->addon->search();

        $features = [];
        if(_get_setting('enable_item_features',false)) {
            if($ids) {
                $this->feature_value->where_in('item_id',$ids);
            }
            $features = $this->feature_value->search();
        }
        $inventory = [];
        if(1==2) {
            if($ids) {
                $this->inventory->where_in('item_id',$ids);
            }
            $inventory = $this->inventory->search();
        }

        return [
            'sku'       =>  $skus,
            'prices'    =>  $prices,
            'notes'     =>  $notes,
            'addons'    =>  $addons,
            'features'  =>  $features,
            'inventory' =>  $inventory
        ];

    }

    public function _find_addon($params) {

        _model('item_addon');

        $addon_id = $params['addon_id'];

        return $this->item_addon->single(['id'=>$addon_id]);

    }

    public function _find_note($params) {

        _model('item_note');

        $note_id = $params['note_id'];

        return $this->item_note->single(['id'=>$note_id]);

    }

    public function _search_addons($params) {

        _model('item_addon');

        $item_id = $params['item_id'];
        $convert = (@$params['convert'])?true:false;
        $exclude = (@$params['exclude'])?true:false;

        $result = $this->item_addon->search(['item_id'=>$item_id]);

        if($result) {
            if($exclude) {
                $this->_exclude_keys($result,$this->item_addon->exclude_keys,true);
            }
            if($convert) {
                $this->_sql_to_vue($result,$this->item_addon->keys,true);
            }
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
        _model('items/item_sku','item_sku');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('item_feature_value','feature_value');

        $id = $params['id'];
        $warehouse_id = _get_setting('default_warehouse',1);
        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);
        $item_sku_exclude_fields = $this->item_sku->exclude_keys;
        $item_feature_exclude_fields = $this->feature_value->exclude_keys;

        if($result) {
            $item_keys = $this->{$this->model}->keys;
            $item_sku_keys = $this->item_sku->keys;
            $item_inventory_keys =$this->inventory->keys;
            $item_feature_value_keys =$this->feature_value->keys;

            foreach ($item_keys as $new=>$old) {
                change_array_key($old,$new,$result);
            }

            $item_sku = $this->item_sku->single(['item_id'=>$result['id']]);
            $sku_id = $item_sku['id'];
            $item_sku = filter_array_keys($item_sku,$item_sku_exclude_fields);

            foreach ($item_sku_keys as $new=>$old) {
                change_array_key($old,$new,$item_sku);
            }

            $this->feature_value->left_join(FEATURE_TABLE,FEATURE_TABLE.'.id='.FEATURE_VALUE_TABLE.'.feature_id');
            $this->feature_value->select(FEATURE_TABLE . '.title as key, ' . FEATURE_VALUE_TABLE . '.title as value');
            $item_features = $this->feature_value->search(['item_id'=>$result['id']]);
            $item_features = filter_array_keys($item_features,$item_feature_exclude_fields);

            $temp = [];
            foreach ($item_features as $feature) {
                foreach ($item_feature_value_keys as $vue=>$sql) {
                    change_array_key($sql, $vue, $feature);
                }
                $temp[] = $feature;
            }
            $item_features = $temp;

            $result['features'] = $item_features;

            $inventory = $this->inventory->single(['item_id'=>$result['id'],'sku_id'=>$sku_id,'warehouse_id'=>$warehouse_id,'reason'=>'opening']);
            $item_inventory = [];
            if($inventory) {
                $item_inventory = [
                    'quantity'  =>  $inventory['quantity'],
                    'amount'    =>  $inventory['amount']
                ];
            }

            foreach ($item_inventory_keys as $new=>$old) {
                change_array_key($old,$new,$item_inventory);
            }

            $result = array_merge($result,$item_sku,$item_inventory);

            return $result;

        }else{
            return false;
        }
    }

    public function _single_item_get() {

        _model('items/item_addon','addon');
        _model('items/item_price','price');
        _model('items/item_note','note');
        _model('items/item_sku','item_sku');
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

    public function _get_item_skus($params) {

        _model('items/item_sku','item_sku');

        $filter = $params['filter'];
        $limit = (isset($params['limit']) && is_int($params['limit']))?$params['limit']:_get_setting('global_limit',500);
        $offset = (isset($params['offset']) && is_int($params['offset']))?$params['offset']:0;
        $orders = (isset($params['orders']) && is_array($params['orders']))?$params['orders']:[];
        $exclude = false;
        $convert = false;
        if(isset($params['exclude'])) {
            if(is_array($params['exclude'])) {
                $exclude = $params['exclude'];
            }elseif ($params['exclude']===true) {
                $exclude = $this->item_sku->exclude_keys;
            }
        }
        if(isset($params['convert'])) {
            if(is_array($params['convert'])) {
                $convert = $params['convert'];
            }elseif ($params['convert']===true) {
                $convert = $this->item_sku->keys;
            }
        }
        if($orders) {
            foreach ($orders as $order) {
                $this->item_sku->order_by($order['order_by'],$order['order']);
            }
        }
        $records = $this->item_sku->search($filter,$limit,$offset);
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

    //Search in Database
    public function _query_po_get() {

        _model('item_price','price');

        $query_string = _input('query');

        $items = [];
        $result = $this->{$this->model}->query($query_string);

        foreach ($result as $row) {
            $value = $row['title'] . ' (' . $row['sku'] . ')';
            $prices = $this->price->search(['item_id'=>$row['id'],'sku_id'=>$row['sku_id']]);
            if($prices) {
                $temp = [];
                foreach ($prices as $price) {
                    $custom_exclude = ['purchase_price'];
                    $this->_exclude_keys($price, array_merge($this->price->exclude_keys,$custom_exclude));
                    $this->_sql_to_vue($price, $this->price->keys);
                    $temp[] = $price;
                }
                $prices = $temp;
            }

            //TODO below statement will be different for group items
            $display_title = $row['title'];// . ' - ' . $row['sku'];

            $items[] = [
                'id'            =>  '',
                'itemId'        =>  $row['id'],
                'baseUnitId'    =>  $row['unit_id'],
                'purchaseUnitId'=>  $row['purchase_unit_id'],
                'unit'          =>  '',
                'purchaseUnit'  =>  '',
                'poId'          =>  '',
                'skuId'         =>  $row['sku_id'],
                'value'         =>  $value,
                'prices'        =>  $prices,
                'title'         =>  $display_title,
                'quantity'      =>  0,
                'rate'          =>  0,
                'unitRate'      =>  0,
                'unitQuantity'  =>  1,
                'sku'           =>  $row['sku']
            ];
        }

        _response_data('items',$items);
        return true;

    }

    //Search in Database
    public function _query_so_get() {

        _model('item_price','price');

        $query_string = _input('query');

        $items = [];
        $result = $this->{$this->model}->query($query_string);

        foreach ($result as $row) {
            $value = $row['title'] . ' (' . $row['sku'] . ')';
            $prices = $this->price->search(['item_id'=>$row['id'],'sku_id'=>$row['sku_id']]);
            if($prices) {
                $temp = [];
                foreach ($prices as $price) {
                    $custom_exclude = ['purchase_price'];
                    $this->_exclude_keys($price, array_merge($this->price->exclude_keys,$custom_exclude));
                    $this->_sql_to_vue($price, $this->price->keys);
                    $temp[] = $price;
                }
                $prices = $temp;
            }

            //TODO below statement will be different for group items
            $display_title = $row['title'];// . ' - ' . $row['sku'];

            $items[] = [
                'id'            =>  '',
                'itemId'        =>  $row['id'],
                'baseUnitId'    =>  $row['unit_id'],
                'saleUnitId'    =>  $row['sale_unit_id'],
                'unit'          =>  '',
                'saleUnit'      =>  '',
                'soId'          =>  '',
                'skuId'         =>  $row['sku_id'],
                'value'         =>  $value,
                'prices'        =>  $prices,
                'title'         =>  $display_title,
                'quantity'      =>  0,
                'rate'          =>  0,
                'unitRate'      =>  0,
                'unitQuantity'  =>  1,
                'sku'           =>  $row['sku']
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
            $value = $row['title'] . ' (' . $row['sku'] . ')';
            $items[] = [
                'id'            =>  '',
                'itemId'        =>  $row['id'],
                'toId'          =>  '',
                'skuId'         =>  $row['sku_id'],
                'value'         =>  $value,
                'title'         =>  ($row['display_title'])?$row['display_title']:$row['title'].' - '.$row['sku'],
                'sku'           =>  $row['sku'],
                'quantity'      =>  1,
                'sourceStock'   =>  0,
                'destStock'     =>  0
            ];
        }

        _response_data('items',$items);
        return true;

    }

    public function _update_warehouse_opening($params=[]) {

        $warehouses = _get_module('warehouses','_get_active_list',[]);
        $item = $params['item'];

        if($warehouses) {
            foreach ($warehouses as $warehouse) {

                $default_inventory = [
                    'item_id'       =>  $item['item_id'],
                    'sku_id'        =>  $item['sku_id'],
                    'warehouse_id'  =>  $warehouse['id'],
                    'reason'        =>  'opening',
                    'date'          =>  sql_now_datetime(),
                    'quantity'      =>  0,
                    'rate'          =>  0,
                    'amount'        =>  0,
                    'created_by'    =>  _get_user_id(),
                    'added'         =>  sql_now_datetime()
                ];
                $this->_update_opening_inventory(['inventory'=>$default_inventory]);

            }
        }
        return true;
    }

    public function _update_opening_inventory($params=[]) {

        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');

        $item_inventory = $params['inventory'];
        $item_id = $item_inventory['item_id'];
        $sku_id = $item_inventory['sku_id'];
        $warehouse_id = $item_inventory['warehouse_id'];

        $this->inventory->update_opening($item_inventory);

        $this->stock->update_soh($item_id,$sku_id,$warehouse_id);
        return true;
    }

    public function _update_inventory($params=[]) {

        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');

        $item_inventory = $params['inventory'];
        $item_id = $item_inventory['item_id'];
        $sku_id = $item_inventory['sku_id'];
        $warehouse_id = $item_inventory['warehouse_id'];

        $this->inventory->update_inventory($item_inventory);

        $this->stock->update_soh($item_id,$sku_id,$warehouse_id);
        return true;
    }

    public function _clear_inventory($params=[]) {

        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');

        $order = $params['order'];
        $order_items = $params['order_items'];

        $order_id = $order['id'];
        $warehouse_id = $order['warehouse_id'];
        $reason = $order['reason'];

        foreach ($order_items as $item) {
            $item_id = $item['item_id'];
            $sku_id = $item['sku_id'];

            $filter = [
                'item_id'       =>  $item_id,
                'order_id'      =>  $order_id,
                'sku_id'        =>  $sku_id,
                'warehouse_id'  =>  $warehouse_id,
                'reason'        =>  $reason
            ];
            $this->inventory->delete($filter);
            $this->stock->update_soh($item_id, $sku_id, $warehouse_id);
        }

        return true;
    }

    public function _duplicate_sku_get() {

        $sku = _input('sku');

        _model('items/item_sku','item_sku');

        $sku_exists = $this->item_sku->sku_exists($sku);
        _response_data('result',$sku_exists);
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

    public function _add_feature_put() {

        _model('item_feature','feature');
        $obj = _input('obj');

        $insert = [
            'title' =>  $obj['title'],
            'added' =>  sql_now_datetime()
        ];
        $this->feature->insert($insert);
        $id = $this->feature->insert_id();
        $response_obj = [
            'id'    =>  $id,
            'title' =>  $obj['title']
        ];
        _response_data('obj',$response_obj);
        return true;

    }

    public function _add_feature_post() {

        _model('item_feature','feature');
        $obj = _input('obj');

        $update = [
            'id'    =>  $obj['id'],
            'title' =>  $obj['title'],
            'added' =>  sql_now_datetime()
        ];
        $this->feature->update($update);
        $response_obj = [
            'id'    =>  $obj['id'],
            'title' =>  $obj['title']
        ];
        _response_data('obj',$response_obj);
        return true;

    }

    public function _add_feature_delete() {

        _model('item_feature','feature');
        $obj = _input('obj');

        $delete = [
            'id'    =>  $obj['id']
        ];
        $this->feature->delete($delete);
        _response_data('message','Deleted Successfully');
        return true;

    }

    public function _get_features($params=[]) {

        _model('item_feature','feature');

        $features = $this->feature->get_list();
        $features = get_select_array($features,'id','title',false);

        return $features;
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

    public function _stock_get() {
        _model('items/item_stock','stock');

        $items = _input('items');
        $warehouse_id = _input('warehouse_id');

        $result = [];

        if(count($items)) {
            foreach ($items as $item) {
                $item_id = $item['itemId'];
                $sku_id = $item['skuId'];

                $filter = [
                    'item_id'   =>  $item_id,
                    'sku_id'    =>  $sku_id
                ];

                $stock = $this->stock->single(array_merge($filter,['warehouse_id'=>$warehouse_id]));

                $result[] = [
                    'item_id'   =>  $item_id,
                    'sku_id'    =>  $sku_id,
                    'warehouse' =>  [
                        'id'        =>  $warehouse_id,
                        'on_hand'   =>  ($stock)?$stock['on_hand']:0.00
                    ]
                ];
            }
        }
        _response_data('items',$result);
        return true;
    }

    public function _transfer_stock_get() {


        _model('items/item_stock','stock');

        $items = _input('items');
        $source_wh = _input('sourceWh');
        $dest_wh = _input('destWh');

        $result = [];

        if(count($items)) {
            foreach ($items as $item) {
                $item_id = $item['itemId'];
                $sku_id = $item['skuId'];

                $filter = [
                    'item_id'   =>  $item_id,
                    'sku_id'    =>  $sku_id
                ];

                $this->stock->update_soh($item_id,$sku_id,$source_wh);

                $source_stock = $this->stock->single(array_merge($filter,['warehouse_id'=>$source_wh]));
                $dest_stock = $this->stock->single(array_merge($filter,['warehouse_id'=>$dest_wh]));

                $result[] = [
                    'item_id'   =>  $item_id,
                    'sku_id'    =>  $sku_id,
                    'source_wh' =>  [
                        'id'        =>  $source_wh,
                        'on_hand'   =>  ($source_stock)?$source_stock['on_hand']:0.00
                    ],
                    'dest_wh'   =>  [
                        'id'        =>  $dest_wh,
                        'on_hand'   =>  ($dest_stock)?$dest_stock['on_hand']:0.00
                    ],
                ];
            }
        }
        _response_data('items',$result);
        return true;
    }
    /* public function export() {
        $this->view = false;
        $spreadsheet = new Spreadsheet();
        $items = $this-> _get_items_export();
        $basic_items = $items['Basic'];
        $variants_items = $items['Variants'];
        $prices_items = $items['Prices'];
        $addons_items = $items['Addons'];
        $notes_items = $items['Notes'];
        $stock_items = $items['Stock'];
        $features_items = $items['Features'];
        $basic_fields = [
            'item_code','category','taxable','status','title','description','unit','purchase_unit','sale_unit','manufacturer',
            'vendor','image','has_spice_level','is_addon','print_location','web_status','app_status','pos_status','icon'
        ];
        $variants_fields = ['item_code','sku','title','upc','is_veg','reorder_level','weight','ean'];
        $prices_fields = ['item_code','sku','unit','purchase_price','sale_price','conversion_rate'];
        $addons_fields = ['item_code','sku','addon_item_code','type','title','sale_price'];
        $notes_fields = ['item_code','title'];
        $stock_fields = ['item_code','sku','warehouse','opening_stock','opening_stock_value'];
        $features_fields = ['item_code','feature','value'];
        $basic_sheet = $spreadsheet->getActiveSheet()->setTitle('Basic');
        $basic_sheet->fromArray($basic_fields, NULL, 'A1');
        $row=2;
        foreach ($basic_items as $item) {
            $char='A';
            foreach($basic_fields as $f) {
                $data = $item[$f];
                $basic_sheet->setCellValue($char.$row, $data);
                $char = chr(ord($char)+1);
            }
            $row++;
        }

        $spreadsheet->createSheet();
        $variants_sheet = $spreadsheet->setActiveSheetIndex(1)->setTitle('Variants');
        $variants_sheet->fromArray($variants_fields, NULL, 'A1');
        $row=2;
        foreach ($variants_items as $item) {
            $char='A';
            foreach($variants_fields as $f) {
                $data = $item[$f];
                $variants_sheet->setCellValue($char.$row, $data);
                $char = chr(ord($char)+1);
            }
            $row++;
        }
        $spreadsheet->createSheet();
        $prices_sheet = $spreadsheet->setActiveSheetIndex(2)->setTitle('Prices');
        $prices_sheet->fromArray($prices_fields, NULL, 'A1');
        $row=2;
        foreach ($prices_items as $item) {
            $char='A';
            foreach($prices_fields as $f) {
                $data = $item[$f];
                $prices_sheet->setCellValue($char.$row, $data);
                $char = chr(ord($char)+1);
            }
            $row++;
        }

        $spreadsheet->createSheet();
        $addons_sheet = $spreadsheet->setActiveSheetIndex(3)->setTitle('Addons');
        $addons_sheet->fromArray($addons_fields, NULL, 'A1');
        $row=2;
        foreach ($addons_items as $item) {
            $char='A';
            foreach($addons_fields as $f) {
                $data = $item[$f];
                $addons_sheet->setCellValue($char.$row, $data);
                $char = chr(ord($char)+1);
            }
            $row++;
        }
        $spreadsheet->createSheet();
        $notes_sheet = $spreadsheet->setActiveSheetIndex(4)->setTitle('Notes');
        $notes_sheet->fromArray($notes_fields, NULL, 'A1');
        $row=2;
        foreach ($notes_items as $item) {
            $char='A';
            foreach($notes_fields as $f) {
                $data = $item[$f];
                $notes_sheet->setCellValue($char.$row, $data);
                $char = chr(ord($char)+1);
            }
            $row++;
        }
        $spreadsheet->createSheet();
        $stock_sheet = $spreadsheet->setActiveSheetIndex(5)->setTitle('Stock');
        $stock_sheet->fromArray($stock_fields, NULL, 'A1');
        $row=2;
        foreach ($stock_items as $item) {
            $char='A';
            foreach($stock_fields as $f) {
                $data = $item[$f];
                $stock_sheet->setCellValue($char.$row, $data);
                $char = chr(ord($char)+1);
            }
            $row++;
        }
        $spreadsheet->createSheet();
        $features_sheet = $spreadsheet->setActiveSheetIndex(6)->setTitle('Features');
        $features_sheet->fromArray($features_fields, NULL, 'A1');
        $row=2;
        foreach ($features_items as $item) {
            $char='A';
            foreach($features_fields as $f) {
                $data = $item[$f];
                $features_sheet->setCellValue($char.$row, $data);
                $char = chr(ord($char)+1);
            }
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $file_name = 'items.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename="'.$file_name.'"');
        ob_end_clean();
        $writer->save('php://output');
        //$writer->save(_get_config('global_upload_path') . 'export/items.xlsx');
        exit;
    } */

    private function _get_items_export() {
        _model('items/item_sku','item_sku');
        _model('items/item_price','price');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('items/item_addon','item_addon');
        _model('items/item_note','item_note');
        _model('item_feature_value','feature_value');

        $filters['filter'] = [
            'type'  =>  'single'
        ];
        $filters['limit'] = 99999;
        $filters['orders'] = [['order_by'=>'code','order'=>'ASC']];
        $items = $this->_search($filters);

        $body=[];

        if($items) {
            foreach ($items as $item) {

                $item_id = $item['id'];
                $category_id = $item['category_id'];
                $unit_id = $item['unit_id'];

                $category = $this->_get_category($category_id);
                $unit = $this->_get_unit($unit_id);
                $code = $item['code'];
                $body['Basic'][] = [
                    'item_code'		         =>	$code,
                    'category'           =>	$category,
                    'taxable'	         =>	$item['taxable'],
                    'status'	         =>	$item['status'],
                    'title'	             =>	$item['title'],
                    'description'        => '',
                    'unit'               => $unit,
                    'purchase_unit'      => $unit,
                    'sale_unit'          => $unit,
                    'manufacturer'       => $item['manufacturer'],
                    'vendor'             => $item['vendor_id'],
                    'image'              => $item['image'],
                    'has_spice_level'    => $item['has_spice_level'],
                    'is_addon'           => $item['is_addon'],
                    'print_location'     => $item['print_location'],
                    'web_status'         => $item['web_status'],
                    'app_status'         => $item['app_status'],
                    'pos_status'         => $item['pos_status'],
                    'icon'               => $item['icon'],
                ];
                $item_sku = $this->item_sku->single(['item_id' => $item_id]);
                $sku = $item_sku['sku'];
                $body['Variants'][] = [
                    'item_code'    => $code,
                    'sku'          =>$sku,
                    'title'        =>$item_sku['title'],
                    'upc'          =>0,
                    'ean'          =>0,
                    'weight'       =>0,
                    'reorder_level'=>0,
                    'is_veg'       =>$item_sku['is_veg']
                ];

                $item_price = $this->price->single(['item_id' => $item_id]);
                $body['Prices'][]=[
                    'item_code'=>$code,
                    'sku'            =>$sku,
                    'unit'           =>$unit,
                    'purchase_price' =>$item_price['purchase_price'],
                    'sale_price'     =>$item_price['sale_price'],
                    'conversion_rate'=>$item_price['conversion_rate']
                ];
                $item_addon = $this->item_addon->search(['item_id' => $item_id]);
                if($item_addon){
                    foreach ($item_addon as $addon){
                        $addon_item_id = $addon['addon_item_id'];
                        $addon_code = array_values(array_filter($items, function ($single) use ($addon_item_id) {
                            return $single['id'] === $addon_item_id;
                        }));

                        $body['Addons'][]=[
                            'item_code'      => $code,
                            'sku'            =>$sku,
                            'addon_item_code'=>$addon_code[0]['code'],
                            'type'           =>$addon['type'],
                            'title'          =>$addon['title'],
                            'sale_price'     =>$addon['sale_price']
                        ];
                    }
                }
                $item_note = $this->item_note->search(['item_id' => $item_id]);
                if($item_note){
                    foreach ($item_note as $note)
                    $body['Notes'][] = [
                        'item_code'=> $code,
                        'title'          =>$note['title']
                    ];
                }
                $item_stock = $this->stock->single(['item_id'=>$item_id]);
                $warehouse = _get_module('warehouses','_find',['filter'=>['id'=>$item_stock['warehouse_id']]]);
                $body['Stock'][]=[
                  'item_code'            =>$code,
                  'sku'                  =>$sku,
                  'warehouse'            =>$warehouse['code'] ?? 'OTL001',
                  'opening_stock'        =>0,
                  'opening_stock_value'  =>0
                ];
                $body['Features'][]=[
                    'item_code' =>'',
                    'feature'   =>'',
                    'value'     =>''
                ];
            }
        }
        return $body;
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

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
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
