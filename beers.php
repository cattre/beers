<?php

require_once 'code.php';

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
            <a href='beers.php'>
                A world of beer
            </a>
            <h3>(starting in the UK)</h3>
            <?php if (!$beerFormVisibility) { ?>
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
                        <br><br>
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
                        <br><br>
                        <label>URL <input type='url' name='url'></label>
                        <br><br>
                        <div class='formButtons'>
                            <button type='submit' name='backOne' value='backOne'>Back</button>
                            <button type='submit' name='saveBrewery' value='saveBeer'>Save</button>
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
                        <label>Name (required)<input type='text' name='beer'></label>
                        <span class='error'><?php echo $nameError;?></span>
                        <br><br>
                        <label>Brewery <select name='brewery'>
                                <option value='' selected hidden>
                                    Select brewery
                                </option>
                                <?php foreach ($breweries as $brewery): ?>
                                    <option value='<?php echo $brewery['id']; ?>'>
                                        <?php echo $brewery['brewery']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></label>
                        <button type='submit' name='addBrewery' value='addBrewery'>Add new brewery</button>
                        <br><br>
                        <label>Style <select name='style'>
                                <option value='' selected hidden>
                                    Select style
                                </option>
                                <?php foreach ($styles as $style): ?>
                                    <option value='<?php echo $style['style']; ?>'>
                                        <?php echo $style['style']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select></label>
                        <br><br>
                        <label>ABV <input type='number' min='0' max='20' step='any' name='abv'></label>
                        <br><br>
                        <label>Photo <input type='file' name='photo'></label>
                        <br><br>
                        <div class='formButtons'>
                            <button type='submit' name='back' value='back'>Back to list</button>
                            <button type='submit' name='saveBeer' value='saveBeer'>Save</button>
                        </div>
                    </form>
                </div>
            </section>
        <?php } ?>
        <?php if ($mainVisibility) { ?>
        <main>
            <?php if (isset($letters)) {
                foreach ($letters as $letter): ?>
                    <section class='letter'>
                        <h1><?php echo $letter ?></h1>
                        <div class ='beers'>
                            <?php foreach ($beers as $beer):
                                // Create entry for beer within section if first letter matches
                                if ($beer['beer'][0] === $letter) {
                            ?>
                                <article class='beer'>
                                    <div class='summary'>
                                        <h2><?php echo $beer['beer']; ?></h2>
                                        <?php echo getSummary($beer)?>
                                        <br>
                                        <a target='_blank' href='<?php echo $beer['url'] ?>'>Visit website</a>
                                    </div>
                                    <img src='<?php echo $beer['image'] ?>' alt='Beer photo'>
                                    <details>
                                        <br>
                                        <?php echo "Style: {$beer['style']}"; ?>
                                        <br>
                                        <?php echo "ABV: {$beer['abv']}"; ?>
                                    </details>
                                </article>
                            <?php } endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; } ?>
		</main>
        <?php } ?>
		<footer>

		</footer>
	</body>
</html>