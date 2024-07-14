<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-07-04 18:05:35 --> Severity: Notice --> Undefined index: children E:\2022\I\InnTechPOS2-Master\core\application\modules\core\controllers\Menu.php 98
ERROR - 2022-07-04 18:05:35 --> Module controller failed to run: wapi/_get_menu
ERROR - 2022-07-04 18:05:49 --> Severity: Notice --> Undefined index: children E:\2022\I\InnTechPOS2-Master\core\application\modules\core\controllers\Menu.php 98
ERROR - 2022-07-04 18:05:49 --> Module controller failed to run: wapi/_get_menu
ERROR - 2022-07-04 18:05:54 --> Severity: Notice --> Undefined index: children E:\2022\I\InnTechPOS2-Master\core\application\modules\core\controllers\Menu.php 98
ERROR - 2022-07-04 18:05:54 --> Module controller failed to run: wapi/_get_menu
ERROR - 2022-07-04 18:05:58 --> Severity: Notice --> Undefined index: children E:\2022\I\InnTechPOS2-Master\core\application\modules\core\controllers\Menu.php 98
ERROR - 2022-07-04 18:05:58 --> Module controller failed to run: wapi/_get_menu
ERROR - 2022-07-04 18:06:21 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'AND oo.session_id = 1 AND oo.order_status IN('closed','partial_refund')GROUP BY ' at line 1 - Invalid query: SELECT sr.title as registerTitle,SUM(oo.grand_total) as grandTotal,SUM(oo.tip) as tip FROm ord_order oo LEFT JOIN sys_register sr ON oo.close_register_id = sr.id  WHERE oo.employee_id=  AND oo.session_id = 1 AND oo.order_status IN('closed','partial_refund')GROUP BY oo.close_register_id;
