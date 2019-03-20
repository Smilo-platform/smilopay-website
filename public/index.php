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
<div class="containerSmiloCalculator">
    <div class="container">
    <div class="row">
        <div class="col-md-12 smiloCalculator">
            <div class="center">
                <h1>Peak Number of Transactions Calculator</h1>
                <div class="form-group">
                    <p>How many transactions per second between peaks of transactions? (default: 0)</p>
                    <input type="number" class="form-control smiloCalcInput" id="amountTxCoolDown" name="amountTxCoolDown" oninput="updateTxPeakCalculator()" value="0"> <SPAN STYLE="font-weight: Bold; font-size: 18px;"> transactions/second </SPAN>
                    <br>
                    <p>What is the average price of gas in GWEI? (default: 5)</p>
                    <input type="number" class="form-control smiloCalcInput" id="averageGasPrice" name="averageGasPrice" oninput="updatePeakGasPriceCalculator()" value="5"> <SPAN STYLE="font-weight: Bold; font-size: 18px;"> GWEI/gas unit </SPAN>
                    <br>
                </div>
            </div>
			<table class="table table-striped">
				<tr class="tableRowInfo">
					<td>Cool Down Number of Blocks</td>
					<td id="calcCoolDownPeriod"></td>
				</tr>
			</table>
            <table class="table table-striped">
                <tr class="tableRowInfo">
                    <th>Peak Duration</th>
                    <th>Total Peak Transactions</th>
                    <th>Average Tx/Block</th>
                    <th onclick="showSinusoidal()">Sinusoidal Max Tx/Block</th>
                    <th onclick="showTriangular()">Triangular Max Tx/Block</th>
                </tr>
                <tr class="tableRowInfo">
                    <td>10s</td>
                    <td id="calcTotalMaxTx10"></td>
                    <td id="calcPeakTxBlock10"></td>
                    <td id="calcPeakMaxSin10"></td>
                    <td id="calcPeakMaxTri10"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>30s</td>
                    <td id="calcTotalMaxTx30"></td>
                    <td id="calcPeakTxBlock30"></td>
                    <td id="calcPeakMaxSin30"></td>
                    <td id="calcPeakMaxTri30"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>60s</td>
                    <td id="calcTotalMaxTx60"></td>
                    <td id="calcPeakTxBlock60"></td>
                    <td id="calcPeakMaxSin60"></td>
                    <td id="calcPeakMaxTri60"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>5min</td>
                    <td id="calcTotalMaxTx5min"></td>
                    <td id="calcPeakTxBlock5min"></td>
                    <td id="calcPeakMaxSin5min"></td>
                    <td id="calcPeakMaxTri5min"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>15min</td>
                    <td id="calcTotalMaxTx15min"></td>
                    <td id="calcPeakTxBlock15min"></td>
                    <td id="calcPeakMaxSin15min"></td>
                    <td id="calcPeakMaxTri15min"></td>
                </tr>
                <tr class="tableRowInfo">
                    <td>1h</td>
                    <td id="calcTotalMaxTx1h"></td>
                    <td id="calcPeakTxBlock1h"></td>
                    <td id="calcPeakMaxSin1h"></td>
                    <td id="calcPeakMaxTri1h"></td>
                </tr>
            </table>
        </div>
    </div>
    </div>
