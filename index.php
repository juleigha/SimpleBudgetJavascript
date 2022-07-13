<?php
if (!(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')){
  $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header('Location: ' . $location);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <style media="screen">
      .hidden {
        height:0;
        position: fixed;
        visibility: hidden;
      }
      p{
        border-bottom: solid black 1px;
        margin: 0;
      }
      p.amount {
        text-align: right;
        display: inline-block;
        width: 45vw;
      }
      p.name {
        display: inline-block;
        width: 40vw;
      }
      .total {
        text-align: right;
        width: 70vw;
      }
      * {
        margin: 0;
        padding: 0;
      }
      #topNav {
        background: grey;
        padding: 5vw;
        padding-bottom: 40px;
        position: absolute;
        margin: -7vh -5vw 0 -5vw;
        opacity: .9;
        font-weight: bold;
        text-align: center;
        transition: all 1s;
        bottom:50%;
      }
      li {
        display: block;
        list-style: none;
        height: 2em;
        border-bottom: solid lightgray;
        width: 90vw;
        line-height: 2em;
        cursor: pointer;
      }
      div#topNav.closed {
        bottom:95%;
      }
      span.trash {
        cursor: pointer;
      }
      input, select{
        height: 2em;
      }
      body {
          margin-top: 7vh;
          width: 90vw;
          padding: 0 5vw;
      }
      div#divForNewExpense {
          display: flex;
          flex-direction: column;
      }

    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="../jquery.js"></script>
    <title>Budget App</title>
     <?php include 'gacreds.php'; ?>
  </head>
  <body>
    <div id="topNav"> </div>
    <script type="module">
      globalThis["folderName"] = "/budget/#";
      import * as pages from "./modules/screenChangerModule.js";
      import * as frbs from "./modules/db.js";
      import * as budgetMath from "./modules/budgetMath.js";

      var frbsUser = false;

      var expenses = {};
      var expensesArr = [];

      var nav = new pages.Nav("nav1","#topNav");

      var allPages = {
        Daily: {
          nav : new pages.Page("Daily"),
          content: "<h1>Daily</h1><expenses></expenses><div class='total'></div>"
        },
        Weekly: {
          nav : new pages.Page("Weekly"),
          content: "<h1>Weekly</h1><expenses></expenses><div class='total'></div>"
        },
        BiWeekly: {
          nav : new pages.Page("Bi-Weekly"),
          content: "<h1>Bi-Weekly</h1><expenses></expenses><div class='total'></div>"
        },
        Monthly: {
          nav : new pages.Page("Monthly"),
          content: "<h1>Monthly</h1><expenses></expenses><div class='total'></div>"
        },
        EveryOtherMonth: {
          nav : new pages.Page("Ever  y Other Month"),
          content: "<h1>Every other Month</h1><expenses></expenses><div class='total'></div>"
        },
        Every6Months: {
          nav : new pages.Page("Every 6 Months"),
          content: "<h1>Every Six Months</h1><expenses></expenses><div class='total'></div>"
        },
        Yearly: {
          nav : new pages.Page("Yearly"),
          content: "<h1>Yearly</h1><expenses></expenses><div class='total'></div>"
        },
        NewExpense:{
          nav: new pages.Page("New Expense"),
          content: `<?php include "screens/addExpense.txt" ?>`,
          init: function () {
            $("#btnAddExpense").click(e=>{
              var newExpense = {
                name : $("#txtInExpense").val(),
                amount : $("#txtInAmount").val(),
                flag : $("#frequency").val()
              }
              if($("#incomeOrExpense").val()==="E"){
                newExpense.amount*=-1;
              }
              $("#txtInExpense").val(""),
              $("#txtInAmount").val(""),
              console.log(newExpense);
              expensesArr[expensesArr.length]=newExpense;
              if(frbsUser)frbs.lib.add("budgetUsers",{"budget":expensesArr});
              saveBudget();
              initExpenses(expensesArr);
            })
          }
        }
      }
      var parent = "body";
      pages.build(nav, allPages, parent);
      $("#topNav").click(e=>{
        if($("#topNav").hasClass("closed"))$("#topNav").removeClass("closed");
        else $("#topNav").addClass("closed");
      })
      if(allPages[(location.href).replace(location.origin + globalThis["folderName"],"")]){
        allPages[(location.href).replace(location.origin + globalThis["folderName"],"")].nav.Activate();
        $("#topNav").addClass("closed");
      }

      function saveBudget() {
        console.log(expensesArr,JSON.stringify(expensesArr));
        $.getJSON("customCachePut",btoa(unescape(encodeURIComponent(JSON.stringify(expensesArr)))),res=>{
        },err=>{
        })
      }
      function getBudget() {
        $.ajax("customCacheGet").then((resp)=>{
        },err=>{
          expensesArr = JSON.parse(decodeURIComponent(escape(atob(err.responseText))));
          initExpenses();
        })
      }
      firebase.auth().onAuthStateChanged(user=>{
        console.log(user);
        if(!user){
          getBudget();
          // display loging button button
          var glgn = document.createElement("button");
          glgn.id = "loginBtn";
          glgn.innerText = "Sign in with Google to access across your devices";
          $("#topNav").append(glgn);
          $("#loginBtn").click(e=>{
            frbs.lib.login();
          })
        }
        else {
          console.log("FIREBASE");
          frbsUser = true;
          frbs.lib.load({"budget":[]},frbsInitExpenses,"budgetUsers");
        }
      })
      function frbsInitExpenses(allExpenses) {
        console.log(allExpenses);
        expensesArr = allExpenses.budget;
        initExpenses();
      }
      function initExpenses(){
          expensesArr.forEach((expense, i) => {
            var idStr = expense.name.replace(/[\/,\-," "]/,"");
            if (!expenses[expense.name]){
              expenses[expense.name] = new budgetMath.Expense(expense.name,expense.flag,expense.amount);
              console.log(expenses[expense.name]);
              $("#divForDaily expenses").append(`<p class="name exp-id-${idStr}">${expense.name}</p><p class="amount exp-id-${idStr}">${expenses[expense.name].getDaily()} <span class="trash">&#x1F5D1</span></p>`);
              $("#divForWeekly expenses").append(`<p class="name exp-id-${idStr}">${expense.name}</p><p class="amount exp-id-${idStr}">${expenses[expense.name].getWeekly()} <span class="trash">&#x1F5D1</span></p>`);
              $("#divForBiWeekly expenses").append(`<p class="name exp-id-${idStr}">${expense.name}</p><p class="amount exp-id-${idStr}">${expenses[expense.name].getBiWeekly()} <span><span class="trash">&#x1F5D1</span></span></p>`);
              $("#divForMonthly expenses").append(`<p class="name exp-id-${idStr}">${expense.name}</p><p class="amount exp-id-${idStr}">${expenses[expense.name].getMonthly()} <span class="trash">&#x1F5D1</span></p>`);
              $("#divForEveryOtherMonth expenses").append(`<p class="name exp-id-${idStr}">${expense.name}</p><p class="amount exp-id-${idStr}">${expenses[expense.name].getOtherMonthly()} <span class="trash">&#x1F5D1</span></p>`);
              $("#divForEvery6Months expenses").append(`<p class="name exp-id-${idStr}">${expense.name}</p><p class="amount exp-id-${idStr}">${expenses[expense.name].getSixMonth()} <span class="trash">&#x1F5D1</span></p>`);
              $("#divForYearly expenses").append(`<p class="name exp-id-${idStr}">${expense.name}</p><p class="amount exp-id-${idStr}">${expenses[expense.name].getAnually()} <span class="trash">&#x1F5D1</span></p>`);
              $(`.exp-id-${idStr} .trash`).click(e=>{
                expenses[expense.name].delete = true;
                initExpenses();
              })
            }
            else if (expenses[expense.name].delete) {
              delete expenses[expense.name];
              $(`.exp-id-${idStr}`).remove();
              expensesArr.splice(i,1);
              if(frbsUser)frbs.lib.add("budgetUsers",{"budget":expensesArr});
              saveBudget();
            }
            $("#divForDaily .total").text("Total: " + budgetMath.total(expenses,"D"));
            $("#divForWeekly .total").text("Total: " + budgetMath.total(expenses,"W"));
            $("#divForBiWeekly .total").text("Total: " + budgetMath.total(expenses,"B"));
            $("#divForMonthly .total").text("Total: " + budgetMath.total(expenses,"M"));
            $("#divForEveryOtherMonth .total").text("Total: " + budgetMath.total(expenses,"O"));
            $("#divForEvery6Months .total").text("Total: " + budgetMath.total(expenses,"S"));
            $("#divForYearly .total").text("Total: " + budgetMath.total(expenses,"A"));
          });
      }
      if ('serviceWorker' in navigator && 'PushManager' in window) {
          window.addEventListener('load', function() {
              navigator.serviceWorker.register('./sw.js', {
                  updateViaCache: 'none'
              }).then(function(reg) {
                // Registration success
                reg.update();
                // reg.fetch;
              }, function(err) {
                console.log(err);
              });
          });
      }

    </script>
  </body>
</html>
