<?php
// Database configuration
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "quizapp"; 

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create a database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Database '$database' created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($database);

// create quiz topics table
$quiz_topics_sql = "CREATE TABLE IF NOT EXISTS quiz_topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_subject varchar(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// create quiz question & answers table
$quiz_question_answer_sql = "CREATE TABLE IF NOT EXISTS quiz_question_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_topic_id int NOT NULL,
    question TEXT NOT NULL,
    level varchar(50) NOT NULL,
    option_a varchar(255) NOT NULL,
    option_b varchar(255) NOT NULL,
    option_c varchar(255) NOT NULL,
    option_d varchar(255) NOT NULL,
    correct_answer varchar(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($quiz_topics_sql) === TRUE) {
    echo "Table 'quiz_topics' created successfully in database '$database'.";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($quiz_question_answer_sql) === TRUE) {
    echo "Table 'quiz_question_answers' created successfully in database '$database'.";
} else {
    echo "Error creating table: " . $conn->error;
}


// insert data in the quiz topics table

$truncate_quiz_topics_sql = "TRUNCATE TABLE quiz_topics;";
$conn->query($truncate_quiz_topics_sql);

$quiz_topics_insert_sql = "INSERT INTO quiz_topics (quiz_subject)
VALUES 
('Sports'),
('Science & Technology'),
('Arts'),
('Politics')";

if ($conn->query($quiz_topics_insert_sql) === TRUE) {
    echo "Records created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }


  // insert data in the quiz question and answers table for sports

$truncate_quiz_question_anaswer_sql = "TRUNCATE TABLE quiz_question_answers;";
$conn->query($truncate_quiz_question_anaswer_sql);



$data = [
    [1, "Which sport is known as 'The Beautiful Game'?", "Beginner", "Basketball", 'Tennis', 'Football (Soccer)', 'Baseball', 'Football (Soccer)'],
    [1, 'In tennis, what is a score of zero called?', 'Beginner', 'Love', 'Ace', 'Deuce', 'Net', 'Love'],
    [1, 'How many players are on a standard basketball team on the court at one time?', 'Beginner', '4', '5', '6', '7', '5'],
    [1, 'Which country hosts the Tour de France cycling race?', 'Beginner', 'Italy', 'Spain', 'France', 'Germany', 'France'],
    [1, 'In cricket, what is the term used when a bowler takes three wickets in three consecutive deliveries?', 'Intermediate', 'Maiden', 'Hat-trick', 'Century', 'Overthrow', 'Hat-trick'],
    [1, 'Which golf tournament is traditionally the first of the four major championships held each year?', 'Intermediate', 'The Open Championship', 'The PGA Championship', 'The U.S. Open', 'The Masters', 'The Masters'],
    [1, 'In soccer, what is the maximum number of substitutions a team is allowed to make in a regular match (without extra time)?', 'Intermediate', '2', '3', '4', '5', '5'],
    [1, "In baseball, what is the significance of a 'perfect game'?", 'Profession', 'A game where the pitcher strikes out 15 or more batters', 'A game where no player on the opposing team reaches base', 'A game where the team hits four or more home runs', 'A game with no errors made by the defensive team', 'A game where no player on the opposing team reaches base'],
    [1, 'In Formula 1, what is the term used for the aerodynamic effect that occurs when a car closely follows another car, gaining speed due to reduced air resistance?', 'Intermediate', 'Drafting', 'Slipstreaming', 'Downforce', 'Understeer', 'Slipstreaming'],
    [1, 'In rugby union, what is the maximum number of points a team can score with a try, conversion, and penalty kick in a single sequence?', 'Intermediate', '5', '7', '8', '10', '8'],
    
];


$stmt = $conn->prepare("INSERT INTO quiz_question_answers (quiz_topic_id, question, level, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

// Check if the preparation was successful
if ($stmt === false) {
    die("Failed to prepare statement: " . $conn->error);
}

// Loop through the data and bind parameters for each row
foreach ($data as $row) {
    [$quiz_topic_id, $question, $level, $option_a, $option_b, $option_c, $option_d, $correct_answer] = $row;
    $stmt->bind_param("ssssssss", $quiz_topic_id, $question, $level, $option_a, $option_b, $option_c, $option_d, $correct_answer);

    // Execute the query
    $stmt->execute();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
