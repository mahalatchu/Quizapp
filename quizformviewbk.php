<?php
error_reporting(E_ERROR | E_PARSE);
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "quizapp"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$quiztopicsql= "SELECT id, quiz_subject FROM quiz_topics";

// Execute the query
$quizsubjects = $conn->query($quiztopicsql);

if (!empty($_POST['quiz_subject'])) {
    echo 'maha'; exit;

    $quizquestionanswersql = "SELECT id, question, option_a, option_b, option_c, option_id FROM quiz_topics";
}

//echo $_SESSION['user_email']; exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Application</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .error { color: red; }
    </style>
</head>
<form action="quizformview.php" method="post">

    <h2>Welcome to Quiz Application</h2>
    <select id="quiz_subject" name="quiz_subject" style="width=100%;">
        <option value="">Select Subject</option>
        <?php if ($quizsubjects->num_rows > 0) { 
             while($row = $quizsubjects->fetch_assoc()) { ?>
                <option value="<?php echo  $row["id"]; ?>"><?php echo $row["quiz_subject"]; ?></option>
                <?php } }?>
    </select><span id="errorMessage" style="display:block;">Please select subject to take your quiz...</span><br>
    <button type="submit" style="margin-top: 30px;">Submit To Take Quiz</button>
</form>
</body>
</html>
<script>
    $(document).form.submit(function(){
       // alert('testdata');
       var subjectval = $('#quiz_subject').val();
       $('#errorMessage').css('display', 'block'); 
       if (subjectval != "") {
           $('#errorMessage').css('display', 'none'); 
       }
    });
</script>