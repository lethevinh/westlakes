<?php
/**
 * The Template for the sidebar containing the main widget area
 *
 * @package  WordPress
 * @subpackage  Timber
 */

$context = array();
$context['widget'] = my_function_to_get_widget();
$context['ad'] = my_function_to_get_an_ad();
Timber::render('sidebar.twig', $context);
