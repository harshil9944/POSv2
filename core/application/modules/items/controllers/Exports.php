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
        $notes_items = $items['Notes'];
        $basic_fields = [
            'item_code','type','parent','category','outlet_id','taxable','title'
            ,'image','has_spice_level','is_addon','print_location','web_status','app_status','pos_status','is_veg','rate'
        ];
        $notes_fields = ['item_code','title'];
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
        $notes_sheet = $spreadsheet->setActiveSheetIndex(1)->setTitle('Notes');
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
        _model('items/item_note','item_note');
        _model('items/item','item');
        _model('item_category','category');

      /*   $filters['filter'] = [
            'type'  =>  'single'
        ]; */
       // $filters['limit'] = 99999;
       // $filters['orders'] = [['order_by'=>'code','order'=>'ASC']];
        $this->item->order_by('code','ASC');
        $items = $this->item->search();
        $categories = $this->category->search();
        $all_warehouse = _get_module('warehouses', '_search', ['filter' => []]);
        $body=[];

        if($items) {
            foreach ($items as $i) {
               
                $item_id = $i['id'];
                $category_id = $i['category_id'];
                $unit_id = $i['unit_id'];
                $category = array_values(array_filter($categories,function($single) use ($category_id) {
                    return $category_id == $single['id'];
                }));
                $parent_id = $i['parent'];
                $parent = array_values(array_filter($items, function ($single) use ($parent_id) {
                    return $single['id'] === $parent_id;
                }));
                $outlet_id = $i['outlet_id'];
                $outlet = array_values(array_filter($all_warehouse, function ($single) use ($outlet_id) {
                    return $single['id'] === $outlet_id;
                }));
               // $unit = $this->_get_unit($unit_id);
                $code = $i['code'];
                $body['Basic'][] = [
                    'item_code'		     =>	$code,
                    'type'               =>$i['type'],
                    'parent'             =>@$parent[0]['code'] ?? 0,
                    'category'           =>	@$category[0]['title']??'',
                    'outlet_id'          =>@$outlet[0]['title']??'',
                    'taxable'	         =>	$i['taxable'],
                    'title'	             =>	$i['title'],
                   // 'unit'               => $unit,
                    'image'              => $i['image'],
                    'has_spice_level'    => $i['has_spice_level'],
                    'is_addon'           => $i['is_addon'],
                    'print_location'     => $i['print_location'],
                    'web_status'         => $i['web_status'],
                    'app_status'         => $i['app_status'],
                    'pos_status'         => $i['pos_status'],
                    //'icon'               => $i['icon'],
                    'is_veg'             =>$i['is_veg'],
                    'rate'               =>$i['rate'],

                ];
                $item_note = $this->item_note->search(['item_id' => $item_id]);
                if($item_note){
                    foreach ($item_note as $note)
                    $body['Notes'][] = [
                        'item_code'=> $code,
                        'title'          =>$note['title']
                    ];
                }
            }
        }
       // dd($body);
        return $body;
    }
    public function _get_category($category_id){
        $categories = $this->category->search();

        $category = array_values(array_filter($categories,function($single) use ($category_id) {
            return $category_id == $single['id'];
        }));
        if($category){
           $title = $category['title'];
           return $title;
        }
    }
    public function _get_unit($unit_id){
        $filter = [];
        $filter['id'] = $unit_id;
    
        $unit = _get_module('core/units','_search',[]);
        //dd($unit);
        if($unit){
            $code = $unit['code'];
            return $code;
        }
    }
}
