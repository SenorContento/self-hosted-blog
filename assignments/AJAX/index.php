<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment8.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentEight();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();
  $mainPage->printWarning();
  $mainPage->printForm();

  $loadPage->loadFooter();

  class homeworkAssignmentEight {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 8 has not been created yet! Please come back later!</h1></center>');
    }

    /* Potential Alternate Generators
     *
     * https://serverfault.com/questions/707859/generate-entropy-for-pgp-key
     * https://crypto.stackexchange.com/a/26477/39179
     * https://davesteele.github.io/gpg/2014/09/20/anatomy-of-a-gpg-key/
     *
     * haveged
     * rng-tools
     *
     * /dev/random
     * /dev/urandom
     */

    /* Potential Entropy Fakers
     *
     * phidelius - https://dankaminsky.com/2012/01/03/phidelius/ - http://s3.amazonaws.com/dmk/phidelius-1.01.tgz - PRNG is not seeded
     * keygen - https://ritter.vg/blog-non_persistent_pgp.html - https://ritter.vg/resources/non-persistent-gpg-keys.tgz - MPI subsystem not initialized
     *
     * Both of these could allow replacing the pseudorandom generator with data from Hotbits (for some cryptographic strength testing without introducing uncontrolled variables).
     * Problem is they both are broken for the reasons tacked on to the end of each list item. For example PRNG is not seeded was outputted by ssh-keygen probably because data is
     * not being piped into /dev/urandom. SSH-Keygen grabs data from that device (as monitored by strace on my RPi). MPI subsystem... is outputted by keygen itself.
     *
     * https://www.ibm.com/developerworks/community/blogs/cgaix/entry/fatal_prng_is_not_seeded?lang=en - Potential Solution for PRNG is not seeded
     * phidelius has the most chance of working. It is possible that phidelius is not generating the devices when it executes the keygen programs.
     */

    /* Debug Trace - phidelius
     *
     * ➜  phidelius-1.01 ./phidelius -p "ax-op-nm-qw-yi" -d -e "ssh-keygen -f id_testme"
     * open64 /dev/null
     * poll
     * PRNG is not seeded
     *
     * ----------------------------------------------------------------------------------------------------------------------------------------------------------------
     * I now use https://github.com/robbyrussell/oh-my-zsh for my shell, so it is going to look different
     * from a typical bash shell (as it is not bash and is a modified zsh shell)
     */

    /*
      http://api.jquery.com/category/ajax/

      * .load()

      * jQuery.get()
      * jQuery.getJSON()

      * jQuery.post()

      * jQuery.ajax()
    */

    public function printForm() {
      // https://blog.senorcontento.com/sitedata/general/debug/debugger
      // https://raw.githubusercontent.com/bgbrandongomez/blog/master/sitedata/general/debug/debugger.md
      print('<script src="ajax-api.js"></script>');

      print('
      <fieldset>
        <legend>Example Form</legend>
        <div class="form">
          <form id="hotbits">
            <label>URL: </label><input type="text" id="url" value="/assignments/AJAX/hotbits.php">
            <label>Data: </label><input type="text" id="data" value=\'{"retrieve": true, "id": 1}\'><br>

            <a id="submit">Submit</a>
          </form>

          <br>

          <h3>Ajax Output</h3>
          <pre id="ajax-output-debug"><div class="highlight">Output Not Generated...</div></pre>

          <br>

          <table>
            <thead>
              <tr>
                <th>Index</th>
                <th>Item</th>
                </tr>
            </thead>
            <tbody id="ajax-table-body">
              <tr>
                <td class="index-table">Output Not Generated...</td>
                <td class="item-table">Output Not Generated...</td>
                </tr>
            </tbody>
          </table>
        </div>
      </fieldset>');
    }

    public function getHTML() {
      // https://www.w3schools.com/jquery/jquery_ajax_load.asp
      // http://api.jquery.com/load/
      // .load()
    }

    public function getData() {
      // https://www.w3schools.com/jquery/jquery_ajax_get_post.asp
      // http://api.jquery.com/jQuery.get/
      // jQuery.get()
    }

    public function getJSON() {
      // http://api.jquery.com/jQuery.getJSON/
      // jQuery.getJSON()
    }

    public function postData() {
      // https://www.w3schools.com/jquery/jquery_ajax_get_post.asp
      // http://api.jquery.com/jQuery.post/
      // jQuery.post()
    }

    public function ajaxRequest() {
      // http://api.jquery.com/jQuery.ajax/
      // jQuery.ajax()
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 8 - AJAX";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>