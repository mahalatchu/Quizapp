<?php 
error_reporting(E_ERROR | E_PARSE);
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

/*if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // CSRF attack detected..
    header('Location: index.php');
    exit();
} */

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


$quiz_subject_id = 1;
if (!empty($_POST['quiz_subject_id'])) {
    $quiz_subject_id = $_POST['quiz_subject_id'];
}
$_SESSION['quiz_subject_id'] = $quiz_subject_id;
$_SESSION['question_index'] =  ($_SESSION['question_index'] > 0) ? $_SESSION['question_index'] : 0;
$_SESSION['score'] = ($_SESSION['score'] > 0) ? $_SESSION['score'] : 0;

if ($_POST['question_index'] == 9) {
    $_SESSION['question_index'] = $_POST['question_index'];
} else {
    if (isset($_POST['question_index'])) {
        $_SESSION['question_index'] = $_POST['question_index'] + 1;
        if ($_POST['correct_answer'] == $_POST['answer']) {
            $_SESSION['score']++;
        }
    } else {
        $_SESSION['question_index'] = 0;
        $_SESSION['score'] = 0;
    }
}
$limit = 1;
$offset = $_SESSION['question_index'];
$quizquestionanswersql = "SELECT id, question, option_a, option_b, option_c, option_d, correct_answer FROM quiz_question_answers where quiz_topic_id='".$_SESSION['quiz_subject_id']."' ORDER BY id Asc LIMIT $limit OFFSET $offset";

$quizquesanswers= $conn->query($quizquestionanswersql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Application</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
        }

        /* Header Styling */
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .header .nav-menu {
            list-style-type: none;
            display: flex;
            gap: 20px;
        }

        .header .nav-menu a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .header .nav-menu a:hover {
            color: #ddd;
        }

        /* Main Content Styling */
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 70px); /* Adjusting for the header height */
            padding: 20px;
        }

        /* Quiz Container */
        .quiz-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        /* Quiz Header */
        .quiz-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        /* Quiz Question */
        .quiz-container .question {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Quiz Answers */
        .quiz-container .answers {
            margin-bottom: 20px;
            text-align: left;
        }

        .quiz-container .answers label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
        }

        /* Radio Buttons Styling */
        .quiz-container input[type="radio"] {
            margin-right: 10px;
        }

        /* Submit Button */
        .quiz-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .quiz-container button:hover {
            background-color: #0056b3;
        }


        /* Highlight the selected menu item */
        ul li.active {
            background-color: #003366; /* Dark Blue */
            padding : 10px;
            border-radius : 10px;
            box-shadow : 0 0 10px rgba(0,0,0,1);
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="logo">Quiz App</div>
        <ul class="nav-menu">
        <?php if ($quizsubjects->num_rows > 0) { 
            while($row = $quizsubjects->fetch_assoc()) { ?>
            <li data-value="<?php echo $row['id']; ?>" style="cursor:pointer;" <?php if (!empty($_POST['quiz_subject_id']) && ($_POST['quiz_subject_id'] == $row['id'])){?> class="active" <?php }elseif($row['id'] == 1 && empty($_POST['quiz_subject_id'])){?> class="active" <?php } else{?> <?php } ?>><?php echo $row['quiz_subject']; ?></li>
            <?php } }?>
        </ul>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="quiz-container">
            
            <?php if ($_POST['question_index'] == 9) {?>
                <div class="question">Your Total Score is <?php echo $_SESSION['score']; ?> out of 10</div>
            <?php } else {?>
                <h1>Quiz Question</h1>
                <form action="quizformview.php" method="POST">
                <input type="hidden" name="quiz_subject_id" value="<?php echo $_SESSION['quiz_subject_id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="question_index" value="<?php echo $_SESSION['question_index']; ?>">
                <input type="hidden" name="score" value="<?php echo $_SESSION['score']; ?>">
                    <?php if ($quizquesanswers->num_rows > 0) { 
                        while($row = $quizquesanswers->fetch_assoc()) { ?>
                            <input type="hidden" name="correct_answer" value="<?php echo $row['correct_answer']; ?>">
                            <div class="question">
                            <?php echo $row['question']; ?>
                            </div>
                            <div class="answers">
                                <label>
                                    <input type="radio" name="answer" value="<?php echo $row['option_a']; ?>" required> <?php echo $row['option_a']; ?>
                                </label>
                                <label>
                                    <input type="radio" name="answer" value="<?php echo $row['option_b']; ?>"> <?php echo $row['option_b']; ?>
                                </label>
                                <label>
                                    <input type="radio" name="answer" value="<?php echo $row['option_c']; ?>"> <?php echo $row['option_c']; ?>
                                </label>
                                <label>
                                    <input type="radio" name="answer" value="<?php echo $row['option_d']; ?>"> <?php echo $row['option_d']; ?>
                                </label>
                            </div>
                    <?php } }?>
                    <button type="submit">Submit</button>
                </form>
            <?php } ?>
        </div>
    </div>

</body>

<form method="post" action="quizformview.php" id="quiz_topic_form">
    <input type="hidden" value="1" name="quiz_subject_id" id="quiz_subject_id">
</form>
</html>
<script>
    $(document).ready(function() {
        $('li').click(function() {
            var dataValue = $(this).data('value');
            $('#quiz_subject_id').val(dataValue);
            $('#quiz_topic_form').submit();
        });
    });
   
        
</script>
