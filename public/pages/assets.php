<?php include('../includes/header.php'); ?>

<?php
$assets = array
(
    array("Smilo","XSM",200000000, 0, "1-1-2019"),
    array("SmiloPay","XSP",200000000, 18, "1-1-2019"),
);
?>

<div class="container">
    <div class="row" id="showAssets">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <td><h3>Assets</h3></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="col">Asset</th>
                    <th scope="col">Symbol</th>
                    <th scope="col">Total Supply</th>
                    <th scope="col">Decimal</th>
                    <th scope="col">Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($assets as $asset){
                    echo("<tr><th scope='row'>".$asset[0]."</th><td>".$asset[1]."</td><td>".$asset[2]."</td><td>".$asset[3]."</td><td>".$asset[4]."</td></tr>");
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php include('../includes/footer.php'); ?>
