var connection = null;

$(function () {
    "use strict";
    // for better performance - to avoid searching in DOM
    var websocketServerLocation = 'ws://' + window.location.hostname + ':1337';
    var content = $('#content');
    var blockDisplay = $('#blockDisplay');
    var transactionDisplay = $('#transactionDisplay');
    var transactionDisplayBlock = $('#transactionDisplayBlock');

    var input = $('#input');
    var status = $('#status');

    // if user is running mozilla then use it's built-in WebSocket
    window.WebSocket = window.WebSocket || window.MozWebSocket;

    function start(){

        connection = new WebSocket(websocketServerLocation);
        connection.onopen = function(){
            // open connection
            console.log('connected!');
            status.text('Status: connected');
        };
        connection.onmessage = function(e){
            console.log(e.data);
        };
        connection.onclose = function(){
            console.log('closed!');
            status.text('Status: disconnected');
            //reconnect now
            check();

        };
        connection.onerror = function (error) {
            content.html($('<p>', {text: 'Sorry, there\'s a problem with your connection or the server is down.'}));
        };
    }

    function check(){
        if(!connection || connection.readyState === 3) start();
    }

    function updateClock(){
        document.getElementsByName('timestamp').forEach(function(element) {
            element.innerHTML = timeDifference(element.id);
        });
        console.log("Update time");
    }

    start();

    setInterval(check, 5000);
    setInterval(updateClock, 1000);

    connection.onclose = function(){
        // Try to reconnect in 5 seconds
        setTimeout(function(){connection = new WebSocket(websocketServerLocation)}, 5000);
    };

    // incoming messages
    connection.onmessage = function (message) {
        // try to parse JSON message.
        try {
            var json = JSON.parse(message.data);
            console.log(json);
        } catch (e) {
            console.log('Invalid JSON: ', message.data);
            return;
        }
        if (json.type === 'blockHistory') { // Block history array
            // insert every block
            for (var i=0; i < json.data.length; i++) {
                showBlock(json.data[i]);
            }
        } else if (json.type === 'msgBlock') { // Block
            showBlock(json.data);
        } else if (json.type === 'txHistory'){ // Transaction history array
            // insert every single tx to the chat window
            for (var i=0; i < json.data.length; i++) {
                showTx(json.data[i], false);
            }
        } else if (json.type === 'msgTx') { // transaction
            showTx(json.data, false);
        } else {
            console.log('Unknown JSON format:', json);
        }
    };

    function showBlock(msgBlock) {
        // Show specific block or blockList
        var urlParams = new URLSearchParams(window.location.search);
        var blockNum = urlParams.get('blockNum');

        msgBlock.transactions.forEach(function(transaction){
            confirmTx(transaction.hash);
        });

        if(blockNum === msgBlock.blocknum){
            showBlockDetails(msgBlock);
        }
        else
        {
            // Display max 25 blocks
            var blocks = document.getElementsByClassName("block");
            while(blocks.length >= 25){
                blocks[blocks.length-1].remove();
            }

            var totalFee = 0;
            for(var i = 0; i < msgBlock.transactions.length; i++){
                totalFee += parseFloat(msgBlock.transactions[i].txFee);
            }

            blockDisplay.prepend('<tr class="block"><th><a href="/pages/block.php?&blockNum='+msgBlock.blocknum+'">'+ msgBlock.blocknum +'</a></th><td><a href="/pages/block.php?&blockNum='+msgBlock.blocknum+'">' + msgBlock.blockHash +'</a></td><td name="timestamp" id="' + msgBlock.timestamp + '">' + timeDifference(msgBlock.timestamp) + '</td><td>' + msgBlock.transactions.length + '</td><td>' + totalFee + '</td></tr>');
        }
    }

    function showBlockDetails(msgBlock){

        // Update HTML content
        document.getElementById("blockTitle").innerHTML = "Block " + msgBlock.blocknum;
        document.getElementById("blockBlockHeight").innerHTML = msgBlock.blocknum;
        document.getElementById("blockTransactionsCount").innerHTML = msgBlock.transactions.length;
        document.getElementById("blockTime").innerHTML = new Date(parseInt(msgBlock.timestamp));
        document.getElementById("blockHash").innerHTML = msgBlock.blockHash;
        document.getElementById("blockPreviousHash").innerHTML = msgBlock.prevBlockHash;

        // Get transactions details
        var totalXsmSend = 0;
        var totalFee = 0;
        for(var i = 0; i < msgBlock.transactions.length; i++){
            totalXsmSend += parseFloat(msgBlock.transactions[i].inputAmount);
            totalFee += parseFloat(msgBlock.transactions[i].txFee);
        }
        document.getElementById("blockTotalXsmSend").innerHTML = totalXsmSend + " XSM";
        document.getElementById("blockTotalFee").innerHTML = totalFee + " XSP";

        // Show all transactions
        for(var i = 0; i < msgBlock.transactions.length; i++){
            showTx(msgBlock.transactions[i], true);
        }
    }

    function showTx(msgTx, block){
        // Display max 25 transactions
        var transactions = document.getElementsByClassName("transaction");
        while(transactions.length >= 25){
            transactions[transactions.length-1].remove();
        }

        // Select pending or confirm button
        var statusBtnClass = "btn btn-warning";
        if(msgTx.txStatus === "confirmed"){
            statusBtnClass = "btn btn-success";
        }

        // Generate HTML transaction string
        var outputAddresHtmlString = "";
        var outputAmountHtmlString = "";
        for (var i = 0; i < msgTx.txArray.length; i++) {
            outputAddresHtmlString += msgTx.txArray[i][0]+"<br/>";
            outputAmountHtmlString += msgTx.txArray[i][1]+"<br/>";
        }

        var htmlSingleTx = '<table class="table transaction"><thead><tr class="btn-primary"><th class="hash">'+ msgTx.hash +'</th><th></th><th></th><th name="timestamp" id="' + msgTx.timestamp + '">' + timeDifference(msgTx.timestamp) +'</th></tr><tr><td class="txInputAddress" scope="col">'+ msgTx.inputAddress +'</td><td class="td-border" scope="col">&#8594;</td><td class="td-border" scope="col">'+ outputAddresHtmlString +'</td><td class="txInputAmount td-border" scope="col">'+ outputAmountHtmlString +'</td> </tr> <tr> <td><button class="'+ statusBtnClass +'" id="'+ msgTx.hash + '">'+ msgTx.txStatus +'</button></td> <td></td> <td></td> <td><button class="btn btn-primary">'+ msgTx.inputAmount +' XSM</button></td> </tr> </thead> </table>';
        if(block){
            if(msgTx.hash !== undefined){
                transactionDisplayBlock.prepend(htmlSingleTx);
            }else{
                transactionDisplayBlock.prepend("<p>This block doesn't contain transactions </p>");
            }
        }else{
            transactionDisplay.prepend(htmlSingleTx);
        }
    }

    // Update HTML pending to confirmed transaction
    function confirmTx(hash){
        var statusBtn = document.getElementById(hash);
        if (statusBtn !== null) {
            statusBtn.innerHTML = "Confirmed";
            statusBtn.classList.add("btn-success");
            statusBtn.classList.remove("btn-warning");
        }
    }
});
