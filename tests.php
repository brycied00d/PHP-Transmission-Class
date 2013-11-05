#!/usr/bin/env php
<?php

require_once( dirname( __FILE__ ) . '/class/TransmissionRPC.class.php' );

$test_torrent = "http://www.slackware.com/torrents/slackware64-13.1-install-dvd.torrent";

$rpc = new TransmissionRPC();
// A more complex example
//$rpc = new TransmissionRPC('http://somehost:9091/transmission/rpc', 'testuser', 'testpassword');
//$rpc->debug = true;

try
{
  $result = $rpc->sstats( );
  print "GET SESSION STATS... [{$result->result}]\n";

  sleep( 2 );

  $result = $rpc->add( $test_torrent, '/tmp' );
  $id = $result->arguments->torrent_added->id;
  print "ADD TORRENT TEST... [{$result->result}] (id=$id)\n";

  sleep( 2 );

  $result = $rpc->set( $id, array('uploadLimit' => 10) );
  print "SET TORRENT INFO TEST... [{$result->result}]\n";

  sleep( 2 );

  $rpc->return_as_array = true;
  $result = $rpc->get( $id, array( 'uploadLimit' ) );
  print "GET TORRENT INFO AS ARRAY TEST... [{$result['result']}]\n";
  $rpc->return_as_array = false;

  sleep( 2 );

  $result = $rpc->get( $id, array( 'uploadLimit' ) );
  print "GET TORRENT INFO AS OBJECT TEST... [{$result->result}]\n";
  
  sleep( 2 );
  
  $result2 = $result->arguments->torrents[0]->uploadLimit == 10 ? 'success' : 'failed';
  print "VERIFY TORRENT INFO SET/GET... [{$result2}] (".$result->arguments->torrents[0]->uploadLimit.")\n";

  $result = $rpc->stop( $id );
  print "STOP TORRENT TEST... [{$result->result}]\n";
  sleep( 2 );

  $result = $rpc->verify( $id );
  print "VERIFY TORRENT TEST... [{$result->result}]\n";

  sleep( 10 );

  $result = $rpc->start( $id );
  print "START TORRENT TEST... [{$result->result}]\n";

  sleep( 2 );

  $result = $rpc->reannounce( $id );
  print "REANNOUNCE TORRENT TEST... [{$result->result}]\n";

  sleep( 2 );

  $result = $rpc->move( $id, '/tmp/torrent-test', true );
  print "MOVE TORRENT TEST... [{$result->result}]\n";

  sleep( 2 );

  $result = $rpc->remove( $id, false );
  print "REMOVE TORRENT TEST... [{$result->result}]\n";
  
} catch (Exception $e) {
  die('[ERROR] ' . $e->getMessage() . PHP_EOL);
}

?>
