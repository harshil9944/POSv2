<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Exports extends MY_Controller {

    public $module = 'items/exports';
    public $model = 'item_export';
    public $singular = 'Export';
    public $plural = 'Exports';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function index() {
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
        
    }
    private function _get_items_export() {
        _model('items/item_sku','item_sku');
        _model('items/item_price','price');
        _model('items/item_inventory','inventory');
        _model('items/item_stock','stock');
        _model('items/item_addon','item_addon');
        _model('items/item_note','item_note');
        _model('item_feature_value','feature_value');
        _model('items/item','item');

      /*   $filters['filter'] = [
            'type'  =>  'single'
        ]; */
       // $filters['limit'] = 99999;
       // $filters['orders'] = [['order_by'=>'code','order'=>'ASC']];
        $this->item->order_by('code','ASC');
        $items = $this->item->search( ['type'  =>  'single']);

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
}
