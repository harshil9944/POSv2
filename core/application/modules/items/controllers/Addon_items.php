<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addon_items extends MY_Controller {

    public $module = 'items/addon_items';
    public $model = 'addon_item';
    public $singular = 'Addon Item';
    public $plural = 'Addon Items';
    public $language = 'items/items';
    public $edit_form = '';
    public $form_xtemplate = 'addon_items_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }
    public function index()	{

        _language('items');
        _library('table');
        _model('item_category','category');

        $filter_dropdown_value = _input('filterDropdown');

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

                $item_name = _vue_text_link($result['title'],'handleViewItem('.$result['id'].')','View Item');

                $body[] = [
                    $item_name,
                    $result['sku'],
                    $result['on_hand'],
                    $result['reorder_level'],
                    $action_cell
                ];
            }
        }

        $heading = [
            $this->singular . ' Name',
            'SKU',
            'Stock on Hand',
            'Reorder Level',
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
        //_set_additional_component('items_detail_view');
        _set_layout(LIST_VIEW_PATH);

    }

    public function add() {

        $this->_add();

    }

    public function edit($id) {

        $this->_edit($id);

    }

    public function _populate_get() {

        _model('item_feature','feature');
        _model('item_category','category');

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

        _response_data('addonItems',($items)?$items:[]);
        _response_data('features',$features);
        _response_data('categories',$categories);
        _response_data('units',$units);
        _response_data('vendors',$vendors);
        return true;

    }

    public function _action_put() {

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

            if(@$obj['item_price_table']) {
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
                    $addon['sku_id'] = $sku_id;
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

            if(@$obj['addons_table']) {
                $ignore = [];
                foreach ($obj['addons_table'] as $addon) {
                    if(@$addon['id']!='') {
                        $this->addon->update($addon,['id'=>$addon['id']]);
                        $ignore[] = $addon['id'];
                    }else {
                        unset($addon['id']);
                        $addon['item_id'] = $item_id;
                        $addon['sku_id'] = $sku_id;
                        $addon['added'] = sql_now_datetime();
                        $this->addon->insert($addon);
                        $ignore[] = $this->addon->insert_id();
                    }
                }
                $this->_clear_addons($item_id,$ignore);
            }

            if(@$obj['notes_table']) {
                $ignore = [];
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
                $this->_clear_notes($item_id,$ignore);
            }

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

        _model('items/item_sku','item_sku');
        _model('items/item_inventory','inventory');
        _model('items/item_note','note');
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
        $obj['item_table']['is_addon'] = 1;
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

        if(@($obj['features'])) {
            foreach ($obj['features'] as $single) {
                foreach ($item_feature_value_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $obj['feature_value_table'][] = $single;
            }
            unset($obj['features']);
        }

        if(@($obj['addons'])) {
            foreach ($obj['addons'] as $single) {
                foreach ($item_addons_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $single['addon_item_id'] = $single['addon_item_id']['id'];
                $obj['addons_table'][] = $single;
            }
            unset($obj['addons']);
        }

        if(@($obj['notes'])) {
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

    protected function _load_files() {

        if(_get_method()=='index') {
            _load_plugin(['dt']);
        }

        if(_get_method()=='add' || _get_method()=='edit') {
            _helper('control');
            $this->layout = 'addon_items_form_view';
            _page_script_override('items/addon-items-form');
        }
        _set_js_var('defaultTaxable',_get_setting('default_taxable',1),'s');

    }
}
