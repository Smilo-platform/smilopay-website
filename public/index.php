<?php include('includes/header.php'); ?>

<div class="container">
    <div class="row smilopayCalculator">
        <div class="center">
            <h1>SmiloPay generation calculator</h1>
            <div class="form-group">
                Amount Smilo: <input type="number" class="form-control smiloPayCalcInput" id="amountSmilo" name="amountSmilo" oninput="updateXspCalculator()" value="1000"><br>
            </div>
        </div>

        <table class="table table-striped">
            <tr>
                <th>Time</th>
                <th>SmiloPay</th>
            </tr>
            <tr class="tableRowInfo">
                <td>Block</td>
                <td id="calcBlock"></td>
            </tr>
            <tr class="tableRowInfo">
                <td>Day</td>
                <td id="calcDay"></td>
            </tr>
            <tr class="tableRowInfo">
                <td>Week</td>
                <td id="calcWeek"></td>
            </tr>
            <tr class="tableRowInfo">
                <td>Month</td>
                <td id="calcMonth"></td>
            </tr>
            <tr class="tableRowInfo">
                <td>Year</td>
                <td id="calcYear"></td>
            </tr>
        </table>
    </div>
</div>

</body>
<?php include('includes/footer.php'); ?>

<script>
    // Load first time
    updateXspCalculator()

    function updateXspCalculator(){
        var amountSmilo = document.getElementById("amountSmilo").value;
        if(amountSmilo >= 200000000){
            amountSmilo = 200000000;
            document.getElementById("amountSmilo").value = amountSmilo;
        }

        var calcDay = 0.00054;
        var calcBlock = 0.0000001

        document.getElementById("calcBlock").innerHTML = toFixed(amountSmilo*calcBlock);
        document.getElementById("calcDay").innerHTML = toFixed((amountSmilo*calcDay));
        document.getElementById("calcWeek").innerHTML = toFixed((amountSmilo*calcDay*7));
        document.getElementById("calcMonth").innerHTML = toFixed((amountSmilo*calcDay*365/12));
        document.getElementById("calcYear").innerHTML = toFixed((amountSmilo*calcDay*365));
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