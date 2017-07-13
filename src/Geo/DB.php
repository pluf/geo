<?php

geoPHP::load();

function Geo_DB_PointFromDb ($val)
{
    echo PHP_EOL;
    $v = unpack ("C*", $val);
    // XXX: maso, 1395: convert $val (from BLOB) to WKT  
    $wkb_reader = new WKB();
    $geometry = $wkb_reader->read('010100000000008099542fc8410000001027a0da41',TRUE);
    $wkt_writer = new WKT();
    $wkt = $wkt_writer->write($geometry);
    return $wkt;
}

function Geo_DB_PointToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : (string) "PointFromText('".$val."')";
}