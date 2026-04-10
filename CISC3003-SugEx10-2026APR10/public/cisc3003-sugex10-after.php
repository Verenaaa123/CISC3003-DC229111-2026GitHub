<?php

include 'includes/book-utilities.inc.php';

$customers = readCustomers('data/customers.txt');

$selected_customer = null;
$orders = array();

if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];
    foreach ($customers as $c) {
        if ($c['id'] == $customer_id) {
            $selected_customer = $c;
            break;
        }
    }
    $orders = readOrders($customer_id, 'data/orders.txt');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>DC229111 Li Wu Yue</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-orange.min.css">
    <link rel="stylesheet" href="css/styles.css">

    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>

</head>

<body>

<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">

    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>

    <main class="mdl-layout__content mdl-color--grey-50">
        <section class="page-content">

            <div class="mdl-grid">

              <!-- mdl-cell + mdl-card -->
              <div class="mdl-cell mdl-cell--7-col card-lesson mdl-card  mdl-shadow--2dp">
                <div class="mdl-card__title mdl-color--orange">
                  <h2 class="mdl-card__title-text">Customers</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <table class="mdl-data-table  mdl-shadow--2dp">
                      <thead>
                        <tr>
                          <th class="mdl-data-table__cell--non-numeric">Name</th>
                          <th class="mdl-data-table__cell--non-numeric">University</th>
                          <th class="mdl-data-table__cell--non-numeric">City</th>
                          <th>Sales</th>
                        </tr>
                      </thead>
                      <tbody>

                        <?php foreach ($customers as $c): ?>
                        <tr>
                          <td class="mdl-data-table__cell--non-numeric">
                            <a href="cisc3003-sugex10.php?id=<?= htmlspecialchars($c['id']) ?>">
                              <?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?>
                            </a>
                          </td>
                          <td class="mdl-data-table__cell--non-numeric"><?= htmlspecialchars($c['university']) ?></td>
                          <td class="mdl-data-table__cell--non-numeric"><?= htmlspecialchars($c['city']) ?></td>
                          <td><span class="sparkline"><?= htmlspecialchars($c['sales']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>

                      </tbody>
                    </table>
                </div>
              </div>  <!-- / mdl-cell + mdl-card -->


            <div class="mdl-grid mdl-cell--5-col">



                  <!-- mdl-cell + mdl-card -->
                  <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card  mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Customer Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">

                      <?php if ($selected_customer): ?>
                        <h4><?= htmlspecialchars($selected_customer['first_name'] . ' ' . $selected_customer['last_name']) ?></h4>
                        <p><strong>Email:</strong> <?= htmlspecialchars($selected_customer['email']) ?></p>
                        <p><strong>University:</strong> <?= htmlspecialchars($selected_customer['university']) ?></p>
                        <p><strong>Address:</strong> <?= htmlspecialchars($selected_customer['address']) ?></p>
                        <p><strong>City:</strong> <?= htmlspecialchars($selected_customer['city']) ?></p>
                        <p><strong>Country:</strong> <?= htmlspecialchars($selected_customer['country']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($selected_customer['phone']) ?></p>
                      <?php else: ?>
                        <h4>No customer selected</h4>
                        <p>Click a customer name to view details.</p>
                      <?php endif; ?>

                    </div>
                  </div>  <!-- / mdl-cell + mdl-card -->

                  <!-- mdl-cell + mdl-card -->
                  <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card  mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Order Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">

                      <?php if ($selected_customer && empty($orders)): ?>
                        <p>No orders found for this customer.</p>
                      <?php endif; ?>

                      <?php if (!empty($orders)): ?>
                               <table class="mdl-data-table  mdl-shadow--2dp">
                              <thead>
                                <tr>
                                  <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                  <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                  <th class="mdl-data-table__cell--non-numeric">Title</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                  <td class="mdl-data-table__cell--non-numeric">
                                    <img src="images/tinysquare/<?= htmlspecialchars($order['isbn']) ?>.jpg" alt="<?= htmlspecialchars($order['title']) ?>" width="40">
                                  </td>
                                  <td class="mdl-data-table__cell--non-numeric"><?= htmlspecialchars($order['isbn']) ?></td>
                                  <td class="mdl-data-table__cell--non-numeric"><?= htmlspecialchars($order['title']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                      <?php elseif (!$selected_customer): ?>
                               <table class="mdl-data-table  mdl-shadow--2dp">
                              <thead>
                                <tr>
                                  <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                  <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                  <th class="mdl-data-table__cell--non-numeric">Title</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                      <?php endif; ?>

                        </div>
                   </div>  <!-- / mdl-cell + mdl-card -->


               </div>


            </div>  <!-- / mdl-grid -->

        </section>
    </main>
</div>    <!-- / mdl-layout -->

<footer style="text-align:center; padding: 12px; font-size: 14px; color: #555;">
    CISC3003 Web Programming: DC229111 Li Wu Yue 2026
</footer>

<script>
$(function() {
    $('.sparkline').sparkline('html', {type: 'bar', barColor: '#ff6d00', height: '30px'});
});
</script>

</body>
</html>
