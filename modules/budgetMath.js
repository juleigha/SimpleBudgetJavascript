
class Expense {
  constructor(name, flag, amount) {
    this.anually;
    this.name = name;
    switch (flag) {
      //daily
      case "D":
          this.anually = amount *365;
        break;
      //weekly
      case "W":
        this.anually = amount * 52;
        break;
      // bi weekly
      case "B":
        this.anually = amount * 26;
        break;
      // monthly
      case "M":
        this.anually = amount * 12;
        break;
      // 2-monthly
      case "O":
        this.anually = amount * 6;
        break;
        // 6mo
        case "S":
        this.anually = amount * 2;
        break;
      // anually
      case "A":
        this.anually = amount;
        break;
    }
  }
  getDaily(){
    var num =  this.anually / 365;
    return Math.round((num + Number.EPSILON) * 100) / 100
  }
  getWeekly(){
    var num = this.anually / 52;
    return Math.round((num + Number.EPSILON) * 100) / 100
  }
  getBiWeekly(){
    var num = this.anually / 26;
    return Math.round((num + Number.EPSILON) * 100) / 100
  }
  getMonthly(){
    var num = this.anually / 12;
    return Math.round((num + Number.EPSILON) * 100) / 100
  }
  getOtherMonthly(){
    var num = this.anually / 6;
    return Math.round((num + Number.EPSILON) * 100) / 100
  }
  getSixMonth(){
    var num = this.anually / 2;
    return Math.round((num + Number.EPSILON) * 100) / 100
  }
  getAnually(){
    var num = this.anually;
    return Math.round((num + Number.EPSILON) * 100) / 100
  }
}
function total(expenses,flag){
  var amnt = 0;
  Object.keys(expenses).forEach((expense, i) => {
    console.log(expenses[expense]);
    switch (flag) {
      //daily
      case "D":
      amnt += expenses[expense].getDaily();
        break;
      //weekly
      case "W":
        amnt += expenses[expense].getWeekly();
        break;
      // bi weekly
      case "B":
        amnt += expenses[expense].getBiWeekly();
        break;
      // monthly
      case "M":
        amnt += expenses[expense].getMonthly();
        break;
      // 2-monthly
      case "O":
        amnt += expenses[expense].getOtherMonthly();
        break;
        // 6mo
        case "S":
          amnt += expenses[expense].getSixMonth();
        break;
      // anually
      case "A":
        amnt += expenses[expense].getAnually();
        break;
    }
  });
  return amnt;
}


/////////////////////////////////////////////////////////
var name = "budgetMath";
export {Expense,name,total};
