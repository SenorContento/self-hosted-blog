<!DOCTYPE html>
<html>
  <head>
    <title><?= isset($PageTitle) ? $PageTitle : "web.SenorContento.com"?></title>
    <link rel="author" href="/humans.txt" />

    <link rel="stylesheet" href="/HTML-CSS/assignment1.css">

    <link rel="icon" href="/images/svg/SenorContento.svg">
    <link rel="icon" href="/images/png/SenorContento-1024x1024.png">

    <?php if (function_exists('customPageHeader')){
      customPageHeader();
    }?>

  </head>
  <body>
