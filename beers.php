<?php

require 'functions.php';
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
            <h1>A world of beer</h1>
            <h3>(starting in the UK)</h3>
            <?php if (!$formVisibility) { ?>
                <form id='addBeerButton' method='post'>
                    <input type='submit' name= 'addBeer' value='Add a beer'>
                </form>
            <?php } ?>
        </header>
        <?php if ($formVisibility) { ?>
            <section class='addBeerPage'>
                <h1>Add a new beer</h1>
                <form id='addBeerForm' method='post'>
                    <label>Name <input type='text' name='beer'></label>
                    <label>Brewery <select name='brewery'>
                        <option name='default' selected='selected'>Please choose</option>
                        <?php foreach ($breweries as $brewery): ?>
                            <option>
                                <?php echo $brewery['brewery']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select></label>
                    <label>Style <input type='text' name='style'></label>
                    <label>ABV <input type='number' name='abv'></label>
                    <label>Photo <input type='file' name='photo'></label>
                    <div class='buttons'>
                        <input type='submit' name='cancel' value='Cancel'>
                        <input type='submit' name='save' value='Save'>
                    </div>
                </form>
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