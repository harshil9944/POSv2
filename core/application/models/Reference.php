<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reference extends MY_Model
{
    public $references;
    public function __construct()
    {
        $this->table = REFERENCE_TABLE;

        $this->references = [
            1 => 'YEAR/Sequence Number (SL/2014/001)',
            2 => 'YEAR/MONTH/Sequence Number (SL/2014/08/001)',
            3 => 'Sequence Number',
            //4 => 'Random Number'
        ];
    }

    public function update_code($code,$value=false) {

        $ref = $this->single(['code'=>$code]);
        if ($ref) {
            $value = ($value)?$value:$ref['value']+1;
            $this->update(['value'=>$value],['code'=>$code]);
            return true;
        }else{
            $value = ($value)?$value:1;
            $this->insert(['code'=>$code,'value'=>$value]);
        }
        return true;

    }

    public function get($code,$format,$total_digits) {

        $ref = $this->single(['code'=>$code]);

        if(!$ref) {
            $this->update_code($code);
            $ref = $this->single(['code'=>$code]);
        }

        $ref_no = strtoupper($code);

        $regex = '%0'.$total_digits.'s';

        if ($format == 1) {
            $ref_no .= '/' . date('Y') . '/' . sprintf($regex, $ref['value']);
        } elseif ($format == 2) {
            $ref_no .= '/' . date('Y') . '/' . date('m') . '/' . sprintf($regex, $ref['value']);
        } elseif ($format == 3) {
            $ref_no .= sprintf($regex, $ref['value']);
        } elseif ($format == 4) {
            $ref_no .= '-' . sprintf($regex, $ref['value']);
        }
        return $ref_no;

    }
}
