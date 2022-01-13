<?php
$module = 'promotions';
defined('PROMOTIONS_MIGRATION_PATH')    OR  define('PROMOTIONS_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('PROMOTIONS_MIGRATION_TABLE')   OR  define('PROMOTIONS_MIGRATION_TABLE', 'mig_'.$module);

defined('PROMOTIONS_TABLE')             OR  define('PROMOTIONS_TABLE', 'prm_promotion');
defined('PROMOTIONS_OUTLET_TABLE')      OR  define('PROMOTIONS_OUTLET_TABLE', 'prm_outlet');
defined('PROMOTIONS_CRITERIA_TABLE')    OR  define('PROMOTIONS_CRITERIA_TABLE', 'prm_criteria');
defined('PROMOTIONS_CRI_PRODUCT_TABLE') OR  define('PROMOTIONS_CRI_PRODUCT_TABLE', 'prm_criteria_product');
defined('PROMOTIONS_REWARD_TABLE')      OR  define('PROMOTIONS_REWARD_TABLE', 'prm_reward');
defined('PROMOTIONS_REW_PRODUCT_TABLE') OR  define('PROMOTIONS_REW_PRODUCT_TABLE', 'prm_reward_product');
