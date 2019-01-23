<?php

$mainPage = new generateSitemap();

$mainPage->setVars();
$mainPage->generateSitemapXMLHeaders();
$mainPage->generateSitemapXML();

class generateSitemap {
  #public $exec_cat_path;
  public $exec_grep_path;
  #public $exec_cut_path;
  public $exec_find_path;
  public $exec_awk_path;

  function setVars() {
    if(getenv('alex.server.type') === "production") {
      # The below variables are for the production server
      #$this->exec_cat_path = "";
      $this->exec_grep_path = "/bin/grep";
      #$this->exec_cut_path = "";
      $this->exec_find_path = "/usr/bin/find";
      $this->exec_awk_path = "/usr/bin/awk";

    } else if(getenv('alex.server.type') === "development") {
      # The below variables are for testing on localhost
      #$this->exec_cat_path = "/bin/cat";
      $this->exec_grep_path = "/usr/bin/grep";
      $this->exec_cut_path = "/usr/bin/cut";
      $this->exec_find_path = "/usr/local/bin/gfind";
      $this->exec_awk_path = "/usr/local/bin/gawk";
    }
  }

  public function generateSitemapXMLHeaders() {
    header("Content-Type: text/xml");
    header("Content-Type: application/xml");
  }

  public function generateSitemapXML() {
    print('<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
          ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n");

    $URLS = shell_exec($this->exec_find_path . ' ' .
                       $_SERVER['DOCUMENT_ROOT'] . '/ | ' .
                       $this->exec_grep_path . ' -v ".git" | ' . # Added the .git part for the development server
                       $this->exec_grep_path . ' -v "/php_data" | ' . # and php_data for all servers
                       $this->exec_grep_path . ' -v "/sql_admin" | ' .
                       $this->exec_awk_path . " -F '" . $_SERVER['DOCUMENT_ROOT'] . "' '{ print \"<url><loc>\"$2\"</loc></url>\" }'"); # and sql_admin for all servers

    $URLSArray = array_unique(explode("\n", $URLS));

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

    $URLS = shell_exec($this->exec_find_path . ' ' .
            $_SERVER['DOCUMENT_ROOT'] . '/ | ' .
            $this->exec_grep_path . ' -v ".git" | ' . # Added the .git part for the development server
            $this->exec_grep_path . ' -v "/php_data" | ' . # and php_data for all servers
            $this->exec_grep_path . ' -v "/sql_admin" | ' .
            $this->exec_awk_path . " -F '" . $_SERVER['DOCUMENT_ROOT'] . "' '{ print $2 }'"); # and sql_admin for all servers

    $URLSArray = array_unique(explode("\n", $URLS));

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