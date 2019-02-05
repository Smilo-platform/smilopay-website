<?php include('includes/header.php'); ?>
<div class="blockBanner">
    <ul>
        <li>Smilo Price: 0.25$</li>
        <li>Average TX Price: ..... XSP</li>
        <li>Block time: 1 Sec</li>
    </ul>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 smilopayCalculator">
            <div class="center">
                <h1>SmiloPay Calculator</h1>
                <div class="form-group">
                    <p>How many Smilo do you have?</p>
                    <input type="number" class="form-control smiloPayCalcInput" id="amountSmilo" name="amountSmilo" oninput="updateXspCalculator()" value="1000"><br>
                </div>
            </div>

            <table class="table table-striped">
                <tr class="tableRowInfo">
                    <td>MaxSmiloPay</td>
                    <td id="calcMaxSmiloPay"></td>
                    <td>XSP</td>
                </tr>
                <tr class="tableRowInfo">
                    <td>Recovery Speed</td>
                    <td id="calcSpeed"></td>
                    <td>XSP/Block</td>
                </tr>
                <tr class="tableRowInfo">
                    <td>Blocks till full</td>
                    <td id="calcBlocks">
                    <td>Blocks</td>
                </tr>
            </table>
        </div>
    </div>
</div>

</body>
<?php include('includes/footer.php'); ?>
<script>
    // Load first time
    updateXspCalculator()
    function updateXspCalculator(){
        var amountSmilo = document.getElementById("amountSmilo").value;
        if(amountSmilo >= 350000000){
            amountSmilo = 350000000;
            document.getElementById("amountSmilo").value = amountSmilo;
        }

        var maxSmiloPay;
        var RecoverySpeed;
        var recoveryBlocks;

        if(amountSmilo == 0){
            maxSmiloPay = 0;
            RecoverySpeed = 0;
            recoveryBlocks = '\u221e';
        } else {
            maxSmiloPay = (0.001 + (Math.sqrt(amountSmilo)/50000)) * 5000;
            recoverySpeed =  (0.000001 + (Math.sqrt(amountSmilo) / 750000)) * 5000;
            recoveryBlocks = toFixed((maxSmiloPay/recoverySpeed));
        }

        document.getElementById("calcMaxSmiloPay").innerHTML = toFixed(maxSmiloPay);
        document.getElementById("calcSpeed").innerHTML = toFixed(recoverySpeed);
        document.getElementById("calcBlocks").innerHTML = recoveryBlocks;
    }

    function toFixed(x) {
        if (Math.abs(x) < 1.0) {
            var e = parseInt(x.toString().split('e-')[1]);
            if (e) {
                x *= Math.pow(10,e-1);
                x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
            }
        } else {
            var e = parseInt(x.toString().split('+')[1]);
            if (e > 20) {
                e -= 20;
                x /= Math.pow(10,e);
                x += (new Array(e+1)).join('0');
            }
        }
        x = Number.parseFloat(x).toFixed(8);
        return x;
    }
</script>