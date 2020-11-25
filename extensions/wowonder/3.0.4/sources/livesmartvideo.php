<?php
if ($wo['loggedin'] == false || !$wo['config']['livesmart_url']) {
  header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
  exit();
}


$wo['description'] = $wo['config']['siteDesc'];
$wo['keywords']    = $wo['config']['siteKeywords'];
$wo['page']        = 'livesmartvideo';
$wo['title']       = $wo['config']['siteTitle'];
$wo['content']     = Wo_LoadPage('livesmartvideo/content');