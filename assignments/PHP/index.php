<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment4.css">');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentFour();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  $mainPage->printWarning();
  $mainPage->checkValues();
  $mainPage->printForm();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  class homeworkAssignmentFour {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 4</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<h1>Assignment 4 has not been created yet! Please come back later!</h1>');
    }

    public function checkValues() {
      if(!empty($_POST)) {
        $this->printData();
      }
    }

    public function getValue($value) {
      if(isset($_POST[$value]) && $_POST[$value] !== '') {
        return $_POST[$value];
      } else {
        return "Not Set";
      }
    }

    public function printData() {
      print('
      <fieldset>
        <legend>Post Data</legend>
        <table>
          <thead>
            <tr>
              <th>Form Item Name</th>
              <th>Form Item Value</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>First Name</td>
              <td>' . $this->getValue('first_name') . '</td>
            </tr>
            <tr>
              <td>Last Name</td>
              <td>' . $this->getValue('last_name') . '</td>
            </tr>

            <tr>
              <td>Color</td>
              <td>' . $this->getValue('color') . '</td>
            </tr>

            <tr>
              <td>Food</td>
              <td>' . $this->getValue('food') . '</td>
            </tr>
          </tbody>
        </table>
      </fieldset>');
    }

    public function printForm() {
      print('
      <fieldset>
        <legend>Example Form</legend>
        <div class="form">
          <form method="post">
            <label>First Name: </label><input name="first_name" type="text"><br>
            <label>Last Name: </label><input name="last_name" type="text">

            <br><br>

            <label>Pick a Color: </label>
            <select name="color">
              <option value="red">Red</option>
              <option value="green">Green</option>
              <option value="blue">Blue</option>
            </select>

            <br><br>

            <!--<label>Hot Food: </label><input name="food" value="hot_food" type="radio" checked><br>-->
            <label>Hot Food: </label><input name="food" value="hot_food" type="radio" checked="checked"><br>
            <label>Cold Food: </label><input name="food" value="cold_food" type="radio">

            <br><br>

            <input type="submit">
          </form>
        </div>
      </fieldset>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 4 - PHP";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>