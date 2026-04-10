<?php

function readCustomers($filename) {
    $customers = array();
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $fields = explode(';', $line);
        $customers[] = array(
            'id'         => trim($fields[0]),
            'first_name' => trim($fields[1]),
            'last_name'  => trim($fields[2]),
            'email'      => trim($fields[3]),
            'university' => trim($fields[4]),
            'address'    => trim($fields[5]),
            'city'       => trim($fields[6]),
            'state'      => trim($fields[7]),
            'country'    => trim($fields[8]),
            'zip'        => trim($fields[9]),
            'phone'      => trim($fields[10]),
            'sales'      => trim($fields[11])
        );
    }
    return $customers;
}

function readOrders($customer, $filename) {
    $orders = array();
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $fields = explode(',', $line);
        $order_id    = trim($fields[0]);
        $customer_id = trim($fields[1]);
        $isbn        = trim($fields[2]);
        $category    = trim(array_pop($fields));
        $title       = trim(implode(',', array_slice($fields, 3)));

        if ($customer_id == $customer) {
            $orders[] = array(
                'order_id'    => $order_id,
                'customer_id' => $customer_id,
                'isbn'        => $isbn,
                'title'       => $title,
                'category'    => $category
            );
        }
    }
    return $orders;
}

?>
