<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>User Evaluation</title>
<link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="fluidlayout.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="gridContainer clearfix">
  <div id="evaluation">
    <h1>User Evaluation</h1>
    <form class="form-container" method="POST" action="evaluationAnswers.php">
    <p>1. Which search engine do you normally use?</p>
      <p>(Google, Bing, Blekko, Other...)</p>
     <p>Type here: <textarea name="question1" style="overflow:hidden" id="comments"></textarea></p>
     <br />
    <hr />
    <p>Please answer the following, and where appropriate use the following as a guide:</p>
    <br />
    <p>1 = ‘strongly disagree’, 
    <br />
    2 = ‘disagree’, 
    <br />
    3= ‘neither agree or disagree’,
    <br /> 
    4= ‘agree',
    <br /> 
    5 = ‘strongly agree'</p>
    <br />
    <hr />
    
    <p>2. In general, I found that the quality of the results returned were superior to my normal search engine of choice (as indicated in Question1 above).</p>
    <input type="radio" name="question2" value="1"> 1
     <input type="radio" name="question2" value="2"> 2 
     <input type="radio" name="question2" value="3"> 3
     <input type="radio" name="question2" value="4"> 4
     <input type="radio" name="question2" value="5"> 5

    <p>3. In general when using the meta search engine, I found that the quality of the aggregated results returned were of better quality when compared to the non-aggregated results. <p>
     <input type="radio" name="question3" value="1"> 1
     <input type="radio" name="question3" value="2"> 2 
     <input type="radio" name="question3" value="3"> 3
     <input type="radio" name="question3" value="4"> 4
     <input type="radio" name="question3" value="5"> 5
     
    <p>4. I found the interface very easy to use.</p>
    <input type="radio" name="question4" value="1"> 1
     <input type="radio" name="question4" value="2"> 2 
     <input type="radio" name="question4" value="3"> 3
     <input type="radio" name="question4" value="4"> 4
     <input type="radio" name="question4" value="5"> 5
     
    <p>5. I liked how the results were presented. </p>
	<input type="radio" name="question5" value="1"> 1
     <input type="radio" name="question5" value="2"> 2 
     <input type="radio" name="question5" value="3"> 3
     <input type="radio" name="question5" value="4"> 4
     <input type="radio" name="question5" value="5"> 5
     
    <p>6. The speed of the meta engine is better than my typical engine of choice. </p>
      <input type="radio" name="question6" value="1"> 1
     <input type="radio" name="question6" value="2"> 2 
     <input type="radio" name="question6" value="3"> 3
     <input type="radio" name="question6" value="4"> 4
     <input type="radio" name="question6" value="5"> 5
      
    <p>7. If given the option, would you make this your default search engine?</p>
      <input type="radio" name="question7" value="1"> Yes
     <input type="radio" name="question7" value="2"> No
     
     <p>8. What gender are you?</p>
      <input type="radio" name="question8" value="1"> Male
     <input type="radio" name="question8" value="2"> Female
     
    <p>9. Please choose which age bracket you are in.</p>
     <input type="radio" name="question9" value="1"> 15-24
     <input type="radio" name="question9" value="2"> 25-34
     <input type="radio" name="question9" value="3"> 35-44
     <input type="radio" name="question9" value="4"> 45-54
     <input type="radio" name="question9" value="5"> 55-64
     <input type="radio" name="question9" value="6"> 65+
      
         <br /><br />

        
    
        <div class="form-title"><h2></h2></div>
        <div class="form-title">Name</div>
        <input name="fullname" type="text" class="form-field" /><br />
        <div class="form-title">Email</div>
      <input name="email" type="text" class="form-field" />
      <br />
        
        <div class="form-title">Comments</div>
        <textarea name="comment" class="form-field" type="text"></textarea><br />
        <div class="submit-container">
        <input class="submit-button" type="submit" value="Submit" />
        </div>
    </form>
    <p>On the wrong page?
    <a href="index.php">
  		(Return to the Home page)
  	</a></p>
	</div>



</div>
</body>
</html>