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
                    <button type='submit' name='addBeer'>Add a beer</button>
                </form>
            <?php } ?>
        </header>
        <?php if ($formVisibility) { ?>
            <section class='addBeerPage'>
                <div class='addBeerContainer'>
                    <h1>Add a new beer</h1>
                    <form id='addBeerForm' method='post'>
                        <label>Name <input type='text' name='beer'></label>
                        <label>Brewery <select name='brewery'>
                            <option value='' disabled selected hidden>
                                Select brewery
                            </option>
                            <?php foreach ($breweries as $brewery): ?>
                                <option value='<?php echo $brewery['id']; ?>'>
                                    <?php echo $brewery['brewery']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select></label>
                        <label>Style <select name='style'>
                            <option name='' disabled selected hidden>
                                Select style
                            </option>
                            <?php foreach ($styles as $style): ?>
                                <option>
                                    <?php echo $style['style']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select></label>
                        <label>ABV <input type='number' min='0' step='any' name='abv'></label>
                        <div class='formButtons'>
                            <button type='submit' name='cancel'>Cancel</button>
                            <button type='submit' name='save'>Save</button>
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