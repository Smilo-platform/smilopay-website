<?php include('../includes/header.php'); ?>
<!-- //Todo send a "get_block blockNum" request to the server -->
<div class="container">
    <h3 id="blockTitle">Block</h3>
    <div class="row" id="blockDetail">
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Block info</th>
                    <th></th>
                </tr>
                <tr>
                    <td>Amount transactions</td>
                    <td id="blockTransactionsCount"></td>
                </tr>
                <tr>
                    <td>Total XSM send</td>
                    <td id="blockTotalXsmSend"></td>
                </tr>
                <tr>
                    <td>Total fee</td>
                    <td id="blockTotalFee"></td>
                </tr>
                <tr>
                    <td>Block height</td>
                    <td id="blockBlockHeight"></td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td id="blockTime"></td>
                </tr>
                </thead>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Hashes</th>
                    <td></td>
                </tr>
                <tr>
                    <td>Hash</td>
                    <td id="blockHash"></td>
                </tr>
                <tr>
                    <td>Previous block hash</td>
                    <td id="blockPreviousHash"></td>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row" id="blockDetailTx">
        <h3>Transactions</h3>
        <div id="transactionDisplayBlock" class="col-md-12">
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
