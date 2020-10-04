<?php

/**
 * Creates connection to database
 *
 * @return object
 *               Database object
 */
function connectDB() :object {
    $db = new PDO ('mysql:host=db; dbname=beers', 'root', 'password');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}

/**
 * Queries database and returns results
 *
 * @param object $db
 *                  Database object
 * @param string $query
 *                     Query to be executed
 *
 * @return array
 *              Array of query results
 */
function queryDB(object $db, string $query) :array {
    $query = $db->prepare($query);
    $query->execute();

    return $query->fetchAll();
}

/**
 * Iterates through database items to get section letters
 *
 * @param array  $dbArray
 *                       Database array
 * @param string $value
 *                     Value to use for section letters
 *
 * @return array
 *              Array of section letters
 */
function getLetters(array $dbArray, string $value) :array {
    $letters = [];
    foreach ($dbArray as $item) {
        $letters[] = $item[$value][0];
    }
    asort($letters);
    return array_values(array_unique($letters));
}

/**
 * Builds beer summary based on what values are available
 *
 * @param array $beer
 *                   Single beer database entry (row)
 * @return string
 *               Summary text
 */
function getSummary (array $beer) :string {
    if (isset($beer['brewery']) && isset($beer['county']) && isset($beer['country'])) {
        return $beer['brewery'] . ' - ' . $beer['county'] . ', ' . $beer['country'];
    } else if (isset($beer['brewery']) && isset($beer['county']) && !isset($beer['country'])) {
        return $beer['brewery'] . ' - ' . $beer['county'];
    } else if (isset($beer['brewery']) && !isset($beer['county']) && isset($beer['country'])) {
        return $beer['brewery'] . ' - ' . $beer['country'];
    } else if (isset($beer['brewery']) && !isset($beer['county']) && !isset($beer['country'])) {
        return $beer['brewery'];
    } else {
        return '';
    }
}

/**
 * Adds new beer to database
 * Changes empty strings to null values where needed and sanitises user input
 *
 * @param object $db
 *                  Database object
 * @param string $queryString
 *                           Insert item query
 * @param string $imageFile
 *                         Destination image file
 */
function addBeer(object $db, string $queryString, string $imageFile) {
    $brewery = $_POST['brewery'] !== '' ? $_POST['brewery'] : null;
    $beerstyle = $_POST['style'] !== '' ? $_POST['style'] : null;
    $abv = $_POST['abv'] !== '' ? $_POST['abv'] : null;
    $photo = $imageFile !== '' ? $imageFile : 'media/placeholder.jpg';

    $query = $db->prepare($queryString);
    $query->execute([
        ':beer' => $_POST['beer'],
        ':brewery' => $brewery,
        ':beerstyle' => $beerstyle,
        ':abv' => $abv,
        ':photo' => $photo
    ]);
}

/**
 * Adds new brewery to database
 * Changes empty strings to null values where needed and sanitises user input
 *
 * @param object $db
 *                  Database object
 * @param string $queryString
 *                           Add brewery query
 */
function addBrewery(object $db, string $queryString) {
    $location = $_POST['location'] !== '' ? $_POST['location'] : null;
    $url = $_POST['url'] !== '' ? $_POST['url'] : null;

    $query = $db->prepare($queryString);
    $query->execute([
        ':brewery' => $_POST['brewery'],
        ':location' => $location,
        ':url' => $url,
    ]);
}

/**
 * Deletes selected beer
 *
 * @param object $db
 *                           Database object
 * @param string $queryString
 *                           Delete item query
 * @param string $id
 *                  Item id to filter query
 */
function deleteBeer(object $db, string $queryString, string $id) {
    $query = $db->prepare($queryString);
    $query->execute([
        ':id' => $id,
    ]);
}

/**
 * Retrieves single row from database for selected id
 *
 * @param object $db
 *                  Database object
 * @param string $query
 *                     Query to execute
 * @param string $id
 *                  Item id to filter query
 *
 * @return array
 *              Item array
 */
function getBeer(object $db, string $query, string $id) :array {
    $query = $db->prepare($query);
    $query->execute([':id' => $id]);

    return $query->fetch();
}

/**
 * Updates single item in database with new values
 *
 * @param object $db
 *                  Database object
 * @param string $query
 *                     Query to execute
 * @param string $imageFile
 *                         Destination image file
 * @param string $id
 *                  Item id to filter query
 */
function updateBeer(object $db, string $query, string $imageFile, string $id) {
    $brewery = $_POST['brewery'] !== '' ? $_POST['brewery'] : null;
    $beerstyle = $_POST['style'] !== '' ? $_POST['style'] : null;
    $abv = $_POST['abv'] !== '' ? $_POST['abv'] : null;
    $photo = $imageFile !== '' ? $imageFile : 'media/placeholder.jpg';

    $query = $db->prepare($query);
    $query->execute([
        ':id' => $id,
        ':beer' => $_POST['beer'],
        ':brewery' => $brewery,
        ':beerstyle' => $beerstyle,
        ':abv' => $abv,
        ':photo' => $photo
    ]);
}

/**
 * Executes search query using term provided
 *
 * @param object $db
 *                           Database object
 * @param string $queryString
 *                           Query to be executed
 * @param string $search
 *                           Search string
 *
 * @return
 *        Returns array of filtered items
 */
function search(object $db, string $queryString, string $search) {
    $query = $db->prepare($queryString);
    $query->execute([
        ':searchTerm' => '%' . $search . '%',
    ]);

    return $query->fetchAll();
}