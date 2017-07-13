<?php

geoPHP::load();

function Geo_DB_PointFromDb ($val)
{
    // XXX: maso, 1395: convert $val (from BLOB) to WKT  
    $wkb_reader = new WKB();
    $geometry = $wkb_reader->read('0101000000000000000000f03f000000000000f03f',TRUE);
    $wkt_writer = new WKT();
    $wkt = $wkt_writer->write($geometry);
    return $wkt;
}

function Geo_DB_PointToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : (string) "PointFromText('".$val."')";
}