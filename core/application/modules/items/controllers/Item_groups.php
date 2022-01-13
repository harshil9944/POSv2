<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Item_groups extends MY_Controller {

    public $module = 'items/item_groups';
    public $model = 'item_group';
    public $singular = 'Item Group';
    public $plural = 'Item Groups';
    public $language = 'items/items';
    public $list_url = '';
    public $edit_form = '';
    public $form_xtemplate = 'item_groups_form_xtemplate';
    public function __construct(){
        parent::__construct();
        $this->list_url = base_url('items/'.$this->module);
        _language('items');
        _model($this->model);
    }
    public function index()	{

        _library('table');
        _model('item_category','category');
        _model('items/item_price','price');
        _set_js_var('exportUrl',base_url('items/item_groups/export'));
        $search_in_vendors = (bool)_get_setting('search_item_by_vendors',false);

        $table = $this->{$this->model}->table;
        $offset = (_input('offset') && is_int((int)_input('offset')))?(int)_input('offset'):0;
        $searchString = trim(_input('search'));
        if(!$searchString) {
            $search_in_vendors = false;
        }
        $searchFields = [$table.'.title'];
        if($search_in_vendors) {
            _model('contacts/vendor','vendor');
            $vendor_table = CONTACT_VENDOR_TABLE;
            $searchFields[] = $vendor_table . '.display_name';
            $searchFields[] = $vendor_table . '.vendor_id';
        }

        $filters = [];
        $filters['filter'] = ['type'=>'group'];
        $filters['search_in_vendors'] = $search_in_vendors;
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
                $price_table = ITEM_PRICE_TABLE;
                $item_id = $result['id'];
                $price_min =_db_get_query("(SELECT MIN(sale_price) as min FROM $price_table iip WHERE iip.item_id=$item_id)",true);
                $price_max =_db_get_query("(SELECT MAX(sale_price) as max FROM $price_table iip WHERE iip.item_id=$item_id)",true);
                $item_name = _vue_text_link($result['title'],'handleViewStock('.$result['id'].')','View Item');
                $price = [
                    'class' =>  'text-center',
                    'data'  =>  custom_money_format($price_min['min'])."-".custom_money_format($price_max['max']),
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
            ['data'=>'Price Range','class'=>'text-center no-sort w-150p'],
            ['data'=>'Action','class'=>'text-center no-sort w-110p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $search = true;
        if($search) {
            _set_js_var('searchUrl',base_url($this->module));
        }

        $methods = [];
        $methods['path'] = $this->module;
        $methods['get'] = '';
        $methods['add'] = '';
        $methods['edit'] = '';
        $methods['delete'] = '';
        _set_js_var('currentModule',$methods,'j');

        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  base_url($this->module . '/add'),
            'vue_add_url'   =>  '',
            'table'         =>  $table,
            'search'        =>  $search,
            'edit_form'     =>  '',
            'total_rows'    =>  $total_rows,
            'per_page'      =>  $per_page,
            'paginate_url'  =>  $paginate_url
        ];
        _vars('page_data',$page);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_additional_component('item_groups_detail_view');
        _set_additional_component('items_import_export_tag');
        _set_additional_component('items_xtemplates','outside');
        _set_layout_type('wide');
        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);

    }

    /*public function add() {

        _helper('control');
         _set_js_var('back_url',base_url('items/'.$this->module),'s');
        _set_js_var('mode','add','s');
        _set_page_heading('New ' . $this->singular);
        _set_page_title('New ' . $this->singular);
        _set_layout('item_groups_form_view');

    }

    public function edit($id) {

        _helper('control');
        _set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('id',$id,'s');
        _set_page_heading('Edit ' . $this->singular);
        _set_layout_type('wide');
        _set_layout('item_groups_form_view');

    }*/

    public function add() {

        $this->_add();

    }

    public function edit($id) {

        $this->_edit($id);

    }

    public function _populate_get() {

        _model('item_option','option');
        _model('item_category','category');
        _model('item_icon','icon');
        $params['include_select'] = true;

        $options = $this->option->get_list();
        $options = get_select_array($options,'id','title',false);

        $this->{$this->model}->order_by('title','ASC');
        $this->{$this->model}->select('id,title');
        $items = $this->{$this->model}->search(['is_addon'=>1]);

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

        _response_data('addonItems',($items)?$items:[]);
        _response_data('units',$units);
        _response_data('options',$options);
        _response_data('categories',$categories);
        _response_data('icons',$icons);
        _response_data('printLocations',$printLocations);
        return true;

    }

    public function _action_put() {
        _clear_cache('item');
        _model('items/item_sku','item_sku');
        _model('items/item_option_value','value');
        _model('items/item_sku_value','sku_value');
        _model('items/item_inventory','inventory');
        _model('items/item_note','note');
        _model('items/item_addon','addon');
        _model('items/item_stock','stock');
        _model('items/item_price','price');


        $obj = _input('obj');
        if($obj) {
            $obj = (array) json_decode($obj,true);
        }
        $this->_prep_obj($obj);

        $item = $obj['item_table'];
        $image = '';
        if(isset($_FILES['image']) && $_FILES['image']) {
            $image = $this->_upload_image();
            $item['image'] = $image;

        }

        $item['code'] = _get_ref('GI',3,6);
        $item['created_by'] = _get_user_id();
        $item['added'] = sql_now_datetime();
        $item['type'] = 'group';
        $warehouse_id = _get_setting('default_warehouse',1);
        $item_id = 0;
        if($this->{$this->model}->insert($item)) {

            $item_id = $this->{$this->model}->insert_id();
            $item['code'] = _update_ref('GI');
        }
        if($item_id) {
            $item_skus = $obj['item_sku_table'];
            if($item_skus){
                foreach ($item_skus as $item_sku) {
                    $price = $item_sku['item_price_table'];
                    unset($item_sku['item_price_table']);
                    $item_sku['item_id'] = $item_id;
                    $this->item_sku->insert($item_sku);
                    $sku_id = $this->item_sku->insert_id();
                    //log_message('error',$item_id . ' - ' . $sku_id);

                    $price['item_id'] = $item_id;
                    $price['sku_id'] = $sku_id;

                    $this->_update_item_prices($price);
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

           /*  $item_prices = $obj['item_price_table'];
            if($item_prices){
                foreach ($item_prices as $prices) {
                    //$item_id = $prices['item_id'];
                    //$sku_id = $prices['sku_id'];

                    $this->_update_item_prices($prices, $item_id, $sku_id);

                }
            }



           $inventory_table = $obj['item_inventory_table'];
            if($inventory_table){
                foreach($inventory_table as $inventory) {

                    $item_id = $inventory['item_id'];
                    $sku_id = $inventory['sku_id'];
                    $warehouse_id = $inventory['warehouse_id'] = 1;

                    $this->inventory->update_opening($inventory);
                    $this->stock->update_soh($item_id,$sku_id,$warehouse_id);
                }
            }*/


            /*foreach ($sku_ids as $sku_id) {
                foreach ($options as $option_id=>$values) {
                    foreach ($values as $value_id) {
                        $insert = [
                            'item_id'=>$item_id,
                            'sku_id'=>$sku_id,
                            'option_id'=>$option_id,
                            'value_id'=>$value_id
                        ];
                        $this->sku_value->insert($insert);
                    }
                }
            }*/
           
            _response_data('redirect',$this->module);
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;

    }

    public function _action_post() {

        _clear_cache('item');
        _model('items/item_sku','item_sku');
        _model('items/item_option_value','value');
        _model('items/item_sku_value','sku_value');
        _model('items/item_inventory','inventory');
        _model('items/item_note','note');
        _model('items/item_addon','addon');
        _model('items/item_stock','stock');
        _model('items/item_price','price');

        $obj = _input('obj');
        if($obj) {
            $obj = (array) json_decode($obj,true);
        }
        $this->_prep_obj($obj);

        $item = $obj['item_table'];

        $image = '';
        if(isset($_FILES['image']) && $_FILES['image']) {
            $image = $this->_upload_image();
            $item['image'] = $image;

        }


        $item['created_by'] = _get_user_id();
        $item['added'] = sql_now_datetime();
        $warehouse_id = _get_setting('default_warehouse',1);

        $item_id = $item['id'];
        unset($item['id']);


        //Clear Existing Records
        $this->inventory->delete(['item_id'=>$item_id,'reason'=>'opening']);
        $this->item_sku->delete(['item_id'=>$item_id]);
        //$this->sku_value->delete(['item_id'=>$item_id]);
        //$this->value->delete(['item_id'=>$item_id]);
        //$this->{$this->model}->delete(['id'=>$item_id]);
        $this->price->delete(['item_id'=>$item_id]);
        if($item_id) {
            $this->{$this->model}->update($item,['id'=>$item_id]);
        }else{
            $this->{$this->model}->insert($item);
            $item_id = $this->{$this->model}->insert_id();
        }

        if($item_id) {
            $item_skus = $obj['item_sku_table'];
            if($item_skus){
                foreach ($item_skus as $item_sku) {
                    $price = $item_sku['item_price_table'];
                    unset($item_sku['item_price_table']);
                    $item_sku['item_id'] = $item_id;
                    $this->item_sku->insert($item_sku);
                    $sku_id = $this->item_sku->insert_id();

                    $price['item_id'] = $item_id;
                    $price['sku_id'] = $sku_id;

                    $this->_update_item_prices($price);


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

           /* $item_prices = $obj['item_price_table'];
            if($item_prices){
                foreach ($item_prices as $prices) {

                    $item_id = $prices['item_id'];
                    $sku_id = $prices['sku_id'];

                    $this->_update_item_prices($prices, $item_id, $sku_id);
                }
            }

            $inventory_table = $obj['item_inventory_table'];
            if($inventory_table){
                foreach($inventory_table as $inventory) {

                    $item_id = $inventory['item_id'];
                    $sku_id = $inventory['sku_id'];
                    $warehouse_id = $inventory['warehouse_id'] = 1;

                    $this->inventory->update_opening($inventory);
                    $this->stock->update_soh($item_id,$sku_id,$warehouse_id);
                }

            }*/
           
            _response_data('redirect',$this->module);

        }
        else{
            _response_data('message','Something wrong. Please try again.');
        }


        return true;
    }

    public function _action_delete() {
        _clear_cache('item');
        _model('items/item_sku','item_sku');
        _model('items/item_option_value','value');
        _model('items/item_sku_value','sku_value');
        _model('items/item_inventory','inventory');
        _model('items/item_addon','addon');
        _model('items/item_note','note');
        _model('items/item_stock','stock');

        $ignore_list = [];

        $item_id = _input('id');

        if(!in_array($item_id,$ignore_list)) {

            $this->inventory->gs();
            $this->inventory->or_where('reason','purchase');
            $this->inventory->or_where('reason','sale');
            $this->inventory->or_where('reason','transferIn');
            $this->inventory->or_where('reason','transferOut');
            $this->inventory->ge();


            //$where = "`item_id` = '$item_id' AND (`reason` = 'purchase' OR `reason` = 'sale' OR `reason` = 'transferIn' OR `reason` = 'transferOut')";

            $items = $this->inventory->search(['item_id'=>$item_id]);

            if(count($items)==0) {
                $result = $this->{$this->model}->single(['id' => $item_id]);

                if ($result) {

                    //Clear Existing Records
                    $this->inventory->delete(['item_id' => $item_id]);
                    $this->item_sku->delete(['item_id' => $item_id]);
                    $this->sku_value->delete(['item_id' => $item_id]);
                    $this->value->delete(['item_id' => $item_id]);
                    $this->stock->delete(['item_id' => $item_id]);
                    $this->_clear_notes($item_id,[]);
                    $this->_clear_addons($item_id,[]);
                    $affected_rows = $this->{$this->model}->delete(['id' => $item_id]);
                    if ($affected_rows) {
                        _response_data('redirect', $this->module);
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

    private function _update_item_prices($price) {


        $filter = [
            'item_id'   =>  $price['item_id'],
            'sku_id'    =>  $price['sku_id'],
        ];
        $existing = $this->price->single($filter);

        if($existing) {
            $this->price->update($price,array_merge($filter));
        }else{
            $prices['added'] = sql_now_datetime();
            $this->price->insert($price);
        }
        return true;
    }

    private function _prep_obj(&$obj) {
        $obj['preferredVendor'] = '';

        $item_keys = $this->{$this->model}->keys;
        $item_sku_keys = $this->item_sku->keys;
        $item_inventory_keys = $this->inventory->keys;
        $item_value_keys = $this->value->keys;
        $item_price_keys = $this->price->keys;
        $item_notes_keys = $this->note->keys;
        $item_addons_keys = $this->addon->keys;

        $obj['item_table'] = [];
        $obj['item_sku_table'] = [];
        $obj['addons_table'] = [];
        $obj['notes_table'] = [];



       // $obj['item_value_table'] = [];
        //$obj['item_inventory_table'] = [];

        foreach ($item_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['item_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }
    	$obj['item_table']['is_addon'] = 0;
        $obj['item_table']['purchase_unit_id'] = $obj['item_table']['sale_unit_id'] = $obj['item_table']['unit_id'];

        $count = 0;
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


        /*foreach ($obj['options'] as $option) {
            if(array_key_exists('values',$option)) {
                $option_id = $option['id'];
                foreach ($option['values'] as $value) {
                    foreach ($item_value_keys as $old => $new) {
                        change_array_key($old, $new, $value);
                    }
                    $value['option_id'] = $option_id;
                    $obj['item_value_table'][] = $value;
                }
                $count++;
            }
        }
        unset($obj['options']);*/

       $this->_vue_to_sql($obj['skus'],$item_sku_keys,true);

        if(@$obj['skus']) {
            foreach ($obj['skus'] as $key=>$sku) {

                /*if(isset($sku['inventory'])) {
                    foreach ($item_inventory_keys as $iold => $inew) {
                        foreach ($sku['inventory'] as $ikey=>$inventory) {
                            change_array_key($iold, $inew, $obj['skus'][$key]['inventory']);
                            if (isset($obj['skus'][$key]['inventory'][$inew])) {
                                $sku['item_sku_table'][$key]['item_inventory_table'][$inew] = $obj['skus'][$key]['inventory'][$inew];
                                unset($obj['skus'][$key]['inventory'][$inew]);
                            }
                        }
                    }
                }*/

                /*$quantity = (@$sku['openingStock'])?(float)$sku['openingStock']:0;
                $amount =(@$sku['openingStockValue'])?(float)$sku['openingStockValue']:0;

                $rate = 0;
                if($quantity && $amount) {
                    $rate = (float)$amount / (float)$quantity;
                }

                unset($sku['openingStock']);
                unset($sku['openingStockValue']);

                $obj['item_inventory_table'][] = [
                    'item_id'       =>  (@$obj['item_table']['id'])?$obj['item_table']['id']:'',
                    'sku_id'        =>  (@$sku['id'])?$sku['id']:'',
                    'warehouse_id'  =>  '',
                    'reason'        =>  'opening',
                    'date'          =>  sql_now_datetime(),
                    'quantity'      =>  $quantity,
                    'rate'          =>  $rate,
                    'amount'        =>  $amount,
                    'created_by'    =>  _get_user_id(),
                    'added'         =>  sql_now_datetime()
                ];*/

                $purchaseprice = $sku['purchasePrice'];
                $sellingprice = $sku['sellingPrice'];

                unset($sku['purchasePrice']);
                unset($sku['sellingPrice']);

                $sku['item_price_table'] = [
                    'item_id'           =>  (@$obj['item_table']['id'])?$obj['item_table']['id']:'',
                    'sku_id'            =>  (@$sku['id'])?$sku['id']:'',
                    'unit_id'           =>  $obj['item_table']['unit_id'],
                    'conversion_rate'   =>  1.0000,
                    'purchase_currency' =>  _get_setting('currency_code','INR'),
                    'sale_currency'     =>  _get_setting('currency_code','INR'),
                    'purchase_price'    =>  $purchaseprice,
                    'sale_price'        =>  $sellingprice,
                    'added'              =>  sql_now_datetime()
                ];

                unset($sku['inventory']);
                unset($obj['options']);
                unset($obj['prices']);

                $obj['item_sku_table'][] = $sku;




            }
        }
        unset($obj['skus']);


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

    private function _update_opening($item_inventory) {
        $filter = [
            'item_id'       =>  $item_inventory['item_id'],
            'sku_id'        =>  $item_inventory['sku_id'],
            'warehouse_id'  =>  $item_inventory['warehouse_id'],
            'reason'        =>  'opening'

        ];
        if(!$this->single($filter)){
            $this->insert($item_inventory);
        }else{
            $this->update($item_inventory,$filter);
        }
        return true;
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
        return $true;
    }

    public function _single_get() {

        _model('items/item_sku','item_sku');
        _model('items/item_price','item_price');
        _model('items/item_option_value','value');
        _model('items/item_stock','stock');
        _model('items/item_addon','addon');
        _model('items/item_note','note');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');

        $id = _input('id');
        $warehouse_id = _get_setting('default_warehouse',1);
        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);
        $item_sku_exclude_fields = $this->item_sku->exclude_keys;
        $item_exclude_fields = $this->{$this->model}->exclude_keys;

        if($result) {
            $item_keys = $this->{$this->model}->keys;
            $item_sku_keys = $this->item_sku->keys;
            $item_inventory_keys =$this->inventory->keys;

            $item_prices = $this->item_price->search(['item_id'=>$id]);

            $result['addons'] = [];
            $result['notes'] = [];

            $notes = $this->note->search(['item_id' => $id]);
            if($notes) {
                $this->_exclude_keys($notes,array_merge($this->note->exclude_keys),true);
                $this->_sql_to_vue($notes,$this->note->keys,true);
                $result['notes'] = $notes;
            }

            $addons = $this->addon->search(['item_id' => $id]);
            if($addons) {
                $temp = [];
                foreach ($addons as $addon) {
                    $addon['quantity'] = 1;
                    $addon['enabled'] = false;
                    $addon['addon_item_id'] = [
                        'id'    =>  $addon['addon_item_id'],
                        'title' =>  ''
                    ];
                    $temp[] = $addon;
                }
                $addons = $temp;
                $this->_exclude_keys($addons,array_merge($this->addon->exclude_keys),true);
                $this->_sql_to_vue($addons,$this->addon->keys,true);
                $result['addons'] = $addons;
            }

            $result = filter_array_keys($result,$item_exclude_fields);
            foreach ($item_keys as $new=>$old) {
                change_array_key($old,$new,$result);
            }

            $item_skus = $this->item_sku->search(['item_id'=>$result['id']]);

            $skus = [];
            foreach ($item_skus as $item_sku) {
                $sku_id = $item_sku['id'];
                $item_sku = filter_array_keys($item_sku,$item_sku_exclude_fields);

                $sku_price = array_values(array_filter($item_prices,function($single) use ($id,$sku_id) {
                    return $single['item_id']==$id && $single['sku_id']==$sku_id;
                }));

                $item_sku['purchasePrice'] = (@$sku_price[0]['purchase_price'])?$sku_price[0]['purchase_price']:'';
                $item_sku['sellingPrice'] = (@$sku_price[0]['sale_price'])?$sku_price[0]['sale_price']:'';

                foreach ($item_sku_keys as $new=>$old) {
                    change_array_key($old,$new,$item_sku);
                }

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
                $item_sku['inventory'] = $item_inventory;

                $skus[] = $item_sku;
            }
            $result['skus'] = $skus;

            $item_options = [];
            $values = $this->value->search(['item_id'=>$result['id']]);
            $options = get_index_id_array($values,'option_id',true);

            foreach ($options as $option_id=>$values) {
                $temp = [
                    'id'    =>  $option_id,
                    'tag'   =>  '',
                    'values'=>  []
                ];
                foreach ($values as $value) {
                    $temp['values'][] = [
                        'id'    =>  $value['id'],
                        'text'  =>  $value['title']
                    ];
                }
                $item_options[] = $temp;
            }
            $result['options'] = $item_options;

            _response_data('obj',$result);

        }else{
            _set_message('The requested details could not be found.','warning');
            _response_data('redirect',$this->module);
        }
        return true;
    }

    public function _single($params=[]) {

        $id = $params['id'];

        $meta = (@$params['meta'])?$params['meta']:false;
        $result = (@$params['item'])?$params['item']:false;

        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('items/item_sku','item_sku');
        _model('items/item_price','price');
        _model('items/item_addon','addon');
        _model('items/item_note','note');
        _model('item_feature_value','feature_value');

        $filter = ['id'=>$id];
        if(!$result) {
            $result = $this->{$this->model}->single($filter);
        }
        /*$item_sku_exclude_fields = $this->item_sku->exclude_keys;
        $item_feature_exclude_fields = $this->feature_value->exclude_keys;
        $item_price_exclude_fields = $this->price->exclude_keys;*/

        $variations = $prices = $notes = $addons = $features = $inventory = [];
        if($result) {

            $result['imageUrl'] = '';
            if($result['image']) {
                $result['imageUrl'] = _get_config('global_upload_url') . $result['image'];
            }

            $item_id = $result['id'];
            $warehouse_id = _get_setting('default_warehouse',1);

            if($meta) {
                $variations = array_values(array_filter((@$meta['sku'])?$meta['sku']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));

                $prices = array_values(array_filter((@$meta['prices'])?$meta['prices']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));

                $notes = array_values(array_filter((@$meta['notes'])?$meta['notes']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));

                $addons = array_values(array_filter((@$meta['addons'])?$meta['addons']:[],function($single) use ($item_id) {
                    return $item_id == $single['item_id'];
                }));

            }

            $unit_id = $result['unit_id'];


            $this->_exclude_keys($result);
            $this->_sql_to_vue($result);

            $result['baseName'] = $result['name'];
            $result['variations'] = [];

            $result['hasSpiceLevel'] = ($result['hasSpiceLevel']==='1')?true:false;
            $result['spiceLevel'] = DEFAULT_SPICE_LEVEL;

            //Get Required SKUs
            if(!$meta && !$variations) {
                if(SORT_VARIATION_BY_NAME) {
                    $this->item_sku->order_by('title', 'ASC');
                }
                $variations = $this->item_sku->search(['item_id' => $item_id]);
            }


            if(!$meta && !$prices) {
                $prices = $this->price->search(['item_id' => $item_id]);
            }

            $result['notes'] = [];
            $result['selectedNotes'] = [];
            if(!$meta && !$notes) {
                $notes = $this->note->search(['item_id' => $result['id']]);
            }
            if($notes) {
                $this->_exclude_keys($notes,array_merge($this->note->exclude_keys),true);
                $this->_sql_to_vue($notes,$this->note->keys,true);
                $result['notes'] = $notes;
            }

            $result['addons'] = [];
            //$result['selectedAddons'] = [];
            if(!$meta && !$addons) {
                $addons = $this->addon->search(['item_id' => $result['id']]);
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

            foreach ($variations as $variation) {

                $item_id = $variation['item_id'];
                $sku_id = $variation['id'];
                $price = array_values(array_filter($prices,function($single) use ($item_id,$sku_id,$unit_id) {
                    return $single['item_id'] === $item_id && $single['sku_id'] === $sku_id && $single['unit_id'] === $unit_id;
                }));
                $price = ($price)?$price[0]:[];


                $result['variations'][] = [
                    'itemId'    =>  $item_id,
                    'skuId'     =>  $sku_id,
                    'unitId'    =>  $unit_id,
                    'isVeg'     =>  $variation['is_veg'],
                    'sku'       =>  $variation['sku'],
                    'title'     =>  $variation['title'],
                    'salePrice' =>  $price['sale_price']
                ];
            }

            $item_features = $this->feature_value->search(['item_id'=>$result['id']]);

            $temp = [];
            $item_feature_exclude_fields = [];
            $item_feature_value_keys = $this->feature_value->keys;
            foreach ($item_features as $feature) {
                $feature = filter_array_keys($feature,$item_feature_exclude_fields);
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

            $item_inventory_keys = $this->inventory->keys;
            foreach ($item_inventory_keys as $new=>$old) {
                change_array_key($old,$new,$item_inventory);
            }

            $result = array_merge($result,$item_inventory);

            return $result;

        }else{
            return false;
        }
    }

    public function _single_stock_get() {

        _model('items/item_price','price');
        _model('items/item_sku','item_sku');
        _model('items/item_addon','addon');
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

                $action = base_url($this->module.'/edit/'.$result['id']);

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

    public function update_inventory($data) {
        $filter = [
            'item_id'       =>  $data['item_id'],
            'sku_id'        =>  $data['sku_id'],
            'warehouse_id'  =>  $data['warehouse_id'],
            'reason'        =>  $data['reason']
        ];
        if($data['order_id']) {
            $filter['order_id'] = $data['order_id'];
        }

        if(!$this->single($filter)) {
            $this->insert($data);
        }else{
            $this->update($data,$filter);
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


    public function _get_categories($params=[]) {

        _model('item_category','category');

        $order = (@$params['order'])?$params['order']:['order_by'=>'title','order'=>'ASC'];

        $filter = [];

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

        $filter = [];
        if(isset($params['category_id']) && $params['category_id']) {
            $filter['category_id'] = $params['category_id'];
        }

        $this->{$this->model}->order_by('title');
        $items = $this->{$this->model}->search($filter);

        $this->_sql_to_vue($items,[],true);

        return $items;

    }
    public function export() {
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
        $row=2;
        $basic_sheet->fromArray($basic_fields, NULL, 'A1');
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
        $file_name = 'item-groups.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename="'.$file_name.'"');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    private function _get_items_export() {
        _model('items/item_sku','item_sku');
        _model('items/item_price','price');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('items/item_addon','item_addon');
        _model('items/item_note','item_note');
        _model('item_feature_value','feature_value');

        $filters['filter'] = [
            'type'  =>  'group'
        ];
        $filters['limit'] = 99999;
        $filters['orders'] = [['order_by'=>'code','order'=>'ASC']];
        $items = $this->_search($filters);

        $addon_filters['filter'] = [
            'is_addon'  =>  1
        ];
        $addon_filters['limit'] = 99999;
        $addon_filters['orders'] = [['order_by'=>'code','order'=>'ASC']];

        $all_addons = $this->_search($addon_filters);

        $body=[];

        if($items) {
            foreach ($items as &$item) {

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
                $item_skus = $this->item_sku->search(['item_id' => $item_id]);
                if($item_skus){
                    foreach ($item_skus as &$item_sku){
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
                        $item_price = $this->price->single(['sku_id' => $item_sku['id']]);
                        $body['Prices'][]=[
                            'item_code'=>$code,
                            'sku'            =>$sku,
                            'unit'           =>$unit,
                            'purchase_price' =>$item_price['purchase_price'],
                            'sale_price'     =>$item_price['sale_price'],
                            'conversion_rate'=>$item_price['conversion_rate']
                        ];
                    }
                }
                $item_addons = $this->item_addon->search(['item_id' => $item_id]);
                if($item_addons){
                    foreach ($item_addons as &$addon){
                        $addon_item_id = $addon['addon_item_id'];
                        $addon_code = array_values(array_filter($all_addons, function ($single) use ($addon_item_id) {
                            return (int)$single['id'] === (int)$addon_item_id;
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
                $body['Notes'] = [];
                if($item_note){
                    foreach ($item_note as &$note)
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
            _helper('control');
            _load_plugin(['select2','vue_taginput','vue_multiselect']);
            $this->layout = 'item_groups_form_view';
            _page_script_override('items/item_groups-form');
        }

    }
}
