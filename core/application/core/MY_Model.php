<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Smart model.
 */
class MY_Model extends CI_Model
{
    public $table;
    public $migration_table;
    private $return_type = 'array';
    public $keys = [];
    public $exclude_keys = [];

    public function __construct()
    {
        parent::__construct();
        //$this->table = get_Class($this);
    }

    public function get_version() {
        $result = $this->single([],$this->migration_table);
        return ($result)?$result['version']:0;
    }

    public function set_return_type(string $return_type): void
    {
        $this->return_type = $return_type;
    }

    public function save($data, $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        $op = 'update';
        $keyExists = false;
        $fields = $this->db->field_data($table);

        foreach ($fields as $field) {
            if ($field->primary_key == 1) {
                $keyExists = true;
                if (isset($data[$field->name])) {
                    $this->db->where($field->name, $data[$field->name]);
                } else {
                    $op = 'insert';
                }
            }
        }
        if ($keyExists && $op == 'update') {
            $this->db->set($data);
            $this->db->update($table);
            if ($this->db->affected_rows() == 1) {
                return $this->db->affected_rows();
            }
        }
        $this->db->insert($table, $data);

        return $this->db->affected_rows();
    }

    public function left_join($table,$join) {
        $this->join($table,$join,'left');
    }

    public function right_join($table,$join) {
        $this->join($table,$join,'right');
    }

    public function join($table,$join,$type) {
        $this->db->join($table, $join, $type);
    }

    public function order_by($order_by,$order='ASC') {

        $this->db->order_by($order_by, $order);

    }

    public function search($conditions = null, $limit = 1000, $offset = 0, $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        if ($conditions != null) {
            $this->db->where($conditions);
        }

        $query = $this->db->get($table, $limit, $offset);

        if($this->return_type=='array') {
            return ($query)?$query->result_array():[];
        } else {
            return $query->result();
        }
    }

    public function single($conditions, $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        $this->db->where($conditions);

        if($this->return_type=='array') {
            $result = $this->db->get($table);
            return ($result)?$result->row_array():[];
        }else {
            return $this->db->get($table)->row();
        }
    }

    public function insert($data, $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        $this->db->insert($table, $data);

        return $this->db->affected_rows();
    }

    public function insert_batch($data, $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        $this->db->insert_batch($table, $data);

        return $this->db->affected_rows();
    }

    public function insert_id() {
        return $this->db->insert_id();
    }

    public function update($data, $conditions, $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        $this->db->where($conditions);
        $result = $this->db->update($table, $data);

        return $result;
    }

    public function affected_rows() {
        return $this->db->affected_rows();
    }

    public function replace($data, $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        $this->db->replace($table, $data);

        return $this->db->affected_rows();
    }

    public function delete($conditions, $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        $this->db->where($conditions);
        $this->db->delete($table);

        return $this->db->affected_rows();
    }

    public function count($conditions = null, $table = '')
    {
        if ($conditions != null) {
            $this->db->where($conditions);
        }

        if ($table == '') {
            $table = $this->table;
        }

        $this->db->select('1');
        return $this->db->get($table)->num_rows();
    }

    public function get_query($query,$single=false){
        $result = $this->db->query($query);
        if($result){
            if($result->num_rows()){
                if($single) {
                    return $result->row_array();
                }
                return $result->result_array();
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    public function query($query){
        return $this->db->query($query);
    }

    public function select_sum($select,$alias='') {
        $this->db->select_sum($select,$alias);
    }

    public function select($select = '*', $escape = NULL) {
        $this->db->select($select,$escape);
    }

    public function like($field,$match='',$side='both',$escape=NULL) {
        $this->db->like($field,$match,$side,$escape);
    }

    public function or_like($field,$match='',$side='both',$escape=NULL) {
        $this->db->or_like($field,$match,$side,$escape);
    }

    public function where($key, $value = NULL, $escape = NULL) {
        $this->db->where($key,$value,$escape);
    }

    public function where_in($key, $value = NULL) {
        $this->db->where_in($key,$value);
    }

    public function where_string($string) {
        $this->db->where($string);
    }

    public function or_where($key, $value = NULL, $escape = NULL) {
        $this->db->or_where($key,$value,$escape);
    }

    public function group_by($field) {
        $this->db->group_by($field);
    }

    public function gs() {
        $this->db->group_start();
    }

    public function ge() {
        $this->db->group_end();
    }
}
