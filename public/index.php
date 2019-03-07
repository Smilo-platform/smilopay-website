<?php include('includes/header.php'); ?>
<div class="blockBanner">
    <ul>
        <li>Smilo Price: 1 Euro</li>
        <li>Average TX Price: 0.115 XSP</li>
        <li>Block time: ~ 1 Sec</li>
    </ul>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 smilopayCalculator">
            <div class="center">
                <h1>SmiloPay Calculator</h1>
                <div class="form-group">
                    <p>How many Smilo do you have?</p>
                    <input type="number" class="form-control smiloPayCalcInput" id="amountSmilo" name="amountSmilo" oninput="updateXspCalculator()" value="1000"> <SPAN STYLE="font-weight: Bold; font-size: 18px;"> Smilo </SPAN><br>
                </div>
            </div>

            <table class="table table-striped">
                <tr class="tableRowInfo">
                    <th>MaxSmiloPay<br>(XSP)</th>
                    <th>Recovery speed<br>(XSP/Block)</th>
                    <th>Recovery time<br>(Blocks)</th>
                </tr>

                <tr class="tableRowInfo">
                    <td id="calcMaxSmiloPay"></td>
                    <td id="calcSpeed"></td>
                    <td id="calcBlocks"></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="containerSmiloCalculator">
    <div class="container">
    <div class="row">
        <div class="col-md-12 smiloCalculator">
            <div class="center">
                <h1>Transaction Calculator</h1>
                <div class="form-group">
                    <p>How many Smilo do you have?</p>
                    <input type="number" class="form-control smiloCalcInput" id="amountTx" name="amountTx" oninput="updateTxCalculator()" value="1000"> <SPAN STYLE="font-weight: Bold; font-size: 18px;"> Smilo </SPAN>
                    <br>
                    <p>How many Gas (Calculations) does your transaction cost? (default: 21000)</p>
                    <input type="number" class="form-control smiloCalcInput" id="amountGas" name="amountGas" oninput="updateGasUsedCalculator()" value="21000"> <SPAN STYLE="font-weight: Bold; font-size: 18px;"> Gas </SPAN>
                    <br>
                </div>
            </div>

            <table class="table table-striped">
                <tr class="tableRowInfo">
                    <th>Gas price</th>
                    <th>Transaction price</th>
                    <th>Max Tx/Block</th>
                    <th>Tx each Block</th>
                </tr>
                <tr class="tableRowInfo">
                    <td>1 GWEI</td>
                    <td id="calcTxPrice1">XSP</td>
                    <td id="calcMaxTx1"></td>
                    <td id="calcTx1Block"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>5 GWEI</td>
                    <td id="calcTxPrice5">XSP</td>
                    <td id="calcMaxTx5"></td>
                    <td id="calcTx5Block"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>10 GWEI</td>
                    <td id="calcTxPrice10">XSP</td>
                    <td id="calcMaxTx10"></td>
                    <td id="calcTx10Block"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>15 GWEI</td>
                    <td id="calcTxPrice15">XSP</td>
                    <td id="calcMaxTx15"></td>
                    <td id="calcTx15Block"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>20 GWEI</td>
                    <td id="calcTxPrice20">XSP</td>
                    <td id="calcMaxTx20"></td>
                    <td id="calcTx20Block"></td>
                </tr>
            </table>
        </div>
    </div>
    </div>
</div>

