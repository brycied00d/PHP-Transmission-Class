#!/usr/bin/php
<?php

require_once( dirname( __FILE__ ) . '/class/TransmissionRPC.class.php' );

$test_torrent = "http://releases.ubuntu.com/10.04/ubuntu-10.04-desktop-i386.iso.torrent";

$rpc = new TransmissionRPC();
//$rpc->debug = true;

try
{
  $result = $rpc->add( $test_torrent, '/tmp' );
  $id = $result->arguments->torrent_added->id;
  print "ADD TORRENT TEST... [{$result->result}] (id=$id)\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

sleep( 2 );

try
{
  $result = $rpc->set( $id, array('uploadLimit' => 100) );
  print "SET TORRENT INFO TEST... [{$result->result}]\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

sleep( 2 );

try
{
  $result = $rpc->get( $id, array( 'uploadLimit' ) );
  print "GET TORRENT INFO TEST... [{$result->result}]\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

sleep( 2 );

$result2 = $result->arguments->torrents[0]->uploadLimit == 100 ? 'success' : 'failed';
print "VERIFY TORRENT INFO SET/GET... [{$result2}] (".$result->arguments->torrents[0]->uploadLimit.")\n";

try
{
  $result = $rpc->stop( $id );
  print "STOP TORRENT TEST... [{$result->result}]\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

sleep( 2 );

try
{
  $result = $rpc->verify( $id );
  print "VERIFY TORRENT TEST... [{$result->result}]\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

sleep( 10 );

try
{
  $result = $rpc->start( $id );
  print "START TORRENT TEST... [{$result->result}]\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

sleep( 2 );

try
{
  $result = $rpc->reannounce( $id );
  print "REANNOUNCE TORRENT TEST... [{$result->result}]\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

sleep( 2 );

try
{
  $result = $rpc->move( $id, '/tmp/torrent-test', true );
  print "MOVE TORRENT TEST... [{$result->result}]\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

sleep( 2 );

try
{
  $result = $rpc->remove( $id, false );
  print "REMOVE TORRENT TEST... [{$result->result}]\n";
} catch (Exception $e)
{
  die('Caught exception: ' . $e->getMessage() . PHP_EOL);
}

?>