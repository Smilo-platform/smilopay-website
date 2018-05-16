<?php include('includes/header.php'); ?>

<div class="container">
    <div class="row smilopayCalculator">
        <div class="center">
            <h1>SmiloPay generation calculator</h1>
            <div class="form-group">
                Amount Smilo: <input type="number" class="form-control smiloPayCalcInput" id="amountSmilo" name="amountSmilo" oninput="updateXspCalculator()" value="1000"><br>
            </div>
        </div>

        <table>
            <tr>
                <th>Time</th>
                <th>SmiloPay</th>
            </tr>
            <tr>
                <td>Day</td>
                <td id="calcDay"></td>
            </tr>
            <tr>
                <td>Week</td>
                <td id="calcWeek"></td>
            </tr>
            <tr>
                <td>Month</td>
                <td id="calcMonth"></td>
            </tr>
            <tr>
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
                
        var calcDay = 0.00054;

        var calcHour = calcDay/24;
        var calcMin = calcHour/60;
        var calcSec = calcMin/60;

        var calcWeek = calcDay*7;
        var calcMonth = calcWeek*4.3;
        var calcYear = calcMonth*12;
        document.getElementById("calcDay").innerHTML = (amountSmilo*calcDay).toFixed(4);
        document.getElementById("calcWeek").innerHTML = (amountSmilo*calcWeek).toFixed(4);
        document.getElementById("calcMonth").innerHTML = (amountSmilo*calcDay*365/12).toFixed(4);
        document.getElementById("calcYear").innerHTML = (amountSmilo*calcDay*365).toFixed(4);
    }
</script>