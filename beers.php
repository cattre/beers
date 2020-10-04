<?php

require 'code.php';

?>

<!DOCTYPE html>
<html lang='en'>
	<head>
		<title>A world of beers</title>
        <link href='https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Mansalva&display=swap' rel='stylesheet'>
        <link rel='stylesheet' type= 'text/css' href='normalize.css'>
		<link rel='stylesheet' type= 'text/css' href='styling.css'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<meta charset='UTF-8'>
	</head>
	<body>
        <div class='bg'></div>
		<header>
            <?php if ($mainVisibility) { ?>
                <form id='searchForm' method='post'>
                    <div class='searchBox'>
                        <label><input type='search' name='searchTerm' placeholder='Search by beer or brewery'></label>
                        <input type='submit' name='search' value='Search'>
                    </div>
                    <div class='filterText'>
                        <?php if ($_SESSION['searchTerm'] !== '') { echo 'Filtering by: ' . $_SESSION['searchTerm']; ?>
                            <input type='submit' name='reset' value='Clear'>
                        <?php } ?>
                    </div>
                </form>
            <?php } ?>
            <a href='beers.php'>
                A world of beer
            </a>
            <h3>(starting in the UK)</h3>
            <?php if ($mainVisibility) { ?>
                <form id='addBeerButton' method='post'>
                    <button type='submit' name='addBeer' value='addBeer'>Add a beer</button>
                </form>
            <?php } ?>
        </header>
        <?php if ($breweryFormVisibility) { ?>
            <section class='addPage'>
                <div class='addContainer'>
                    <h1>Add a new brewery</h1>
                    <form id='addForm' method='post' enctype='multipart/form-data' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'>
                        <label>Name (required)<input type='text' name='brewery'></label>
                        <span class='error'><?php echo $nameError;?></span>
                        <label>Location <select name='location'>
                                <option value='' selected hidden>
                                    Select location
                                </option>
                                <?php foreach ($locations as $location): ?>
                                    <option value='<?php echo $location['id']; ?>'>
                                        <?php echo $location['county'] . ', ' . $location['country']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></label>
                        <label>URL <input type='url' name='url'></label>
                        <div class='formButtons'>
                            <button type='submit' name='backOne' value='backOne'>Back</button>
                            <button type='submit' name='saveBrewery' value='saveBrewery'>Save</button>
                        </div>
                    </form>
                </div>
            </section>
        <?php } ?>
        <?php if ($beerFormVisibility) { ?>
            <section class='addPage'>
                <div class='addContainer'>
                    <h1>Add a new beer</h1>
                    <form id='addForm' method='post' enctype='multipart/form-data' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'>
                        <span class='error'><?php echo $imageError;?></span>
                        <label>Name (required)<input type='text' name='beer'></label>
                        <span class='error'><?php echo $nameError;?></span>
                        <label>Brewery <select name='brewery'>
                                <?php if (!isset($beer)) { ?>
                                    <option value='' selected hidden>
                                        Select brewery
                                    </option>
                                <?php } ?>
                                <?php foreach ($breweries as $brewery): ?>
                                    <option value='<?php echo $brewery['id']; ?>' >
                                        <?php echo $brewery['brewery']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></label>
                        <button type='submit' name='addBrewery' value='addBrewery'>Add new brewery</button>
                        <label>Style <select name='style'>
                                <option value='' selected hidden>
                                    Select style
                                </option>
                                <?php foreach ($styles as $style): ?>
                                    <option value='<?php echo $style['id']; ?>'>
                                        <?php echo $style['style']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></label>
                        <label>ABV <input type='number' min='0' max='20' step='any' name='abv'></label>
                        <label>Photo <input type='file' name='photo'></label>
                        <div class='formButtons'>
                            <button type='submit' name='back' value='back'>Back to list</button>
                            <button type='submit' name='saveBeer' value='saveBeer'>Save</button>
                        </div>
                    </form>
                </div>
            </section>
        <?php } ?>
        <?php if ($updateBeerFormVisibility) { ?>
            <section class='addPage'>
                <div class='addContainer'>
                    <h1>Update beer</h1>
                    <form id='addForm' method='post' enctype='multipart/form-data' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'>
                        <label hidden>ID <input type='number' name='id' value='<?php echo $beer['beer_id']; ?>'></label>
                        <label>Name (required)<input type='text' name='beer' value='<?php echo $beer['beer']; ?>'></label>
                        <span class='error'><?php echo $nameError;?></span>
                        <label>Brewery <select name='brewery'>
                                <?php if (!isset($beer)) { ?>
                                    <option value='' selected hidden>
                                        Select brewery
                                    </option>
                                <?php } ?>
                                <?php foreach ($breweries as $brewery): ?>
                                    <option value='<?php echo $brewery['id']; ?>' <?php if(isset($beer) && $beer['brewery_id'] === $brewery['id']) { echo 'selected'; } ?>>
                                        <?php echo $brewery['brewery']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></label>
                        <button type='submit' name='addBrewery' value='addBrewery'>Add new brewery</button>
                        <label>Style <select name='style'>
                                <option value='' selected hidden>
                                    Select style
                                </option>
                                <?php foreach ($styles as $style): ?>
                                    <option value='<?php echo $style['id']; ?>' <?php if(isset($beer) && $beer['style_id'] === $style['id']) { echo 'selected'; } ?>>
                                        <?php echo $style['style']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></label>
                        <label>ABV <input type='number' min='0' max='20' step='any' name='abv' value='<?php echo $beer['abv']; ?>'></label>
                        <label>Photo <input type='file' name='photo'></label>
                        <span class='error'><?php echo $imageError;?></span>
                        <div class='formButtons'>
                            <button type='submit' name='back' value='back'>Back to list</button>
                            <button type='submit' name='saveChanges' value='saveChanges'>Save changes</button>
                        </div>
                    </form>
                </div>
            </section>
        <?php } ?>
        <?php if ($mainVisibility) { ?>
        <main>
            <?php if (!isset($beers)) {
                echo '<h1>No beers found</h1>';
            } else {
                if (isset($letters)) {
                    foreach ($letters as $letter): ?>
                        <section class='letter'>
                            <h1><?php echo $letter ?></h1>
                            <div class ='beers'>
                                <?php foreach ($beers as $beer):
                                    // Create entry for beer within section if first letter matches
                                    if ($beer['beer'][0] === $letter) { ?>
                                        <article class='beer'>
                                            <div class='summary'>
                                                <h2><?php echo $beer['beer']; ?></h2>
                                                <?php echo getSummary($beer); ?>
                                                <br>
                                                <a target='_blank' href='<?php echo $beer['url']; ?>'>Visit website</a>
                                            </div>
                                            <img src='<?php echo $beer['image']; ?>' alt='Beer photo'>
                                            <details>
                                                <br>
                                                <?php echo "Style: {$beer['style']}"; ?>
                                                <br>
                                                <?php echo "ABV: {$beer['abv']}"; ?>
                                            </details>
                                            <?php if (!$beer['protected']) { ?>
                                            <form id='beerButtons' method='post'>
                                                <button type='submit' name='updateBeer' value='<?php echo $beer['beer_id']; ?>'>Update beer</button>
                                                <button type='submit' name='deleteBeer' value='<?php echo $beer['beer_id']; ?>'>Delete beer</button>
                                            </form>
                                            <?php } ?>
                                        </article>
                                    <?php }
                                endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach;
                }
            } ?>
		</main>
        <?php } ?>
		<footer>

		</footer>
	</body>
</html>