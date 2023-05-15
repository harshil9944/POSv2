<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends MY_Controller {

    public $module = 'contacts/vendors';
    public $model = 'vendor';
    public $singular = 'Vendor';
    public $plural = 'Vendors';
    public $language = 'contacts/vendors';
    public $edit_form = '';
    public $form_xtemplate = 'vendors_form_xtemplate';
    public function __construct()
    {

        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => CONTACT_MIGRATION_PATH,
            'migration_table' => CONTACT_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }

    // Public methods Start

    public function index()	{

        _language('vendors');
        _library('table');

        $results = $this->{$this->model}->get_list();

        $can_edit = _can($this->module . '/edit');
        $can_delete = _can($this->module . '/remove');

        $body = [];
        if($results) {
            foreach ($results as $result) {
                $action = '';
                if($can_edit) {
                    $action .= _edit_link(base_url($this->module . '/edit/' . $result['id']));
                }
                if($can_delete) {
                    $remove_url = base_url($this->module . '/remove/' . $result['id']);
                    $action .= _vue_delete_link("handleRemove('" . $remove_url . "')");
                }
                $action_cell = [
                    'class' =>  'text-center',
                    'data'  =>  $action
                ];
                $receivables_cell = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format(0.00,_get_setting('currency_sign','₹'))
                ];
                $payables_cell = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format(0.00,_get_setting('currency_sign','₹'))
                ];
                $body[] = [
                    $result['display_name'],
                    $result['company_name'],
                    $result['email'],
                    $result['phone'],
                    $receivables_cell,
                    $payables_cell,
                    ($action)?$action_cell:''
                ];
            }
        }

        $heading = [
            'Name',
            'Company Name',
            'Email',
            'Phone',
            'Receivables',
            'Payables',
            ($can_edit || $can_delete)?array('data'=>_line('text_action'),'class'=>'text-center no-sort'):''
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  base_url($this->module.'/add'),
            'vue_add_url'   =>  '',
            'table'         =>  $table,
            'edit_form'     =>  ''
        ];
        _vars('page_data',$page);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_layout_type('wide');
        _set_page_heading('Vendors');
        _set_layout(LIST_VIEW_PATH);

    }

    public function add() {

        //TODO make below statement dynamic
        $default_currency = _get_setting('default_currency','');
        _set_js_var('defaultCurrency',$default_currency,'s');

        $new_vendor_id = _get_ref('ven',3,7);
        _set_js_var('newVendorId',$new_vendor_id,'s');
        $this->_add();

    }

    public function edit($id) {

        $this->_edit($id);

    }

    public function remove($id) {
        $this->view = false;
        if(_can($this->module . '/remove')){
            $result = $this->_delete($id);

            if($result) {
                _set_message(_get_var('mesage'),'success');
            }else{
                _set_message(_get_var('mesage'),'error');
            }
        }else{
            _set_message('You do not have enough privilege to delete this ' . $this->singular,'error');
        }
        $this->set_redirect($this->module);
    }

    public function import() {

        $this->view = false;

        _model('vendor_address','address');
        _model('vendor_contact','vcontact');

        $file = _get_config('global_upload_path') . 'import/' . 'vendors.xlsx';

        if(file_exists($file)) {
            $excel_data = _excel_to_array($file);

            $contacts = (isset($excel_data['basic'])) ? $excel_data['basic'] : [];
            $additional_contacts = (isset($excel_data['additional contacts'])) ? $excel_data['additional contacts'] : [];

            if ($contacts) {
                foreach ($contacts as $contact) {

                    $code = trim($contact['vendor_code']);
                    $company_name = trim($contact['company_name']);

                    if ($code && $company_name) {
                        $filter = ['vendor_id' => $code];
                        $vendor = $this->_find(['filter' => $filter]);

                        $billing_state_id = '';
                        $billing_country_id = '';
                        if ($contact['billing_state']) {
                            $filter = ['name' => $contact['billing_state']];
                            $state = _get_module('core/states', '_find', ['filter' => $filter]);
                            if ($state) {
                                $billing_country_id = $state['country_id'];
                                $billing_state_id = $state['id'];
                            }
                        }

                        $baddress_update = [
                            'attention' => $contact['billing_attention'],
                            'address1' => $contact['billing_address1'],
                            'address2' => $contact['billing_address2'],
                            'city' => $contact['billing_city'],
                            'state' => $billing_state_id,
                            'zip_code' => $contact['billing_postcode'],
                            'country' => $billing_country_id,
                            'phone' => $contact['billing_phone']
                        ];

                        $shipping_state_id = '';
                        $shipping_country_id = '';
                        if ($contact['shipping_state']) {
                            $filter = ['name' => $contact['shipping_state']];
                            $state = _get_module('core/states', '_find', ['filter' => $filter]);
                            if ($state) {
                                $shipping_country_id = $state['country_id'];
                                $shipping_state_id = $state['id'];
                            }
                        }

                        $saddress_update = [
                            'attention' => $contact['shipping_attention'],
                            'address1' => $contact['shipping_address1'],
                            'address2' => $contact['shipping_address2'],
                            'city' => $contact['shipping_city'],
                            'state' => $shipping_state_id,
                            'zip_code' => $contact['shipping_postcode'],
                            'country' => $shipping_country_id,
                            'phone' => $contact['shipping_phone'],
                            'added' => sql_now_datetime(),
                        ];

                        if ($vendor['baddress_id']) {
                            $baddress = $this->address->single(['id' => $vendor['baddress_id']]);
                            if ($baddress) {
                                $baddress_update['added'] = ($baddress['added']) ? $baddress['added'] : sql_now_datetime();
                                $baddress_update['id'] = $baddress['id'];
                            } else {
                                $baddress_update['added'] = sql_now_datetime();
                            }
                        }
                        $this->_clear_address($vendor['baddress_id']);
                        $this->address->insert($baddress_update);
                        $baddress_id = $this->address->insert_id();

                        if ($vendor['saddress_id']) {
                            $saddress = $this->address->single(['id' => $vendor['saddress_id']]);
                            if ($saddress) {
                                $saddress_update['added'] = ($saddress['added']) ? $saddress['added'] : sql_now_datetime();
                                $saddress_update['id'] = $saddress['id'];
                            } else {
                                $saddress_update['added'] = sql_now_datetime();
                            }
                        }
                        $this->_clear_address($vendor['saddress_id']);
                        $this->address->insert($saddress_update);
                        $saddress_id = $this->address->insert_id();

                        $additional_emails = ($contact['additional_emails']) ? serialize(explode(',', $contact['additional_emails'])) : '';

                        $currency_id = '';
                        if ($contact['currency_code']) {
                            $filter = ['code' => $contact['currency_code']];
                            $currency = _get_module('core/currencies', '_find', ['filter' => $filter]);
                            if ($currency) {
                                $currency_id = $currency['id'];
                            }
                        }

                        $contact_data = [
                            'vendor_id' => $code,
                            'salutation_id' => 0,
                            'first_name' => $contact['first_name'],
                            'last_name' => $contact['last_name'],
                            'position' => $contact['position'],
                            'company_name' => $company_name,
                            'display_name' => ($contact['display_name']) ? $contact['display_name'] : $company_name,
                            'email' => $contact['primary_email'],
                            'phone' => $contact['phone'],
                            'baddress_id' => $baddress_id,
                            'saddress_id' => $saddress_id,
                            'additional_emails' => $additional_emails,
                            'currency' => ($currency_id) ? $currency_id : _get_setting('default_currency', 'INR'),
                            'payment_terms' => $contact['payment_terms'],
                            'notes' => $contact['notes'],
                            'status' => ($contact['status']) ? $contact['status'] : 1,
                            'deleted' => 0
                        ];

                        if ($vendor) {
                            $vendor_id = $vendor['id'];
                            $this->{$this->model}->update($contact_data, ['id' => $vendor_id]);
                        } else {
                            $contact_data['added'] = sql_now_datetime();
                            $this->{$this->model}->insert($contact_data);
                            $vendor_id = $this->{$this->model}->insert_id();
                        }

                        $vendor_contacts = array_filter($additional_contacts, function ($contact) use ($code) {
                            return $contact['vendor_code'] === $code;
                        });
                        if ($vendor_contacts) {
                            $this->_update_additional_contacts($vendor_contacts, $vendor_id);
                        }
                    }

                }
            }

        }
        $last_reference = $this->{$this->model}->get_query('SELECT MAX(vendor_id) as max_code from ' . CONTACT_VENDOR_TABLE, true);
        if ($last_reference['max_code']) {
            $last_number = ltrim(str_replace('VEN', '', $last_reference['max_code']), '0');
            _update_ref('ven', $last_number + 1);
        }
        redirect($this->module);
    }

    //Public methods End

    //Import Methods Start

    private function _clear_address($id) {
        return $this->address->delete(['id'=>$id]);
    }

    private function _clear_additional_contacts($vendor_id) {
        return $this->vcontact->delete(['contact_id'=>$vendor_id]);
    }

    private function _update_additional_contacts($contacts,$id) {
        $this->_clear_additional_contacts($id);
        foreach ($contacts as $contact) {
            $insert = [
                'contact_id'    =>  $id,
                'salutation_id' =>  0,
                'position'      =>  $contact['position'],
                'department_id' =>  0,
                'first_name'    =>  $contact['first_name'],
                'last_name'     =>  $contact['last_name'],
                'email'         =>  $contact['email'],
                'phone'         =>  $contact['phone'],
                'added'         =>  sql_now_datetime()
            ];
            $this->vcontact->insert($insert);
        }
        return true;
    }

    //Import Methods End

    public function _populate_get() {

        $statuses = get_status_array();

        $params = [];
        $params['include_select'] = true;
        $salutations = _get_module('contacts/salutations','_get_select_data',$params);

        $currency_filters = $params;
        $currency_filters['fields'] = ['code'];
        $currencies = _get_module('core/currencies','_get_select_data',$currency_filters);
        if($currencies) {
            $temp = [];
            foreach ($currencies as $currency) {
                if($currency['id']) {
                    $currency['value'] = $currency['code'] . ' - ' . $currency['value'];
                }
                $temp[] = $currency;
            }
            $currencies = $temp;
        }

        $countries = _get_module('core/countries','_get_select_data',$params);

        _response_data('countries',$countries);
        _response_data('salutations',$salutations);
        _response_data('currencies',$currencies);
        _response_data('statuses',$statuses);
        return true;

    }

    public function _action_put() {
        _model('vendor_address','address');
        _model('vendor_contact','vcontact');
        $obj = _input('obj');
        $this->_prep_obj($obj);

        //Add Billing Address
        $obj['billing']['added'] = sql_now_datetime();
        $this->address->insert($obj['billing']);
        $obj['baddress_id'] = $this->address->insert_id();
        //Add Shipping Address
        $obj['shipping']['added'] = sql_now_datetime();
        $this->address->insert($obj['shipping']);
        $obj['saddress_id'] = $this->address->insert_id();

        $obj['status'] = 1;
        $obj['added'] = sql_now_datetime();

        $additional_contacts = $obj['additional_contacts'];

        unset($obj['id']);
        unset($obj['billing']);
        unset($obj['shipping']);
        unset($obj['additional_contacts']);


        if($this->{$this->model}->insert($obj)) {
            $id = $this->{$this->model}->insert_id();

            //Add Additional Contacts
            foreach ($additional_contacts as $contact) {
                unset($contact['id']);
                $contact['contact_id'] = $id;
                $contact['added'] = sql_now_datetime();
                $this->vcontact->insert($contact);
            }

            _update_ref('ven');
            $redirect = base_url($this->module);
            _response_data('redirect',$redirect);
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_post() {
        _model('vendor_address','address');
        _model('vendor_contact','vcontact');
        $obj = _input('obj');
        $this->_prep_obj($obj);

        //Add Billing Address
        $this->address->replace($obj['billing']);

        //Add Shipping Address
        $this->address->replace($obj['shipping']);

        $id = $obj['id'];

        $additional_contacts = $obj['additional_contacts'];

        unset($obj['id']);
        unset($obj['billing']);
        unset($obj['shipping']);
        unset($obj['additional_contacts']);

        $filter=[
            'id'    =>  $id
        ];

        if($this->{$this->model}->update($obj,$filter)) {

            //Update Additional Contacts
            foreach ($additional_contacts as $contact) {
                $contact['contact_id'] = $id;
                if($contact['id']) {
                    unset($contact['added']);
                    $this->vcontact->update($contact,['id'=>$contact['id']]);
                }else{
                    unset($contact['id']);
                    $contact['added'] = sql_now_datetime();
                    $this->vcontact->insert($contact);
                }
            }

            $redirect = base_url($this->module);
            _response_data('redirect',$redirect);
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_delete() {
        return true;
    }

    private function _prep_obj(&$obj) {

        $vendor_keys = $this->{$this->model}->keys;
        $address_keys = $this->address->keys;
        $contact_keys = $this->vcontact->keys;
        foreach ($vendor_keys as $old => $new) {
            change_array_key($old,$new,$obj);
        }
        foreach ($address_keys as $old => $new) {
            change_array_key($old,$new,$obj['billing']);
        }
        foreach ($address_keys as $old => $new) {
            change_array_key($old,$new,$obj['shipping']);
        }
        $temp = [];
        if($obj['additionalContacts'] && is_array($obj['additionalContacts'])) {
            foreach ($obj['additionalContacts'] as $contact) {
                foreach ($contact_keys as $old => $new) {
                    change_array_key($old,$new,$contact);
                }
                $temp[] = $contact;
            }
        }
        $additional_contacts = $temp;
        unset($obj['additionalContacts']);
        $obj['additional_contacts'] = $additional_contacts;

        $obj['additional_emails'] = (isset($obj['additional_emails']) && $obj['additional_emails'])?serialize($obj['additional_emails']):'';
    }

    protected function _delete($id) {

        $ignore_list = [];

        if(!in_array($id,$ignore_list)) {

            $filter = ['id' => $id];

            $result = $this->{$this->model}->single($filter);

            if($result) {
                $update = ['deleted'=>1];
                $affected_rows = $this->{$this->model}->update($update,$filter);

                if ($affected_rows) {
                    _vars('message',$this->singular . ' has been deleted successfully');
                    return true;
                } else {
                    _vars('message','Something went wrong. Please try again');
                    return false;
                }

            }else{
                _vars('message','You cannot delete a protected customer.');
                return false;
            }
        }else{
            _vars('message','You cannot delete a protected customer.');
            return false;
        }
    }

    public function _single_get() {

        $id = _input('id');

        $result = $this->_single(['id'=>$id]);

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

        _model('vendor_address', 'address');
        _model('vendor_contact', 'vcontact');

        $vendor_keys = $this->{$this->model}->keys;
        $address_keys = $this->address->keys;
        $vcontact_keys = $this->vcontact->keys;

        $filter = ['id' => $id];
        $result = $this->{$this->model}->single($filter);
        $vendor_exclude_fields = $this->{$this->model}->exclude_keys;
        $address_exclude_fields = $this->address->exclude_keys;
        $vcontact_exclude_fields = $this->vcontact->exclude_keys;

        if ($result) {

            $result['additional_emails'] = ($result['additional_emails']) ? unserialize($result['additional_emails']) : [];

            //$result['salutation'] = ($result['salutation']==0)?'':$result['salutation'];

            $baddress_id = $result['baddress_id'];
            $saddress_id = $result['saddress_id'];

            $result = filter_array_keys($result, $vendor_exclude_fields);

            $filter = ['id' => $baddress_id];
            $baddress = $this->address->single($filter);
            $baddress = filter_array_keys($baddress, $address_exclude_fields);

            $filter = ['id' => $saddress_id];
            $saddress = $this->address->single($filter);
            $saddress = filter_array_keys($saddress, $address_exclude_fields);

            $filter = ['contact_id' => $result['id']];
            $additional_contacts = $this->vcontact->search($filter);
            if ($additional_contacts) {
                $temp = [];
                foreach ($additional_contacts as $contact) {
                    $contact = filter_array_keys($contact, $vcontact_exclude_fields);
                    $temp[] = $contact;
                }
                $additional_contacts = $temp;
                //$additional_contacts = $temp[0];
            } else {
                //This is the case when only single additional contact is allowed to add
                $additional_contacts[] = [
                    'id' => '',
                    'contact_id' => '',
                    'salutation_id' => '',
                    'department_id' => '',
                    'first_name' => '',
                    'last_name' => '',
                    'email' => '',
                    'phone' => ''
                ];
            }

            foreach ($address_keys as $new => $old) {
                change_array_key($old, $new, $baddress);
            }
            foreach ($address_keys as $new => $old) {
                change_array_key($old, $new, $saddress);
            }

            $temp = [];
            foreach ($additional_contacts as $additional_contact) {
                foreach ($vcontact_keys as $new => $old) {
                    change_array_key($old, $new, $additional_contact);
                }
                $temp[] = $additional_contact;
            }
            $additional_contacts = $temp;

            $result['billing'] = $baddress;
            $result['shipping'] = $saddress;
            $result['additionalContacts'] = $additional_contacts;

            foreach ($vendor_keys as $new => $old) {
                change_array_key($old, $new, $result);
            }
            return $result;
        }
        return false;
    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $result = $this->_get_select_data($params);

        _response_data('vendors',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $filter = ['deleted'=>0,'status'=>1];
        $this->{$this->model}->order_by('display_name');
        $result = $this->{$this->model}->search($filter);
        if($result) {
            $result = get_select_array($result,'id','display_name',$include_select,'0','Select '.$this->singular);
            return $result;
        }else{
            return [];
        }

    }

    public function _get_data($param=[]) {
        $filter = ['id'=>$param['vendor_id']];
        $convert_vue = (isset($param['convert_vue']) && $param['convert_vue'])?true:false;
        $result = $this->{$this->model}->single($filter);
        if($result) {
            if($convert_vue) {
                $vendor_keys = $this->{$this->model}->keys;
                foreach ($vendor_keys as $old => $new) {
                    change_array_key($new,$old,$result);
                }
            }
            return $result;
        }else{
            return [];
        }
    }

    public function _get_address_data($param=[]) {
        _model('vendor_address','address');
        $address_id = $param['address_id'];

        $filter = ['id'=>$address_id];
        $convert_vue = (isset($param['convert_vue']) && $param['convert_vue'])?true:false;
        $result = $this->address->single($filter);
        if($result) {
            if($convert_vue) {

            }
            return $result;
        }else{
            return [];
        }
    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    protected function _load_files() {

        $list_pages = ['index'];
        if(in_array(_get_method(),$list_pages)) {
            _load_plugin(['dt']);
        }

        if(_get_method()=='add' || _get_method()=='edit') {
            _helper('control');
            _load_plugin(['vue_taginput']);
            $this->layout = 'vendors_form_view';
            _page_script_override('contacts/vendors-form');
        }

    }
}
