<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?= isset($PageTitle) ? $PageTitle : "web.SenorContento.com"?></title>
    <link rel="author" href="/humans.txt" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="green">

    <link rel="stylesheet" href="/css/assignment1.css">
    <link rel="stylesheet" href="/css/main.css">

    <link rel="icon" href="/images/svg/SenorContento.svg">
    <link rel="icon" href="/images/png/SenorContento-1024x1024.png">

    <?php if (function_exists('customPageHeader')){
      customPageHeader();
    }?>

  </head>
  <body>
    <header>Hello</header>