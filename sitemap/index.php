<?php

$mainPage = new generateSitemap();

$mainPage->setVars();
$mainPage->generateSitemapXMLHeaders();
$mainPage->generateSitemapXML();

class generateSitemap {
  public $server_host;
  #public $exec_cat_path;
  public $exec_grep_path;
  #public $exec_cut_path;
  public $exec_find_path;
  public $exec_awk_path;
  public $exec_sed_path;
  public $exec_sort_path;

  function setVars() {
    if(getenv('alex.server.type') === "production") {
      # The below variables are for the production server
      $this->server_host = getenv('alex.server.host');
      #$this->exec_cat_path = "";
      $this->exec_grep_path = "/bin/grep";
      #$this->exec_cut_path = "";
      $this->exec_find_path = "/usr/bin/find";
      $this->exec_awk_path = "/usr/bin/awk";
      $this->exec_sed_path = "/bin/sed";
      $this->exec_sort_path = "/usr/bin/sort";

    } else if(getenv('alex.server.type') === "development") {
      # The below variables are for testing on localhost
      $this->server_host = getenv('alex.server.host');
      #$this->exec_cat_path = "/bin/cat";
      $this->exec_grep_path = "/usr/bin/grep";
      #$this->exec_cut_path = "/usr/bin/cut";
      $this->exec_find_path = "/usr/local/bin/gfind";
      $this->exec_awk_path = "/usr/local/bin/gawk";
      $this->exec_sed_path = "sed";
      $this->exec_sort_path = "sort";
    }
  }

  public function generateSitemapXMLHeaders() {
    header("Content-Type: text/xml");
    header("Content-Type: application/xml");
  }

  public function grabURLs() {
    # { find . -type d -print | sed 's!$!/!'; find . \! -type d; } | sort
    #return ($this->exec_find_path . ' ' . $_SERVER['DOCUMENT_ROOT'] . '/ | ' .
    #        $this->exec_grep_path . ' -v ".git" | ' . # Added the .git part for the development server
    #        $this->exec_grep_path . ' -v "/php_data" | ' . # and php_data for all servers
    #        $this->exec_grep_path . ' -v "/sql_admin" | ');

    #print("Host: " . $this->server_host . "\n");

    return ('{ ' . $this->exec_find_path . ' ' . $_SERVER['DOCUMENT_ROOT'] . '/ -type d -print | ' .
                   $this->exec_sed_path . " 's!$!/!'; " .
                   $this->exec_find_path . ' ' . $_SERVER['DOCUMENT_ROOT'] . '/ \! -type d; } | ' .
                   $this->exec_sort_path . ' | ' .
                   $this->exec_sed_path . " 's/\/\//\//' | " . # To remove double slashed root

                   $this->exec_grep_path . ' -v ".git" | ' . # Added the .git part for the development server
                   $this->exec_grep_path . ' -v "/php_data" | ' . # and php_data for all servers
                   $this->exec_grep_path . ' -v "/sql_admin" | '); # and sql_admin for all servers
  }

  public function generateSitemapXML() {
    print('<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
          ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n");

    $URLs = shell_exec($this->grabURLs() .
            $this->exec_awk_path . " -F '" . $_SERVER['DOCUMENT_ROOT'] . "' '{ print \"<url><loc>" . $this->server_host . "\"$2\"</loc></url>\" }'");

    $URLSArray = array_unique(explode("\n", $URLs));

    #var_dump($URLSArray); # Dumps Raw Variable - Useful for Debugging

    # Removes empty values from array
    foreach($URLSArray as $key => $value)
      if(empty($value))
        unset($URLSArray[$key]);

    foreach($URLSArray as $key => $value) {
      print($value . "\n");
    }

    print(' </urlset>' . "\n");
  }

  public function generateSitemapTXTHeaders() {
    header("Content-Type: text/plain");

    header("Content-Disposition: attachment;filename=sitemap.txt"); # So, Google requires me to put a .txt extension on plain text sitemaps. I am going to try to trick Google into accepting my sitemap as is.
    header("Content-Transfer-Encoding: binary");
  }

  public function generateSitemapTXT() {
    # I was originally going to filter out the directories denied in robots.txt, but I soon came to realize how
    #   stupid and overly complicated of an idea that was. Multilevel arrays do not like cooperating in PHP with me.

    #$disallowedURLS = shell_exec($this->exec_cat_path . ' ' . $_SERVER['DOCUMENT_ROOT'] . '/robots.txt | ' .
    #                         $this->exec_grep_path . ' "Disallow: " | ' .
    #                         $this->exec_grep_path . ' -v "\"" | ' .
    #                         $this->exec_cut_path . ' -d\' \' -f2-');
    #$disallowedURLSArray = array_unique(explode("\n", $disallowedURLS));

    #$URLS = shell_exec($this->exec_find_path . ' ' .
    #        $_SERVER['DOCUMENT_ROOT'] . '/ | ' .
    #        $this->exec_grep_path . ' -v ".git" | ' . # Added the .git part for the development server
    #        $this->exec_grep_path . ' -v "/php_data" | ' . # and php_data for all servers
    #        $this->exec_grep_path . ' -v "/sql_admin" | ' .
    #        $this->exec_awk_path . " -F '" . $_SERVER['DOCUMENT_ROOT'] . "' '{ print $2 }'"); # and sql_admin for all servers

    $URLs = shell_exec($this->grabURLs() .
            $this->exec_awk_path . " -F '" . $_SERVER['DOCUMENT_ROOT'] . "' '{ print \"" . $this->server_host . "\"$2 }'");

    $URLSArray = array_unique(explode("\n", $URLs));

    #var_dump($URLSArray); # Dumps Raw Variable - Useful for Debugging

    # Removes empty values from array
    foreach($URLSArray as $key => $value)
    if(empty($value))
        unset($URLSArray[$key]);

    foreach($URLSArray as $key => $value) {
      print($value . "\n");
    }
  }

  # Was used in removing denied entries in robots.txt
  #private function startsWith($string, $substring) {
  #  $length = strlen($substring);
  #  return(substr($string, 0, $length) === $substring);
  #}
}

?>