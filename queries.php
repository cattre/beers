<?php

// Returns all beers, any linked breweries, and any linked locations
$getBeers = '
    SELECT `beers`.`id` as `beer_id`, `beers`.`name` AS `beer`, `abv`, `style_id`, `styles`.`style`, `brewery_id`, `breweries`.`name`AS `brewery`, `url`, `county`, `country`, `image`, `protected`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`
        LEFT JOIN `styles`
        ON `beers`.`style_id` = `styles`.`id`;
';

$getBreweries = '
    SELECT `id`, `name` as `brewery`
    FROM `breweries`
    ORDER BY `name` ASC;
';

$getStyles = '
    SELECT `id`, `style`
    FROM `styles`
    ORDER BY `style` ASC;
';

$addBeer = '
    INSERT INTO `beers` (`name`, `brewery_id`, `style_id`, `abv`, `image`)
    VALUES (:beer, :brewery, :beerstyle, :abv, :photo);
';

$getLocations = '
    SELECT DISTINCT `county`, `country`, `id`
    FROM `locations`
    ORDER BY `county` ASC;
';

$addBrewery = '
    INSERT INTO `breweries` (`name`, `url`, `location_id`)
    VALUES (:brewery, :url, :location);
';

$deleteBeer = '
    DELETE FROM `beers`
    WHERE `id` = :id AND (`protected` IS null OR `protected` = 0);
';

$getBeer = '
    SELECT `beers`.`id` as `beer_id`, `beers`.`name` AS `beer`, `abv`, `style_id`, `styles`.`style`, `brewery_id`, `breweries`.`name`AS `brewery`, `url`, `county`, `country`, `image`, `protected`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`
        LEFT JOIN `styles`
        ON `beers`.`style_id` = `styles`.`id`
    WHERE `beers`.`id` = :id;
';

$updateBeer = '
    UPDATE `beers`
    SET
        `name` = :beer,
        `brewery_id` = :brewery,
        `style_id` = :beerstyle,
        `abv` = :abv,
        `image` = :photo
    WHERE `id` = :id AND (`protected` IS null OR `protected` = 0);
';

// Returns all beers, any linked breweries, and any linked locations, based on search terms
$search = '
    SELECT `beers`.`id` as `beer_id`, `beers`.`name` AS `beer`, `abv`, `style_id`, `styles`.`style`, `brewery_id`, `breweries`.`name`AS `brewery`, `url`, `county`, `country`, `image`, `protected`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`
        LEFT JOIN `styles`
        ON `beers`.`style_id` = `styles`.`id`
    WHERE `beers`.`name` LIKE :searchTerm
        OR `breweries`.`name` LIKE :searchTerm
        OR `style` LIKE :searchTerm
        OR `abv` LIKE :searchTerm
        OR `url` LIKE :searchTerm
        OR `county` LIKE :searchTerm
        OR `country` LIKE :searchTerm;
';