<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imports extends MY_Controller {

    public $module = 'items/imports';
    public $model = 'item_import';
    public $singular = 'Import';
    public $plural = 'Imports';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function index() {

        /*ignore_user_abort(1);
        set_time_limit(1800);*/
        $this->view = false;

        $types = ['single','group'];

        foreach ($types as $type) {

            if($type==='single') {
                $file = _get_config('global_upload_path') . 'import/' . 'items.xlsx';
            }elseif($type==='group') {
                $file = _get_config('global_upload_path') . 'import/' . 'item-groups.xlsx';
            }else{
                die('Something went wrong');
            }
            if(file_exists($file)) {
               $this->_import_item($file);
            }
        }
        redirect('items');
    }
    public function _file_put(){
        $type = _input('type');
        $url = null;
        if($type =='single'){
            $url = 'items';
        }else{
            $url = 'items/item_groups';
        }
   
        if(isset($_FILES['xlsx']) && $_FILES['xlsx']) {
           
            $file = (isset($_FILES['xlsx']) && $_FILES['xlsx']) ? 'xlsx' : null;
            
            $upload_path = _get_config('global_upload_path') . 'import/';
            if (!file_exists( $upload_path)) {
                mkdir( $upload_path, 0777, true);
            }
            $config['upload_path'] =  $upload_path;
            $config['allowed_types'] = 'xlx|xlsx';
            $config['detect_mime'] = false;
            $config['encrypt_name'] = true;
            $config['file_ext_tolower'] = true;
            _library('upload', $config);
            $this->upload->file_type = $_FILES['xlsx']['type'];
            $upload_data = $this->upload->do_upload($file);
            if ($upload_data) {
                $uploaded = $this->upload->data();
                
               $this->_import_item($uploaded['full_path']);
               _response_data('redirect', base_url($url));
               return true;
            }
        }
        
    }
    private function _import_item($file){
        _model('item');
        _model('item_category','category');
        _model('item_sku','sku');
        _model('item_price','price');
        _model('item_addon','addon');
        _model('item_note','note');
        _model('item_feature','feature');
        _model('item_feature_value','fvalue');
        if($file){
            $excel_data = _excel_to_array($file);
            $items = (isset($excel_data['basic'])) ? $excel_data['basic'] : [];
            $variants = (isset($excel_data['variants'])) ? $excel_data['variants'] : [];
            $prices = (isset($excel_data['prices'])) ? $excel_data['prices'] : [];
            $stocks = (isset($excel_data['stock'])) ? $excel_data['stock'] : [];
            $features = (isset($excel_data['features'])) ? $excel_data['features'] : [];

            $addons = (@$excel_data['addons']) ? $excel_data['addons'] : [];
            $notes = (@$excel_data['notes']) ? $excel_data['notes'] : [];

            if ($items && $variants && $prices && $stocks) {

                $wh_filter = [];
                $warehouses = _get_module('warehouses', '_search', ['filter' => $wh_filter]);

                foreach ($items as $item) {

                    $code = $item['item_code'];
                    $code_prefix = strtolower(substr($code, 0, 2));
                    if ($code_prefix === 'si') {
                        $item['type'] = 'single';
                    } elseif ($code_prefix === 'gi') {
                        $item['type'] = 'group';
                    } else {
                        continue;
                    }

                    $item_id = $this->_update_item_table($item);

                    $this->_clear_item_features($item_id);
                    //$this->_clear_item_variants($item_id);
                    $this->_clear_item_openings($item_id);

                    $this->_clear_item_addons($item_id);
                    $this->_clear_item_notes($item_id);

                    $item_features = array_filter($features, function ($single) use ($code) {
                        return $single['item_code'] === $code;
                    });

                    $item_notes = array_values(array_filter($notes, function ($single) use ($code) {
                        return $single['item_code'] === $code;
                    }));

                    $item_variants = array_filter($variants, function ($single) use ($code) {
                        return $single['item_code'] === $code;
                    });

                    $ignore_variant_ids = [];
                    if ($item_variants) {
                        foreach ($item_variants as $variant) {
                            unset($variant['item_code']);
                            $sku_id = $this->_update_item_variant($variant, $item_id);
                            $sku = $variant['sku'];
                            $ignore_variant_ids[] = $sku_id;

                            $item_prices = array_filter($prices, function ($single) use ($code, $sku) {
                                return $single['item_code'] === $code && $single['sku'] === $sku;
                            });

                            if ($item_prices) {
                                $first = $item['type']==='single';
                                foreach ($item_prices as $price) {
                                    unset($price['item_code']);
                                    $this->_update_item_price($price, $item_id, $sku_id, $first);
                                    $first = false;
                                }
                            }

                            if ($warehouses) {
                                foreach ($warehouses as $single) {
                                    $warehouse = $single['code'];

                                    $item_stocks = array_filter($stocks, function ($stock) use ($code, $sku, $warehouse) {
                                        return $stock['item_code'] === $code && $stock['sku'] === $sku && $stock['warehouse'] === $warehouse;
                                    });

                                    if ($item_stocks) {
                                        foreach ($item_stocks as $item_stock) {
                                            $this->_update_opening_stock($item_stock, $item_id, $sku_id);
                                        }
                                    }

                                }
                            }
                        }
                    }
                    $this->_clear_item_variants($item_id,$ignore_variant_ids);

                    if ($item_features) {
                        foreach ($item_features as $feature) {
                            $this->_update_item_feature($feature, $item_id);
                        }
                    }

                    if ($item_notes) {
                        foreach ($item_notes as $note) {
                            $this->_update_item_note($note, $item_id);
                        }
                    }

                }

                if($addons) {
                    $addon_filters = [];
                    foreach ($addons as $addon) {
                        if(!in_array($addon['item_code'],$addon_filters)) {
                            $addon_filters[] = $addon['item_code'];
                        }
                        if(!in_array($addon['addon_item_code'],$addon_filters)) {
                            $addon_filters[] = $addon['addon_item_code'];
                        }
                    }
                    if($addon_filters) {
                        $this->item->where_in('code',$addon_filters);
                    }
                    $db_items = $this->item->search([],9999);
                    $db_variants = [];
                    if($db_items) {
                        $addon_variant_filters = [];
                        foreach ($db_items as $db_item) {
                            $addon_variant_filters[] = $db_item['id'];
                        }
                        if($addon_variant_filters) {
                            $this->sku->where_in('item_id',$addon_variant_filters);
                        }
                        $db_variants = $this->sku->search([], 9999);
                    }
                    foreach ($db_items as $item) {
                        $item_id = $item['id'];
                        $code = $item['code'];
                        $item_variants = array_filter($db_variants, function ($single) use ($item_id) {
                            return $single['item_id'] === $item_id;
                        });
                        foreach ($item_variants as $variant) {

                            $sku_id = $variant['id'];
                            $sku = $variant['sku'];

                            $item_addons = array_values(array_filter($addons, function ($single) use ($code, $sku) {
                                return $single['item_code'] === $code && $single['sku'] === $sku;
                            }));

                            if ($item_addons) {
                                foreach ($item_addons as $addon) {

                                    $addon_item_code = $addon['addon_item_code'];

                                    unset($addon['item_code']);
                                    unset($addon['sku']);
                                    unset($addon['addon_item_code']);
                                    $addon['item_id'] = $item_id;
                                    $addon['sku_id'] = 0 ;

                                    $addon_item = array_values(array_filter($db_items, function ($single) use ($addon_item_code) {
                                        return $single['code'] === $addon_item_code;
                                    }));

                                    if (@$addon_item[0]) {
                                        $addon_item = $addon_item[0];
                                        $addon['addon_item_id'] = $addon_item['id'];
                                        $this->_update_item_addon($addon);
                                    }
                                }
                            }
                        }
                    }
                }

                $last_si_reference = $this->item->get_query('SELECT MAX(code) as max_code from ' . ITEM_TABLE . ' WHERE code LIKE "SI%"', true);
                $last_gi_reference = $this->item->get_query('SELECT MAX(code) as max_code from ' . ITEM_TABLE . ' WHERE code LIKE "GI%"', true);

                if ($last_si_reference['max_code']) {
                    $last_si_number = ltrim(str_replace('SI', '', $last_si_reference['max_code']), '0');
                    _update_ref('si', (int)$last_si_number + 1);
                }

                if ($last_gi_reference['max_code']) {
                    $last_gi_number = ltrim(str_replace('GI', '', $last_gi_reference['max_code']), '0');
                    _update_ref('gi', (int)$last_gi_number + 1);
                }
                unlink($file);
            }
        }
    }

    private function _update_item_table($item) {

        $code = $item['item_code'];

        $category_id = $this->_get_category_id($item['category']);

        $base_unit_id = $this->_get_unit_id($item['unit']);
        $purchase_unit_id = $this->_get_unit_id($item['purchase_unit']);
        $sale_unit_id = $this->_get_unit_id($item['sale_unit']);

        $vendor_id = '';
        if(@$item['vendor']) {
            $vendor_id = $this->_get_vendor_id($item['vendor']);
        }
        if(!$vendor_id) {
            if(@$item['manufacturer']) {
                $vendor_id = $this->_query_vendor_id($item['manufacturer']);
            }
        }

        $existing_item = [];
        if($code) {
            $existing_item = $this->item->single(['code'=>$code,'type'=>$item['type']]);
        }

        $item_table = [
            'code'  =>  $code,
            'type'  =>  $item['type'],
            'category_id'  =>  $category_id,
            'title'  =>  $item['title'],
            'taxable'  =>  $item['taxable'],
            'print_location' =>$item['print_location'],
            'unit_id'  =>  $base_unit_id,
            'purchase_unit_id'  =>  $purchase_unit_id,
            'sale_unit_id'  =>  $sale_unit_id,
            'manufacturer'  =>  $item['manufacturer'],
            'vendor_id'  =>  $vendor_id,
            'has_spice_level' => (isset($item['has_spice_level']) && $item['has_spice_level']==1)?1:0,
            'web_status'=>$item['web_status'],
            'pos_status'=>$item['pos_status'],
            'is_addon' => (isset($item['is_addon']) && $item['is_addon']==1)?1:0,
            'status'  =>  $item['status'],
            'created_by'  =>  _get_user_id(),
            'app_status'=>$item['app_status'],
            'icon'=>$item['icon']
        ];

        if(!$existing_item) {
            $item_table['added'] = sql_now_datetime();

            $image_path = $this->_get_image_path($item['image']);
            $item_table['image'] = $image_path;

            $this->item->insert($item_table);
            $item_id = $this->item->insert_id();

        }else{
            $item_id = $existing_item['id'];

            if(!$existing_item['image'] && 1==2) {
                $image_path = $this->_get_image_path($item['image']);
                $item_table['image'] = $image_path;
            }

            $this->item->update($item_table,['id'=>$item_id]);
        }
        return $item_id;
    }

    private function _update_item_feature($feature,$item_id) {

        $title = trim($feature['feature']);
        $value = trim($feature['value']);

        $existing_feature = $this->feature->single(['title'=>$title]);

        if(!$existing_feature) {
            $insert = [
                'title' =>  $title,
                'added' =>  sql_now_datetime()
            ];
            if($this->feature->insert($insert)){
                $feature_id = $this->feature->insert_id();
            }else{
                $feature_id = false;
            }
        }else{
            $feature_id = $existing_feature['id'];
        }

        if($feature_id) {
            $filter = ['item_id'=>$item_id,'feature_id'=>$feature_id];
            $existing_value = $this->fvalue->single($filter);

            if($existing_value) {
                $this->fvalue->update(['title'=>$value],array_merge($filter,['id'=>$existing_value['id']]));
            }else{
                $this->fvalue->insert(array_merge(['title'=>$value],$filter));
            }
        }
        return false;
    }

    private function _update_item_variant($variant,$item_id) {

        $variant['item_id'] = $item_id;

        $filter = [
            'item_id'   =>  $item_id,
            'sku'       =>  trim($variant['sku'])
        ];
        $existing = $this->sku->single($filter);

        if($existing) {
            $this->sku->update($variant,array_merge(['id'=>$existing['id']],$filter));
            $sku_id = $existing['id'];
        }else{
            $this->sku->insert($variant);
            $sku_id = $this->sku->insert_id();
        }
        return $sku_id;
    }

    private function _update_item_price($price,$item_id,$sku_id,$first=false) {

        if($first) {
            $this->price->delete(['item_id'=>$item_id]);
        }

        $price['item_id'] = $item_id;
        $price['sku_id'] = $sku_id;
        $price['purchase_currency'] = _get_setting('currency_code','INR');
        $price['sale_currency'] = _get_setting('currency_code','INR');

        $unit_id = $this->_get_unit_id(trim($price['unit']));
        $price['unit_id'] = $unit_id;

        unset($price['unit']);
        unset($price['sku']);

        $filter = [
            'item_id'   =>  $item_id,
            'sku_id'    =>  $sku_id,
            'unit_id'   =>  $unit_id
        ];
        $existing = $this->price->single($filter);

        if($existing) {
            $this->price->update($price,array_merge($filter));
        }else{
            $price['added'] = sql_now_datetime();
            $this->price->insert($price);
        }
        return true;
    }

    private function _update_opening_stock($stock,$item_id,$sku_id) {

        $warehouse_id = $this->_get_warehouse_id($stock['warehouse']);

        $current_item = [
            'item_id'       =>  $item_id,
            'sku_id'        =>  $sku_id
        ];
        _get_module('items','_update_warehouse_opening',['item'=>$current_item]);

        if($warehouse_id) {

            $on_hand = $stock['opening_stock'];
            $amount = $stock['opening_stock_value'];
            $rate = ($amount && $on_hand)?(float)$amount/(float)$on_hand:0;

            $item_inventory = [
                'item_id' => $item_id,
                'sku_id' => $sku_id,
                'warehouse_id' => $warehouse_id,
                'reason' => 'opening',
                'date' => sql_now_datetime(),
                'quantity' => $on_hand,
                'rate' => $rate,
                'amount' => $amount,
                'created_by' => _get_user_id(),
                'added' => sql_now_datetime()
            ];
            _get_module('items', '_update_opening_inventory', ['inventory' => $item_inventory]);
        }

    }

    private function _update_item_addon($addon) {

        $addon['added'] = sql_now_datetime();
        $this->addon->insert($addon);
        $id = $this->addon->insert_id();
        return $id;
    }

    private function _update_item_note($note,$item_id) {

        unset($note['item_code']);
        $note['added'] = sql_now_datetime();
        $note['item_id'] = $item_id;
        $this->note->insert($note);
        return $this->note->insert_id();

    }

    private function _clear_item_variants($item_id,$ignore_ids=[]) {
        if($ignore_ids) {
            $this->db->where_not_in('id',$ignore_ids);
        }
        return $this->sku->delete(['item_id'=>$item_id]);
    }

    private function _clear_item_openings($item_id) {

        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');

        $this->inventory->delete(['item_id'=>$item_id,'reason'=>'opening']);
        $this->stock->delete(['item_id'=>$item_id]);
        return true;
    }

    private function _clear_item_features($item_id) {
        return $this->fvalue->delete(['item_id'=>$item_id]);
    }

    private function _clear_item_addons($item_id) {
        return $this->addon->delete(['item_id'=>$item_id]);
    }

    private function _clear_item_notes($item_id) {
        return $this->note->delete(['item_id'=>$item_id]);
    }

    private function _get_category_id($title)
    {
        if (trim($title) !== '') {
            $category = $this->category->single(['title' => trim($title)]);

            if ($category) {
                return (int)$category['id'];
            } else {

                $insert = [
                    'title' => trim($title),
                    'parent' => 0,
                    'added' => sql_now_datetime()
                ];
                $affected = $this->category->insert($insert);

                if ($affected) {
                    return (int)$this->category->insert_id();
                }
                return null;

            }
        }
        return null;
    }

    private function _get_unit_id($title) {

        $filter = [];
        $filter['code'] = $title;
        $unit = _get_module('core/units','_find',['filter'=>$filter]);

        if($unit) {
            return (int)$unit['id'];
        }else{

            $insert = [
                'parent'=>  NULL,
                'code' =>  trim(strtolower(str_replace(' ','',$title))),
                'title' =>  trim($title),
                'value' => NULL,
                'added' =>  sql_now_datetime()
            ];
            return _get_module('core/units','_insert',['data'=>$insert]);

        }
    }

    private function _get_vendor_id($code) {

        $filter = [];
        $filter['vendor_id'] = $code;
        $vendor = _get_module('contacts/vendors','_find',['filter'=>$filter]);

        if($vendor) {
            return (int)$vendor['id'];
        }else{
            return 0;
        }

    }

    private function _query_vendor_id($string,$field='company_name') {

        $filter = [];
        $filter[$field] = $string;
        $vendor = _get_module('contacts/vendors','_find',['filter'=>$filter]);

        if($vendor) {
            return (int)$vendor['id'];
        }else{
            return 0;
        }

    }

    private function _get_warehouse_id($code) {

        $filter = [];
        $filter['code'] = $code;
        $warehouse = _get_module('warehouses','_find',['filter'=>$filter]);

        if($warehouse) {
            return (int)$warehouse['id'];
        }else{
            return 0;
        }

    }

    private function _get_image_path($url) {

        if($url) {
            $item_image_dir = 'items/';
            $upload_path = _get_config('global_upload_path') . $item_image_dir;

            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $file_name = basename($url);

            if (!file_exists($upload_path . $file_name)) {
                $url = preg_replace("/ /", "%20", $url);
                $image_data = @file_get_contents($url);
                if ($image_data) {
                    file_put_contents($upload_path . $file_name, $image_data);
                    return $item_image_dir . $file_name;
                } else {
                    return '';
                }
            } else {
                return $item_image_dir . $file_name;
            }
        }else{
            return '';
        }

    }
}
