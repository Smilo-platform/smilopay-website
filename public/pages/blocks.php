<?php include('../includes/header.php'); ?>
<div class="container">
    <h3>Blocks</h3>
    <div class="row" id="blockList">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="col">Height</th>
                    <th scope="col">Hash</th>
                    <th scope="col">Time</th>
                    <th scope="col">Transactions</th>
                    <th scope="col">Cost XSP</th>
                </tr>
                </thead>
                <tbody id="blockDisplay">
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
