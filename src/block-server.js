"use strict";
process.title = 'node-explorer';
var webSocketsServerPort = 1337;
// websocket and http servers
var webSocketServer = require('websocket').server;
var http = require('http');
var connection = null;

// list of currently connected clients (users)
var clients = [ ];

// latest blocks/transactions
var blockHistory = [ ];
var txHistory = [ ];

// server
var server = http.createServer(function(request, response) {
    // We use writing WebSocket server
});

server.listen(webSocketsServerPort, function() {
    // Todo remove 1 method
    startTransactions();
    console.log((new Date()) + " Server is listening on port "
        + webSocketsServerPort);
});

//WebSocket server
var wsServer = new webSocketServer({
    httpServer: server
});
// This callback function is called every time someone
// tries to connect to the WebSocket server
wsServer.on('request', function(request) {
    // accept connection
    // check 'request.origin' to make sure that client is connecting from your website
    connection = request.accept(null, request.origin);

    var client = connection.remoteAddress;

    // safe client index to remove them on 'close' event
    var index = clients.push(connection) - 1;
    console.log((new Date()) + ' Connection accepted from ' + client +'.');

    // send back blockHistory
    if (blockHistory.length > 0) {
        connection.sendUTF(
            JSON.stringify({ type: 'blockHistory', data: blockHistory} ));
    }

    // send back txHistory
    if (txHistory.length > 0) {
        connection.sendUTF(
            JSON.stringify({ type: 'txHistory', data: txHistory} ));
    }

    // user sent some message
    connection.on('message', function(message) {
        processMessage(message);
    });

    connection.on('error', function() {
        // Do nothing.
    });

    connection.on('close', function() {
        console.log((new Date()) + ' Connection closed from ' + client + '.');
    });

});

function safeTxAsObject(splitRow, block){
    //console.log(splitRow);
    var arrayLength = splitRow.length;

    // Add transaction send to address and values
    var txArray = [];
    for (var i = 0; i < arrayLength; i++) {
        // arrayLengt - 4 since there are 4 buckets after the transactions and === since 5 is not an even number
        if(i > 3 && i < arrayLength-5 && i % 2 === 0){
            txArray.push([splitRow[i], splitRow[i+1]]);
        }
    }

    // Check if transaction is confirmed
    var txStatus;
    if(block){
        txStatus = "confirmed";
    }else{
        txStatus = "pending";
        for(i = 0; i < blockHistory.length; i++){
            for(var b = 0; b < blockHistory[i].transactions.length; b++){
                var blockTxHash = blockHistory[i].transactions[b].hash;
                // Check if transaction hash and block hash matches
                if(blockTxHash === splitRow[arrayLength-3]){
                    // Confirm transaction
                    txStatus = "confirmed";
                }
            }
        }
    }

    // Safe transaction as an object
    var txObj = {
        timestamp: splitRow[0],
        assetID: splitRow[1],
        inputAddress: splitRow[2],
        inputAmount: splitRow[3],
        txArray: txArray,
        txFee: splitRow[arrayLength-4],
        hash: splitRow[arrayLength-3],
        signatureData: splitRow[arrayLength-2],
        signatureIndex: splitRow[arrayLength-1],
        txStatus: txStatus
    };

    return txObj;
}

