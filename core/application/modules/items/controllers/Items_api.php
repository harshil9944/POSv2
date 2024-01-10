<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Items_api extends API_Controller
{

    public $model = 'item';
    public $module = 'items';
    public $singular = 'Item';
    public $plural = 'Items';

    public function __construct()
    {
        parent::__construct();
        _model(['item', 'item_category', 'item_addon']);
    }

    public function _api_populate_items($params = [])
    {
        _helper('zebra');
        $categoryId = _input('category');

        $categories = [];//_get_cache('web_categories');
        if (!$categories) {
            $categories = $this->_get_categories(['order' => ['order_by' => 'sort_order', 'order' => 'ASC'], 'filter' => ['web_status' => 1], 'select_data' => true, 'include_select' => false]);
            $skip_categories = [];
            $skip_categories[] = OPEN_ITEM_CATEGORY_ID;

            if ($categories) {
                $temp = [];
                foreach ($categories as $category) {
                    if (in_array($category['id'], $skip_categories)) {
                        continue;
                    }
                    $temp[] = $category;
                }
                $categories = $temp;
            }

            // _set_cache('web_categories', $categories, 1800);
        }
        $items = [];//_get_cache('web_items');
        if (!$items || $categoryId) {

            _delete_cache('web_items');

            $skip_items = [];
            $skip_items[] = OPEN_ITEM_ID;

            $fields = [
                'id'
            ];

            $sql = "SELECT " . implode(',', $fields) . " from itm_item where type='product' AND web_status=1 AND is_addon=0 AND id NOT IN (" . implode(',', $skip_items) . ")";
            if ($categoryId) {
                $sql .= " AND category_id=$categoryId";
            }

            //$items = _db_get_query("SELECT " . implode(',', $fields) . " from itm_item where type='product' AND web_status=1 AND is_addon=0 AND id NOT IN (" . implode(',', $skip_items) . ")");
            $items = _db_get_query($sql);
            if ($items) {

                $ids = array_column($items, 'id');

                $meta = $this->_get_items_meta(['ids' => $ids]);

                $temp = [];
                foreach ($items as $item) {

                    $filter_params = ['id' => $item['id'], 'meta' => $meta];
                    $item_details = $this->_single_item($filter_params);
                    $item_details['quantity'] = 1;

                    $temp[] = $item_details;
                }
                $items = $temp;
            }
            // _set_cache('web_items',$items,1800);
        }
        $spiceLevels = SPICE_LEVELS;

        if ($items) {
            $response = [
                'status'      => 'ok',
                'type'        => 'HTTP_CREATED',
                'items'       => $items,
                'spiceLevels' => $spiceLevels,
                'categories'  => $categories,
                'message'     => 'Data fetch Successful'
            ];
        } else {
            $response = [
                'status'  => 'error',
                'type'    => 'HTTP_BAD_REQUEST',
                'message' => 'Something Wrong'
            ];
        }
        return $response;
    }

    public function _api_menu_items()
    {

        $type = _input('type');
        $categoryId = _input('category');
        $category_cache = $type ? "web_menu_categories_$type" : "web_menu_categories";
        $item_cache = $type ? "web_menu_items_$type" : "web_menu_items";

        _helper('zebra');

        if (!$categories = _get_cache($category_cache)) {
            $filters = ['web_status' => 1];
            if ($type != null) {
                $filters['type'] = $type;
            }
            $categories = $this->_get_categories(['order' => ['order_by' => 'sort_order', 'order' => 'ASC'], 'filter' => $filters]);
            $skip_categories = [];
            $skip_categories[] = OPEN_ITEM_CATEGORY_ID;

            if ($categories) {
                $temp = [];
                foreach ($categories as $category) {
                    if (in_array($category['id'], $skip_categories)) {
                        continue;
                    }
                    $temp[] = $category;
                }
                $categories = $temp;
            }
            // _set_cache($category_cache, $categories);
        }
        if (!$items = _get_cache($item_cache) && !$categoryId) {
            $item_params = [];
            $item_params['filter'] = ['web_status' => 1, 'type' => 'product', 'parent' => 0];
            if ($categoryId) {
                $item_params['filter']['category_id'] = $categoryId;
            }

            $item_params['limit'] = 3000;
            $item_params['orders'] = [['order_by' => 'title', 'order' => 'ASC']];
            $item_params['exclude'] = true;
            $item_params['convert'] = true;
            $items = _get_module('items', '_search', $item_params);
            if ($items) {
                $temp = [];
                foreach ($items as $item) {

                    $variant_params = [
                        'filter'  => [
                            'parent' => $item['id'],
                            'type'   => ITEM_TYPE_VARIANT,
                        ],
                        'exclude' => true,
                        'convert' => true,
                    ];

                    $variations = _get_module('items', '_get_item_variations', $variant_params);
                    $new_variations = [];
                    if ($variations) {
                        $prices = array_column($variations, 'rate');
                        $min_price = min($prices);
                        $item['rate'] = $min_price;
                        foreach ($variations as $v) {
                            $new_variations[] = [
                                'title' => $v['title'],
                                'isVeg' => $v['isVeg'],
                                'rate'  => $v['rate']
                            ];
                        }
                    }
                    $temp[] = [
                        'id'         => $item['id'],
                        'name'       => $item['title'],
                        'rate'       => $item['rate'],
                        'type'       => $item['type'],
                        'variations' => $new_variations,
                        'categoryId' => $item['categoryId'],
                    ];
                }

                $items = $temp;
                if (!$categoryId) {
                    _set_cache($item_cache, $items);
                }
            }
        }
        _response_data('categories', $categories);
        _response_data('items', $items);
        return true;

    }

    private function _get_categories($params = [])
    {
        $order = (@$params['order']) ? $params['order'] : ['order_by' => 'title', 'order' => 'ASC'];

        $filter = (@$params['filter'] && is_array($params['filter'])) ? $params['filter'] : [];

        $this->item_category->order_by($order['order_by'], $order['order']);
        $categories = $this->item_category->search($filter);

        if ($categories) {
            $temp = [];
            foreach ($categories as &$c) {
                $temp[] = [
                    'id'    => $c['id'],
                    'value' => $c['title'],
                    'type'  => $c['type'],
                ];
            }
            $categories = $temp;
        }
        return $categories;
    }

    private function _get_items_meta($params)
    {

        $ids = (@$params['ids']) ? $params['ids'] : false;

        // Variations, Notes, Addons
        if ($ids) {
            $this->{$this->model}->where_in('parent', $ids);
            $this->{$this->model}->where('type', ITEM_TYPE_VARIANT);
        }
        if (SORT_VARIATION_BY_NAME) {
            $this->{$this->model}->order_by('title', 'ASC');
        }
        $variations = $this->{$this->model}->search();

        if ($ids) {
            $this->{$this->model}->where_in('parent', $ids);
            $this->{$this->model}->where('type', ITEM_TYPE_VARIANT_OPTIONAL);
        }
        $addons = $this->{$this->model}->search();
        return [
            'variations' => $variations,
            'addons'     => $addons,
        ];
    }

    private function _single_item($params = [])
    {
        $id = $params['id'];

        $meta = (@$params['meta']) ? $params['meta'] : false;
        $result = (@$params['item']) ? $params['item'] : false;

        $filter = ['id' => $id];
        if (!$result) {
            $result = $this->{$this->model}->single($filter);
        }
        if ($result) {
            $item_id = $result['id'];
            $addons = $variations = [];
            if ($meta) {

                $addons = array_values(array_filter((@$meta['addons']) ? $meta['addons'] : [], function ($single) use ($item_id) {
                    return $item_id == $single['parent'] && $single['type'] === 'optional';
                }));
                $variations = array_values(array_filter((@$meta['variations']) ? $meta['variations'] : [], function ($single) use ($item_id) {
                    return $item_id == $single['parent'] && $single['type'] === 'variant';
                }));
            }
            $this->_exclude_keys($result);
            $this->_sql_to_vue($result);

            $result['hasSpiceLevel'] = $result['hasSpiceLevel'] === '1';
            $result['isVegan'] = $result['isVegan'] === '1';
            $result['isGlutenFree'] = $result['isGlutenFree'] === '1';
            $result['isDairyFree'] = $result['isDairyFree'] === '1';
            $result['spiceLevel'] = DEFAULT_SPICE_LEVEL;
            $result['originalPrice'] = $result['rate'];
            $result['orderItemNotes'] = null;

            $result['addons'] = [];
            if (!$addons && !$meta) {
                $addons = $this->{$this->model}->getOptionalVariants($result['id']);
            }
            if (@$addons) {
                foreach ($addons as &$v) {
                    $this->_exclude_keys($v, ['code', 'app_status', 'category_id', 'code', 'created_by', 'is_addon', 'icon', 'parent', 'taxable', 'image', 'icon', 'added', 'outlet_id', 'print_location', 'unit_id', 'has_spice_level', 'is_veg', 'pos_status', 'web_status', 'app_status']);
                    $this->_sql_to_vue($v);
                    $v['quantity'] = 1;
                    $v['enabled'] = false;
                }
            }
            $result['addons'] = $addons;
            $result['variations'] = [];
            if (!$meta && !$variations) {
                $variations = $this->{$this->model}->getVariants($result['id']);
            }
            if (@$variations) {
                foreach ($variations as &$v) {
                    $this->_exclude_keys($v);
                    $this->_sql_to_vue($v);
                    $v['hasSpiceLevel'] = $v['hasSpiceLevel'] === '1';
                    $v['spiceLevel'] = DEFAULT_SPICE_LEVEL;
                    $v['originalPrice'] = $v['rate'];
                    $v['orderItemNotes'] = null;
                }
            }
            $result['variations'] = $variations;
            return $result;

        } else {
            return false;
        }
    }

    public function _get_item_variations($params)
    {
        $filter = $params['filter'];
        $limit = (isset($params['limit']) && is_int($params['limit'])) ? $params['limit'] : _get_setting('global_limit', 9999);
        $offset = (isset($params['offset']) && is_int($params['offset'])) ? $params['offset'] : 0;
        $orders = (isset($params['orders']) && is_array($params['orders'])) ? $params['orders'] : [];
        $exclude = false;
        $convert = false;

        if (isset($params['exclude'])) {
            if (is_array($params['exclude'])) {
                $exclude = $params['exclude'];
            } elseif ($params['exclude'] === true) {
                $exclude = $this->{$this->model}->exclude_keys;
            }
        }
        if (isset($params['convert'])) {
            if (is_array($params['convert'])) {
                $convert = $params['convert'];
            } elseif ($params['convert'] === true) {
                $convert = $this->{$this->model}->keys;
            }
        }
        if ($orders) {
            foreach ($orders as $order) {
                $this->{$this->model}->order_by($order['order_by'], $order['order']);
            }
        }
        $records = $this->{$this->model}->search($filter, $limit, $offset);

        if ($records) {
            $temp = [];
            foreach ($records as $single) {
                if ($exclude) {
                    $this->_exclude_keys($single, $exclude);
                }
                if ($convert) {
                    $this->_sql_to_vue($single, $convert);
                }
                $temp[] = $single;
            }
            $records = $temp;
        }
        return $records;
    }


}
