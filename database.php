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
  
    [2, "What is the powerhouse of the cell?", "Beginner", "Nucleus", 'Mitochondria', 'Ribosome', 'Golgi apparatus', 'Mitochondria'],
    [2, 'Which planet is known as the Red Planet?', 'Beginner', 'Earth', 'Venus', 'Mars', 'Jupiter', 'Mars'],
    [2, 'Who is known as the father of the computer?', 'Beginner', 'Alan Turing', 'Charles Babbage', 'John von Neumann', 'Bill Gates', 'Charles Babbage'],
    [2, 'What is the chemical symbol for gold?', 'Beginner', 'Au', 'Ag', 'Pb', 'Fe', 'Au'],
    [2, 'What does HTTP stand for?', 'Intermediate', 'HyperText Transfer Protocol', 'HyperText Transmission Protocol', 'HighText Transfer Protocol', 'HyperTransfer Text Protocol', 'HyperText Transfer Protocol'],
    [2, 'Which planet has the most moons?', 'Intermediate', 'Earth', 'Mars', 'Jupiter', 'Saturn', 'Saturn'],
    [2, 'What does DNA stand for?', 'Intermediate', 'Deoxyribonucleic Acid', 'Deoxyribose Acid', 'Deoxyribonitric Acid', 'Deoxyribonecule Acid', 'Deoxyribonucleic Acid'],
    [2, "Who invented the World Wide Web?", 'Profession', 'Steve Jobs', 'Bill Gates', 'Tim Berners-Lee', 'Mark Zuckerberg', 'Tim Berners-Lee'],
    [2, 'What is the nearest star to Earth?', 'Intermediate', 'Proxima Centauri', 'Sirius', 'Alpha Centauri', 'Betelgeuse', 'Proxima Centauri'],
    [2, "Which gas is most abundant in the Earth's atmosphere?", 'Intermediate', 'Oxygen', 'Carbon Dioxide', 'Nitrogen', 'Hydrogen', 'Nitrogen'],

    [3, "Who painted the Mona Lisa?", "Beginner", "Vincent van Gogh", 'Pablo Picasso', 'Leonardo da Vinci', 'Claude Monet', 'Leonardo da Vinci'],
    [3, 'Which artist is known for the painting "The Starry Night"?', 'Beginner', 'Paul Cézanne', 'Edgar Degas', 'Vincent van Gogh', 'Henri Matisse', 'Vincent van Gogh'],
    [3, 'What is the art movement associated with Salvador Dalí?', 'Beginner', 'Impressionism', 'Surrealism', 'Cubism', 'Baroque', 'Surrealism'],
    [3, 'Which famous structure did the ancient Greeks dedicate to the goddess Athena?', 'Beginner', 'The Colosseum', 'The Pantheon', 'The Parthenon', 'The Acropolis', 'The Parthenon'],
    [3, 'In which museum is the famous painting "The Last Supper" located?', 'Intermediate', 'The Louvre', 'The Vatican Museums', 'The Uffizi Gallery', 'Santa Maria delle Grazie', 'Santa Maria delle Grazie'],
    [3, 'Who is known as the "Father of Modern Art"?', 'Intermediate', 'Pablo Picasso', 'Henri Matisse', 'Claude Monet', 'Paul Cézanne', 'Paul Cézanne'],
    [3, 'Which famous composer wrote the "Fifth Symphony"?', 'Intermediate', 'Johann Sebastian Bach', ' Wolfgang Amadeus Mozart', 'Ludwig van Beethoven', 'Franz Schubert', 'Ludwig van Beethoven'],
    [3, "What is the primary medium used in traditional Chinese painting?", 'Profession', 'Oil', 'Watercolor', 'Ink', 'Acrylic', 'Ink'],
    [3, 'Which artist is known for his Blue and Rose Periods?', 'Intermediate', 'Paul Gauguin', 'Pablo Picasso', 'Wassily Kandinsky', 'Edvard Munch', 'Pablo Picasso'],
    [3, 'Who composed the opera "The Magic Flute"?', 'Intermediate', 'Richard Wagner', 'Giuseppe Verdi', 'Wolfgang Amadeus Mozart', ' Giacomo Puccini', 'Wolfgang Amadeus Mozart'],

    [4, "Who was the first President of the United States?", "Beginner", "Thomas Jefferson", 'John Adams', 'George Washington', 'James Madison', 'George Washington'],
    [4, 'Which political philosophy advocates for a classless society?', 'Beginner', 'Capitalism', 'Socialism', 'Communism', 'Conservatism', 'Communism'],
    [4, 'Who is known as the principal author of the Declaration of Independence?', 'Beginner', 'Benjamin Franklin', 'John Adams', 'Thomas Jefferson', 'Alexander Hamilton', 'Thomas Jefferson'],
    [4, 'Which British Prime Minister declared war on Germany in 1939?', 'Beginner', 'Neville Chamberlain', 'Winston Churchill', 'Clement Attlee', 'Anthony Eden', 'Neville Chamberlain'],
    [4, 'What does NATO stand for?', 'Intermediate', 'North American Treaty Organization', 'National Atlantic Treaty Organization', 'North Atlantic Treaty Organization', 'National Association of Treaty Organizations', 'North Atlantic Treaty Organization'],
    [4, 'Who was the first female Prime Minister of the United Kingdom?', 'Intermediate', 'Margaret Thatcher', 'Theresa May', 'Angela Merkel', 'Golda Meir', 'Margaret Thatcher'],
    [4, 'What political event took place on July 14, 1789?', 'Intermediate', 'The signing of the Magna Carta', 'The storming of the Bastille', 'The Declaration of Independence', 'The start of World War I', 'The storming of the Bastille'],
    [4, "Who is known as the 'Father of the Constitution'?", 'Profession', 'George Washington', 'Benjamin Franklin', 'James Madison', 'Thomas Jefferson', 'James Madison'],
    [4, 'Which political party did Franklin D. Roosevelt belong to?', 'Intermediate', 'Republican', 'Democratic', 'Whig', 'Federalist', 'Democratic'],
    [4, 'What is the highest court in the United States?', 'Intermediate', 'The Court of Appeals', 'The District Court', 'The Supreme Court', 'The Circuit Court', 'The Supreme Court'],
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
