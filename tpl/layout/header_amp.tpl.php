<!doctype html>
<html amp lang="cs">
  <head>
    <meta charset="utf-8">
    <title><?php if (isset($title)) echo $title . ' - '; echo $site['title'] ?></title>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <link rel="canonical" href="<?php echo $postUrl ?>"/>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <script type="application/ld+json">
      {
        "@context": "http://schema.org",
        "@type": "NewsArticle",
        "headline": "Open-source framework for publishing content",
        "datePublished": "<?php echo $post->date ?>",
        "author": {
            "@type": "Person",
            "name": "Daniel Milde"
        },
      }
    </script>
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400&subset=latin,latin-ext"/>
  <style amp-custom>
    body {
      font-family: RobotoDraft, 'Helvetica Neue', 'Helvetica Neue', Arial, sans-serif;
      font-size: 18px;
      line-height: 26px;
      color: #333;
    }
    #main {
      padding: 1em 2em 1em 2em;
      position: relative;
    }
    #header {
        background: #c52d2d;
        border-bottom: 7px solid #cfe9fb;
        padding-left: 1em;
    }
    #logobar {
        width: 10em;
        margin: 0 auto 0.2em;
    }
    #logo {
        padding: 0.6em 0 0 1em;
        color: #fff;
        text-decoration: none;
        font: bold 1.5em Tahoma, Verdana,"Geneva CE",sans-serif;
    }
    h1 {
        font-size: 1.5em;
        font-weight: normal;
        line-height: 1.2em;
        margin: 0.7em 0;
    }
    h2 {
        font-size: 1.25em;
        font-weight: normal;
    }
    h3 {
        color: #7fae3f;
        font-size: 1em;
        margin-left: 0.5125em;
        margin-top: 1em;
        clear: left;
    }
  </style>
  </head>
  <body>
    <div id="header">
        <div id="logobar">
            <a id="logo" href="<?php echo APP_URL ?>"><?php echo $name ?></a>
        </div>
    </div>
    <div id="main">