function processMessage(message){
    console.log(message);
    if (message.type === 'utf8') { // accept only text
        console.log((new Date()) + ' Received Message: ' + message.utf8Data);

        // Keep blockHistory
        var splitType = message.utf8Data.replace(/{|}/g,"");

        splitType = splitType.split(" ");
        if(splitType[0].toLowerCase() === "block"){
            var splitRow = splitType[1].split(",");

            var blockInfoRow = splitRow[0].split(":");

            // Split block to array of transaction strings
            var blockTxs = splitRow[2].split("*");
            // Split block to array of transaction items
            var blockTx = new Array();
            // Check if block confirms transactions and safe in array
            var confirmTxList = [];
            var allTxInBlock = [];
            for(i = 0; i < blockTxs.length; i++){
                blockTx.push(blockTxs[i].split(";"));
                var blockTxHash = blockTx[i][blockTx[i].length-3];
                for(var b = 0; b < txHistory.length; b++){
                    // Check if hashes match
                    if(txHistory[b].hash === blockTxHash){
                        txHistory[b].txStatus = "confirmed";
                        confirmTxList.push(blockTxHash);
                    }
                }
            }

            for(i = 0; i < blockTx.length; i++){
                allTxInBlock.push(safeTxAsObject(blockTx[i], true));
            }

            // Safe block as an object
            var obj = {
                timestamp: blockInfoRow[0],
                blocknum: blockInfoRow[1],
                prevBlockHash: blockInfoRow[2],
                transactions: allTxInBlock,
                blockHash: splitRow[3]
            };

            // Validate if previous hash is correct
            if(blockHistory.length == 0 || blockHistory[blockHistory.length-1].blockHash == obj.prevBlockHash){

                blockHistory.push(obj);
                blockHistory = blockHistory.slice(-10);
                // broadcast message to all connected clients
                var json = JSON.stringify({ type:'msgBlock', data: obj });
                for (var i=0; i < clients.length; i++) {
                    clients[i].sendUTF(json);
                }
            }else{
                console.log("Hash doesn't match");
            }
        }else if(splitType[0].toLowerCase() === "transaction"){
            // Safe tx value in object
            var splitRow = splitType[1].split(";");
            var obj = safeTxAsObject(splitRow, false);

            // broadcast message to all connected clients
            var json = JSON.stringify({ type:'msgTx', data: obj });

            // Update history
            txHistory.push(obj);
            // Only display latest X amount of transactions
            txHistory = txHistory.slice(-25);

            for (var i = 0; i < clients.length; i++) {
                clients[i].sendUTF(json);
            }
        }else{
            console.log("Message format is unknown");
        }
    } else {
        console.log("Wrong message type");
    }
}

// Garbage
function startTransactions() {
    setInterval(function(){
        console.log((new Date()) + ' new transaction.');
        sendTx();
    }, 4000);

    setInterval(function(){
        console.log((new Date()) + 'new block.');
        sendBlock();
    }, 10000);
}

function generateRandomString(length) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function generateRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}

function addTxOutputData(inputAmount){
    var amountOfTransactions = generateRandomInt(1, 6);
    var totalValue = inputAmount;

    var txOutputString = "";

    while(amountOfTransactions > 0){
        amountOfTransactions --;
        var newOutputAddress = "XSM" + generateRandomString(25);
        var newOutputAmount = generateRandomInt(1, inputAmount);
        if(newOutputAmount >= totalValue){
            newOutputAmount = totalValue/1.5;
        }
        totalValue -= newOutputAmount;

        // Spend everything on last tx
        if(amountOfTransactions === 0){
            newOutputAmount += totalValue;
        }

        txOutputString += newOutputAddress + ";" + newOutputAmount + ";";

    }

    return txOutputString;
}


var txPool = [];

function sendTx() {
    if (connection !== null) {

        // Todo - Pool aanmaken (array met pending transacties) deze in 1x een nieuwe block in pushen.
        // X aantal blokken laten weergeven en X aantal transacties laten weergeven (waardes van die variablen aanpassen)

        var transaction = {
            timestamp: Date.now(),
            inputAddress: "XSM" + generateRandomString(25),
            inputAmount:generateRandomInt(10,5000),
            txOutputString: null,
            hash: generateRandomString(32),
            totalMsg: ""
        };

        transaction.txOutputString = addTxOutputData(transaction.inputAmount);


        transaction.totalMsg = "TRANSACTION "+transaction.timestamp+";1;"+transaction.inputAddress+";"+transaction.inputAmount+";"+transaction.txOutputString+";0.001;"+transaction.hash+";SignatureData;SignatureIndex";

        var message = {
            type: 'utf8',
            utf8Data: transaction.totalMsg
        };
        processMessage(message);
        txPool.push(transaction);
        console.log((new Date()) + ' new transaction send.');

    }
}