</div>
<div id="sinusoidal" class="modal" onclick="hideAll();"><img src="images/Sinusoidal.png"></div>
<div id="triangular" class="modal" onclick="hideAll();"><img src="images/Triangular.png"></div>
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
            // maxSmiloPay := (0.001 + (f / 50000)) * 5000 * 1000000000000000
            // console.log("===== MaxSmiloPay " + amountSmilo + " =======");
            // console.log("Sqrt: " + Math.sqrt(amountSmilo));
            // console.log("fDiv: " + Math.sqrt(amountSmilo) / 50000 );
            // console.log("fAdd: " + ((Math.sqrt(amountSmilo) / 50000 ) + 0.001));
            // console.log("maxSmiloPay: " + ((Math.sqrt(amountSmilo) / 50000 ) + 0.001) * 5);
            maxSmiloPay = ((0.001 + (Math.sqrt(amountSmilo) / 50000)) * 5);

            // smiloSpeed := (0.000001 + (sqrt / 750000)) * 8000 * 100000000000000

            // console.log("===== recoverySpeed " + amountSmilo + "=======");
            // console.log("Sqrt: " + Math.sqrt(amountSmilo));
            // console.log("fDiv: " + Math.sqrt(amountSmilo) / 750000 );
            // console.log("fAdd: " + ((Math.sqrt(amountSmilo) / 750000 ) + 0.000001));
            // console.log("recoverySpeed: " + ((Math.sqrt(amountSmilo) / 750000 ) + 0.000001) * 0.5);
            recoverySpeed =  ((0.000001 + (Math.sqrt(amountSmilo) / 750000)) * 0.5);
            recoveryBlocks = toFixed((maxSmiloPay/recoverySpeed));
            // maxGas = (maxSmiloPay/Gwei1);
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

        document.getElementById("calcMaxTx1").innerHTML = toFixed(maxSmiloPay/txPrice);
        document.getElementById("calcMaxTx5").innerHTML = toFixed(maxSmiloPay/txPrice/5);
        document.getElementById("calcMaxTx10").innerHTML = toFixed(maxSmiloPay/txPrice/10);
        document.getElementById("calcMaxTx15").innerHTML = toFixed(maxSmiloPay/txPrice/15);
        document.getElementById("calcMaxTx20").innerHTML = toFixed(maxSmiloPay/txPrice/20);

        document.getElementById("calcTx1Block").innerHTML = toFixed(recoverySpeed / (txPrice));
        document.getElementById("calcTx5Block").innerHTML = toFixed(recoverySpeed / (txPrice*5));
        document.getElementById("calcTx10Block").innerHTML = toFixed(recoverySpeed / (txPrice*10));
        document.getElementById("calcTx15Block").innerHTML = toFixed(recoverySpeed / (txPrice*15));
        document.getElementById("calcTx20Block").innerHTML = toFixed(recoverySpeed / (txPrice*20));
        
        updatePeakGasPriceCalculator()
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

    function showSinusoidal() {
    	document.getElementById('sinusoidal').style.display = "block";
    }
    function showTriangular() {
    	document.getElementById('triangular').style.display = "block";
    }
    function hideAll() {
    	document.getElementById('sinusoidal').style.display = "none";
    	document.getElementById('triangular').style.display = "none";
    }

    function updatePeakGasPriceCalculator(){
		var gasPrice = document.getElementById("averageGasPrice").value;

        if (gasPrice < 1) {
			gasPrice = 1;
			document.getElementById("averageGasPrice").value = gasPrice;			
        }		
        updateTxPeakCalculator();
    }
    
    function updateTxPeakCalculator() {
		var gasPrice = document.getElementById("averageGasPrice").value;
		var coolDownTx = document.getElementById("amountTxCoolDown").value;
        var amountSmilo = document.getElementById("amountSmilo").value;
        var amountGas = document.getElementById("amountGas").value;
		
        var maxSmiloPay = ((0.001 + (Math.sqrt(amountSmilo) / 50000)) * 5);
        var recoverySpeed =  ((0.000001 + (Math.sqrt(amountSmilo) / 750000)) * 0.5);
        
        var maxTXRecovery = recoverySpeed / (gasPrice * 0.000000001 * amountGas);
        var maxTXTotal = maxSmiloPay / (gasPrice * 0.000000001 * amountGas);

        if (coolDownTx < 0) {
			coolDownTx = 0;
			document.getElementById("amountTxCoolDown").value = coolDownTx;			
        }		

        if (coolDownTx > maxTXRecovery) {
			coolDownTx = Math.floor(maxTXRecovery);
			document.getElementById("amountTxCoolDown").value = coolDownTx;			
        }		
    	
        document.getElementById("calcCoolDownPeriod").innerHTML	= Math.ceil(maxTXTotal/(maxTXRecovery-coolDownTx));
            	
        document.getElementById("calcTotalMaxTx10").innerHTML = Math.floor(maxTXTotal + (maxTXRecovery * 10));
        document.getElementById("calcTotalMaxTx30").innerHTML = Math.floor(maxTXTotal + (maxTXRecovery * 30));
        document.getElementById("calcTotalMaxTx60").innerHTML = Math.floor(maxTXTotal + (maxTXRecovery * 60));
        document.getElementById("calcTotalMaxTx5min").innerHTML = Math.floor(maxTXTotal + (maxTXRecovery * 300));
        document.getElementById("calcTotalMaxTx15min").innerHTML = Math.floor(maxTXTotal + (maxTXRecovery * 900));
        document.getElementById("calcTotalMaxTx1h").innerHTML = Math.floor(maxTXTotal + (maxTXRecovery * 3600));

        document.getElementById("calcPeakTxBlock10").innerHTML = toFixed(Math.floor(maxTXTotal + (maxTXRecovery * 10))/10.0);
        document.getElementById("calcPeakTxBlock30").innerHTML = toFixed(Math.floor(maxTXTotal + (maxTXRecovery * 30))/30.0);
        document.getElementById("calcPeakTxBlock60").innerHTML = toFixed(Math.floor(maxTXTotal + (maxTXRecovery * 60))/60.0);
        document.getElementById("calcPeakTxBlock5min").innerHTML = toFixed(Math.floor(maxTXTotal + (maxTXRecovery * 300))/300.0);
        document.getElementById("calcPeakTxBlock15min").innerHTML = toFixed(Math.floor(maxTXTotal + (maxTXRecovery * 900))/900.0);
        document.getElementById("calcPeakTxBlock1h").innerHTML = toFixed(Math.floor(maxTXTotal + (maxTXRecovery * 3600))/3600.0);
        
        document.getElementById("calcPeakMaxSin10").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 10))*Math.PI/(2 * 10.0)));
        document.getElementById("calcPeakMaxSin30").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 30))*Math.PI/(2 * 30.0)));
        document.getElementById("calcPeakMaxSin60").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 60))*Math.PI/(2 * 60.0)));
        document.getElementById("calcPeakMaxSin5min").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 300))*Math.PI/(2 * 300.0)));
        document.getElementById("calcPeakMaxSin15min").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 900))*Math.PI/(2 * 900.0)));
        document.getElementById("calcPeakMaxSin1h").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 3600))*Math.PI/(2 * 3600.0)));

        document.getElementById("calcPeakMaxTri10").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 10)) * 2 / 10.0));
        document.getElementById("calcPeakMaxTri30").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 30)) * 2 / 30.0));
        document.getElementById("calcPeakMaxTri60").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 60)) * 2 / 60.0));
        document.getElementById("calcPeakMaxTri5min").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 300)) * 2 / 300.0));
        document.getElementById("calcPeakMaxTri15min").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 900)) * 2 / 900.0));
        document.getElementById("calcPeakMaxTri1h").innerHTML = numberOrDash(Math.floor(Math.floor(maxTXTotal + (maxTXRecovery * 3600)) * 2 / 3600.0));

    }
    
    function numberOrDash(value) {
    	if (value > 1) return value;
    	return "-";
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