<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 03.11.15
 * Time: 12:11
 */

#!/usr/bin/php
#!/usr/bin/env  php
#!/usr/bin/php -n
#!/usr/bin/php -ddisplay_errors=E_ALL
#!/usr/bin/php -n -ddisplay_errors=E_ALL


$mysql = mysqli_connect("localhost", "tms_u", "ESXwudzg", "tms_db");
if (!$mysql) {
    die("Connection failed: " . mysqli_connect_error());
}
$query = "DELETE from tbl_order_temp WHERE date_add < DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
if (mysqli_query($mysql, $query)) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . mysqli_error($mysql);
}