function sendBlock(){
    if(connection !== null){
        console.log("_");
        console.log(blockHistory);
        console.log("_");
        if(blockHistory.length !== 0){
            var block = {
                timestamp: Date.now(),
                height:  parseInt(blockHistory[blockHistory.length-1].blocknum)+1,
                prevBlockHash: blockHistory[blockHistory.length-1].blockHash,
                blockHash: generateRandomString(50),
                txString: generateTxString()
            };

            if(block.prevBlockHash === undefined){
                block.prevBlockHash = "0000000000000000000000000000000000000000000000000000000000000000";
                block.height = 1;
            }

            var blockString = "BLOCK {"+block.timestamp+":"+block.height+":"+block.prevBlockHash+":S1RQ3ZVRQ2K42FTXDONQVFVX73Q37JHIDCSFAR},{0000000000000000000000000000000000000000000000000000000000000000},{"+block.txString+"},{"+block.blockHash+"},{FSPWKgck9EV1dD5E:Iz9Z5uHsmskJNWltZUL5::tNoUj1c71InYEiUSq6fA:PC2N5g8JjMD6jQm0::hZ4M80pCCLDD9FQzu9QC:k4wpnxBMDdqxajMg::BegLxeeA1u2tbs69CcFO:lI1x4oBdQyyfBUX6::IQMUzKYuGM12gEhi:pqUZisIcN1v9nVS84p2L::tSaOV0MICMVgUJCtfm3n:LJFCAmPTdLyDFDDl::lej2ltc5RPnjsHG/:YoV6TD0dUWj5Si97faXg::uPSTFhVXjGkjiWPpcZJw:cpN1nsuYk5oktzsK::NODDGuXpr9hemj82:wfjd7cAN2DbXDQLYUpdM::tFY73u34OBsiG7CVsz0X:+otqEXbE3eCTnl3+::SpfhRTTIQtlaFzzg:dLpjhNGMydiWZVnRoJCU::Gp6RsMohYwUZHcf3:yeFXiycvofDePTnDZfyZ::vm2udD8pvXrDKUtnNNZi:7whjw4VOXi4KPiUi::v08mSbkttDz2SrkB09Pu:X3xEr+m/XzihH3iH::jxkomBHSG9pEbmDZgDaZ:iWyU77iLtyOp9ZSi::bpuvlhFkycdqtyeXqJV7:3gabT+r6EVvW+Xin::y6K73JsoaCHe3V1KWutw:VEeB3MFHycmjWige::mSZDyPKV6P1V1509:tWxV0VcJDrRAqdygOxQ1::OchhrizUJfLGtVS5:BDVfbal5izQOmo77Zvmf::6QDcCefar83PlXxabHEm:RcN++/aM9jTVNN+N::TBICJ/+eb8i98IEQ:REFW5TVWt0nmjeGf5trW::AtZwqcZ42GGNiltb:klGp6UE7svnhmLlF908v::lLTKLPSBdhBvg2u6WxYB:vEjrRg5Qbc913ins::YtRTZjFqvagOPAawDZwH:5zaMgR4a5bBxK5dR::cDetRAzMqtsS7fxf3JXt:+NnWnJhU/xZ9Axgr::U4nKwBEq3or5auApUvZZ:hYJFZCo0TBfIy94D::GZqoBqKe7BqR6VBC:Dj9QK62l9R9NtAyTcvSD::CnCxgM3iZPGuYThOf2hW:VEeqpH3CDr9qQnT1::JjCumaGBHfCAFo15:H7Ynyvtx8s2xKocpFwsS::I3Gx0MBMzKJqrCbo:zXgsxDWXFUxKLRkMji8u::Kwro7OM9Y2UQymFA:sEcwQTs5WCWYqR5eKrnd::RzT6EcgzPDW40Cm7R1mH:7bA6EN+gVsSVB+yi::b9Dv3WWkMNNETyzid0DQ:yz4MBYf5ad+ot7gJ::Z71llw1h8PLFXBZ/:z8EoNasXFRn1v4PHbMJm::GjCUt2LsLUPP6BLPUbBa:uiUE7TOTBgAkx4GQ::D4F1s5tQjG//cBkO:FVymWndbehWjVnakGuqc::yEfSYIfFVtnIh2OwlUSo:UbOArjIN9DjD7+4O::CGUtB09BqOnYRCn/:O3g5CAmGq4iDqpAWcQe6::q6HRVC3uxnl/Lc9d:Y8EJ8wJiAzLTkogPY5zs::KfzHVyw1sHTQ0wQ8peIj:SO9kc6HJTYEwdNIH::ss/itWKngkfauy1R:vX8Vw33ZmJ2Js0lIhLOh::aX0IulRwxDdiR91e:Fv9znAJIFnbx0twfzfdK::XBKEydFgjrwmEF1oe4TE:57QKw+BnadWYMv44::93uZttu9CaD0INalWQC5:/Dim3ZsJ9Q55PpsM::BRpOyPXeURei4Oavu2FS:bZR0oHPtu9Bo9RwU::sUBH5VmMcT3N5o8Hp3Lb:D3o+T7eJ36Kds6tG::49kB7DPKmLOdO4j1BJ3a:NOCYm3fnp85pXAEB::tHKA7zszoEuneyGuTopA:nHSAX9TkDl36lWCK::8RG6xI9z9jNZ7HQMHt75:i+IZVDDaFH4zu4X9::uGAtoQGudgyiLKse:2GNOtSlIHRFLTjTf4Pav::bZznPlo35TgnJsXQY4ns:iY7MnLditIC1RfK5::tAyACD69sMiVFgeEVe16:CGYNuDP8zFSMs2Ga::YZN4Vir+t6qWyBgy:3Sf5BHtXTeJJMfZ3Cgos::e8CJReDGWckroQb9:BuFR4Sn54fvkAYVRYIeQ::o4FMrDvKbjvGhkwm:yNJEaftiAI1v0Mz4gsgi::nFDYhlKtNZEI6CWK:rWmXMfnNQK9YSMY9upwP::WnWrg3el2ojB17Ok:kWKX1btPl9xNejvYe9NX::XCmVssvuTrnrdh4yOGEa:N/T8qwXaRvBn/f+6::4oKHMdkxlleOGzFg:U9gs0fFdlNr8L6nHMOQr::vR08X2zToZPdh9IY:24dhv2DGSDFundESZaya::XPDAVhpo35tjxWiqaozK:pOUi2NfOslwVrdDI::5mOye0FLPg1bMA1a:H6O3wyPKK4p3As8rOlMG::iq9H7Ra3lv5n9MQWQWEe:H8gRfFt+PGo6GYMT::KFLEJLqVJG3sNYHsDUAj:+UXlIhbaXwZXdfMZ::jQXF62UL1xIMu6rF7kO0:RzNxDOHjftxuJV+V::Z1O8T5ebleTvb58i:enbErvrMBAONaUuYXfti::c+XQo/OBhz/JOfg5:H3MxXDZQ4syB8FTibTg8::IFD1JeAxxSLWf9of:tj3NYJFrPvkgw6qHsclc::LfXLxfFeGmnkmPKAymKZ:OiRPppxc4QxADDG4::FqLk92ZIaHOlov2b:Ih9mClbSBIcLYrrDyby7::c6HLR1eK+j+3kRq8:hA3XSWl6BNfkni5rx7hB::9W03JkOlwpbXnh9FjN7F:QnNRU1nn5rqgBa8Q::EiVIiWtmX5uxhgJH:8lrbFJSmCOiWkMPBHOQo::ZT9n2jA6gZaNuMMi:znXoJslaUoqJR2VShNPm::ADLdKaHr6n9ionRnnluX:fLSEQ1IxnAhCk36S::WYP/t37ZfbvZL4/m:Xow4FsuDEyUZdNpuZuPP::k8x6r1NkxcnRu76y:OFdZ6cIQZsCPqHVJhxcD::V3RcxTRBRs5DrwNjvkrZ:moCcoPwkvYqF/JGr::MYxqaqM8WsMH/jUT:k3OfyG85q2ZL7H5SXas0::EDLMcaUiNfyUJLxmDqd1:mTg2Jhma9im0XUI2::9Pr5c6NbqvTD/tzk:LwSbnKZCtEOK4dwgfaZy::wDzBPO6xNXbAUfbk:RAR6MXLwS86A8uRDNBxJ::XLwu4XBQGH0c0vJmhgM0:Ijx5Hnl/zsvgwLS3::V6OaNqn4ZIpj9fhz5npa:iE+L+hTHQIv6Jvbb::RKiypqgJ3zd/ljx/:AYXQdOC3jy8HRxOSkfpF::9JaHkFa+MRV7GrKO:3HOaSlnMBzJe82rQtlah::yozmD4jralE6gAd08uCV:Kb8PNstQ7EweB9jw::m0y3pb+fpMyEK2vq:Rukb5bRy9lmCAuoy72sK::QYO7Ga91hWUTnroIIwp5:B988NixJdgdoQIWU::fvxRkLxpON1HbrHE:UBvjLh6ox2myTeA333W6::AtlVShjpI3W7CQrt:7yzQxgEv4bJbZLx3RyFA::guk4j4rVvnD9+Gan:THxRXOeglDeF2JgzVuZl::GDGNWyDhTabYjVbf:g2cqIIjRwmXBX5vHCrCv::8OPHEA2UmSkxnpGvhxad:Xk4P5+lnjh3FdHwb::CqamwT7vK9zeXRvFNfY4:WirILL2a+GSFBHxl::vZX1k14kRofOvWH3SyqE:ESenetZLX9TCMY3a::BcBov2GLPy5L7v0e:OH5PUUeK64LO8gnDPqqq::pTDmOMaUm3OODYCLHx7R:OfCq2A2gr2aipyHu::iRsupbA9ZkoTeVUUzBuf:EBz1AB01uwkAhX7S::NnhRr2NvRBerFgGYt8KILbmA4w8c9KSfQToCpFoUQYeVBkk1iBhkepC9cN9zr3QIXOl25gJ6Bs46O04lHFPVjg==:Z6I6s75yCsumlpxBhXux,2LJjoi3AHhVmo0bkFrrBQnnVaZgLCd4c3SMVMz9URYg=:aJi33m6RWEyBTlTJ0FX2NdIOv5JyYLvBUBfgLMmagUc=:JSXFxf/y9QA5N2GSMi2VRx6myIYTMVcBFBDWiSWOzdU=:BOzsi7Rf7n42OlfKhdl2hdjikhX/ZnQFeXHum4SvgMQ=:O+/1A+Z0rDGVgrp3ZFux2itCRwMi2FPzcS2v6Znd9X8=:SMxRsen0ZcNOOQOEga9VHWlh/Y5VXPBTgXHMFl6W0Jw=:urrs+BBjLeio8dwYYuRxgC7b6tHpBMnIQmk0t5nD9Nc=:voOyTXZi5zMFRRlijSY7M4atrD1rqXFBy6CgjR5I48c=:BVrK/BStKRpQCUIPBOM5Axw8sqcK/2wYh7/p/YGXZjY=:Y6i3qIxq22qFHFQtF6yPNfnzlRhwUCpyESu+X/v6mZg=:Zx3tQR6D4nW6BbxKXf4P6MGXHqcnzL8qREMFNJT7Cuk=:OXWXgApfUdT1Ve73POpjsjp44R6LNHBiGgSZOj5c+8E=:XezpPlGSH7ph5casnXCJEqmYkUjOPOVAItfqv3NbHAM=},{0}";

            var message = {
                type: 'utf8',
                utf8Data: blockString
            };

            processMessage(message);

        }else{
            var firstBlock = "BLOCK {"+Date.now()+":0:0000000000000000000000000000000000000000000000000000000000000000:S1RQ3ZVRQ2K42FTXDONQVFVX73Q37JHIDCSFAR},{0000000000000000000000000000000000000000000000000000000000000000},{},{9BD5C754B2420681FFFD9F38F87B628A742F8FE3DBC8BB7C2A1F237BCAC63FF0},{FSPWKgck9EV1dD5E:Iz9Z5uHsmskJNWltZUL5::tNoUj1c71InYEiUSq6fA:PC2N5g8JjMD6jQm0::hZ4M80pCCLDD9FQzu9QC:k4wpnxBMDdqxajMg::BegLxeeA1u2tbs69CcFO:lI1x4oBdQyyfBUX6::IQMUzKYuGM12gEhi:pqUZisIcN1v9nVS84p2L::tSaOV0MICMVgUJCtfm3n:LJFCAmPTdLyDFDDl::lej2ltc5RPnjsHG/:YoV6TD0dUWj5Si97faXg::uPSTFhVXjGkjiWPpcZJw:cpN1nsuYk5oktzsK::NODDGuXpr9hemj82:wfjd7cAN2DbXDQLYUpdM::tFY73u34OBsiG7CVsz0X:+otqEXbE3eCTnl3+::SpfhRTTIQtlaFzzg:dLpjhNGMydiWZVnRoJCU::Gp6RsMohYwUZHcf3:yeFXiycvofDePTnDZfyZ::vm2udD8pvXrDKUtnNNZi:7whjw4VOXi4KPiUi::v08mSbkttDz2SrkB09Pu:X3xEr+m/XzihH3iH::jxkomBHSG9pEbmDZgDaZ:iWyU77iLtyOp9ZSi::bpuvlhFkycdqtyeXqJV7:3gabT+r6EVvW+Xin::y6K73JsoaCHe3V1KWutw:VEeB3MFHycmjWige::mSZDyPKV6P1V1509:tWxV0VcJDrRAqdygOxQ1::OchhrizUJfLGtVS5:BDVfbal5izQOmo77Zvmf::6QDcCefar83PlXxabHEm:RcN++/aM9jTVNN+N::TBICJ/+eb8i98IEQ:REFW5TVWt0nmjeGf5trW::AtZwqcZ42GGNiltb:klGp6UE7svnhmLlF908v::lLTKLPSBdhBvg2u6WxYB:vEjrRg5Qbc913ins::YtRTZjFqvagOPAawDZwH:5zaMgR4a5bBxK5dR::cDetRAzMqtsS7fxf3JXt:+NnWnJhU/xZ9Axgr::U4nKwBEq3or5auApUvZZ:hYJFZCo0TBfIy94D::GZqoBqKe7BqR6VBC:Dj9QK62l9R9NtAyTcvSD::CnCxgM3iZPGuYThOf2hW:VEeqpH3CDr9qQnT1::JjCumaGBHfCAFo15:H7Ynyvtx8s2xKocpFwsS::I3Gx0MBMzKJqrCbo:zXgsxDWXFUxKLRkMji8u::Kwro7OM9Y2UQymFA:sEcwQTs5WCWYqR5eKrnd::RzT6EcgzPDW40Cm7R1mH:7bA6EN+gVsSVB+yi::b9Dv3WWkMNNETyzid0DQ:yz4MBYf5ad+ot7gJ::Z71llw1h8PLFXBZ/:z8EoNasXFRn1v4PHbMJm::GjCUt2LsLUPP6BLPUbBa:uiUE7TOTBgAkx4GQ::D4F1s5tQjG//cBkO:FVymWndbehWjVnakGuqc::yEfSYIfFVtnIh2OwlUSo:UbOArjIN9DjD7+4O::CGUtB09BqOnYRCn/:O3g5CAmGq4iDqpAWcQe6::q6HRVC3uxnl/Lc9d:Y8EJ8wJiAzLTkogPY5zs::KfzHVyw1sHTQ0wQ8peIj:SO9kc6HJTYEwdNIH::ss/itWKngkfauy1R:vX8Vw33ZmJ2Js0lIhLOh::aX0IulRwxDdiR91e:Fv9znAJIFnbx0twfzfdK::XBKEydFgjrwmEF1oe4TE:57QKw+BnadWYMv44::93uZttu9CaD0INalWQC5:/Dim3ZsJ9Q55PpsM::BRpOyPXeURei4Oavu2FS:bZR0oHPtu9Bo9RwU::sUBH5VmMcT3N5o8Hp3Lb:D3o+T7eJ36Kds6tG::49kB7DPKmLOdO4j1BJ3a:NOCYm3fnp85pXAEB::tHKA7zszoEuneyGuTopA:nHSAX9TkDl36lWCK::8RG6xI9z9jNZ7HQMHt75:i+IZVDDaFH4zu4X9::uGAtoQGudgyiLKse:2GNOtSlIHRFLTjTf4Pav::bZznPlo35TgnJsXQY4ns:iY7MnLditIC1RfK5::tAyACD69sMiVFgeEVe16:CGYNuDP8zFSMs2Ga::YZN4Vir+t6qWyBgy:3Sf5BHtXTeJJMfZ3Cgos::e8CJReDGWckroQb9:BuFR4Sn54fvkAYVRYIeQ::o4FMrDvKbjvGhkwm:yNJEaftiAI1v0Mz4gsgi::nFDYhlKtNZEI6CWK:rWmXMfnNQK9YSMY9upwP::WnWrg3el2ojB17Ok:kWKX1btPl9xNejvYe9NX::XCmVssvuTrnrdh4yOGEa:N/T8qwXaRvBn/f+6::4oKHMdkxlleOGzFg:U9gs0fFdlNr8L6nHMOQr::vR08X2zToZPdh9IY:24dhv2DGSDFundESZaya::XPDAVhpo35tjxWiqaozK:pOUi2NfOslwVrdDI::5mOye0FLPg1bMA1a:H6O3wyPKK4p3As8rOlMG::iq9H7Ra3lv5n9MQWQWEe:H8gRfFt+PGo6GYMT::KFLEJLqVJG3sNYHsDUAj:+UXlIhbaXwZXdfMZ::jQXF62UL1xIMu6rF7kO0:RzNxDOHjftxuJV+V::Z1O8T5ebleTvb58i:enbErvrMBAONaUuYXfti::c+XQo/OBhz/JOfg5:H3MxXDZQ4syB8FTibTg8::IFD1JeAxxSLWf9of:tj3NYJFrPvkgw6qHsclc::LfXLxfFeGmnkmPKAymKZ:OiRPppxc4QxADDG4::FqLk92ZIaHOlov2b:Ih9mClbSBIcLYrrDyby7::c6HLR1eK+j+3kRq8:hA3XSWl6BNfkni5rx7hB::9W03JkOlwpbXnh9FjN7F:QnNRU1nn5rqgBa8Q::EiVIiWtmX5uxhgJH:8lrbFJSmCOiWkMPBHOQo::ZT9n2jA6gZaNuMMi:znXoJslaUoqJR2VShNPm::ADLdKaHr6n9ionRnnluX:fLSEQ1IxnAhCk36S::WYP/t37ZfbvZL4/m:Xow4FsuDEyUZdNpuZuPP::k8x6r1NkxcnRu76y:OFdZ6cIQZsCPqHVJhxcD::V3RcxTRBRs5DrwNjvkrZ:moCcoPwkvYqF/JGr::MYxqaqM8WsMH/jUT:k3OfyG85q2ZL7H5SXas0::EDLMcaUiNfyUJLxmDqd1:mTg2Jhma9im0XUI2::9Pr5c6NbqvTD/tzk:LwSbnKZCtEOK4dwgfaZy::wDzBPO6xNXbAUfbk:RAR6MXLwS86A8uRDNBxJ::XLwu4XBQGH0c0vJmhgM0:Ijx5Hnl/zsvgwLS3::V6OaNqn4ZIpj9fhz5npa:iE+L+hTHQIv6Jvbb::RKiypqgJ3zd/ljx/:AYXQdOC3jy8HRxOSkfpF::9JaHkFa+MRV7GrKO:3HOaSlnMBzJe82rQtlah::yozmD4jralE6gAd08uCV:Kb8PNstQ7EweB9jw::m0y3pb+fpMyEK2vq:Rukb5bRy9lmCAuoy72sK::QYO7Ga91hWUTnroIIwp5:B988NixJdgdoQIWU::fvxRkLxpON1HbrHE:UBvjLh6ox2myTeA333W6::AtlVShjpI3W7CQrt:7yzQxgEv4bJbZLx3RyFA::guk4j4rVvnD9+Gan:THxRXOeglDeF2JgzVuZl::GDGNWyDhTabYjVbf:g2cqIIjRwmXBX5vHCrCv::8OPHEA2UmSkxnpGvhxad:Xk4P5+lnjh3FdHwb::CqamwT7vK9zeXRvFNfY4:WirILL2a+GSFBHxl::vZX1k14kRofOvWH3SyqE:ESenetZLX9TCMY3a::BcBov2GLPy5L7v0e:OH5PUUeK64LO8gnDPqqq::pTDmOMaUm3OODYCLHx7R:OfCq2A2gr2aipyHu::iRsupbA9ZkoTeVUUzBuf:EBz1AB01uwkAhX7S::NnhRr2NvRBerFgGYt8KILbmA4w8c9KSfQToCpFoUQYeVBkk1iBhkepC9cN9zr3QIXOl25gJ6Bs46O04lHFPVjg==:Z6I6s75yCsumlpxBhXux,2LJjoi3AHhVmo0bkFrrBQnnVaZgLCd4c3SMVMz9URYg=:aJi33m6RWEyBTlTJ0FX2NdIOv5JyYLvBUBfgLMmagUc=:JSXFxf/y9QA5N2GSMi2VRx6myIYTMVcBFBDWiSWOzdU=:BOzsi7Rf7n42OlfKhdl2hdjikhX/ZnQFeXHum4SvgMQ=:O+/1A+Z0rDGVgrp3ZFux2itCRwMi2FPzcS2v6Znd9X8=:SMxRsen0ZcNOOQOEga9VHWlh/Y5VXPBTgXHMFl6W0Jw=:urrs+BBjLeio8dwYYuRxgC7b6tHpBMnIQmk0t5nD9Nc=:voOyTXZi5zMFRRlijSY7M4atrD1rqXFBy6CgjR5I48c=:BVrK/BStKRpQCUIPBOM5Axw8sqcK/2wYh7/p/YGXZjY=:Y6i3qIxq22qFHFQtF6yPNfnzlRhwUCpyESu+X/v6mZg=:Zx3tQR6D4nW6BbxKXf4P6MGXHqcnzL8qREMFNJT7Cuk=:OXWXgApfUdT1Ve73POpjsjp44R6LNHBiGgSZOj5c+8E=:XezpPlGSH7ph5casnXCJEqmYkUjOPOVAItfqv3NbHAM=},{0}";
            var message = {
                type: 'utf8',
                utf8Data: firstBlock
            };
            processMessage(message);
        }
    }
}

function generateTxString(){
    var totalString = "";
    var counter;

    for(var i = 0; i < txPool.length; i++){
        var splitTx = txPool[i].totalMsg.split(" ");

        totalString += splitTx[1] + "*";
        counter = i;
    }

    while(i > 0){
        txPool.shift();
        i--;
    }

    // Remove last star
    totalString = totalString.slice(0, -1);

    return totalString;
}