#!/usr/bin/php
<?php

// Include RPC class
require_once( dirname( __FILE__ ) . '/class/TransmissionRPC.class.php' );

// Define target folder (/tmp for demo)
$series_folder = '/tmp';

// Load thepiratebay series rss feed
$sxml = simplexml_load_file( 'http://rss.thepiratebay.org/205' );

// Define allowed formats (case insensitive)
$formats = array( 'xvid' );

// Define allowed shows (case insensitive)
$shows = array(
  "Blogstar.TV",
  "Ein Kaefig voller Helden"
);

// output..
print "Parsing " . count( $sxml->channel->item ) . " torrents..\n";

// Loop through RSS items and filter based on $formats and $shows
$downloads = array();
foreach ( $sxml->channel->item as $item ) {
  foreach ( $shows as $show ) {
    if ( stristr( $item->title, $show ) ) {      
      print "Found {$show} episode..\n";      
      foreach ( $formats as $format ) {
        if ( stristr( $item->link, $format ) ) {
          if ( !isset( $downloads[$show] ) ) $downloads[$show] = array();
          array_push( $downloads[$show], $item );
        }
      }
    }
  }
}

// Add torrents to Transmission
if ( count( $downloads ) > 0 ) {

  // create new transmission communication class
  $rpc = new TransmissionRPC();
  
  // Set authentication when needed
  //$rpc->username = 'test';
  //$rpc->password = 'test';
  
  // Loop through filtered results, add torrents and set download path to $series_folder/$show (e.g: /tmp/Futurama);
  foreach ( $downloads as $show => $episodes ) {
    foreach ( $episodes as $episode ) {
      $target = $series_folder . '/' . $show;
      print "Adding: {$episode->title}.. ";
      try
      {
        $result = $rpc->add( (string) $episode->link, $target ); // Magic happens here :)
        print "[{$result->result}]";
        print "\n";
      } catch (Exception $e)
      {
        die('Caught exception: ' . $e->getMessage() . PHP_EOL);
      }
    } 
  }
}

?>
