<?php

//database information
include_once 'include/dbh.inc.php';

$apiKey = '';
//Save to database on click on Save to favorite
if (isset($_POST['save_to_fave'])) {

  //Add to database
  $query = 'INSERT INTO favorite (
        calories,
        protein,
        fat,
        carbohydrates,
        breakfast_img,
        breakfast_title,
        breakfast_time,
        breakfast_srcUrl,
        lunch_img,
        lunch_title,
        lunch_time,
        lunch_srcUrl,
        dinner_img,
        dinner_title,
        dinner_time,
        dinner_srcUrl

      ) VALUES (
         "' . mysqli_real_escape_string($connect, $_POST['calories']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['protein']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['fat']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['carbohydrates']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['breakfast_img']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['breakfast_title']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['breakfast_time']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['breakfast_srcUrl']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['lunch_img']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['lunch_title']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['lunch_time']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['lunch_srcUrl']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['dinner_img']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['dinner_title']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['dinner_time']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['dinner_srcUrl']) . '"
      )';
  mysqli_query($connect, $query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <!-- TODO <script type="text/javascript" src="js/script-main.js"></script> -->
  <title>NutriFood</title>
</head>

<body>
  <header id="header">
    <nav id="main-menu">
      <h3 class="hidden">Main navigation</h3>
      <ul class="home_menu">
        <li id="site-name"><a href="index.php">NutriFood</a></li>
        <!-- TODO <li><a href="#">Account</a></li> -->
        <li style="float:right"><a href="favourites.php">Favorites</a></li>
      </ul>
    </nav>
  </header>
  <main id="main">
    <section class="bmr">
      <form id="bmr_form" name="bmr_form">
        <!-- Calculate BMR based on Mifflin-St Jeor Equation -->
        <h3>Calculate how much calories you need a day.</h3>

        <!-- Height -->
        <label for="bmr__height">Height:</label>
        <input type="number" id="bmr__height" name="bmr__height" placeholder="Height in CM" required>
        <!-- Weight -->
        <label for="bmr__weight">Weight:</label>
        <input type="number" id="bmr__weight" name="bmr__weight" placeholder="Weight in KG" required>
        <!-- Age -->
        <label for="bmr__age">Age:</label>
        <input type="number" id="bmr__age" name="bmr__age" placeholder="Age" required>

        <!-- Age -->
        <label for="bmr__gender">Gender:</label>
        <select name="bmr__gender" id="bmr__gender" required>
          <option disabled selected value> -- select your gender -- </option>
          <option value="5">Male</option>
          <option value="-161">Female</option>
        </select>
        <!-- Activity level -->
        <label for="bmr__activity">Activity Level:</label>
        <select name="bmr__activity" id="bmr__activity" required>
          <option disabled selected value> -- select your activity level -- </option>
          <option value="1.2">Sedentary (little or no exercise)</option>
          <option value="1.375">Lightly active (light exercise/sports 1-3 days/week)</option>
          <option value="1.55">Moderately active (moderate exercise/sports 3-5 days/week)</option>
          <option value="1.725">Very active (hard exercise/sports 6-7 days a week)</option>
          <option value="1.9">Extra active (very hard exercise/sports & a physical job)</option>
        </select>

        <!-- Weight Goal -->
        <label for="bmr__goal">Weight goal:</label>
        <select name="bmr__goal" id="bmr__goal" required>
          <option disabled selected value> -- select your weight goal -- </option>
          <option value="-1.05">Loose Weight</option>
          <option value="1">Maintain Weight</option>
          <option value="1.05">Gain Weight</option>
        </select>
        <button type="submit" name="submit" value="Submit">Calculate</button>

        <?php
        $bmr = 0;
        //Get the data from the form on click Calculate
        if (isset($_GET['submit'])) {
          $height = $_GET['bmr__height'];
          $weight = $_GET['bmr__weight'];
          $age = $_GET['bmr__age'];
          $goal = $_GET['bmr__goal'];
          $gender = $_GET['bmr__gender'];
          $activity = $_GET['bmr__activity'];
          // Run calcBmr with the data from the form
          $bmr = calcBmr($weight, $height, $age, $gender, $activity, $goal);

          //Get the food plan json with the passed bmr
          $food_plan = getDayMeals($bmr, $apiKey);
        }

        //Store the meals object
        $breakfast = $food_plan->{'meals'}[0];
        $lunch = $food_plan->{'meals'}[1];
        $dinner = $food_plan->{'meals'}[2];

        //Get the Image info with the passed id and Store
        $breakfast_Img = getMealInfo($breakfast->{'id'}, $apiKey);
        $lunch_Img = getMealInfo($lunch->{'id'}, $apiKey);
        $dinner_Img = getMealInfo($dinner->{'id'}, $apiKey);
        ?>
        <!-- Output the bmr -->
        <?php echo '<h4>You should consume ' . $bmr . ' Calories a day.</h4>' ?>

      </form>
      <img src="assets/images/dose-juice-sTPy-oeA3h0-unsplash.jpg" alt="Vegetables" width="400">
    </section>


    <section class="mealplan">
      <h3>Daily Meal plan for <?php echo $food_plan->{'nutrients'}->{'calories'}; ?> Calories: </h3>

      <form class="mealplan_form" method="post" action="index.php">

        <div>
          <h4>Macros:</h4>
          <ul>
            <li>Protein: <?php echo $food_plan->{'nutrients'}->{'protein'}; ?></li>
            <li>Fat: <?php echo $food_plan->{'nutrients'}->{'fat'}; ?></li>
            <li>Carbohydrates: <?php echo $food_plan->{'nutrients'}->{'carbohydrates'}; ?></li>
          </ul>
        </div>
        <div>
          <h4>Breakfast</h4>
          <img src="<?php echo $breakfast_Img->{'image'}; ?>" alt="<?php echo $breakfast->{'title'}; ?>" width="250">
          <p>Title: <?php echo $breakfast->{'title'}; ?></p>
          <p>Preparation time: <?php echo $breakfast->{'readyInMinutes'}; ?> Mins</p>
          <a target="_blank" href="<?php echo $breakfast->{'sourceUrl'}; ?>">See the recipe</a>

        </div>
        <div>
          <h4>Lunch</h4>
          <img src="<?php echo $lunch_Img->{'image'}; ?>" alt="<?php echo $lunch->{'title'}; ?>" width="250">
          <p>Title: <?php echo $lunch->{'title'}; ?></p>
          <p>Preparation time: <?php echo $lunch->{'readyInMinutes'}; ?> Mins</p>
          <a target="_blank" href="<?php echo $lunch->{'sourceUrl'}; ?>">See the recipe</a>
        </div>
        <div>
          <h4>Dinner</h4>
          <img src="<?php echo $dinner_Img->{'image'}; ?>" alt="<?php echo $dinner->{'title'}; ?>" width="250">
          <p>Title: <?php echo $dinner->{'title'}; ?></p>
          <p>Preparation time: <?php echo $dinner->{'readyInMinutes'}; ?> Mins</p>
          <a target="_blank" href="<?php echo $dinner->{'sourceUrl'}; ?>">See the recipe</a>
        </div>

        <!--Save the data to be sent to database-->

        <input type="hidden" name="calories" value="<?php echo $food_plan->{'nutrients'}->{'calories'}; ?>">
        <input type="hidden" name="protein" value="<?php echo $food_plan->{'nutrients'}->{'protein'}; ?>">
        <input type="hidden" name="fat" value="<?php echo $food_plan->{'nutrients'}->{'fat'}; ?>">
        <input type="hidden" name="carbohydrates" value="<?php echo $food_plan->{'nutrients'}->{'carbohydrates'}; ?>">

        <input type="hidden" name="breakfast_img" value="<?php echo $breakfast_Img->{'image'}; ?>">
        <input type="hidden" name="breakfast_title" value="<?php echo $breakfast->{'title'}; ?>">
        <input type="hidden" name="breakfast_time" value="<?php echo $breakfast->{'readyInMinutes'}; ?>">
        <input type="hidden" name="breakfast_srcUrl" value="<?php echo $breakfast->{'sourceUrl'}; ?>">

        <input type="hidden" name="lunch_img" value="<?php echo $lunch_Img->{'image'}; ?>">
        <input type="hidden" name="lunch_title" value="<?php echo $lunch->{'title'}; ?>">
        <input type="hidden" name="lunch_time" value="<?php echo $lunch->{'readyInMinutes'}; ?>">
        <input type="hidden" name="lunch_srcUrl" value="<?php echo $lunch->{'sourceUrl'}; ?>">

        <input type="hidden" name="dinner_img" value="<?php echo $dinner_Img->{'image'}; ?>">
        <input type="hidden" name="dinner_title" value="<?php echo $dinner->{'title'}; ?>">
        <input type="hidden" name="dinner_time" value="<?php echo $dinner->{'readyInMinutes'}; ?>">
        <input type="hidden" name="dinner_srcUrl" value="<?php echo $dinner->{'sourceUrl'}; ?>">

        <input type="submit" name="save_to_fave" value="Save to Favourites">

      </form>
    </section>

    <?php
    // Calculate BMR function
    // Gets info, int
    // return BMR, int
    function calcBmr($weight, $height, $age, $gender, $activity, $goal)
    {
      $bmr = abs((((10 * $weight) + (6.25 * $height) - (5 * $age) + $gender) * $activity) * $goal);
      return $bmr;
    } //End calcBmr function

    // Function to get the daily meal plan
    // Gets bmr, int
    // returns meal plan object
    function getDayMeals($bmr, $apiKey)
    {
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.spoonacular.com/mealplanner/generate?apiKey=' . $apiKey . '&timeFrame=day&targetCalories=' . $bmr . '',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
      ));

      $response_day = json_decode(curl_exec($curl));

      curl_close($curl);
      return $response_day;
    } //End getDayMeal function


    // Function to get the meal info
    // Gets meal id int
    //returns meal object
    function getMealInfo($meal_id, $apiKey)
    {
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.spoonacular.com/recipes/' . $meal_id . '/information?apiKey=' . $apiKey . '&includeNutrition=false',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
      ));

      $response_meal = json_decode(curl_exec($curl));
      curl_close($curl);

      return $response_meal;
    } //End getMeal function

    ?>
  </main>
  <footer class="footer">
    <div class="footer_container">

      <div class="logos">
        <ul>

          <li>
            <a target="_blank" href="https://www.instagram.com/nayereh.rasuli/"><img src="assets/images/insta.png" alt="instagram icon" /></a>

          </li>
          <li>
            <a target="_blank" href="https://github.com/nirarasuli"><img src="assets/images/git.png" alt="github icon" /></a>
          </li>

          <li>
            <a target="_blank" href="https://www.linkedin.com/in/nira-rasuli/"><img src="assets/images/linkedin.png" alt="linkedin icon" />
            </a>
          </li>
          <li>
            <a target="_blank" href="mailto:nirarasuli@gmail.com"><img src="assets/images/mail.png" alt="email icon" />
            </a>
          </li>

        </ul>

      </div>
      <div class="copyright">
        <p>Copyright &copy; Nira Rasuli. All Rights Reserved</p>
      </div>
    </div>
  </footer>
</body>

</html>