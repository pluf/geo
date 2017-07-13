<?php
geoPHP::load();

/**
 *
 * @param unknown $val            
 * @return string
 */
function Geo_DB_GeometryFromDb ($val)
{
    /*
     * maso, 1395: convert $val (from BLOB) to WKT
     *
     * 1- SRID
     * 2- WKB
     *
     * See:
     * https://dev.mysql.com/doc/refman/5.7/en/gis-data-formats.html#gis-internal-format
     */
    $data = unpack("lsrid/H*wkb", $val);
    $wkb_reader = new WKB();
    $geometry = $wkb_reader->read($data['wkb'], TRUE);
    $wkt_writer = new WKT();
    $wkt = $wkt_writer->write($geometry);
    return $wkt;
}

/**
 *
 * @param unknown $val            
 * @param unknown $db            
 * @return string
 */
function Geo_DB_PointToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : (string) "PointFromText('" . $val . "')";
}

/**
 * Convert text to geometry
 *
 * @param unknown $val            
 * @param unknown $db            
 * @return string
 */
function Geo_DB_PolygonToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : (string) "GeometryFromText('" . $val . "')";
}

/**
 * Convert text to geometry
 *
 * @param unknown $val
 * @param unknown $db
 * @return string
 */
function Geo_DB_GeometryToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : (string) "GeometryFromText('" . $val . "')";
}


