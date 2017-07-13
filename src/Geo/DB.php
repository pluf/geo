<?php


geoPHP::load();

function Geo_DB_PointFromDb ($val)
{
    
//     $oGeometry = geoPHP::load('0101000000000000000000f03f000000000000f03f','wkb', true);
//     $reducedGeom = $oGeometry->simplify(1.5);
//     $sWkt = $reducedGeom->out('wkt');
    
    $a = unpack('C*', $val);
    
    //'0101000000000000000000f03f000000000000f03f'
    //'0000000001010000000000000000000000000000000000F03F
    $hex_ary = array();

    for($i = 4 ; $i < 25; $i++) {
        $hex_ary[] = sprintf("%02X", $a[$i]);
    }
    $hex = implode('',$hex_ary);
    echo $hex;
    
    
    $wkb_reader = new WKB();
    $geometry = $wkb_reader->read($hex,TRUE);
    $wkt_writer = new WKT();
    $wkt = $wkt_writer->write(oGeometry);
    return $Wkt;
}

function Geo_DB_PointToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : (string) "PointFromText('".$val."')";
}