</body>
<?php include('includes/footer.php'); ?>
<script>
    // Load first time
    updateResults();

    function updateResults(){
        var amountSmilo = document.getElementById("amountSmilo").value;
        var amountGas = document.getElementById("amountGas").value;

        /* SmiloPayCalculator */
        var maxSmiloPay;
        var recoverySpeed;
        var recoveryBlocks;

        /* TxCalc */
        var gasUsed = amountGas;
        var maxGas;
        var Gwei1=0.000000001;
        var txPrice = gasUsed * Gwei1;

        if(amountSmilo === 0){
            maxSmiloPay = 0;
            recoverySpeed = 0;
            recoveryBlocks = '\u221e';
            maxGas = 0;
        } else {
            amountSmilo = amountSmilo * 1000;
            // maxSmiloPay := (0.001 + (f / 50000)) * 5000 * 1000000000000000
            console.log("===== MaxSmiloPay " + amountSmilo / 1000 + " =======");
            console.log("Sqrt: " + Math.sqrt(amountSmilo));
            console.log("fDiv: " + Math.sqrt(amountSmilo) / 50000 );
            console.log("fAdd: " + ((Math.sqrt(amountSmilo) / 50000 ) + 0.001));
            console.log("maxSmiloPay: " + ((Math.sqrt(amountSmilo) / 50000 ) + 0.001) * 5);
            maxSmiloPay = ((0.001 + (Math.sqrt(amountSmilo) / 50000)) * 5);

            // smiloSpeed := (0.000001 + (sqrt / 750000)) * 8000 * 100000000000000

            console.log("===== recoverySpeed " + amountSmilo / 1000 + "=======");
            console.log("Sqrt: " + Math.sqrt(amountSmilo));
            console.log("fDiv: " + Math.sqrt(amountSmilo) / 750000 );
            console.log("fAdd: " + ((Math.sqrt(amountSmilo) / 750000 ) + 0.000001));
            console.log("recoverySpeed: " + ((Math.sqrt(amountSmilo) / 750000 ) + 0.001) * 8);
            recoverySpeed =  ((0.000001 + (Math.sqrt(amountSmilo) / 750000)) * 8);
            recoveryBlocks = toFixed((maxSmiloPay/recoverySpeed));
            maxGas = (maxSmiloPay/Gwei1);
        }

        /* XSP Calc */
        document.getElementById("calcMaxSmiloPay").innerHTML = toFixed(maxSmiloPay);
        document.getElementById("calcSpeed").innerHTML = toFixed(recoverySpeed);
        document.getElementById("calcBlocks").innerHTML = recoveryBlocks;

        /* Tx Calc */
        document.getElementById("calcTxPrice1").innerHTML = toFixed(txPrice);
        document.getElementById("calcTxPrice5").innerHTML = toFixed(txPrice*5);
        document.getElementById("calcTxPrice10").innerHTML = toFixed(txPrice*10);
        document.getElementById("calcTxPrice15").innerHTML = toFixed(txPrice*15);
        document.getElementById("calcTxPrice20").innerHTML = toFixed(txPrice*20);

        document.getElementById("calcMaxTx1").innerHTML = toFixed(maxGas/gasUsed);
        document.getElementById("calcMaxTx5").innerHTML = toFixed(maxGas/gasUsed/5);
        document.getElementById("calcMaxTx10").innerHTML = toFixed(maxGas/gasUsed/10);
        document.getElementById("calcMaxTx15").innerHTML = toFixed(maxGas/gasUsed/15);
        document.getElementById("calcMaxTx20").innerHTML = toFixed(maxGas/gasUsed/20);

        document.getElementById("calcTx1Block").innerHTML = toFixed(recoverySpeed / (txPrice));
        document.getElementById("calcTx5Block").innerHTML = toFixed(recoverySpeed / (txPrice*5));
        document.getElementById("calcTx10Block").innerHTML = toFixed(recoverySpeed / (txPrice*10));
        document.getElementById("calcTx15Block").innerHTML = toFixed(recoverySpeed / (txPrice*15));
        document.getElementById("calcTx20Block").innerHTML = toFixed(recoverySpeed / (txPrice*20));
    }

    function updateXspCalculator(){
        var amountSmilo = document.getElementById("amountSmilo").value;
        if(amountSmilo >= 350000000){
            amountSmilo = 350000000;
            document.getElementById("amountSmilo").value = amountSmilo;
        }

        document.getElementById("amountTx").value = amountSmilo;
        document.getElementById("amountSmilo").value = amountSmilo;
        updateResults();
    }

    function updateTxCalculator(){
        var amountTx = document.getElementById("amountTx").value;
        if(amountTx >= 350000000){
            amountTx = 350000000;
            document.getElementById("amountSmilo").value = amountTx;
        }

        document.getElementById("amountTx").value = amountTx;
        document.getElementById("amountSmilo").value = amountTx;
        updateResults();
    }

    function updateGasUsedCalculator(){
        updateResults();
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