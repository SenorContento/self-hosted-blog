<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment4.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentFour();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();
  //$mainPage->printArchiveLink();
  //$mainPage->printWarning();

  $mainPage->checkValues();
  $mainPage->printForm();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  class homeworkAssignmentFour {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 4</a>');
      //print('<br>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
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
      $return_me = '';

      print("<script>");
      if(isset($_POST[$value]) && $_POST[$value] !== '') {
        /* Experimenting Around with Keeping Form Options Selected After Submit */
        /*print('
          $(document).ready(function() {
            $(".' . $value . '").val("red");
          });
        ');*/

        $return_me = $_POST[$value];
      } else {
        print('
                $(document).ready(function() {
                  $("label.form-label-' . $value . '").css("color","red");
                  $("label.form-label-' . $value . '").text("Missing " + $("label.form-label-' . $value . '").text());
                });
              ');

        // This breaks the radio labels, but the only way that the option will be missing a value is if the user manually edits it out. E.g. Developer Tools

        $return_me = "Not Set";
      }

      print('</script>');
      return $return_me;
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
          <form method="post">');

          if(isset($_POST['first_name']) && $_POST['first_name'] !== '') {
            print('
              <label class="form-label-first_name">First Name: </label><input name="first_name" type="text" value="' . $_POST['first_name'] . '"><br>
            ');
          } else {
            print('
              <label class="form-label-first_name">First Name: </label><input name="first_name" type="text"><br>
            ');
          }

          if(isset($_POST['last_name']) && $_POST['last_name'] !== '') {
            print('
              <label class="form-label-last_name">Last Name: </label><input name="last_name" type="text" value="' . $_POST['last_name'] . '">
            ');
          } else {
            print('
              <label class="form-label-last_name">Last Name: </label><input name="last_name" type="text">
            ');
          }

          print('
            <br><br>

            <label class="form-label-color">Pick a Color: </label>
            <select id="option-color" name="color">');

          if(isset($_POST['color']) && $_POST['color'] !== '') {
            print('
              <script>
                $(document).ready(function() {
                  $("#option-color option[value=' . $_POST['color'] . ']").prop("selected", true)
                });
              </script>
            ');
          }

          print('
              <option value="red">Red</option>
              <option value="green">Green</option>
              <option value="blue">Blue</option>
            </select>

            <br><br>');


          print('
            <!--<label>Hot Food: </label><input name="food" value="hot_food" type="radio" checked><br>-->
            <label class="form-label-food">Hot Food: </label><input id="radio-hot-food" class="radio-food" name="food" value="hot-food" type="radio" checked="checked"><br>
            <label class="form-label-food">Cold Food: </label><input id="radio-cold-food" class="radio-food" name="food" value="cold-food" type="radio">
          ');

          if(isset($_POST['food']) && $_POST['food'] !== '') {
            print("
              <script>
                $(document).ready(function() {
                  $('#radio-" . $_POST['food'] . "').attr('checked', true);
                });
              </script>
            ");
          }

          print('
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
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>