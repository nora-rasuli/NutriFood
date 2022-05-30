window.onload = ready;

function ready(){

  //Declare Form
  var bmrFormHandle = document.forms.bmr_form;
  bmrFormHandle.onsubmit = processBmrForm;

  //Declare Form variables
  var bmrHeight = document.getElementById("bmr__height");
  var bmrWeight = document.getElementById("bmr__weight");
  var bmrAge = document.getElementById("bmr__age");
  var bmrGender = document.getElementById("bmr__gender");
  var bmrActivity = document.getElementById("bmr__activity");
  var bmrGoal = document.getElementById("bmr__goal");
  const bmr = 0;
  //BMR Form
  function processBmrForm(){
    calcBmr(bmrHeight, bmrWeight, bmrAge, bmrGender, bmrActivity, bmrGoal);
  };

  //Calculate BMR function
  function calcBmr(weight, height, age, gender, activity, goal){
    bmr = Math.abs((((10 * weight) + (6.25 * height) - (5 * age) + gender) * activity) * goal);
    return bmr;
  }
}
