#!/usr/bin/php
<?php

require_once( dirname( __FILE__ ) . '/class/Transmission.class.php' );

$test_torrent = "http://releases.ubuntu.com/10.04/ubuntu-10.04-desktop-i386.iso.torrent";

$rpc = new Transmission();

$result = $rpc->add( $test_torrent, '/tmp' );
print "ADD TORRENT TEST... [{$result->result}]\n";

$id = $result->arguments->torrent_added->id;

sleep( 2 );

$result = $rpc->set( $id, array('uploadLimit', 100) );
print "SET TORRENT INFO TEST... [{$result->result}]\n";

sleep( 2 );

$result = $rpc->get( $id, array( 'uploadLimit' ) );
print "GET TORRENT INFO TEST... [{$result->result}]\n";

sleep( 2 );

$result = $result->arguments->torrents[0]->uploadLimit == 100 ? 'success' : 'failed';
print "VERIFY TORRENT INFO SET/GET... [{$result}]\n";

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


