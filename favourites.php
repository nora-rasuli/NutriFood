<?php
// database information
include_once 'include/dbh.inc.php';
?>
<?php
// databse information
$connect = mysqli_connect(
  $hostname,
  $username,
  $password,
  $database
); ?>
<?php
// delete the data from database on click delete
if (isset($_GET['delete'])) {

  $query = 'DELETE FROM favorite
    WHERE id = ' . $_GET['delete'] . '
    LIMIT 1';
  mysqli_query($connect, $query);

  header('Location: favourites.php');
  die();
}

// Show the data order by timestamp
$query = 'SELECT *
  FROM favorite
  ORDER BY id DESC';
$result = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <!-- <script type="text/javascript" src="js/script-main.js"></script> -->
  <title>NutriFood</title>
</head>

<body>
  <header id="header">
    <nav id="main-menu">
      <h3 class="hidden">Main navigation</h3>
      <ul class="fav_menu">
        <li id="site-name"><a href="index.php">NutriFood</a></li>
        <!-- <li><a href="#">Account</a></li> -->
        <li style="float:right"><a href="favourites.php">Favorites</a></li>
      </ul>
    </nav>
  </header>
  <main id="main">
    <h2>Favorites Meal plans:</h2>
    <!-- Show each meal -->
    <?php while ($record = mysqli_fetch_assoc($result)) : ?>

      <section class="mealplan">
        <h3>Daily Meal plan for <?php echo $record['calories']; ?> Calories: </h3>
        <div class="meals">
          <div>
            <h4>Macros:</h4>
            <ul>
              <li>Protein: <?php echo $record['protein']; ?></li>
              <li>Fat: <?php echo $record['fat']; ?></li>
              <li>Carbohydrates: <?php echo $record['carbohydrates']; ?></li>
            </ul>
          </div>
          <div>
            <h4>Breakfast</h4>
            <img src="<?php echo $record['breakfast_img']; ?>" alt="<?php echo $record['breakfast_title']; ?>" width="250">
            <p>Title: <?php echo $record['breakfast_title']; ?></p>
            <p>Preparation time:<?php echo $record['breakfast_time']; ?> Mins</p>
            <a target="_blank" href="<?php echo $record['breakfast_srcUrl']; ?>">See the recipe</a>

          </div>
          <div>
            <h4>Lunch</h4>
            <img src="<?php echo $record['lunch_img']; ?> " alt="<?php echo $record['lunch_title']; ?>" width="250">
            <p>Title: <?php echo $record['lunch_title']; ?></p>
            <p>Preparation time: <?php echo $record['lunch_time']; ?> Mins</p>
            <a target="_blank" href="<?php echo $record['lunch_srcUrl']; ?>">See the recipe</a>
          </div>
          <div>
            <h4>Dinner</h4>
            <img src="<?php echo $record['dinner_img']; ?>" alt="<?php echo $record['dinner_title']; ?>" width="250">
            <p>Title: <?php echo $record['dinner_title']; ?></p>
            <p>Preparation time: <?php echo $record['dinner_time']; ?> Mins</p>
            <a target="_blank" href="'<?php echo $record['dinner_srcUrl']; ?>'">See the recipe</a>
          </div>
          <a href="favourites.php?delete=<?php echo $record['id'];; ?>" onclick="javascript:confirm('Are you sure you want to delete this meal plan?');">Delete</a>
        </div>
      </section>
    <?php endwhile; ?>
  </main>
  <footer class="footer fav_footer">
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