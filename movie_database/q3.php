<?php 
session_start();

// require 'setup_database.php';


// Fetch distinct countries from the database
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar header h1 {
            margin: 0;
        }
        .menu a {
            color: white;
            text-decoration: none;
            padding: 10px;
            align-items: center;
        }
        .user-account form {
            display: inline;
        }
        .user-account input[type="submit"] {
            margin-left: 10px;
        }
        .search-container {
            padding: 20px;
            text-align: center;
        }
        input[type="text"] {
            padding: 10px;
            width: 50%;
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .results {
            padding: 20px;
            text-align: center;
        }
        

        .low {
        background-color: #008000; /* Choose your desired color */
        height: 20px;
        }
        .mid {
        background-color: #ffd700; /* Choose your desired color */
        height: 20px;
        }
        .high {
        background-color: #ff6347; /* Choose your desired color */
        height: 20px;
        }
        .relative-high {
            background-color: #ff9f4d; /* Color between high (#ff6347) and mid (#ffd700) */
            height: 20px;
            mix-blend-mode: multiply;
        }
        .relative-low {
            background-color: #80bf7e; /* Color between mid (#ffd700) and low (#008000) */
            height: 20px;
            mix-blend-mode: multiply;
        }
        .rating-bar {
            display: flex;
            height: 20px; /* Set your desired height */
            border: 1px solid #000; /* Add border for clarity */
        }

        .rating-bar div {
            height: 100%;
        }



    </style>
</head>
<body>

<div class="navbar">
    <header>
        <h1>Movie Search</h1>
    </header>
    <div class="menu">
        <a href="index.php">Home</a>
        <a href="search.php">Search</a>
        <a href="q3.php"><u>Q3</u></a>
        <a href="q4.php">Q4</a>
        <a href="q5.php">Q5</a>
        <a href="q6a.php">Personality Traits & Rating Correlation</a>
        <a href="q6b.php">Personality Traits & Genres Correlation</a>
    </div>
</div>

<div class="search-container">
    <form method="post">
        <h3>
            <!-- <input type="text" id="search" name="search" placeholder="Enter movie title..."> -->
            <input type="submit" name="MostRated" value="Most Rated">
            <input type="submit" name="HighestRated" value="Highest Rated">
            <input type="submit" name="MostPolarised" value="Most Polarised">
            <!-- SPACE --> &#160&#160&#160
            IMDB:
            <input type="submit" name="IMDBMost" value="Most Rated">
            <input type="submit" name="IMDBHighest" value="Highest Rated">
            <!-- SPACE --> &#160&#160&#160
            TMDB:
            <input type="submit" name="TMDB" value="Highest Popularity">
        </h3>
    </form>
</div>

<div class="results">
    <?php
    // Most Rated
    if (isset($_POST['MostRated'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';
        $result = mostRated($mysqli);
        $mysqli->close();

        if ($result) {
            // Start the table and optionally add a border for visibility
            echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
            echo "<style>\n";
            echo "body { font-family: Arial, sans-serif; }\n";
            echo ".container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }\n";
            echo "table { width: 100%; border-collapse: collapse; table-layout: fixed; }\n";
            echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }\n";
            echo "th { background-color: #0096FF; color: white; }\n";
            echo "tr:nth-child(even) { background-color: #f2f2f2 }\n";
            echo "tr:hover { background-color: #ddd; }\n";
            echo "a { color: #333; text-decoration: none; }\n";
            echo "a:hover { text-decoration: underline; }\n";
            echo "</style>\n";
            echo "<table>"; 
            echo "<table border='0'>";

            // Table headers
            echo "<tr> <th>Genre</th> <th>Number of Ratings</th> <th>High Ratings</th> <th>Moderate Ratings</th> <th>Low Ratings</th> </tr>";

            // Table contents
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['genreName'] . "</td>";
                echo "<td>" . $row['total_ratings'] . "</td>";
                echo '<td><div class="high" style="width: ' . $row['rating_high_percentage'] . '%;"></div>' . number_format(floatval($row['rating_high_percentage']), 2) . '%</td>';
                echo '<td><div class="mid" style="width: ' . $row['rating_mid_percentage'] . '%;"></div>' . number_format(floatval($row['rating_mid_percentage']), 2) . '%</td>';
                echo '<td><div class="low" style="width: ' . $row['rating_low_percentage'] . '%;"></div>' . number_format(floatval($row['rating_low_percentage']), 2) . '%</td>';
                // echo "<td>" . number_format(floatval($row['rating_high_percentage']), 2) . "%</td>";
                // echo "<td>" . number_format(floatval($row['rating_mid_percentage']), 2) . "%</td>";
                // echo "<td>" . number_format(floatval($row['rating_low_percentage']), 2) . "%</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            $result->free();
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    // Highest Rated
    if (isset($_POST['HighestRated'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';        
        $result = highestRated($mysqli);
        $mysqli->close();

        if ($result) {
            // Start the table and optionally add a border for visibility
            echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
            echo "<style>\n";
            echo "body { font-family: Arial, sans-serif; }\n";
            echo ".container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }\n";
            echo "table { width: 100%; border-collapse: collapse; table-layout: fixed; }\n";
            echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }\n";
            echo "th { background-color: #0096FF; color: white; }\n";
            echo "tr:nth-child(even) { background-color: #f2f2f2 }\n";
            echo "tr:hover { background-color: #ddd; }\n";
            echo "a { color: #333; text-decoration: none; }\n";
            echo "a:hover { text-decoration: underline; }\n";
            echo "</style>\n";
            echo "<table>"; 
            echo "<table border='0'>";

            // Table headers
            echo "<tr> <th>Genre</th> <th>Number of Ratings</th> <th>High Ratings</th> <th>Moderate Ratings</th> <th>Low Ratings</th> </tr>";

            // Table contents
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['genreName'] . "</td>";
                echo "<td>" . $row['total_ratings'] . "</td>";
                echo '<td><div class="high" style="width: ' . $row['rating_high_percentage'] . '%;"></div>' . number_format(floatval($row['rating_high_percentage']), 2) . '%</td>';
                echo '<td><div class="mid" style="width: ' . $row['rating_mid_percentage'] . '%;"></div>' . number_format(floatval($row['rating_mid_percentage']), 2) . '%</td>';
                echo '<td><div class="low" style="width: ' . $row['rating_low_percentage'] . '%;"></div>' . number_format(floatval($row['rating_low_percentage']), 2) . '%</td>';
                // echo "<td>" . number_format(floatval($row['rating_high_percentage']), 2) . "%</td>";
                // echo "<td>" . number_format(floatval($row['rating_mid_percentage']), 2) . "%</td>";
                // echo "<td>" . number_format(floatval($row['rating_low_percentage']), 2) . "%</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            $result->free();
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    // Highest Polarisation
    if (isset($_POST['MostPolarised'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';
        $result = highestPolarisation($mysqli);
        $mysqli->close();

        if ($result) {
            // Start the table and optionally add a border for visibility
            echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
            echo "<style>\n";
            echo "body { font-family: Arial, sans-serif; }\n";
            echo ".container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }\n";
            echo "table { width: 100%; border-collapse: collapse; table-layout: fixed; }\n";
            echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }\n";
            echo "th { background-color: #0096FF; color: white; }\n";
            echo "tr:nth-child(even) { background-color: #f2f2f2 }\n";
            echo "tr:hover { background-color: #ddd; }\n";
            echo "a { color: #333; text-decoration: none; }\n";
            echo "a:hover { text-decoration: underline; }\n";
            echo "</style>\n";
            echo "<table>"; 
            echo "<table border='0'>";

            // Table headers
            echo "<tr> <th>Genre</th> <th>Number of Ratings</th> <th>Polarisation Index</th> <th>Rating 5</th> <th>Rating 4</th> <th>Rating 3</th> <th>Rating 2</th> <th>Rating 1</th> </tr>";
            // echo "<tr> <th>Genre</th> <th>Number of Ratings</th> <th>Polarisation Index</th> <th>Rating</th> </tr>";

            // Table contents
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['genreName'] . "</td>";
                echo "<td>" . $row['total_ratings'] . "</td>";
                echo "<td>" . number_format(floatval($row['rating_index_normalized']), 3). "</td>";

                echo '<td><div class="high" style="width: ' . $row['rating_5'] . '%;"></div>' . number_format(floatval($row['rating_5']), 2) . '%</td>';
                echo '<td><div class="relative-high" style="width: ' . $row['rating_4'] . '%;"></div>' . number_format(floatval($row['rating_4']), 2) . '%</td>';
                echo '<td><div class="mid" style="width: ' . $row['rating_3'] . '%;"></div>' . number_format(floatval($row['rating_3']), 2) . '%</td>';
                echo '<td><div class="relative-low" style="width: ' . $row['rating_2'] . '%;"></div>' . number_format(floatval($row['rating_2']), 2) . '%</td>';
                echo '<td><div class="low" style="width: ' . $row['rating_1'] . '%;"></div>' . number_format(floatval($row['rating_1']), 2) . '%</td>';
                echo "</tr>";

                // echo '<td></td><td></td><td></td>';
                // echo '<td colspan="5"><div class="rating-bar">';
                // echo '<div class="high" style="width: ' . $row['rating_5'] . '%;"></div>';
                // echo '<div class="relative-high" style="width: ' . $row['rating_4'] . '%;"></div>';
                // echo '<div class="mid" style="width: ' . $row['rating_3'] . '%;"></div>';
                // echo '<div class="relative-low" style="width: ' . $row['rating_2'] . '%;"></div>';
                // echo '<div class="low" style="width: ' . $row['rating_1'] . '%;"></div>';
                // echo '</div></td>';

            }
            
            echo "</table>";
            $result->free();
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    // IMDB Most Rating
    if (isset($_POST['IMDBMost'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';
        $result = IMDBMost($mysqli);
        $mysqli->close();

        if ($result) {
            // Start the table and optionally add a border for visibility
            echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
            echo "<style>\n";
            echo "body { font-family: Arial, sans-serif; }\n";
            echo ".container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }\n";
            echo "table { width: 100%; border-collapse: collapse; table-layout: fixed; }\n";
            echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }\n";
            echo "th { background-color: #0096FF; color: white; }\n";
            echo "tr:nth-child(even) { background-color: #f2f2f2 }\n";
            echo "tr:hover { background-color: #ddd; }\n";
            echo "a { color: #333; text-decoration: none; }\n";
            echo "a:hover { text-decoration: underline; }\n";
            echo "</style>\n";
            echo "<table>"; 
            echo "<table border='0'>";

            // Table headers
            echo "<tr> <th>Genre</th> <th>Number of Movies</th> <th>Number of Ratings</th> <th>TMDB Popularity</th> </tr>";

            // Table contents
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['genreName'] . "</td>";
                echo "<td>" . $row['movie_count'] . "</td>";
                echo "<td>" . $row['number_ratings'] . "</td>";
                echo '<td><div class="high" style="width: ' . $row['avg_popularity']*10 . '%;"></div>' . round($row['avg_popularity'], 2) . '</td>';
                echo "</tr>";
            }
            
            echo "</table>";
            $result->free();
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    // IMDB Highest Rating
    if (isset($_POST['IMDBHighest'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';
        $result = IMDBHighest($mysqli);
        $mysqli->close();

        if ($result) {
            // Start the table and optionally add a border for visibility
            echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
            echo "<style>\n";
            echo "body { font-family: Arial, sans-serif; }\n";
            echo ".container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }\n";
            echo "table { width: 100%; border-collapse: collapse; table-layout: fixed; }\n";
            echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }\n";
            echo "th { background-color: #0096FF; color: white; }\n";
            echo "tr:nth-child(even) { background-color: #f2f2f2 }\n";
            echo "tr:hover { background-color: #ddd; }\n";
            echo "a { color: #333; text-decoration: none; }\n";
            echo "a:hover { text-decoration: underline; }\n";
            echo "</style>\n";
            echo "<table>"; 
            echo "<table border='0'>";

            // Table headers
            echo "<tr> <th>Genre</th> <th>Number of Movies</th> <th>Number of Ratings</th> <th>TMDB Popularity</th> </tr>";

            // Table contents
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['genreName'] . "</td>";
                echo "<td>" . $row['movie_count'] . "</td>";
                echo "<td>" . $row['number_ratings'] . "</td>";
                echo '<td><div class="high" style="width: ' . $row['avg_popularity']*10 . '%;"></div>' . round($row['avg_popularity'], 2) . '</td>';
                echo "</tr>";
            }
            
            echo "</table>";
            $result->free();
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    // TMDB Rating
    if (isset($_POST['TMDB'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';        
        $result = TMDB($mysqli);
        $mysqli->close();

        if ($result) {
            // Start the table and optionally add a border for visibility
            echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
            echo "<style>\n";
            echo "body { font-family: Arial, sans-serif; }\n";
            echo ".container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }\n";
            echo "table { width: 100%; border-collapse: collapse; table-layout: fixed; }\n";
            echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }\n";
            echo "th { background-color: #0096FF; color: white; }\n";
            echo "tr:nth-child(even) { background-color: #f2f2f2 }\n";
            echo "tr:hover { background-color: #ddd; }\n";
            echo "a { color: #333; text-decoration: none; }\n";
            echo "a:hover { text-decoration: underline; }\n";
            echo "</style>\n";
            echo "<table>"; 
            echo "<table border='0'>";

            // Table headers
            echo "<tr> <th>Genre</th> <th>Number of Movies</th> <th>TMDB Popularity</th> </tr>";

            // Table contents
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['genreName'] . "</td>";
                echo "<td>" . $row['movie_count'] . "</td>";
                echo '<td><div class="high" style="width: ' . $row['avg_popularity'] . '%;"></div>' . round($row['avg_popularity'], 2) . '</td>';
                echo "</tr>";
            }
            
            echo "</table>";
            $result->free();
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    ?>
</div>

</body>
</html>

<?php

function mostRated($mysqli) {
    // Base SQL query
    $sql = 
    "WITH RatingStats AS (
        SELECT 
            mg.genreID,
            COUNT(*) AS total_ratings,
            AVG(CASE WHEN r.rating < 2.5 THEN 1 ELSE 0 END) * 100 AS rating_low_percentage,
            AVG(CASE WHEN r.rating >= 2.5 AND r.rating <= 3.5 THEN 1 ELSE 0 END) * 100 AS rating_mid_percentage,
            AVG(CASE WHEN r.rating > 3.5 THEN 1 ELSE 0 END) * 100 AS rating_high_percentage
        FROM 
            -- ratings r
             ratings r
        INNER JOIN 
            movie_genre mg ON r.movieID = mg.movieID
        GROUP BY 
            mg.genreID
    )
    SELECT 
        g.genreName,
        rs.total_ratings,
        rs.rating_low_percentage,
        rs.rating_mid_percentage,
        rs.rating_high_percentage
    FROM 
        RatingStats rs
    INNER JOIN 
        genre g ON rs.genreID = g.genreID
    ORDER BY 
        rs.total_ratings DESC;";

    // Execute the query
    $result = $mysqli->query($sql);
    return $result ;
}

function highestRated($mysqli) {
    // Base SQL query
    $sql = 
    "WITH RatingStats AS (
        SELECT 
            mg.genreID,
            COUNT(*) AS total_ratings,
            AVG(CASE WHEN r.rating < 2.5 THEN 1 ELSE 0 END) * 100 AS rating_low_percentage,
            AVG(CASE WHEN r.rating >= 2.5 AND r.rating < 3.5 THEN 1 ELSE 0 END) * 100 AS rating_mid_percentage,
            AVG(CASE WHEN r.rating >= 3.5 THEN 1 ELSE 0 END) * 100 AS rating_high_percentage
        FROM 
            -- ratings r
             ratings r
        INNER JOIN 
            movie_genre mg ON r.movieID = mg.movieID
        GROUP BY 
            mg.genreID
    )
    SELECT 
        g.genreName,
        rs.total_ratings,
        rs.rating_low_percentage,
        rs.rating_mid_percentage,
        rs.rating_high_percentage
    FROM 
        RatingStats rs
    INNER JOIN 
        genre g ON rs.genreID = g.genreID
    ORDER BY 
        rs.rating_high_percentage DESC;";

    // Execute the query
    $result = $mysqli->query($sql);

    return $result ;

}

function highestPolarisation($mysqli) {
    // Base SQL query
    $sql = 
    "WITH RatingStats AS (
        SELECT 
            mg.genreID,
            COUNT(*) AS total_ratings,
            SUM(POWER(r.rating - 3, 2)) AS rating_index,
            -- AVG(CASE WHEN r.rating = 1 OR r.rating = 2 THEN 1 ELSE 0 END) * 100 AS rating_low_percentage,
            -- AVG(CASE WHEN r.rating = 3 THEN 1 ELSE 0 END) * 100 AS rating_mid_percentage,
            -- AVG(CASE WHEN r.rating = 4 OR r.rating = 5 THEN 1 ELSE 0 END) * 100 AS rating_high_percentage
            AVG(CASE WHEN r.rating = 1 OR r.rating = 0.5 THEN 1 ELSE 0 END) * 100 AS rating_1,
            AVG(CASE WHEN r.rating = 2 OR r.rating = 1.5 THEN 1 ELSE 0 END) * 100 AS rating_2,
            AVG(CASE WHEN r.rating = 3 OR r.rating = 2.5 THEN 1 ELSE 0 END) * 100 AS rating_3,
            AVG(CASE WHEN r.rating = 4 OR r.rating = 3.5 THEN 1 ELSE 0 END) * 100 AS rating_4,
            AVG(CASE WHEN r.rating = 5 OR r.rating = 4.5 THEN 1 ELSE 0 END) * 100 AS rating_5
        FROM 
            -- ratings r
             ratings r
        INNER JOIN 
            movie_genre mg ON r.movieID = mg.movieID
        GROUP BY 
            mg.genreID
    )
    SELECT 
        g.genreName,
        rs.total_ratings,
        rs.rating_index / rs.total_ratings AS rating_index_normalized,
        -- rs.rating_high_percentage,
        -- rs.rating_mid_percentage,
        -- rs.rating_low_percentage
        rating_1,
        rating_2,
        rating_3,
        rating_4,
        rating_5
    FROM 
        RatingStats rs
    INNER JOIN 
        genre g ON rs.genreID = g.genreID
    ORDER BY 
        rating_index_normalized DESC;";

    // Execute the query
    $result = $mysqli->query($sql);

    return $result ;

}

function IMDBHighest($mysqli) {
    // Base SQL query
    $sql = 
    "SELECT g.genreName, COUNT(mg.movieID) AS movie_count, AVG(m.imdb_rating) AS avg_popularity, SUM(m.imdb_rating_votes) AS number_ratings
    FROM movie_genre mg
    JOIN movies m ON mg.movieID = m.movieID
    JOIN genre g ON mg.genreID = g.genreID
    GROUP BY g.genreName
    ORDER BY avg_popularity DESC;
    ";

    // Execute the query
    $result = $mysqli->query($sql);
    return $result ;
}

function IMDBMost($mysqli) {
    // Base SQL query
    $sql = 
    "SELECT g.genreName, COUNT(mg.movieID) AS movie_count, AVG(m.imdb_rating) AS avg_popularity, SUM(m.imdb_rating_votes) AS number_ratings
    FROM movie_genre mg
    JOIN movies m ON mg.movieID = m.movieID
    JOIN genre g ON mg.genreID = g.genreID
    GROUP BY g.genreName
    ORDER BY number_ratings DESC;
    ";

    // Execute the query
    $result = $mysqli->query($sql);
    return $result ;
}

function TMDB($mysqli) {
    // Base SQL query
    $sql = 
    "SELECT g.genreName, COUNT(mg.movieID) AS movie_count, AVG(m.tmdb_popularity) AS avg_popularity
    FROM movie_genre mg
    JOIN movies m ON mg.movieID = m.movieID
    JOIN genre g ON mg.genreID = g.genreID
    GROUP BY g.genreName
    ORDER BY avg_popularity DESC;
    ";

    // Execute the query
    $result = $mysqli->query($sql);
    return $result ;
}
?>
