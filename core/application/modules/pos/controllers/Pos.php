<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Pos extends MY_Controller {

    public $module = 'pos';
    public $model = 'pos_model';
    public $singular = 'Point of Sale';
    public $plural = 'Point of Sale';
    public $language = 'pos/pos';
    public $edit_form = '';
    public function __construct() {
        parent::__construct();
        _model( $this->model );
        $params = [
            'migration_path'  => POS_MIGRATION_PATH,
            'migration_table' => POS_MIGRATION_TABLE,
        ];
        $this->init_migration( $params );
    }
    public function index() {

        _helper( 'control' );

        $this->_js_vars_setup();

        $page = [
            'singular' => $this->singular,
            'plural'   => $this->plural,
        ];
        //_vars('body_class','bg-black');
        _vars( 'page_data', $page );
        _set_additional_component( 'pos_xtemplates', 'outside' );
        _set_template( 'pos' );
        _set_layout_type( 'wide' );
        _set_page_title( $this->plural );
        _set_page_heading( $this->plural );
        _set_layout( 'pos_view' );

    }

    public function _js_vars_setup() {

        _model( 'pos_session' );

        $register_id = _get_session( 'register_id' );

        _set_js_var( 'appVersion', CORE_VERSION );
        _set_js_var( 'registerId', $register_id );
        _set_js_var( 'printServerUrl', _get_config( 'print_server_url' ), 's' );

        $update_check = _get_setting( 'update_check', true );
        $update_check_interval = _get_setting( 'update_check_interval', UPDATE_CHECK_INTERVAL );

        _set_js_var( 'updateCheck', $update_check, 's' );
        _set_js_var( 'updateCheckInterval', $update_check_interval, 'n' );

        _set_js_var('timezone', _get_timezone(), 'j');

        $item_caching = _get_setting( 'item_caching', true );
        $preload_cache_items = ( $item_caching ) ? _get_setting( 'preload_cache_items', true ) : false;

        _set_js_var( 'posSourceId', SO_SOURCE_POS_ID, 'n' );
        _set_js_var( 'defaultTaxRate', _get_setting( 'default_tax_rate', 5 ), 'n' );
        _set_js_var( 'enableSplitOrders', _get_setting( 'enable_split_orders', ENABLE_SPLIT_ORDERS ), 'b' );
        _set_js_var( 'enableExtOrderNo', _get_setting( 'enable_ext_order_no', ENABLE_EXT_ORDER_NO ), 'b' );
        _set_js_var( 'enableSourceSwitch', _get_setting( 'enable_source_switch', ENABLE_SOURCE_SWITCH ), 'b' );

        _set_js_var( 'showItemVegNVeg', _get_setting( 'show_item_veg_n_veg', true ), 'b' );
        _set_js_var( 'showItemIcons', _get_setting( 'show_item_icons', true ), 'b' );
        _set_js_var( 'allowOpenCashDrawer', ALLOW_OPEN_CASH_DRAWER, 'b' );
        _set_js_var( 'defaultCashierPrint', DEFAULT_CASHIER_PRINT, 'b' );
        _set_js_var( 'defaultKitchenPrint', DEFAULT_KITCHEN_PRINT, 'b' );

        _set_js_var( 'spiceLevels', SPICE_LEVELS, 'j' );
        _set_js_var( 'defaultSpiceLevel', DEFAULT_SPICE_LEVEL, 's' );
        _set_js_var( 'tableListColClass', TABLE_LIST_COL_CLASS, 's' );

        $webOrderSound = asset_url() . 'assets/audio/' . DEFAULT_WEB_ORDER_SOUND;
        _set_js_var( 'webOrderSound', $webOrderSound );
        _set_js_var( 'playSoundOnNewOrder', PLAY_SOUND_ON_NEW_ORDER, 'b' );

        _set_js_var( 'openItemId', OPEN_ITEM_ID, 'n' );
        _set_js_var( 'limitedContactDisplay', _get_setting( 'pos_limited_contact_display', true ), 'b' );
        _set_js_var( 'pickupContactMandatory', _get_setting( 'pos_pickup_contact_mandatory', PICKUP_CONTACT_MANDATORY ), 'b' );
        _set_js_var( 'customerUserField', _get_setting( 'customer_username_field', CUSTOMER_USERNAME_FIELD ), 's' );
        _set_js_var( 'customerAutofillSearchField', _get_setting( 'customer_autofill_search_field', CUSTOMER_AUTOFILL_FIELD ), 's' );

        _vars( 'customer_user_field', _get_setting( 'customer_username_field', CUSTOMER_USERNAME_FIELD ) );

        _set_js_var( 'itemCaching', $item_caching, 'b' );
        _set_js_var( 'preloadCacheItems', $preload_cache_items, 'b' );
        _set_js_var( 'addonSkuCalculation', _get_setting( 'addon_sku_calculation', false ), 'b' );

        _set_js_var( 'minSplitAmount', _get_setting( 'min_split_amount', 10 ), 'n' );
        _set_js_var( 'minSplitInvoices', _get_setting( 'min_split_invoices', 2 ), 'n' );
        _set_js_var( 'maxSplitInvoices', _get_setting( 'max_split_invoices', 10 ), 'n' );

        _set_js_var( 'maxDiscountAllowed', _get_setting( 'max_discount_allowed', 100 ), 'n' );
        _set_js_var( 'allowRefund', ALLOW_REFUND, 'b' );
        _set_js_var( 'enableRepeatOrder', ENABLE_REPEAT_ORDER, 'b' );

        _set_js_var( 'printQueueWarningLimit', PRINT_QUEUE_WARNING_LIMIT, 'n' );

        $order_source_table = ORDER_SOURCE_TABLE;
        $sources = _db_query( "SELECT * FROM $order_source_table" );
        _set_js_var( 'orderSources', ( $sources ) ? $sources : [], 'j' );

        $promotions = _get_module( 'promotions', '_get_pos_promotions', [] );
        _set_js_var( 'promotions', $promotions, 'j' );

        $allowed_order_methods = _get_setting( 'order_methods', ORDER_METHODS );
        _set_js_var( 'orderMethods', explode( ',', $allowed_order_methods ), 'j' );

        _set_js_var( 'showItemSearch', _get_setting( 'show_pos_item_search', 1 ), 'b' );
        _set_js_var( 'showItemDisplayType', _get_setting( 'show_pos_item_display_type', 1 ), 'b' );
        _set_js_var( 'openingCash', _get_setting( 'pos_default_opening_cash', 0 ), 'n' );
        $user_group_id = _get_session( 'group_id' );
        $tablet_group_id = _get_setting( 'tablet_group_id', TABLET_GROUP_ID );
        $tablet_mode = false;
        if ( $user_group_id == $tablet_group_id ) {
            $tablet_mode = true;
        }
        _set_js_var( 'isTabletMode', _get_setting( 'is_tablet_mode', $tablet_mode ), 'b' );
        _set_js_var( 'customerCustomFields', CUSTOMER_CUSTOM_FIELDS, 'j' );
        _set_js_var( 'allowGratuity', ALLOW_GRATUITY, 'b' );
        _set_js_var( 'allowGratuityChange', ALLOW_GRATUITY_CHANGE, 'b' );
        _set_js_var( 'gratuityRate', GRATUITY_RATE, 'n' );
        _set_js_var( 'gratuityPersons', GRATUITY_PERSONS, 'n' );

        _set_js_var( 'allowReleaseTable', ALLOW_RELEASE_TABLE, 'b' );
        _set_js_var( 'allowVoidItem', ALLOW_VOID_ITEM, 'b' );

        _set_js_var( 'allowCustomerGroup', ALLOW_CUSTOMER_GROUP, 'b' );

        _set_js_var( 'defaultCountryId', DEFAULT_COUNTRY_ID, 'n' );
        _set_js_var( 'defaultStateId', DEFAULT_STATE_ID, 'n' );
        _set_js_var( 'defaultCityId', DEFAULT_CITY_ID, 'n' );

        _set_js_var( 'allowDiscountInSummary', ALLOW_DISCOUNT_IN_SUMMARY, 'b' );

        _set_js_var( 'allowCustomerNotes', ALLOW_CUSTOMER_NOTES, 'b' );

        _set_js_var( 'allowCardPaymentChange', ALLOW_CARD_PAYMENT_CHANGE, 'b' );

        _set_js_var( 'managerEmail', _get_session( 'userEmail' ), 's' );
        _set_js_var( 'allowSummaryCashEmployeeTakeOut', ALLOW_SUMMARY_CASH_EMPLOYEE_TAKEOUT, 'b' );

        _set_js_var( 'defaultSummaryPrint', DEFAULT_SUMMARY_PRINT, 'b' );
        _set_js_var( 'defaultRegisterPrint', DEFAULT_REGISTER_PRINT, 'b' );
        _set_js_var( 'allowShiftPrint', ALLOW_SHIFT_PRINT, 'b' );
        _set_js_var( 'allowConvertChangeToTip', ALLOW_CONVERT_CHANGE_TO_TIP, 'b' );
        _set_js_var( 'onlineOrderPaymentIds', ONLINE_ORDER_PAYMENT_IDS, 'j' );
        _set_js_var( 'allowOrderEdit', ALLOW_ORDER_EDIT, 'b' );
        _set_js_var( 'defaultKitchenPrintInAutoDiscount', DEFAULT_KITCHEN_PRINT_IN_AUTO_DISCOUNT, 'b' );

        if ( ALLOW_CLOVER_PAYMENT ) {
            _set_js_var( 'allowCloverPayment', ALLOW_CLOVER_PAYMENT, 'b' );
            _set_js_var( 'merchant_id', CLOVER_MERCHANT_ID, 's' );
            _set_js_var( 'access_token', CLOVER_ACCESS_TOKEN, 's' );
            _set_js_var( 'targetCloverDomain', CLOVER_DOMAIN, 's' );
            _set_js_var( 'remoteApplicationId', CLOVER_REMOTE_APPLICATION_ID, 's' );
            _set_js_var( 'friendlyId', CLOVER_FRIENDLY_ID, 's' );
            _set_js_var( 'deviceId', CLOVER_DEVICE_ID, 's' );
            _set_js_var( 'cardPaymentId', CARD_PAYMENT_ID, 'n' );
            _set_js_var( 'cloverTipSuggestions', CLOVER_TIP_SUGGESTIONS, 'j' );
            _set_js_var( 'cloverTipPercentage', CLOVER_TIP_PERCENTAGE, 'b' );
            _set_js_var( 'cloverPaymentMessage', CLOVER_PAYMENT_WAITING_MESSAGE, 's' );
            _enqueue_script( 'assets/plugins/remote-pay-cloud.js' );

        }
    }

    public function _populate_get() {

        _model( 'pos_session' );
        _model( 'pos_register_session', 'prs' );

        $register_id = _input( 'register_id' );
        $deviceRegisterId = _input( 'deviceRegisterId' );
        $tables = _get_module( 'areas/tables', '_search', [] );

        $session = $this->pos_session->get_open( $register_id );
        $session_opening_cash = false;
        $register_session_opening_cash = false;
        $registerSession = false;
        if ( $session ) {
            $this->_exclude_keys( $session, $this->pos_session->exclude_keys );
            $this->_sql_to_vue( $session, $this->pos_session->keys );
            $params = ['session_id' => $session['id'], 'register_id' => $deviceRegisterId];
            $registerSession = $this->prs->get_open( $params );
            if ( $registerSession ) {
                $this->_exclude_keys( $registerSession, $this->prs->exclude_keys );
                $this->_sql_to_vue( $registerSession, $this->prs->keys );
            } else {
                $this->prs->order_by( 'closing_date', 'DESC' );
                $last_register_session = $this->prs->single( ['session_id' => $session['id'], 'register_id' => $deviceRegisterId, 'status' => 'Close'] );
                $register_session_opening_cash = ( @$last_register_session['closing_cash'] ) ? round( $last_register_session['closing_cash'], 2 ) : false;
                if ( $register_session_opening_cash === false ) {
                    $register_session_opening_cash = _get_setting( 'pos_default_opening_cash', 0 );
                }
            }
        } else {
            $this->pos_session->order_by( 'closing_date', 'DESC' );
            $last_session = $this->pos_session->single( ['register_id' => $register_id, 'status' => 'Close'] );
            $session_opening_cash = ( @$last_session['closing_cash'] ) ? round( $last_session['closing_cash'], 2 ) : false;
        }
        if ( $session_opening_cash === false ) {
            $session_opening_cash = _get_setting( 'pos_default_opening_cash', 0 );
        }
        _response_data( 'sessionOpeningCash', $session_opening_cash );
        _response_data( 'tables', ( $tables ) ? $tables : [] );
        _response_data( 'session', ( $session ) ? $session : null );
        _response_data( 'registerSession', ( $registerSession ) ? $registerSession : null );
        _response_data( 'registerSessionOpeningCash', $register_session_opening_cash );
        return true;
    }

    public function _pos_employees_post() {
        $session_id = _input( 'session_id' );
        $employees = _get_module( 'employees', '_employees_pos', ['session_id' => $session_id] );
        _response_data( 'employees', $employees );
        return true;
    }

    public function _populate_items_get() {

        _helper( 'zebra' );

        $params['include_select'] = true;
        $categories = _get_cache( 'pos_pre_categories' );
        if ( !$categories ) {
            $categories = _get_module( 'items', '_get_categories', ['filter' => ['pos_status' => 1], 'select_data' => true, 'order' => ['order_by' => 'sort_order', 'order' => 'ASC']] );
            _set_cache( 'pos_pre_categories', $categories );
        }
        $units = _get_cache( 'pos_pre_units' );
        if ( !$units ) {
            $units = _get_module( 'core/units', '_get_list_pos', [] );
            _set_cache( 'pos_pre_units', $units );
        }

        $icons = _get_cache( 'pos_icons' );
        if ( !$icons ) {
            $icons = _get_module( 'items/icons', '_search', [] );
            _set_cache( 'pos_icons', $icons );
        }
        $items = _get_cache( 'pos_pre_items' );
        if ( !$items ) {
            $item_params = [];
            $item_params['filter'] = ['pos_status' => 1, 'type' => 'product', 'parent' => 0];
            $load_addon_items = _get_setting( 'pos_load_addon_items', POS_LOAD_ADDON_ITEMS );
            if ( !$load_addon_items ) {
                $item_params['filter']['is_addon'] = 0;
            }
            $item_params['limit'] = 3000;
            $item_params['orders'] = [['order_by' => 'title', 'order' => 'ASC']];
            $item_params['exclude'] = true;
            $item_params['convert'] = true;
            $items = _get_module( 'items', '_search', $item_params );

            if ( $items ) {
                $temp = [];
                foreach ( $items as $item ) {

                    $tags = [];
                    $tags[] = $item['title'];

                    $variant_params = [
                        'filter'  => [
                            'parent' => $item['id'],
                            'type'   => ITEM_TYPE_VARIANT,
                        ],
                        'exclude' => true,
                        'convert' => true,
                    ];

                    $variations = _get_module( 'items', '_get_item_variations', $variant_params );

                    if ( $variations ) {
                        foreach ( $variations as $variation ) {
                            $tags[] = $variation['title'];
                        }
                    }

                    $image_file_name = $item['image'];
                    if ( $item['image'] ) {
                        $image_path = _get_config( 'global_upload_path' );
                        $cache_path = _get_config( 'global_upload_cache_path' );
                        if ( !file_exists( $cache_path . 'items/' ) ) {
                            mkdir( $cache_path . 'items/', 0777, true );
                        }
                        $image_file_name = _get_image_cache_name( $item['image'], '_' . POS_THUMB_WIDTH . 'x' . POS_THUMB_HEIGHT );
                        if ( !file_exists( $cache_path . $image_file_name ) ) {
                            $params = [
                                'width'       => POS_THUMB_WIDTH,
                                'height'      => POS_THUMB_HEIGHT,
                                'source'      => $image_path . $item['image'],
                                'destination' => $cache_path . $image_file_name,
                            ];
                            $resize = _resize_crop_center( $params );
                            if ( !$resize ) {
                                $image_file_name = '';
                            }
                        }
                    }

                    $temp[] = [
                        'id'         => $item['id'],
                        'type'       => $item['type'],
                        'title'      => $item['title'],
                        'tags'       => implode( ' ', $tags ),
                        'categoryId' => $item['categoryId'],
                        'image'      => $image_file_name,
                        'icon'       => ( trim( $item['icon'] ) ) ? trim( $item['icon'] ) : false,
                    ];
                }
                $items = $temp;
                _set_cache( 'pos_pre_items', $items );
            }
        }
        _response_data( 'categories', $categories );
        _response_data( 'items', $items );
        _response_data( 'units', $units );
        _response_data( 'icons', ( $icons ) ? $icons : [] );
        return true;
    }

    public function _populate_payment_get() {

        $methods = _get_module( 'core/payment_methods', '_search', ['filter' => ['pos_enabled' => 1, 'status' => 1]] );

        if ( $methods ) {
            $order_source_links = (array) ORDER_SOURCE_PAYMENT_LINK;
            $temp = [];
            foreach ( $methods as $method ) {

                $method_id = $method['id'];

                $link = array_values( array_filter( $order_source_links, function ( $l ) use ( $method_id ) {
                    return $method_id == $l['payment_method_id'];
                } ) );

                $source_id = ( @$link[0]['source_id'] ) ? $link[0]['source_id'] : SO_SOURCE_POS_ID;

                $temp[] = [
                    'id'                => $method_id,
                    'code'              => $method['code'],
                    'type'              => $method['type'],
                    'sourceId'          => $source_id,
                    'autoDiscountValue' => (float) $method['auto_discount_value'],
                    'autofill'          => $method['autofill_amount'] == 1,
                    'value'             => $method['title'],
                    'cash'              => $method['is_cash'] == 1,

                ];
            }
            $methods = $temp;
        }

        _response_data( 'paymentMethods', $methods );
        return true;

    }

    public function _populate_item_detail_get() {

        $features = _get_module( 'items', '_get_features', [] );
        $units = _get_module( 'core/units', '_get_select_data', ['include_select' => false] );

        _response_data( 'units', $units );
        _response_data( 'features', $features );

        return true;

    }

    public function _category_items_get() {

        $params = [
            'category_id' => _input( 'category' ),
            'filter'      => ['pos_status' => 1],
        ];

        $items = _get_module( 'items', '_get_category_items', $params );

        if ( $items ) {

            $load_addon_items = _get_setting( 'pos_load_addon_items', POS_LOAD_ADDON_ITEMS );

            $temp = [];
            foreach ( $items as $item ) {
                if ( $item['is_addon'] == 1 && !$load_addon_items ) {
                    continue;
                }
                $temp[] = [
                    'id'    => $item['id'],
                    'title' => $item['title'],
                    'image' => '',
                ];
            }
            $items = $temp;
        }

        _response_data( 'items', $items );
        return true;

    }

    public function _open_session_post() {

        _model( 'pos_session' );

        $obj = _input( 'obj' );

        $this->_vue_to_sql( $obj, $this->pos_session->keys );

        $register_id = $obj['register_id'];

        $session = $this->pos_session->get_open( $register_id );

        if ( !$session ) {
            $obj['opening_user_id'] = _get_user_id();
            $obj['opening_date'] = $obj['added'] = sql_now_datetime();
            $obj['status'] = 'Open';

            if ( $this->pos_session->insert( $obj ) ) {

                if ( START_WEB_ORDERS_WITH_SESSION ) {
                    _set_setting( 'web', '1', 'order_sources_switch' );
                }

                $session = $this->pos_session->get_open( $register_id );
            }
        }
        $this->_sql_to_vue( $session, $this->pos_session->keys );
        _response_data( 'session', $session );
        return true;
    }

    public function _open_register_session_post() {

        _model( 'pos_register_session', 'prs' );

        $obj = _input( 'obj' );

        $this->_vue_to_sql( $obj, $this->prs->keys );

        $session_id = $obj['session_id'];
        $register_id = $obj['register_id'];

        //Check whether session is open
        $open_session = _db_get_query("SELECT * FROM pos_session ps WHERE ps.id =$session_id AND ps.status='Open'",true);
        if(!$open_session){
            _response_data( 'registerSession', null );
            return true;
        }

        $params = [
            'session_id'  => $session_id,
            'register_id' => $register_id,
        ];

        $registerSession = $this->prs->get_open( $params );

        if ( !$registerSession ) {
            $obj['opening_user_id'] = _get_user_id();
            $obj['opening_date'] = $obj['added'] = sql_now_datetime();
            $obj['status'] = 'Open';

            if ( $this->prs->insert( $obj ) ) {
                $registerSession = $this->prs->get_open( $params );
            }
        }
        $this->_sql_to_vue( $registerSession, $this->prs->keys );
        _response_data( 'registerSession', $registerSession );
        return true;
    }

    public function _close_session_summary_get() {

        $obj = _input( 'obj' );

        $session_id = $obj['sessionId'];
        $register_id = $obj['registerId'];
        $register_session_id = $obj['registerSessionId'];
        $employee_id = $obj['employeeId'];
        $type = $obj['type'];

        if ( $type === SUMMARY_TYPE_SESSION ) {
            $session = $this->_close_session_summary( ['session_id' => $session_id, 'enableRefunded' => ALLOW_REFUND, 'register_id' => $register_id] );
        } elseif ( $type === SUMMARY_TYPE_REGISTER ) {
            $session = $this->_close_register_summary( ['session_id' => $session_id, 'enableRefunded' => ALLOW_REFUND, 'register_id' => $register_id] );
        } elseif ( $type === SUMMARY_TYPE_EMPLOYEE ) {
            $session = $this->_close_employee_summary( ['session_id' => $session_id, 'enableRefunded' => ALLOW_REFUND, 'employee_id' => $employee_id] );
        }
        _response_data( 'summary', $session );
        return true;
    }

    public function _close_employee_summary( $params = [] ) {
        $result = $this->_get_session_summary( $params );
        $register_session = [];
        $session = $this->_prep_summary( $register_session, $result, SUMMARY_TYPE_EMPLOYEE );
        $session_id = $params['session_id'];
        $employee_id = $params['employee_id'];
        $session['registersDetail'] = _db_get_query( "SELECT sr.title as registerTitle,SUM(oo.grand_total) as grandTotal,SUM(oo.tip) as tip FROm ord_order oo LEFT JOIN sys_register sr ON oo.close_register_id = sr.id  WHERE oo.employee_id= $employee_id AND oo.session_id = $session_id AND oo.order_status IN('closed','partial_refund')GROUP BY oo.close_register_id;" );
        return $session;
    }

    public function _close_session_summary( $params ) {

        _model( 'pos_session' );
        _model( 'users/user', 'user' );

        $session_id = $params['session_id'];

        $session = $this->pos_session->single( ['id' => $session_id] );

        if ( $session ) {

            $result = $this->_get_session_summary( $params );

            $session = $this->_prep_summary( $session, $result, SUMMARY_TYPE_SESSION );
            return $session;
        }
        return false;
    }
    public function _close_register_summary( $params ) {

        _model( 'pos_register_session', 'prs' );
        _model( 'users/user', 'user' );

        $session_id = $params['session_id'];
        $register_id = $params['register_id'];

        $params = [
            'session_id'  => $session_id,
            'register_id' => $register_id,
        ];

        $register_session = $this->prs->get_open( $params );

        if ( $register_session ) {

            $result = $this->_get_session_summary( $params );

            $emp_tip = _db_get_query( "SELECT ee.*,SUM(oo.tip) AS tip FROM emp_employee ee LEFT JOIN ord_order oo ON oo.employee_id = ee.id WHERE oo.session_id = $session_id" );
            $result['empTips'] = $emp_tip;

            $session = $this->_prep_summary( $register_session, $result, SUMMARY_TYPE_REGISTER );
            return $session;
        }
        return false;
    }

    private function _prep_summary( &$session, &$result, $type = '',$params = [] ) {
        _model( 'users/user', 'user' );
        $session['ordersCount'] = ( $result ) ? $result['orders_count'] : 0;
        $session['openOrdersCount'] = ( $result ) ? (int) $result['open_orders_count'] : 0;
        $session['cancelledOrdersCount'] = ( $result ) ? $result['cancelled_orders_count'] : 0;
        $session['transactionsTotal'] = ( $result ) ? dsRound( $result['transactions_total'] ) : 0;
        $session['cancelledTransactionsTotal'] = ( $result ) ? dsRound( $result['cancelled_transactions_total'] ) : 0;
        $session['changeTotal'] = ( $result ) ? dsRound( $result['change_total'] ) : 0;
        $session['discountTotal'] = ( $result ) ? dsRound( $result['discount_total'] ) : 0;
        $session['tipTotal'] = ( $result ) ? dsRound( $result['tip_total'] ) : 0;
        $session['taxTotal'] = ( $result ) ? dsRound( $result['tax_total'] ) : 0;
        $session['gratuityTotal'] = ( $result ) ? dsRound( $result['gratuity_total'] ) : 0;
        $session['refundedTransactionsTotal'] = ( $result ) ? dsRound( $result['refunded_transactions_total'] ) : 0;
        $session['refundedOrdersCount'] = ( $result ) ? $result['refunded_orders_count'] : 0;
        $session['partialRefundCount'] = ( $result ) ? $result['partial_refund_count'] : 0;
        $session['partialRefundTotal'] = ( $result ) ? $result['partial_refund_total'] : 0;
        $session['cashRefundTransactionsTotal'] = ( $result ) ? dsRound( $result['cash_refund_transactions_total'] ) : 0;
        $session['cashGratuityTotal'] = ( $result ) ? dsRound( $result['cash_gratuity_total'] ) : 0;
        $session['registerToEmpTotal'] = ( @$result['register_to_emp_total'] ) ? dsRound( $result['register_to_emp_total'] ) : 0;
        $session['totalPaymentReceived'] = 0;
        $ignore_print_payment_ids = (array) IGNORE_PRINT_PAYMENT_IDS;
        $payment_methods = _get_module( 'core/payment_methods', '_search', [] );
        $cash_transactions_total = 0;
        $session['payments'] = [];
        if ( $payment_methods ) {
            foreach ( $payment_methods as $method ) {
                if ( in_array( $method['id'], $ignore_print_payment_ids ) ) {
                    continue;
                }
                $code = $method['code'];
                $var = $code . '_transactions_total';
                $title = $method['title'];
                $result_var = "$title";
                $amount = ( @$result[$var] ) ? $result[$var] : 0;
                $session['payments'][] = [
                    'label'  => $result_var,
                    'amount' => $amount,
                ];
                if ( $code == 'cash' ) {
                    $cash_transactions_total = $amount;
                }
                $session['totalPaymentReceived'] += $amount;
            }
        }
        $order_source = _get_module( 'orders/order_sources', '_search', ['filter' => ['show_in_summary' => 1]] );
        $session['source'] = [];
        if ( $order_source ) {
            foreach ( $order_source as $source ) {
                $id = $source['id'];
                $title = $source['title'];
                $var = "source_{$id}_orders_count";
                $total = "source_{$id}_amount";
                $discount = "source_{$id}_discount";
                $total_label = "$title";
                $amount_label = "$title";
                $order = ( @$result[$var] ) ? $result[$var] : 0;
                $amount = ( @$result[$total] ) ? $result[$total] : 0;
                $discount = ( @$result[$discount] ) ? $result[$discount] : 0;
                $session['source'][] = [
                    'label'       => $total_label,
                    'amountLabel' => $amount_label,
                    'order'       => $order,
                    'amount'      => $amount,
                    'discount'    => $discount,
                ];

            }
        }
        $session['empTips'] = [];
        if ( @$result['empTips'] ) {
            foreach ( $result['empTips'] as &$et ) {
                $session['empTips'][] = [
                    'empName' => $et['first_name'] . " " . $et['last_name'],
                    'tip'     => $et['tip'],
                ];
            }
        }
        if ( $type === SUMMARY_TYPE_EMPLOYEE ) {
            $session['openingCash'] = 0;
            $session['takeOut'] = 0;
            $session['closingCash'] = 0;
        }

        if ( $type === SUMMARY_TYPE_REGISTER || $type === SUMMARY_TYPE_SESSION ) {

            $opening_user = $this->user->single( ['id' => $session['opening_user_id']] );
            $closing_user = false;
            if ( $session['opening_user_id'] == $session['closing_user_id'] ) {
                $closing_user = $opening_user;
            } else {
                if ( $session['closing_user_id'] ) {
                    $closing_user = $this->user->single( ['id' => $session['opening_user_id']] );
                }
            }

            $session['openingEmployee'] = ( $opening_user ) ? $opening_user['first_name'] . ' ' . $opening_user['last_name'] : '';
            $session['closingEmployee'] = ( $closing_user ) ? $closing_user['first_name'] . ' ' . $closing_user['last_name'] : '';
            if ( $type === SUMMARY_TYPE_SESSION ) {
                $this->_exclude_keys( $session, $this->pos_session->exclude_keys );
                $this->_sql_to_vue( $session, $this->pos_session->keys );
            } elseif ( $type === SUMMARY_TYPE_REGISTER ) {
                $this->_exclude_keys( $session, $this->prs->exclude_keys );
                $this->_sql_to_vue( $session, $this->prs->keys );
            }
        }

        if ( @$params['recalculate_cash'] !== false || $session['status'] == 'Open' ) {
            $default_opening = (float) _get_setting( 'pos_default_opening_cash', 0 );

            $closing_cash = ( (float) $cash_transactions_total - (float) $session['changeTotal'] ) + (float) $session['openingCash'];
            $session['expectedClosingCash'] = round( $closing_cash, 2 );

            $session['takeOut'] = ( $default_opening < $session['expectedClosingCash'] ) ? $session['expectedClosingCash'] - $default_opening : 0;

            $session['closingCash'] = ( $default_opening < $session['expectedClosingCash'] ) ? $default_opening : $session['expectedClosingCash'];
        }

        if ( @$params['enableRefunded'] !== false || @$params['recalculate_cash'] !== false ) {
            $session['refundTotal'] = $session['refundedTransactionsTotal'] + $session['partialRefundTotal'];
            $session['transactionsTotal'] = $session['transactionsTotal'] - $session['partialRefundTotal'];
            if ( @$session['takeOut'] ) {
                $session['takeOut'] = $session['takeOut'] - $session['cashRefundTransactionsTotal'];
            }
            if ( @$session['expectedClosingCash'] ) {
                $session['expectedClosingCash'] = $session['expectedClosingCash'] - $session['cashRefundTransactionsTotal'];
            }
        }
        if (!ALLOW_GRATUITY_IN_TOTAL_ORDERS_AMOUNT ) {
            $session['transactionsTotal'] = $session['transactionsTotal'] - $session['gratuityTotal'];
            if ( @$session['expectedClosingCash'] ) {
                $session['expectedClosingCash'] = $session['expectedClosingCash'] - $session['cashGratuityTotal'];
            }
        }
        return $session;
    }

    public function _get_session_summary( $params ) {

        $session_id = $params['session_id'];
        $employee_id = @$params['employee_id'] ?? false;
        $register_id = @$params['register_id'] ?? false;
        $register_session_id = @$params['register_session_id'] ?? false;
        $cash_payment_method_id = _get_setting( 'cash_payment_method_id', POS_CASH_PAYMENT_METHOD_ID );
        $card_payment_method_id = _get_setting( 'card_payment_method_id', POS_CARD_PAYMENT_METHOD_ID );
        $order_payment_table = ORDER_PAYMENT_TABLE;
        $order_table = ORDER_TABLE;

        $payment_methods = _get_module( 'core/payment_methods', '_search', [] );
        $order_source = _get_module( 'orders/order_sources', '_search', [] );
        $condition = '';
        if ( $employee_id ) {
            $condition .= " AND so.employee_id = $employee_id ";
        }
        if ( $register_id ) {
            $condition .= " AND so.close_register_id = $register_id ";
        }
        if ( $register_session_id ) {
            $condition .= " AND so.register_session_id = $register_session_id ";
        }

        $query = "SELECT
                    ( SELECT COUNT(*) FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.order_status NOT IN ('cancelled','Refunded','Deleted')) AS orders_count,
                    ( SELECT COUNT(*) FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.cancelled=1 ) AS cancelled_orders_count,
                    ( SELECT COUNT(*) FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.order_status IN ('Refunded')) AS refunded_orders_count,
                    ( SELECT COUNT(*) FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.order_status IN ('Confirmed','Preparing','Ready')) AS open_orders_count,
                    ( SELECT SUM(so.grand_total) FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.order_status='Cancelled') AS cancelled_transactions_total,
                    ( SELECT SUM(so.grand_total) FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.order_status='Refunded') AS refunded_transactions_total,
                    ( SELECT SUM(opr.amount) FROM ord_order so LEFT JOIN ord_payment_refund opr ON so.id = opr.order_id WHERE so.session_id =$session_id " . $condition . " AND so.order_status = 'Partial_refunded') AS partial_refund_total,
                    ( SELECT COUNT(*) FROM ord_order so  WHERE so.session_id = $session_id " . $condition . " AND so.order_status = 'Partial_refunded' AND so.id IN (SELECT opr.id FROM ord_payment_refund opr )) AS partial_refund_count,
                    ( SELECT SUM(so.grand_total) FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.order_status NOT IN ('Cancelled','Refunded')) AS transactions_total,";

        if ( $payment_methods ) {
            foreach ( $payment_methods as $method ) {
                $method_id = $method['id'];
                $code = $method['code'];
                $query .= "( SELECT SUM(sp.amount) FROM $order_payment_table sp WHERE sp.payment_method_id= $method_id AND sp.order_id IN (SELECT so.id FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.order_status!='Refunded') ) AS {$code}_transactions_total,";
            }
        }
        if ( $order_source ) {
            foreach ( $order_source as $source ) {
                $source_id = $source['id'];
                $query .= "( SELECT COUNT(*) FROM $order_table so  WHERE so.session_id=$session_id " . $condition . " AND so.source_id=$source_id AND so.order_status NOT IN ('Cancelled','Refunded','Deleted')) AS source_{$source_id}_orders_count,";
                $query .= "( SELECT SUM(so.grand_total) FROM $order_table so  WHERE so.session_id=$session_id " . $condition . " AND so.source_id=$source_id AND so.order_status NOT IN ('Cancelled','Refunded','Deleted')) AS source_{$source_id}_amount,";
                $query .= "( SELECT SUM(so.discount) FROM $order_table so  WHERE so.session_id=$session_id " . $condition . " AND so.source_id=$source_id  AND so.order_status NOT IN ('Cancelled','Refunded','Deleted')) AS source_{$source_id}_discount,";
            }
        }

        if ( $register_id ) {
            $query .= "( SELECT SUM(so.grand_total) FROM ord_order so  WHERE so.close_register_id = $register_id AND so.order_status NOT IN ('cancelled','Refunded','Deleted') AND so.session_id = $session_id AND so.employee_id IN(SELECT es.employee_id FROM emp_shift es WHERE es.session_id = $session_id AND es.close_register_id IS NULL AND es.end_shift IS NULL ) AND so.id IN (SELECT op.order_id FROM ord_payment op WHERE op.payment_method_id IN (SELECT spm.id FROM sys_payment_method spm WHERE spm.is_cash=1)) ) AS register_to_emp_total,";
        }

        $query .= "( SELECT SUM(opr.amount) FROM ord_payment_refund opr WHERE opr.payment_method_id = $cash_payment_method_id AND opr.order_id IN (SELECT so.id FROM $order_table so WHERE so.session_id=$session_id " . $condition . " AND so.order_status = 'Partial_refunded')) AS cash_refund_transactions_total,";

        $query .= "( SELECT SUM(so.discount) FROM $order_table so WHERE so.session_id=$session_id " . $condition . "  AND so.order_status NOT IN ('Cancelled','Refunded')) AS discount_total,
                    ( SELECT SUM(so.change) FROM ord_order so WHERE so.session_id=$session_id " . $condition . " AND so.id IN (SELECT op.order_id FROM ord_payment op WHERE op.payment_method_id IN (SELECT spm.id FROM sys_payment_method spm WHERE spm.is_cash=1))) AS change_total,
                    ( SELECT SUM(so.tip) FROM ord_order so WHERE so.session_id=$session_id " . $condition . " AND so.order_status!='Refunded' ) AS tip_total,
                    ( SELECT SUM(so.tax_total) FROM ord_order so WHERE so.session_id=$session_id " . $condition . " AND so.order_status NOT IN ('Cancelled','Refunded')) AS tax_total,
                    ( SELECT SUM(so.gratuity_total) FROM ord_order so WHERE so.session_id=$session_id " . $condition . " AND so.order_status NOT IN ('Cancelled','Refunded')) AS gratuity_total,
                    ( SELECT SUM(so.gratuity_total) FROM ord_order so WHERE so.session_id=$session_id " . $condition . " AND so.order_status NOT IN ('Cancelled','Refunded') AND so.id IN (SELECT op.order_id FROM ord_payment op WHERE op.payment_method_id IN (SELECT spm.id FROM sys_payment_method spm WHERE spm.is_cash=1))) AS cash_gratuity_total

                    FROM dual";
        return _db_get_query( $query, true );

    }

    public function _close_session_post() {

        _model( 'pos_session' );

        $obj = _input( 'obj' );

        unset( $obj['type'] ,$obj['printers']);

        $this->_vue_to_sql( $obj, $this->pos_session->keys );

        $session_id = $obj['id'];
        unset( $obj['id'] );
        $session = $this->pos_session->single( ['id' => $session_id] );

        if ( $session ) {

            $obj['closing_user_id'] = _get_user_id();
            $obj['closing_date'] = sql_now_datetime();
            $obj['status'] = 'Close';

            if ( $this->pos_session->update( $obj, ['id' => $session_id] ) ) {

                _set_setting( 'web', '0', 'order_sources_switch' );

                $summary = $this->_close_session_summary( ['session_id' => $session_id, 'recalculate_cash' => false] );
                if ( $summary ) {

                    $obj = $this->_prep_print_summary( $summary );
                    _response_data( 'printData', $obj );
                }

                _response_data( 'message', ['text' => 'Session was closed', 'type' => 'success'] );
            }

        } else {
            _response_data( 'message', ['text' => 'There was a problem while closing current session. Please try again or contact your administrator', 'type' => 'error'] );
            return false;
        }

        return true;

    }
    public function _close_shift_summary_get(){
        $obj = _input('obj');
        $session_id = $obj['sessionId'];
        $employee_id = $obj['employeeId'];

        $summary = $this->_close_employee_summary( ['session_id' => $session_id, 'enableRefunded' => ALLOW_REFUND, 'employee_id' => $employee_id] );
        $employee = _db_get_query("SELECT * FROM emp_employee ee LEFT JOIN emp_shift es ON ee.id=es.employee_id WHERE ee.id = $employee_id AND es.session_id = $session_id",true);

        $name = $employee['first_name'] ." ".$employee['last_name'];
        $summary['openingDate'] = $employee['start_shift'];
        $summary['openingEmployee'] = $name;
        $summary['closingDate']=$employee['end_shift'];
        $summary['closingEmployee'] = $name;
        $obj = $this->_prep_print_summary( $summary );
        _response_data( 'printData', $obj );
        return true;

    }

    private function _prep_print_summary( &$summary ) {
        $payments = [];
        if ( @$summary['payments'] ) {
            $payments[] = ['title' => 'Specific Payments', 'value' => '', 'line' => true];
            foreach ( $summary['payments'] as $payment ) {
                $payments[] = ['title' => $payment['label'], 'value' => custom_money_format( is_numeric( $payment['amount'] ) ? $payment['amount'] : 0 )];
            }
        }

        $specific_orders = [];
        $specific_amounts = [];
        $specific_discounts = [];
        if ( @$summary['source'] ) {
            $specific_orders[] = ['title' => 'Specific Orders', 'value' => '', 'line' => true];
            $specific_amounts[] = ['title' => 'Specific Amounts', 'value' => '', 'line' => true];
            if ( ALLOW_DISCOUNT_IN_SUMMARY ) {
                $specific_discounts[] = ['title' => 'Specific Discounts', 'value' => '', 'line' => true];
            }
            foreach ( $summary['source'] as $single ) {
                $specific_orders[] = ['title' => $single['label'], 'value' => $single['order']];
                $specific_amounts[] = ['title' => $single['amountLabel'], 'value' => custom_money_format( is_numeric( $single['amount'] ) ? $single['amount'] : 0 )];
                if ( ALLOW_DISCOUNT_IN_SUMMARY ) {
                    $specific_discounts[] = ['title' => $single['amountLabel'], 'value' => custom_money_format( is_numeric( $single['discount'] ) ? $single['discount'] : 0 )];

                }
            }
        }

        $total_orders = [
            ['title' => 'Overall Orders', 'value' => '', 'line' => true],
            ['title' => 'Placed', 'value' => $summary['ordersCount']],
            ['title' => 'Cancelled', 'value' => $summary['cancelledOrdersCount']],
        ];

        $employee = [
            ['title' => 'Opened', 'value' => custom_date_format( $summary['openingDate'] )],
            ['title' => 'Opened by', 'value' => $summary['openingEmployee']],
            ['title' => 'Closed', 'value' => custom_date_format( $summary['closingDate'] )],
            ['title' => 'Closed by', 'value' => $summary['closingEmployee']],
        ];
        $amount = [
            ['title' => 'Amounts', 'value' => '', 'line' => true],
            ['title' => 'Opening', 'value' => custom_money_format( is_numeric( $summary['openingCash'] ) ? $summary['openingCash'] : 0 )],
            //['title'=>'Cash','value'=>custom_money_format(is_numeric($summary['cash_transactions_total'])?$summary['cash_transactions_total']:0)],
            // ['title'=>'Card','value'=>custom_money_format(is_numeric($summary['card_transactions_total'])?$summary['card_transactions_total']:0)],
            ['title' => 'Discount', 'value' => custom_money_format( is_numeric( $summary['discountTotal'] ) ? $summary['discountTotal'] : 0 )],
            ['title' => 'Change', 'value' => custom_money_format( is_numeric( $summary['changeTotal'] ) ? $summary['changeTotal'] : 0 )],
            ['title' => 'Tip', 'value' => custom_money_format( is_numeric( $summary['tipTotal'] ) ? $summary['tipTotal'] : 0 )],
            ['title' => 'Tax Total', 'value' => custom_money_format( is_numeric( $summary['taxTotal'] ) ? $summary['taxTotal'] : 0 )],
        ];
        if ( ALLOW_GRATUITY ) {
            $amount = array_merge( $amount, [
                ['title' => 'Gratuity Total', 'value' => custom_money_format( is_numeric( $summary['gratuityTotal'] ) ? $summary['gratuityTotal'] : 0 )],
            ] );
        }
        $amount = array_merge( $amount, [
            ['title' => 'Cancelled Orders', 'value' => custom_money_format( is_numeric( $summary['cancelledTransactionsTotal'] ) ? $summary['cancelledTransactionsTotal'] : 0 )],
            ['title' => 'Refunded Orders', 'value' => custom_money_format( is_numeric( $summary['refundedTransactionsTotal'] ) ? $summary['refundedTransactionsTotal'] : 0 )],
            ['title' => 'Orders', 'value' => custom_money_format( is_numeric( $summary['transactionsTotal'] ) ? $summary['transactionsTotal'] : 0 )],
            ['title' => 'Payment', 'value' => custom_money_format( is_numeric( $summary['totalPaymentReceived'] ) ? $summary['totalPaymentReceived'] : 0 )],
            ['title' => 'Take Out', 'value' => custom_money_format( is_numeric( $summary['takeOut'] ) ? $summary['takeOut'] : 0 )],
            ['title' => 'Closing', 'value' => custom_money_format( is_numeric( $summary['closingCash'] ) ? $summary['closingCash'] : 0 )],
        ] );
        $items = array_merge( $employee, $total_orders, $specific_orders, $specific_amounts, $specific_discounts, $payments, $amount );
        $obj = [
            'items' => $items,
        ];
        return $obj;
    }

    public function _close_register_post() {
        _model( 'pos_register_session', 'prs' );

        $obj = _input( 'obj' );

        unset( $obj['type'] ,$obj['printers']);

        $this->_vue_to_sql( $obj, $this->prs->keys );

        $register_session_id = $obj['id'];
        unset( $obj['id'] );
        $register_session = $this->prs->single( ['id' => $register_session_id] );

        if ( $register_session ) {

            $obj['closing_user_id'] = _get_user_id();
            $obj['closing_date'] = sql_now_datetime();
            $obj['status'] = 'Close';

            $register_id = $register_session['register_id'];
            $session_id = $register_session['session_id'];
            if ( $this->prs->update( $obj, ['id' => $register_session_id] ) ) {
                $params = [
                    'session_id'       => $session_id,
                    'recalculate_cash' => false,
                    'register_id'      => $register_id,
                ];

                $result = $this->_get_session_summary( $params );
                if ( $result ) {
                    $register_session = $this->_prep_summary( $register_session, $result, SUMMARY_TYPE_REGISTER );
                    $obj = $this->_prep_print_summary( $register_session );
                    _response_data( 'printData', $obj );
                }
            }
            _response_data( 'message', ['text' => 'Register was closed', 'type' => 'success'] );
        } else {
            _response_data( 'message', ['text' => 'There was a problem while closing current session. Please try again or contact your administrator', 'type' => 'error'] );
            return false;
        }
        return true;
    }

    public function _order_put() {
        return $this->_manage_order();
    }

    public function _order_post() {

        return $this->_manage_order();

    }

    public function _order_none_split_type_post() {

        _model( 'orders/order', 'order' );
        _model( 'orders/split', 'split' );
        _model( 'orders/split_item', 'split_item' );
        _model( 'orders/split_payment', 'split_payment' );
        _model( 'orders/payment', 'payment' );

        $order_id = _input_post( 'order_id' );

        $this->_clear_split_order( $order_id, [] );
        $this->_clear_split_order_items( $order_id, [] );
        $this->_clear_split_order_payments( $order_id, [] );
        $this->_clear_payments( $order_id, [] );

        $this->order->update( ['split_type' => 'none'], ['id' => $order_id] );
        return true;

    }

    public function _update_split_payment_post() {

        _model( 'orders/split', 'split' );
        _model( 'orders/payment', 'payment' );
        _model( 'orders/split_payment', 'split_payment' );
        _model( 'orders/clover_payment', 'clover_payment' );

        $order_id = _input_post( 'order_id' );
        $split_id = _input_post( 'split_id' );
        $payments = _input_post( 'payments' );
        $clover_payment = _input_post( 'cloverPayment' );

        if ( $payments ) {

            foreach ( $payments as $payment ) {

                $split_order = $this->split->single( ['id' => $split_id] );

                $split_tip_total = $split_order['tip'];
                $tip_total = $payment['tipTotal'] + $split_tip_total;

                $split_change_total = $split_order['change'];
                $change_total = $payment['changeTotal'] + $split_change_total;

                unset( $payment['tipTotal'], $payment['changeTotal'] );

                $this->_vue_to_sql( $payment, $this->payment->keys );
                $payment['order_id'] = $order_id;

                $payment = $this->_add_payment( $payment );

                if ( $payment ) {
                    $insert = [
                        'split_id'   => $split_id,
                        'order_id'   => $order_id,
                        'payment_id' => $payment['id'],
                    ];
                    $this->split_payment->insert( $insert );
                }
                if ( @$clover_payment ) {
                    $clover_obj = [
                        'order_id'   => $order_id,
                        'payment_id' => 0,
                        'row'        => serialize( $clover_payment ),
                        'added'      => sql_now_datetime(),
                    ];
                    $this->clover_payment->save( $clover_obj );
                }

            }
            if ( $tip_total > 0 || $change_total > 0 ) {
                $this->_add_order_change_tip( $order_id, $tip_total, $change_total );
                $this->_add_split_order_change_tip( $split_id, $tip_total, $change_total );
            }

            $payments = _get_module( 'orders', '_get_order_payments', ['order_id' => $order_id] );

            $params = [
                'split_id' => $split_id,
                'payments' => ( $payments ) ? $payments : [],
            ];

            $split_data = _get_module( 'orders', '_get_split', $params );
            _response_data( 'split', $split_data );
            return true;
        }
        return false;

    }

    private function _add_order_change_tip( $order_id, $tip_total, $change_total ) {
        _model( 'orders/order', 'order' );
        $existing_order = $this->order->single( ['id' => $order_id] );
        $order = [
            'tip'    => $tip_total + $existing_order['tip'],
            'change' => $change_total + $existing_order['change'],
        ];
        $this->order->update( $order, ['id' => $order_id] );

    }

    private function _add_split_order_change_tip( $order_id, $tip_total, $change_total ) {
        _model( 'orders/split', 'split' );
        $order = [
            'tip'    => $tip_total,
            'change' => $change_total,
        ];
        $this->split->update( $order, ['id' => $order_id] );

    }

    public function _order_refund_post() {
        _model( 'orders/payment', 'payment' );
        _model( 'orders/payment_refund', 'payment_refund' );
        _model( 'orders/clover_refund_payment', 'clover_refund_payment' );

        $order_id = _input( 'orderId' );
        $refundPayments = _input( 'refundPayments' );
        $session_id = _input( 'sessionId' );
        $register_id = _input( 'registerId' );
        $status = _input( 'orderStatus' );
        $refundTotal = _input( 'refundTotal' );
        $clover_refund_obj = _input( 'cloverRefundObj' );
        $order = _get_module( 'orders', '_single', ['id' => $order_id] );

        $refundTotal = (float) $refundTotal - (float) $order['change'];
        if ( (float) $refundTotal === (float) $order['grandTotal'] + (float) $order['tip'] ) {
            $status = 'Refunded';
        } else {
            $status = 'Partial_refunded';
        }

        if ( $refundPayments ) {
            foreach ( $refundPayments as $payment ) {
                $insert = [
                    'order_id'          => $order_id,
                    'payment_id'        => 0, // $payment['id'],
                    'payment_method_id' => $payment['paymentMethodId'],
                    'amount'            => $payment['amount'],
                    'added'             => sql_now_datetime(),
                ];
                $this->payment_refund->insert( $insert );

            }
            if ( @$clover_refund_obj ) {
                $clover_obj = [
                    'order_id'   => $order_id,
                    'payment_id' => 0,
                    'row'        => serialize( $clover_refund_obj ),
                    'added'      => sql_now_datetime(),
                ];
                $this->clover_refund_payment->save( $clover_obj );
            }

            $params = [];
            $params['order_id'] = $order_id;
            $params['status'] = $status;
            $params['session_id'] = $session_id;
            $params['register_id'] = $register_id;
            $result = $this->_manage_order_status_change( $params );
            return $result;
        }

    }
    public function _partial_refund_post() {
        _model( 'orders/payment', 'payment' );
        _model( 'orders/payment_refund', 'payment_refund' );

        $order_id = _input( 'id' );
        $payments = _input( 'refundPayments' );
        if ( $payments ) {
            foreach ( $payments as $payment ) {
                $insert = [
                    'order_id'          => $order_id,
                    'payment_id'        => '',
                    'payment_method_id' => $payment['paymentMethodId'],
                    'amount'            => $payment['amount'],
                    'added'             => sql_now_datetime(),
                ];
                $this->payment_refund->insert( $insert );
            }
            return true;
        }
        return false;
    }

    public function _set_printed_post() {
        $order_id = _input_post( 'orderId' );

        $this->_set_printed( $order_id );
        return true;
    }

    public function _order_status_post() {

        _model( 'orders/order', 'order' );

        $order_id = _input( 'id' );
        $status = _input( 'orderStatus' );
        $session_id = _input( 'sessionId' );
        $register_id = _input( 'registerId' );
        $params = [];
        $params['order_id'] = $order_id;
        $params['status'] = $status;
        $params['session_id'] = $session_id;
        $params['register_id'] = $register_id;
        $result = $this->_manage_order_status_change( $params );
        return $result;
    }

    public function _manage_order_status_change( $params = [] ) {
        $order_id = $params['order_id'];
        $status = $params['status'];
        $session_id = $params['session_id'];
        $register_id = $params['register_id'];
        $order = false;
        if ( $status == 'Cancelled' || $status == 'Closed' || $status == 'Refunded' || $status == "Partial_refunded" ) {
            $order = _get_module( 'orders', '_single', ['id' => $order_id] );
        }

        $filter = [
            'id'         => $order_id,
        ];
        $data = [
            'order_status' => $status,
            'close_register_id' => $register_id,
        ];

        if ( $status === 'Cancelled' ) {
            $data['cancelled'] = 1;
        }
        $this->order->update( $data, $filter );
        if ( $this->order->affected_rows() ) {
            if ( $order ) {
                if ( $order['type'] == 'dine' ) {
                    $table_id = $order['tableId'];
                    if ( $table_id ) {
                        $this->_release_table_session( $table_id );
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function _manage_order() {

        _model( 'orders/order', 'order' );
        _model( 'orders/order_item', 'order_item' );
        _model( 'orders/order_item_addon', 'addon' );
        _model( 'orders/order_item_note', 'note' );
        _model( 'orders/payment', 'payment' );
        _model( 'orders/order_promotion', 'promotion' );
        _model( 'orders/clover_payment', 'clover_payment' );

        _model( 'orders/split', 'split' );
        _model( 'orders/split_item', 'split_item' );
        _model( 'orders/split_payment', 'split_payment' );

        $obj = _input( 'obj' );
        $close_order = ( @$obj['close'] == true ) ? true : false;

        $mode = $obj['mode'];
        $addToPrintQueue = isset($obj['addToPrintQueue']) && $obj['addToPrintQueue'] == true ? true : false;
        unset($obj['addToPrintQueue']);

        $this->_prep_order_obj( $obj );
        //dd($obj);
        $order_table = $obj['order_table'];
        $payment_table = $obj['order_payment_table'];
        $promotion_table = $obj['order_promotion_table'];

        $split_type = $order_table['split_type'];
        $split_table = [];
        $clover_payment = @$obj['cloverPayment'];

        /*$source_id = SO_SOURCE_POS_ID;
        $order_table['source_id'] = $source_id;*/

        $order = $this->_add_order( $order_table, $mode );
        if ( $order ) {

            $order_id = $order['id'];

            if ( $mode == 'add' ) {
                $part_no = 1;
            } else {
                $part_no = $this->_get_new_part_no( $order_id );
            }

            $this->_clear_promotions( $order_id, [] );
            if ( $promotion_table ) {
                foreach ( $promotion_table as $promotion ) {
                    $promotion['order_id'] = $order_id;
                    $this->promotion->insert( $promotion );
                }
            }

            $items_table = $obj['order_item_table'];
            $order['items'] = [];
            $ignore_items = [];

            if ( $split_type != 'none' ) {
                $split_table = $obj['split_table'];
            }
            foreach ( $items_table as $item ) {

                $item['order_id'] = $order_id;
                if ( !@$item['part_no'] ) {
                    $item['part_no'] = $part_no;
                }

                $temp_order_item_id = false;
                if ( $mode == 'add' && $split_type != 'none' ) {
                    $temp_order_item_id = $item['orderItemId'];
                }
                unset( $item['orderItemId'] );

                $updated_item = $this->_add_order_item( $item );

                if ( $temp_order_item_id ) {
                    $new_order_item_id = $updated_item['id'];

                    array_walk_recursive( $split_table, function ( &$v, $k ) use ( $temp_order_item_id, $new_order_item_id ) {
                        if ( $k == 'order_item_id' && $v == $temp_order_item_id ) {
                            $v = $new_order_item_id;
                        }
                    } );

                }

                $ignore_items[] = $updated_item['id'];

                $order['items'][] = $updated_item;

            }
            $this->_clear_order_items( $order_id, $ignore_items );

            if ( $split_type != 'none' ) {

                $split_ignore = [];
                $split_items_ignore = [];
                foreach ( $split_table as $invoice ) {
                    $split_items = $invoice['item_table'];
                    unset( $invoice['item_table'] );

                    //$split_payments = $invoice['payments'];
                    unset( $invoice['payments'] );

                    $invoice['order_id'] = $order_id;

                    $split_id = $this->_add_split_order( $invoice );
                    $split_ignore[] = $split_id;

                    if ( $split_id ) {

                        foreach ( $split_items as $split_item ) {
                            $split_item['order_id'] = $order_id;
                            $split_item['split_id'] = $split_id;

                            $split_item_id = $this->_add_split_order_item( $split_item );

                            $split_items_ignore[] = $split_item_id;
                        }
                    }
                }
                $this->_clear_split_order( $order_id, $split_ignore );
                $this->_clear_split_order_items( $order_id, $split_items_ignore );
            }

            $ignore_payments = [];
            /*$order_source_links = (array)ORDER_SOURCE_PAYMENT_LINK;

            $discount_value = 0;
            $source_id = $order['source_id'];*/
            if ( $payment_table ) {
                foreach ( $payment_table as $payment ) {

                    /*
                    $payment_method_id = $payment['payment_method_id'];
                    $link = array_values(array_filter($order_source_links,function($single) use ($payment_method_id) {
                    return $single['payment_method_id'] == $payment_method_id;
                    }));

                    if(count($link)==1){
                    $source_id = $link[0]['source_id'];
                    $pm_id = $link[0]['payment_method_id'];
                    $payment_method = _get_module('core/payment_methods','_find',['filter'=>['id'=>$pm_id]]);

                    $discount_value = (@(float)$payment_method['auto_discount_value'] > 0) ? (float)$payment_method['auto_discount_value'] : 0;
                    }*/

                    $payment['reference_no'] = $order['order_no'];
                    $payment['order_id'] = $order_id;

                    $updated_payment = $this->_add_payment( $payment );
                    if ( $updated_payment ) {
                        $ignore_payments[] = $updated_payment['id'];
                    }
                }
            }
            if ( $split_type == 'none' ) {
                $this->_clear_payments( $order['id'], $ignore_payments );
            }
            if ( @$clover_payment ) {
                $clover_obj = [
                    'order_id'   => $order_id,
                    'payment_id' => 0,
                    'row'        => serialize( $clover_payment ),
                    'added'      => sql_now_datetime(),
                ];
                $this->clover_payment->save( $clover_obj );
            }

            /*if($source_id!=SO_SOURCE_POS_ID) {

            $update = [
            'source_id'=>$source_id,
            ];

            if($discount_value>0) {
            $sub_total = $order['sub_total'];
            $tax_rate = $order['tax_rate'];

            $discount = ((float)$sub_total * (float)$discount_value) / 100;
            $tax = (((float)$sub_total - (float)$discount) * (float)$tax_rate) / 100;

            $grand_total = (float)$sub_total - (float)$discount + (float)$tax;

            $discount_type = 'p';

            $update_totals = [
            'discount_type'     =>  $discount_type,
            'sub_total'         =>  $sub_total,
            'tax_total'         =>  $tax,
            'change'            =>  0,
            'discount'          =>  $discount,
            'discount_value'    =>  $discount_value,
            'adjustment'        =>  0,
            'tip'               =>  0,
            'promotion_total'   =>  0,
            'grand_total'       =>  $grand_total
            ];
            $update = array_merge($update,$update_totals);
            }

            $this->order->update($update,['id'=>$order_id]);
            }*/

            $items = $order['items'];
            unset( $order['items'] );
            $this->_exclude_keys( $order, $this->order->exclude_keys );
            $this->_sql_to_vue( $order, $this->order->keys );
            foreach ( $items as $item ) {
                $this->_exclude_keys( $item, $this->order_item->exclude_keys );
                $this->_sql_to_vue( $item, $this->order_item->keys );
                $order['items'][] = $item;
            }

            if ( $order['type'] == 'dine' ) {
                if ( $obj['tableId'] ) {
                    $table_id = $obj['tableId'];
                    $this->_reserve_table( $order['id'], $table_id, $order['seatUsed'] );
                }
            }

            $print = ( @$obj['print'] ) ? true : false;

            if ( $close_order ) {
                if ( $order['type'] == 'dine' ) {
                    if ( @$obj['tableId'] ) {
                        $table_id = $obj['tableId'];
                        $this->_release_table_session( $table_id );
                    }
                }
                $this->_clear_printed_order_queue( $order['id'] );
            }
            if ( $order['type'] == 'p' ) {
                if ( @$obj['tableId'] ) {
                    $table_id = $obj['tableId'];
                    $this->_release_table_session( $table_id );
                }
            }

            if ( $print && !$addToPrintQueue ) {

                $print_order = $this->_get_print_data( $order['id'] );
                if ( $print_order ) {
                    $print_obj = $this->_prep_print_obj( $print_order );
                    /*_vars('order', $print_order);
                    $print_format = _get_setting('print_format', '80mm');
                    $print_view = _view("print_templates/$print_format");*/
                    _response_data( 'printData', $print_obj );
                    //_response_data('printView', $print_view);
                }
            }
            if ( $addToPrintQueue ) {
                $this->_addToPrintQueue( $order['id'] );
            }

            if ( $mode === 'add' ) {
                _response_data( 'orderId', $order['id'] );
                _response_data( 'message', ['type' => 'success', 'text' => 'Order #' . $order['sessionOrderNo'] . ' was placed successfully.'] );
            } elseif ( $mode === 'edit' ) {
                _response_data( 'orderId', $order['id'] );
                _response_data( 'message', ['type' => 'success', 'text' => 'Order #' . $order['sessionOrderNo'] . ' was updated successfully.'] );
            }
            _response_data( 'order', $order );
            return true;

        }
        return false;

    }

    public function _addToPrintQueue( $order_id ) {
        _model( 'orders/print_queue', 'print_queue' );
        $data = [
            'id'       => '',
            'order_id' => $order_id,
            'added'    => sql_now_datetime(),
        ];
        $this->print_queue->insert( $data );
        $id = $this->print_queue->insert_id();
        log_message("error","Queue Created: ".$id." ,Time:".sql_now_datetime());
        return true;

    }

    public function _get_print_queue() {
        $query = "SELECT id,order_id FROM ord_print_queue ORDER BY added ASC;";
        return _db_get_query( $query );
    }

    public function _get_print_orders( $orders ) {
        $print_orders = [];
        $fetched = [];
        if ( $orders ) {
            foreach ( $orders as $order ) {
                $order_id = $order['order_id'];
                if ( in_array( $order_id, $fetched ) ) {
                    continue;
                }
                $fetched[] = $order_id;
                $print_order = $this->_get_print_data( $order_id );

                if ( $print_order ) {
                    $print_orders[] = [
                        'id'    => $order['id'],
                        'order' => $this->_prep_print_obj( $print_order ),
                    ];
                }
            }
        }
        return $print_orders;
    }

    public function _print_queue_orders_get() {
        $orders = _input( 'queue' );
        _response_data( 'printQueueOrders', $this->_get_print_orders( $orders ) );
        return true;
    }

    public function _clear_printed_queue_post() {
        $queueIds = _input( 'queueIds' );
        if ( $queueIds ) {
            return $this->_clear_printed_queue( $queueIds );
        }
        return true;
    }

    public function _clear_printed_queue($queueIds) {
        $ids = implode( ',', $queueIds );
        _db_query( "DELETE FROM ord_print_queue WHERE id IN (" . $ids . ")" );
        log_message("error","Queue Cleared: ".$ids. ". Time:".sql_now_datetime());
        return true;
    }

    public function _clear_printed_order_queue($orderId) {
        _db_query( "DELETE FROM ord_print_queue WHERE order_id = " . $orderId );
        log_message("error","Order Queue Cleared: " . $orderId . ". Time:".sql_now_datetime());
        return true;
    }

    private function _get_new_part_no( $order_id ) {
        $this->order_item->select( 'MAX(part_no) as last_part_no' );
        $existing = $this->order_item->single( ['order_id' => $order_id] );

        return ( @$existing['last_part_no'] ) ? $existing['last_part_no'] + 1 : 1;
    }

    public function _set_printed( $order_id, $part_no = false ) {

        _model( 'orders/order_item', 'order_item' );

        $item_table = $this->order_item->table;

        $sql = "UPDATE $item_table SET printed_qty=quantity,last_modify_qty=quantity WHERE order_id=$order_id";
        if ( $part_no ) {
            $sql .= " AND part_no=$part_no";
        }
        _db_query( $sql );
        return true;

    }

    private function _get_non_printed( $order_id ) {
        return $this->order_item->search( ['order_id' => $order_id, 'printed_qty<' => 'quantity'] );
    }

    public function _order_load_get() {

        $id = _input( 'id' );
        if ( $id ) {
            $result = _get_module( 'orders', '_single', ['id' => $id] );
            if ( $result ) {
                $order = [];
                $total_fields = ['subTotal', 'promotionTotal', 'tip', 'taxTotal', 'taxRate', 'discount', 'discountValue', 'discountType', 'change', 'grandTotal', 'adjustment', 'gratuityRate', 'gratuityTotal'];
                foreach ( $result as $key => $value ) {
                    if ( in_array( $key, $total_fields ) ) {
                        $order['cart']['totals'][$key] = ( $key === 'discountValue' ) ? round( $value, 0 ) : $value;
                    } elseif ( $key == 'items' ) {
                        if ( count( $value ) ) {
                            $temp = [];
                            foreach ( $value as $item ) {
                                $item['originalQty'] = $item['quantity'];
                                $item['hasSpiceLevel'] = ( $item['hasSpiceLevel'] === '1' ) ? true : false;
                                $item['isPriceEditable'] = ( (int) $item['itemId'] === (int) _get_setting( 'open_item_id', OPEN_ITEM_ID ) ) ? true : false;
                                $item['data'] = $this->_single_item( ['id' => $item['itemId'], 'type' => $item['type']] );
                                $temp[] = $item;
                            }
                            $value = $temp;
                        }
                        $order['cart'][$key] = $value;
                    } elseif ( $key == 'payments' ) {
                        $order['cart']['totals'][$key] = $value;
                    } else {
                        $order[$key] = $value;
                    }
                }
                _response_data( 'obj', $order );
                return true;
            } else {
                _response_data( 'message', ['text' => 'The requested details could not be found.', 'type' => 'warning'] );
                return false;
            }
        } else {
            _response_data( 'message', ['text' => 'The requested details could not be found.', 'type' => 'warning'] );
            return false;
        }

    }
    public function _repeat_order_get() {
        $id = _input( 'id' );
        if ( $id ) {
            $result = _get_module( 'orders', '_single', ['id' => $id] );
            $result['subTotal'] = 0;
            $result['tip'] = 0;
            $result['taxTotal'] = 0;
            $result['discount'] = 0;
            $result['discountValue'] = 0;
            $result['change'] = 0;
            $result['grandTotal'] = 0;
            $result['id'] = '';
            $result['cancelled'] = 0;
            $result['tableId'] = null;
            $result['seatUsed'] = null;
            $result['gratuityTotal'] = 0;
            $result['gratuityRate'] = GRATUITY_RATE;
            $result['sourceId'] = SO_SOURCE_POS_ID;
            $result['extOrderNo'] = '';
            $result['split'] = [];
            if ( $result ) {
                $order = [];
                $total_fields = ['subTotal', 'promotionTotal', 'tip', 'taxTotal', 'taxRate', 'discount', 'discountValue', 'discountType', 'change', 'grandTotal', 'adjustment'];
                foreach ( $result as $key => $value ) {
                    if ( in_array( $key, $total_fields ) ) {
                        $order['cart']['totals'][$key] = ( $key === 'discountValue' ) ? round( $value, 0 ) : $value;
                    } elseif ( $key == 'items' ) {
                        if ( count( $value ) ) {
                            $temp = [];
                            foreach ( $value as &$item ) {
                                $item['id'] = null;
                                $item['lastModifyQty'] = 0;
                                $item['hasSpiceLevel'] = ( $item['hasSpiceLevel'] === '1' ) ? true : false;
                                $item['isPriceEditable'] = ( (int) $item['itemId'] === (int) _get_setting( 'open_item_id', OPEN_ITEM_ID ) ) ? true : false;
                                $item['data'] = $this->_single_item( ['id' => $item['itemId'], 'type' => $item['type']] );
                                if ( $item['data'] ) {
                                    $addons = $item['addons'];
                                    $addonPrice = 0;
                                    if ( $addons ) {
                                        foreach ( $addons as $addon ) {
                                            $addon['rate'] = $addon['rate'] * $addon['quantity'];
                                            $enabled = @$addon['enabled'] == true;
                                            if ( $enabled && $addon['quantity'] > 0 ) {
                                                $addonPrice += (float) $addon['rate'];
                                            }
                                        }
                                    }
                                    $item['rate'] = $item['rate'] + $addonPrice;
                                    $item['amount'] = '';
                                    $item['id'] = '';
                                    $item['printedQty'] = 0;
                                    $item['partNo'] = 1;
                                    $temp[] = $item;
                                }
                            }
                            $value = $temp;
                        }
                        $order['cart'][$key] = $value;
                    } elseif ( $key == 'payments' ) {
                        $order['cart']['totals'][$key] = [];
                    } else {
                        $order[$key] = $value;
                    }
                }
                _response_data( 'obj', $order );
                return true;
            } else {
                _response_data( 'message', ['text' => 'The requested details could not be found.', 'type' => 'warning'] );
                return false;
            }
        } else {
            _response_data( 'message', ['text' => 'The requested details could not be found.', 'type' => 'warning'] );
            return false;
        }
    }

    public function _update_change_and_tip_post(){
        _model( 'orders/order', 'order' );
        $order_id = _input( 'orderId' );
        $obj = _input( 'obj' );
       // $order = $this->order->single(['id'=>$order_id]);

      // dd($this->order->affected_rows());
        if($this->order->update( ['tip'=>$obj['tip'],'change'=>$obj['change']], ['id' => $order_id] )){
            _response_data( 'message', ['text' => 'The Successfully Change And Tip.'] );
        }else{
            _response_data( 'message', ['text' => 'The Change And Tip Failed.', 'type' => 'warning'] );
        }

        return true;

    }

    public function _cache_items_get() {

        //$this->output->enable_profiler(TRUE);
        $ids = _input( 'ids' );
        $items = $this->_cache_items( $ids );

        _response_data( 'cacheItems', $items );
        return true;

    }

    private function _cache_items( $ids ) {
        $items = _get_cache( 'pos_items' );
        if ( !$items ) {
            _model( 'items/item', 'item' );
            $items = [];
            if ( $ids ) {
                $meta = _get_module( 'items', '_get_items_meta', ['ids' => $ids] );
                $this->item->where_in( 'id', $ids );
                $result = $this->item->search();
                if ( $result ) {
                    foreach ( $result as $single ) {
                        $param = [];
                        $param['item'] = $single;
                        $param['meta'] = $meta;
                        $param['id'] = $single['id'];
                        $param['type'] = $single['type'];
                        $items[] = $this->_single_item( $param );
                    }
                }
                _set_cache( 'pos_items', $items, 1800 );
            }
        }
        return $items;
    }

    public function _single_item_get() {

        _helper( 'zebra' );

        $id = _input( 'id' );

        $item = $this->_single_item( ['id' => $id] );

        _response_data( 'obj', $item );
        return true;
    }

    public function _single_item( $params = [] ) {

        _helper( 'zebra' );

        $id = $params['id'];
        $item_type = ( @$params['type'] ) ? $params['type'] : false;
        $meta = ( @$params['meta'] ) ? $params['meta'] : false;
        $item_data = ( @$params['item'] ) ? $params['item'] : false;
        $filter_params = ['id' => $id];
        if ( $meta ) {
            $filter_params['meta'] = $meta;
        }
        if ( $item_data ) {
            $filter_params['item'] = $item_data;
        }

        //TODO Remove this function in the future
        /*  if(!$item_type) {
        $item_meta = _get_module('items', '_find', ['filter' => $filter_params]);
        $item_type = ($item_meta) ? $item_meta['type'] : null;
        } */
        $item = _get_module( 'items', '_single', $filter_params );

        if ( $item ) {
            $item['quantity'] = 1;
            $item['isPriceEditable'] = (int) $item['id'] === (int) _get_setting( 'open_item_id', OPEN_ITEM_ID );
            $item['orderItemNotes'] = '';

            if ( @$item['addons'] ) {
                $temp = [];
                foreach ( $item['addons'] as &$a ) {
                    $addon = [
                        'itemId'   => $a['id'],
                        'title'    => $a['title'],
                        'rate'     => $a['rate'],
                        'type'     => $a['type'],
                        'quantity' => 1,
                        'enabled'  => false,
                        'parent'   => $a['parent'],
                    ];
                    $temp[] = $addon;

                }
                $item['addons'] = $temp;
            }

            if ( $item['image'] ) {
                $image_path = _get_config( 'global_upload_path' );
                $cache_path = _get_config( 'global_upload_cache_path' );
                if ( !file_exists( $cache_path . 'items/' ) ) {
                    mkdir( $cache_path . 'items/', 0777, true );
                }
                $image_file_name = _get_image_cache_name( $item['image'], '_' . POS_DETAIL_WIDTH . 'x' . POS_DETAIL_HEIGHT );
                if ( !file_exists( $cache_path . $image_file_name ) ) {
                    $params = [
                        'width'       => POS_DETAIL_WIDTH,
                        'height'      => POS_DETAIL_HEIGHT,
                        'source'      => $image_path . $item['image'],
                        'destination' => $cache_path . $image_file_name,
                    ];
                    $resize = _resize_crop_center( $params );
                    if ( !$resize ) {
                        $image_file_name = '';
                    }
                }
                $item['imageCachePath'] = $image_file_name;
            }
        }
        return $item;
    }

    public function _user_login_post() {
        _model( 'auth/auth_model', 'auth_m' );

        $email = _input( 'email', true );
        $password = _input( 'password', true );

        $user = $this->auth_m->login( $email, $password );

        if ( $user ) {
            _response_data( 'userLogin', true );
            return true;
        } else {
            _response_data( 'message', 'Invalid Email or Password.' );
            return false;
        }
    }

    public function _update_check_get() {
        _model( 'registers/register', 'register' );

        $web_session_id = WEB_SESSION_ID;
        $source_id = SO_SOURCE_WEB_ID;
        $obj = _input( 'obj' );
        $result = [];
        $session_id = $obj['sessionId'];

        //Check whether session is open
        $open_session = _db_get_query("SELECT * FROM pos_session ps WHERE ps.id =$session_id AND ps.status='Open'",true);
        if(!$open_session){
            $result['reload'] = true;
            _response_data( 'result', $result );

            return true;
        }
        $session_query = " SELECT
                            (SELECT COUNT(*)  FROM pos_register_session prs WHERE prs.session_id = $session_id AND prs.closing_user_id IS NOT NULL AND prs.closing_date IS NOT NULL) AS closeRegister,
                            (SELECT COUNT(*) AS openRegister FROM pos_register_session prs WHERE prs.session_id = $session_id AND prs.status = 'Open') AS openRegister,
                            (SELECT COUNT(*) FROM emp_shift es WHERE  es.session_id = $session_id AND es.close_register_id IS NULL AND es.end_shift IS NULL) AS  openEmpShiftCount,
                            (SELECT COUNT(*)  FROM ord_order oo WHERE oo.session_id = $session_id AND oo.order_status IN ('Confirmed','Preparing','Ready'))  AS openOrderCount,
                            (SELECT COUNT(*)  FROM ord_order oo WHERE oo.session_id = $web_session_id AND oo.source_id=$source_id) AS onlineOrderCount
                          FROM dual";

        $session_result = _db_get_query( $session_query, true );
        $result['closeRegister'] = $session_result['closeRegister'];
        $result['openRegister'] = $session_result['openRegister'];
        $result['openEmpShiftCount'] = $session_result['openEmpShiftCount'];
        $result['openOrderCount'] = $session_result['openOrderCount'];
        $result['onlineOrderCount'] = $session_result['onlineOrderCount'];

        $items = _get_cache( 'pos_items' );
        $result['itemCount'] = $items ? count( $items ) : -1;

        $print_queue = [];
        $print_queue_count = 0;
        if ( PRINT_QUEUE && !USE_PRINT_QUEUE_V2) {
            $primary_browser_id = _get_setting( BROWSER_ID_KEY, BROWSER_ID );
            if(!$primary_browser_id){
                log_message('error','No Primary Print Server Found');
            }
            $print_queue_list = $this->_get_print_queue();
            $print_queue_count = ( $print_queue_list ) ? count( $print_queue_list ) : 0;
            //check whether it is print server browser
            if ( $obj['browserId'] == $primary_browser_id && $print_queue_count ) {
                $print_queue = $this->_get_print_orders( $print_queue_list );
            }
        }
        $result['printQueueCount'] = $print_queue_count;
        $result['printQueue'] = $print_queue;
        $result['appVersion'] = CORE_VERSION;

        $register_id = $obj['registerId'];
        $key = $obj['key'];
        $registerCheckLogin = $this->register->single( ['key' => $key, 'id' => $register_id] );
        $register_data = [
            'title'              => $registerCheckLogin ? $registerCheckLogin['title'] : '',
            'registerCheckLogin' => $registerCheckLogin ? true : false,
            'primary'            => $registerCheckLogin ? ((int)$registerCheckLogin['primary'] === 1 ? true : false) : false,
        ];

        $result['register'] = $register_data;

        _response_data( 'result', $result );

        return true;
    }

    public function _orders_get() {

        $session_id = _input( 'session_id' );

        $orders = [];

        if ( $session_id ) {
            $params = [];
            $params['filter'] = [
                'session_id' => $session_id,
            ];
            $params['exclude'] = true;
            $params['convert'] = true;
            $params['orders'] = [
                ['order_by' => 'id', 'order' => 'DESC'],
            ];
            $params['limit'] = 400;
            $orders = _get_module( 'orders', '_search', $params );

            // $sources = _get_module('orders', '_order_sources', ['convert'=>true,'exclude'=>true]);
            $sources = _get_cache( 'pos_order_sources' );
            if ( !$sources ) {
                $sources = _get_module( 'orders', '_order_sources', ['convert' => true, 'exclude' => true] );
                _set_cache( 'pos_order_sources', $sources );
            }
            if ( $orders ) {
                $temp = [];
                foreach ( $orders as $order ) {
                    $total_paid = _get_module( 'orders/payments', '_get_order_payment_total', ['order_id' => $order['id']] );

                    $source_id = $order['sourceId'];
                    $source = array_values( array_filter( $sources, function ( $single ) use ( $source_id ) {
                        return $single['id'] == $source_id;
                    } ) );
                    $source = ( $source ) ? $source[0] : false;

                    $order_type = $order['type'];
                    if ( $order_type == 'p' ) {
                        $order['type'] = 'Pickup';
                        if ( $source ) {
                            $order['type'] .= ' (' . $source['title'] . ')';
                        }
                    } elseif ( $order_type == 'dine' ) {
                        $order['type'] = 'Dine-in';
                        if ( $order_type == 'dine' ) {
                            $table = _get_module( 'areas/tables', '_get_order_table', ['order_id' => $order['id']] );
                            if ( $table ) {
                                $order['type'] = 'Dine-in - ' . $table['title'];
                            }
                            if ( $source ) {
                                $order['type'] .= ' (' . $source['title'] . ')';
                            }
                        }
                    } elseif ( $order_type == 'd' ) {
                        $order['type'] = 'Delivery';
                        if ( $source ) {
                            $order['type'] .= ' (' . $source['title'] . ')';
                        }
                    }
                    if ( $order['extOrderNo'] ) {
                        $order['sessionOrderNo'] = $order['sessionOrderNo'] . ' (' . $order['extOrderNo'] . ')';
                    }

                    $order['totalPaid'] = $total_paid;
                    $order['paymentStatus'] = 'Pending';
                    $order['closeOrder'] = false;
                    $order['cancelOrder'] = false;
                    if ( $order['totalPaid'] > 0 ) {
                        if ( $order['totalPaid'] >= $order['grandTotal'] ) {
                            $order['paymentStatus'] = 'Paid';
                            $order['closeOrder'] = $order['id'];
                        } elseif ( $order['totalPaid'] < $order['grandTotal'] ) {
                            $order['paymentStatus'] = 'Partial';
                        }
                    } else {
                        $order['cancelOrder'] = true;
                    }

                    $unset_array = ['change', 'tip', 'discount', 'adjustment', 'cancelled', 'notes', 'gratuityTotal', 'gratuityRate', 'seatUsed', 'subTotal', 'taxTotal', 'taxRate', 'promotionTotal', 'discountValue', 'discountType', 'freightTotal', 'dutyTotal', 'totalPaid'];
                    if ( $unset_array ) {
                        foreach ( $unset_array as $ua ) {
                            unset( $order[$ua] );
                        }
                    }
                    $temp[] = $order;
                }
                $orders = $temp;
            }

        }

        _response_data( 'orders', $orders );
        return true;
    }

    public function _unaccepted_orders_get() {

        $session_id = WEB_SESSION_ID;

        $orders = [];

        if ( $session_id ) {
            $params = [];
            $params['filter'] = [
                'session_id' => $session_id,
                'source_id'  => SO_SOURCE_WEB_ID,
            ];
            $params['exclude'] = true;
            $params['convert'] = true;
            $params['orders'] = [
                ['order_by' => 'id', 'order' => 'ASC'],
            ];
            $orders = _get_module( 'orders', '_search', $params );

            if ( $orders ) {
                $temp = [];
                foreach ( $orders as $order ) {
                    $total_paid = _get_module( 'orders/payments', '_get_order_payment_total', ['order_id' => $order['id']] );

                    $order['totalPaid'] = $total_paid;
                    $order['paymentStatus'] = 'Pending';
                    $order['closeOrder'] = false;
                    $order['cancelOrder'] = false;
                    if ( $order['totalPaid'] > 0 ) {
                        if ( $order['totalPaid'] >= $order['grandTotal'] ) {
                            $order['paymentStatus'] = 'Paid';
                            $order['closeOrder'] = $order['id'];
                        } elseif ( $order['totalPaid'] < $order['grandTotal'] ) {
                            $order['paymentStatus'] = 'Partial';
                        }
                    } else {
                        $order['cancelOrder'] = true;
                    }

                    $temp[] = $order;
                }
                $orders = $temp;
            }

        }

        _response_data( 'orders', $orders );
        return true;
    }

    public function _accept_online_order_post() {

        _model( 'orders/order', 'order' );

        $id = _input( 'id' );
        $session_id = _input( 'sessionId' );
        $register_id = _input( 'registerId' );

        $session_order_no = $this->_get_new_session_order_no( $session_id );
        $employee_id = _get_user_id();

        $update_data = [
            'session_id'       => $session_id,
            'opening_register_id'      => $register_id,
            'session_order_no' => $session_order_no,
            'employee_id'      => $employee_id,
        ];
        $filter = ['id' => $id];

        if ( $this->order->update( $update_data, $filter ) ) {
            _response_data( 'orderAccepted', true );
            return true;
        }
        return false;

    }

    public function _order_print_post() {

        $order_id = _input( 'id' );

        $print_order = $this->_get_print_data( $order_id );

        if ( $print_order ) {
            $print_obj = $this->_prep_print_obj( $print_order );
            //_vars('order', $print_order);
            //$print_format = _get_setting('print_format', '80mm');
            //$print_view = _view("print_templates/$print_format");
            _response_data( 'printData', $print_obj );
        }
        return true;
    }

    public function _split_order_print_post() {

        $split_order_id = _input( 'split_order_id' );
        $order_id = _input( 'order_id' );

        $print_order = $this->_get_print_data( $order_id );

        if ( $print_order ) {
            $print_obj = $this->_prep_print_obj( $print_order, $split_order_id );
            _response_data( 'printData', $print_obj );
        }
        return true;

    }

    public function _get_menu() {

        $menus = [];

        $menus[] = [
            'id'       => 'menu-pos',
            'class'    => '',
            'icon'     => 'si si-calculator',
            'group'    => 'module',
            'name'     => 'POS',
            'path'     => 'pos',
            'module'   => 'pos',
            'priority' => 1,
            'children' => [],
        ];

        return $menus;

    }

    private function _add_order( $obj, $mode ) {

        $order_id = false;
        if ( $mode === 'add' ) {
            $register_id = $obj['opening_register_id'];
            $outlet_id = _db_get_query("SELECT sr.outlet_id AS outlet_id FROM sys_register sr WHERE sr.id = $register_id",true);
            $session_id = $obj['session_id'];
            $obj['session_order_no'] = $this->_get_new_session_order_no( $session_id );
            $obj['order_no'] = _get_ref( ORDER_REF );
            $obj['outlet_id'] = $outlet_id['outlet_id'];
            $obj['notes'] = ( @$obj['notes'] ) ? $obj['notes'] : '';
            $obj['order_date'] = sql_now_datetime();
            $obj['added'] = sql_now_datetime();
            if ( !isset( $obj['order_status'] ) ) {
                $obj['order_status'] = 'Confirmed';
            }
            $source_id = SO_SOURCE_POS_ID;
            $order_table['source_id'] = $source_id;
            /*  $employee_id = _get_user_id();
            if($employee_id==NULL){
            log_message('error','UserID is null');
            } */
            /*  if($obj['employee_id'] === null){
            $obj['employee_id'] = _get_user_id();
            } */
            // $obj['employee_id'] = ($employee_id)?$employee_id:0;

            $obj['adjustment'] = 0;

            if ( $this->order->insert( $obj ) ) {
                $order_id = $this->order->insert_id();
                _update_ref( ORDER_REF );
            }

        } elseif ( $mode === 'edit' ) {
            $order_id = $obj['id'];
            unset( $obj['id'] );
            $existing = $this->order->single( ['id' => $order_id] );
            $existing_status_rank = $this->_get_order_status_rank( $existing['order_status'] );
            $new_status_rank = $this->_get_order_status_rank( $obj['order_status'] );

            if ( $existing_status_rank !== NULL ) {
                if ( $new_status_rank === NULL OR $new_status_rank < $existing_status_rank ) {
                    $obj['order_status'] = $existing['order_status'];
                }
            }

            $this->order->update( $obj, ['id' => $order_id] );

        }

        if ( $order_id ) {
            $order = $this->order->single( ['id' => $order_id] );
            if ( $order ) {
                return $order;
            }
        }
        return false;
    }

    private function _add_order_item( $obj ) {
        $order_item_id = false;
        $addons = $obj['addons'];
        $notes = $obj['selectedNotes'];
        unset( $obj['addons'] );
        unset( $obj['selectedNotes'] );
        unset( $obj['originalQty'] );
        if ( isset( $obj['id'] ) && $obj['id'] ) {
            if($obj['quantity'] > $obj['last_modify_qty']){
                $obj['last_modify_qty'] = $obj['quantity'];
             }
            $order_item_id = $obj['id'];
            unset( $obj['id'] );
            $this->order_item->update( $obj, ['id' => $order_item_id] );
        } else {
            $obj['last_modify_qty'] = $obj['quantity'];
            $obj['added'] = sql_now_datetime();
            if ( $this->order_item->insert( $obj ) ) {
                $order_item_id = $this->order_item->insert_id();
            }
        }
        if ( $order_item_id ) {
            $this->addon->delete( ['order_item_id' => $order_item_id] );
            if ( @$addons ) {
                $this->_vue_to_sql( $addons, $this->addon->keys, true );
                foreach ( $addons as $addon ) {
                    $enabled = @$addon['enabled'] == true;
                    if ( $enabled && $addon['quantity'] > 0 ) {
                        unset( $addon['enabled'], $addon['parent'] );
                        $addon['order_item_id'] = $order_item_id;
                        $addon['added'] = sql_now_datetime();
                        $this->addon->insert( $addon );
                    }
                }
            }

            $this->note->delete( ['order_item_id' => $order_item_id] );
            if ( $notes ) {
                $this->_vue_to_sql( $notes, $this->note->keys, true );
                foreach ( $notes as $note ) {
                    unset( $note['id'] );
                    $note['order_item_id'] = $order_item_id;
                    $note['added'] = sql_now_datetime();
                    $this->note->insert( $note );
                }
            }
            $item = $this->order_item->single( ['id' => $order_item_id] );
            if ( $item ) {
                return $item;
            }
        }

        return false;

    }

    private function _add_payment( $obj ) {

        $payment_id = ( isset( $obj['id'] ) && $obj['id'] ) ? $obj['id'] : null;

        unset( $obj['cash'] );
        unset( $obj['paymentMethodName'] );

        $payment_obj = false;
        if ( $payment_id ) {
            $payment_obj = $this->payment->single( ['id' => $payment_id] );
        }

        if ( $payment_obj ) {

            $payment_id = $payment_obj['id'];
            $this->payment->update( $obj, ['id' => $payment_id] );

        } else {

            $obj['order_no'] = _get_ref( 'pay' );
            $obj['payment_date'] = sql_now_datetime();
            $obj['added'] = sql_now_datetime();

            if ( $this->payment->insert( $obj ) ) {
                $payment_id = $this->payment->insert_id();
                _update_ref( 'pay' );
            }

        }
        if ( $payment_id ) {
            $payment = $this->payment->single( ['id' => $payment_id] );

            if ( $payment ) {
                return $payment;
            }
        }
        return false;
    }

    private function _add_split_order( $obj ) {

        if ( isset( $obj['id'] ) && $obj['id'] ) {

            $split_id = $obj['id'];
            $this->split->update( $obj, ['id' => $split_id] );
            return $split_id;

        } else {
            $obj['order_no'] = _get_ref( 'spt' );
            $obj['added'] = sql_now_datetime();

            if ( $this->split->insert( $obj ) ) {
                return $this->split->insert_id();
            }
        }

    }

    private function _add_split_order_item( $obj ) {
        if ( isset( $obj['id'] ) && $obj['id'] ) {

            $split_item_id = $obj['id'];
            $this->split_item->update( $obj, ['id' => $split_item_id] );
            return $split_item_id;

        } else {

            $obj['added'] = sql_now_datetime();

            if ( $this->split_item->insert( $obj ) ) {
                return $this->split_item->insert_id();
            }
        }
    }

    private function _clear_split_order( $order_id, $ignore ) {

        if ( $ignore ) {
            $this->db->where_not_in( 'id', $ignore );
        }
        return $this->split->delete( ['order_id' => $order_id] );

    }

    private function _clear_split_order_items( $order_id, $ignore ) {

        if ( $ignore ) {
            $this->db->where_not_in( 'id', $ignore );
        }
        return $this->split_item->delete( ['order_id' => $order_id] );

    }
    private function _clear_split_order_payments( $order_id, $ignore ) {

        if ( $ignore ) {
            $this->db->where_not_in( 'id', $ignore );
        }
        return $this->split_payment->delete( ['order_id' => $order_id] );

    }

    private function _get_new_session_order_no( $session_id ) {

        $query = "SELECT MAX(oo.session_order_no) as last_order_no FROM ord_order oo WHERE oo.session_id=$session_id";

        $result = _db_get_query( $query, true );

        if ( $result['last_order_no'] === null ) {
            return 1;
        } else {
            return (int) $result['last_order_no'] + 1;
        }

    }

    private function _clear_order_items( $so_id, $ignore ) {

        if ( $ignore ) {
            $this->db->where_not_in( 'id', $ignore );
        }
        return $this->order_item->delete( ['order_id' => $so_id] );

    }

    private function _clear_payments( $order_id, $ignore ) {

        if ( $ignore ) {
            $this->db->where_not_in( 'id', $ignore );
        }
        $this->payment->delete( ['order_id' => $order_id] );

    }

    private function _clear_promotions( $order_id, $ignore ) {

        if ( $ignore ) {
            $this->db->where_not_in( 'id', $ignore );
        }
        $this->promotion->delete( ['order_id' => $order_id] );

    }

    private function _prep_order_obj( &$obj ) {

        $order_keys = $this->order->keys;
        $payment_keys = $this->payment->keys;
        $order_item_keys = $this->order_item->keys;

        $split_keys = $this->split->keys;
        $split_item_keys = $this->split_item->keys;
        $split_payment_keys = $this->split_payment->keys;

        if ( empty( @$obj['customer'] ) ) {
            $customer_id = DEFAULT_POS_CUSTOMER;
            $customer = $customer = _get_module( 'contacts/customers', '_single', ['id' => $customer_id] );
        } else {
            $customer = $obj['customer'];
            $customer_id = $customer['id'];
        }

        $obj['customer_id'] = $customer_id;
        unset( $obj['customer'] );

        $obj['billingName'] = $customer['displayName'];
        /*  $obj['address1'] ='';//$customer['billing']['address1'];
        $obj['address2'] = '';//$customer['billing']['address2'];
        $obj['city'] = '';//$customer['billing']['city'];
        $obj['state'] = '';//$customer['billing']['state'];
        $obj['country'] = '';//$customer['billing']['country'];
        $obj['zipCode'] ='';// $customer['billing']['zipCode']; */

        $obj['order_table'] = [];
        $obj['order_item_table'] = [];
        $obj['order_payment_table'] = [];
        $obj['order_promotion_table'] = [];

        if ( @$obj['promotions']['applied'] ) {
            foreach ( $obj['promotions']['applied'] as $promotion_id ) {
                $obj['order_promotion_table'][] = [
                    'promotion_id' => $promotion_id,
                ];
            }
        }

        if ( isset( $obj['cart']['totals']['payments'] ) ) {
            foreach ( $obj['cart']['totals']['payments'] as $payment ) {

                $payment['customerId'] = $customer_id;
                $payment['notes'] = '';
                $this->_vue_to_sql( $payment, $payment_keys );

                $obj['order_payment_table'][] = $payment;
            }
            unset( $obj['cart']['totals']['payments'] );
        }

        foreach ( $obj['cart']['totals'] as $key => $value ) {
            $obj[$key] = $value;
        }
        unset( $obj['cart']['totals'] );

        foreach ( $order_keys as $old => $new ) {
            change_array_key( $old, $new, $obj );
            if ( isset( $obj[$new] ) ) {
                $obj['order_table'][$new] = $obj[$new];
                unset( $obj[$new] );
            }
        }

        if ( @$obj['close'] == true ) {
            $obj['order_table']['order_status'] = 'Closed';
            unset( $obj['close'] );
        }

        if ( !isset( $obj['order_table']['source_id'] ) ) {
            $obj['order_table']['source_id'] = SO_SOURCE_POS_ID;
        }

        if ( !isset( $obj['order_table']['tax_rate'] ) ) {
            $obj['order_table']['tax_rate'] = (int) _get_setting( 'default_tax_rate', 5 );
        }

        if ( !isset( $obj['order_table']['freight_total'] ) ) {
            $obj['order_table']['freight_total'] = 0;
        }

        if ( !isset( $obj['order_table']['duty_total'] ) ) {
            $obj['order_table']['duty_total'] = 0;
        }

        $freight = ( (float) $obj['order_table']['freight_total'] ) ? (float) $obj['order_table']['freight_total'] : 0;
        $duty = ( (float) $obj['order_table']['duty_total'] ) ? (float) $obj['order_table']['duty_total'] : 0;

        $remove_keys = ['data', 'prices', 'isPriceEditable'];
        if ( @$obj['cart']['items'] ) {
            foreach ( $obj['cart']['items'] as $key => $item ) {

                $temp = $item;
                /*foreach ($remove_keys as $key) {
                if (isset($temp[$key])) {
                unset($temp[$key]);
                }
                }*/
                $temp['addons'] = ( @$temp['addons'] ) ? $temp['addons'] : false;
                $temp['selectedNotes'] = ( @$temp['selectedNotes'] ) ? $temp['selectedNotes'] : false;

                $this->_exclude_keys( $temp, $remove_keys );
                $this->_vue_to_sql( $temp, $order_item_keys );

                /*foreach ($order_item_keys as $old => $new) {
                change_array_key($old, $new, $temp);
                }
                dump_exit($temp);*/
                $temp['has_spice_level'] = ( $temp['has_spice_level'] == true ) ? 1 : 0;
                $temp['amount'] = (float) $temp['quantity'] * (float) $temp['rate'];
                /* $temp['freight_total'] = 0;
                $temp['duty_total'] = 0;
                if ($freight > 0) {
                $item_freight = ((float)$temp['amount'] * (float)$freight) / (float)$obj['order_table']['sub_total'];
                $temp['freight_total'] = $item_freight;
                }
                if ($duty > 0) {
                $item_duty = ((float)$temp['amount'] * (float)$duty) / (float)$obj['order_table']['sub_total'];
                $temp['duty_total'] = $item_duty;
                } */
                unset($temp['categoryId']);
                $obj['order_item_table'][] = $temp;
            }
            unset( $obj['cart']['items'] );
        }

        //Split Order
        if ( @$obj['order_table']['split_type'] != 'none' ) {
            if ( @$obj['split'] ) {
                $remove_split_item_keys = ['title'];
                foreach ( $obj['split'] as $invoice ) {

                    /*foreach ($invoice['totals'] as $total_key=>$total) {
                    $invoice[$total_key] = $total;
                    }
                    unset($invoice['totals']);*/

                    $items = ( @$invoice['items'] ) ? $invoice['items'] : [];
                    unset( $invoice['items'] );
                    $invoice['item_table'] = [];
                    foreach ( $items as $item ) {
                        $this->_exclude_keys( $item, $remove_split_item_keys );
                        $this->_vue_to_sql( $item, $split_item_keys );
                        $invoice['item_table'][] = $item;
                    }

                    //$payments = $temp[''];

                    //$this->_exclude_keys($temp,$remove_keys);
                    $this->_vue_to_sql( $invoice, $split_keys );

                    $obj['split_table'][] = $invoice;

                }
            }
        }

        unset( $obj['split'] );
        unset( $obj['cart'] );
        unset( $obj['company'] );
    }

    public function _reserve_table( $order_id, $table_id, $seat_used ) {
        _model( 'areas/area_session', 'area_session' );
        _model( 'areas/area_table', 'area_table' );

        $user_id = _get_user_id();
        $session_data = [
            'table_id'      => $table_id,
            'user_id'       => $user_id,
            'session_start' => sql_now_datetime(),
        ];
        if ( $this->area_session->insert( $session_data ) ) {
            $session_id = $this->area_session->insert_id();

            $table_data = [
                'session_id' => $session_id,
                'seat_used'  => $seat_used,
                'status'     => 'engaged',
                'use_since'  => $session_data['session_start'],
            ];
            $this->area_table->update( $table_data, ['id' => $table_id] );
            return $this->_create_table_session( $order_id, $table_id, $session_id );
        }
        return false;
    }

    private function _create_table_session( $order_id, $table_id, $session_id ) {

        _model( 'areas/area_relation', 'area_relation' );
        _model( 'areas/area_table', 'area_table' );

        $relation_data = [
            'table_id'   => $table_id,
            'order_id'   => $order_id,
            'session_id' => $session_id,
        ];
        $existing = $this->area_relation->single( $relation_data );
        if ( !$existing ) {
            return $this->area_relation->insert( $relation_data );
        } else {
            return false;
        }

    }

    private function _release_table_session( $table_id ) {

        _model( 'areas/area_relation', 'area_relation' );
        _model( 'areas/area_session', 'area_session' );
        _model( 'areas/area_table', 'area_table' );

        $table = $this->area_table->single( ['id' => $table_id] );

        if ( $table ) {

            $session_id = $table['session_id'];

            $session_data = [
                'session_end' => sql_now_datetime(),
            ];
            $this->area_session->update( $session_data, ['id' => $session_id] );

            $table_data = [
                'session_id' => '',
                'seat_used'  => '',
                'status'     => 'available',
                'use_since'  => '',
            ];
            $this->area_table->update( $table_data, ['id' => $table_id] );
        }
    }

    private function _get_order_status_rank( $status ) {

        switch ( $status ) {
        case 'Draft':
            return 1;
        case 'Confirmed':
            return 2;
        case 'Preparing':
            return 3;
        case 'Ready':
            return 4;
        case 'Closed':
            return 5;
        case 'Refunded':
            return 6;
        case 'Partial_refunded':
            return 7;
        default:
            return NULL;
        }

    }

    public function _order_source_switch_settings_get() {
        $settings = _get_code_settings( 'order_sources_switch' );
        _response_data( 'settings', $settings );
        return true;
    }

    public function _order_source_switch_settings_post() {
        $key = _input_post( 'key' );
        $value = _input_post( 'value' ) == 'true' ? '1' : '0';
        _set_setting( $key, $value, 'order_sources_switch' );
        _response_data( 'value', $value == '1' );
        return true;
    }

    public function _hold_order_cancel_post() {
        _model( 'orders/order', 'order' );
        _model( 'orders/order_item', 'order_item' );
        _model( 'orders/order_item_addon', 'addon' );
        _model( 'orders/order_item_note', 'note' );
        $order_id = _input( 'orderId' );
        if ( $order_id ) {
            $ignore_items = [];
            $this->_clear_order_items( $order_id, $ignore_items );
            $obj = [];
            $obj['duty_total'] = 0;
            $obj['freight_total'] = 0;
            $obj['sub_total'] = 0;
            $obj['tax_total'] = 0;
            $obj['discount'] = 0;
            $obj['discount_value'] = 0;
            $obj['tip'] = 0;
            $obj['promotion_total'] = 0;
            $obj['grand_total'] = 0;
            $obj['order_status'] = 'Deleted';
            $obj['customer_id'] = 0;
            $obj['billing_name'] = '';
            $this->order->update( $obj, ['id' => $order_id] );
            return true;

        }
        return false;
    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    protected function _load_files() {

        if ( _get_method() == 'index' ) {
            _load_plugin( ['vue_multiselect', 'moment'] );
            //_load_plugin(['moment','dt']);
        }

    }

    private function _get_print_data( $id ) {

        $order = _get_module( 'orders', '_single', ['id' => $id, 'filter_disabled_addons' => true] );

        if ( $order ) {

            $order_type = '';
            if ( $order['type'] == 'd' ) {
                $order_type = 'Delivery';
            } elseif ( $order['type'] == 'p' ) {
                $order_type = 'Pick-up';
            } elseif ( $order['type'] == 'dine' ) {
                $table_title = ( @$order['tableTitle'] ) ? $order['tableTitle'] : '';
                $order_type = 'Dine-in';
                if ( $table_title ) {
                    $order_type = "Dine-in ($table_title)";
                }
                if ( DISPLAY_SEAT_USED_INVOICE ) {
                    if ( $order['seatUsed'] ) {
                        $order_type .= " (" . $order['seatUsed'] . ")";
                    }
                }
            }
            $order['orderType'] = $order_type;

            array_walk( $order['items'], function ( &$v, $k ) {
                if ( $v['spiceLevel'] == DEFAULT_SPICE_LEVEL ) {
                    $v['spiceLevel'] = 'medium';
                }
            } );
            $order['continue'] = false;
            if($order['items']){
                foreach ($order['items'] as &$item) {
                    if((int)$item['printedQty'] > 0){
                        $order['continue'] = true;
                    }
                }
            }

            //unset unused variables
            unset( $order['selectedAddons'],$order['company'],$order['promotions'] );
            if($order['customer']){
                unset($order['customer']['group'],$order['customer']['addresses'],$order['customer']['password']);
            }

            $user_id = $order['employeeId'];

            $order['cashier'] = '';
            if ( $user_id ) {
                $user = _get_module( 'users', '_find', ['filter' => ['id' => $user_id]] );

                $order['cashier'] = ( @$user['first_name'] ) ? $user['first_name'] : '';
                if ( $order['cashier'] && $user['last_name'] ) {
                    $order['cashier'] .= ' ' . $user['last_name'];
                }
            }

            $payments = ( isset( $order['payments'] ) && $order['payments'] ) ? $order['payments'] : false;

            if ( $payments ) {

                $payment_methods = _get_module( 'core/payment_methods', '_search', [] );

                $temp = [];
                foreach ( $payments as $payment ) {
                    $payment_method_id = $payment['paymentMethodId'];

                    $payment_method = array_filter( $payment_methods, function ( $method ) use ( $payment_method_id ) {
                        return $method['id'] === $payment_method_id;
                    } );
                    $payment_method = ( $payment_method ) ? array_values( $payment_method )[0] : false;

                    $temp[] = [
                        'title'  => ( $payment_method ) ? $payment_method['title'] : '',
                        'amount' => dsRound( $payment['amount'] ),
                    ];

                }
                $order['payments'] = $temp;
            }
        }
        return $order;

    }

    private function _prep_print_obj( $order, $split_order_id = '' ) {

        $split_type = $order['splitType'];
        if ( $split_type !== 'none' && $split_order_id ) {

            $splits = $order['split'];
            $split = array_values( array_filter( $splits, function ( $single ) use ( $split_order_id ) {
                return (int) $single['id'] === (int) $split_order_id;
            } ) );
            $split = ( @$split[0] ) ? $split[0] : [];

            if ( $split ) {

                $order['sessionOrderNo'] .= ' (SPLIT)';
                $order['tip'] = dsRound( $split['tip'] );
                $order['change'] = dsRound( $split['change'] );
                $order['discount'] = dsRound( $split['discount'] );
                $order['subTotal'] = dsRound( $split['subTotal'] );
                $order['discountValue'] = dsRound( $split['discountValue'] );
                $order['freightTotal'] = dsRound( $split['freightTotal'] );
                $order['gratuityTotal'] = dsRound( $split['gratuityTotal'] );
                $order['dutyTotal'] = dsRound( $split['dutyTotal'] );
                $order['taxTotal'] = dsRound( $split['taxTotal'] );
                $order['grandTotal'] = dsRound( $split['grandTotal'] );

                $order['billingName'] = ( @$split['billingName'] ) ? $split['billingName'] : $order['billingName'];

                $split_items = $split['items'];
                if ( $order['items'] ) {

                    $temp = [];
                    /* array_walk($order['items'], function ($v, $k) use ($split_items, $split_order_id) {
                    $order_item_id = $v['id'];
                    $split_item = array_values(array_filter($split_items,function($si) use ($order_item_id, $split_order_id) {
                    return $order_item_id == $si['orderItemId'];
                    }));
                    $split_item = (@$split_item[0])?$split_item[0]:false;

                    if($split_item) {
                    $v['rate'] = $split_item['rate'];
                    $v['quantity'] = $split_item['quantity'];
                    $v['amount'] = $split_item['amount'];
                    $temp[] = $v;
                    }
                    }); */
                    foreach ( $order['items'] as $v ) {
                        $order_item_id = $v['id'];
                        $split_item = array_values( array_filter( $split_items, function ( $si ) use ( $order_item_id, $split_order_id ) {
                            return $order_item_id == $si['orderItemId'];
                        } ) );
                        $split_item = ( @$split_item[0] ) ? $split_item[0] : false;

                        if ( $split_item ) {
                            $v['rate'] = $split_item['rate'];
                            $v['quantity'] = $split_item['quantity'];
                            $v['amount'] = $split_item['amount'];
                            $temp[] = $v;
                        }
                    }
                    $order['items'] = $temp;
                }
                $order['payments'] = $split['payments'];

                unset( $order['split'] );
            }

        }
        //dd($order);
        return $order;
    }

    public function summary( $id ) {

        _model( 'pos_session' );
        _model( 'users/user', 'user' );

        $this->view = false;
        $params = ['session_id' => $id];

        $session = $this->pos_session->single( ['id' => $id] );

        $result = $this->_get_session_summary( $params );

        $session['ordersCount'] = ( $result ) ? $result['orders_count'] : 0;
        $session['openOrdersCount'] = ( $result ) ? (int) $result['open_orders_count'] : 0;
        $session['cancelledOrdersCount'] = ( $result ) ? $result['cancelled_orders_count'] : 0;
        $session['transactionsTotal'] = ( $result ) ? $result['transactions_total'] : 0;
        $session['cashTransactionsTotal'] = ( $result ) ? $result['cash_transactions_total'] : 0;
        $session['cardTransactionsTotal'] = ( $result ) ? $result['card_transactions_total'] : 0;
        $session['changeTotal'] = ( $result ) ? $result['change_total'] : 0;
        $session['discountTotal'] = ( $result ) ? $result['discount_total'] : 0;
        $session['tipTotal'] = ( $result ) ? $result['tip_total'] : 0;

        $closing_cash = ( (float) $session['cashTransactionsTotal'] - (float) $session['changeTotal'] ) + (float) $session['opening_cash'];
        $session['expectedClosingCash'] = $session['closing_cash'] = round( $closing_cash, 2 );

        $opening_user = $this->user->single( ['id' => $session['opening_user_id']] );

        $session['openingEmployee'] = ( $opening_user ) ? $opening_user['first_name'] . ' ' . $opening_user['last_name'] : '';

        $this->_exclude_keys( $session, $this->pos_session->exclude_keys );
        $this->_sql_to_vue( $session, $this->pos_session->keys );

    }
}
