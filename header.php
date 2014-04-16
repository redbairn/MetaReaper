<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<!--Thesaurus service provided by words.bighugelabs.com-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MetaReaper</title>
<link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="fluidlayout.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="gridContainer clearfix">
  <div style="text-align:center">
      <div id="Search">
  			<a href="index.php"><img src="Images/MetaReaper_Banner.png" alt="MetaReaper"></a>
            <form method="GET" action="serviceOp.php" style="display:inline;">
                <input name="query1" type="text" style="width:50%"  id="namanyay-search-box" maxlength="40" value="" placeholder="  Type!  " />
                <input name="bt_search"  id="namanyay-search-btn" type="submit" value=" "   />
                
                    <!-- Advanced Options for MetaSearch engine-->
                    <br /><br />
                    <div class="options">
                        <a href='#' name='NumberResults' class='Number'>Aggregate <img src="Images/arrow_down.png" alt="Menu_arrow_downwards"></a>
                        <!--Other options-->
                        Non-Aggregate:<input name="service_op" type="radio" class="non" value="4" />
                        <a href='#' name='Advanced Options' class='advanced'>Advanced Options <img src="Images/arrow_down.png" alt="Menu_arrow_downwards"></a>
                  </div>
                        <!-- Drop down list for Advanced options-->
                        <div id='advancedOptions' class="sub">
                            <u>Single Search Engine:</u>
                            <br />
                            Bing:<input name="service_op" type="radio" value="5" />
                            <br />
                            Google:<input name="service_op" type="radio" value="6" />
                            <br />
                            Blekko:<input name="service_op" type="radio" value="7" />
                        </div>
                        <!-- Drop down list to choose how many results will be displayed-->
                        <div id='NumResults' class="sub">
                            <u>No.of Results:</u>
                            <br />
                            10:<input name="service_op" type="radio" value="1" />
                            <br />
                            50:<input name="service_op" type="radio" value="2" />
                            <br />
                            100:<input name="service_op" type="radio" value="3" />
                        </div>
              </form>