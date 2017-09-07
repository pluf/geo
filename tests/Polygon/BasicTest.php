<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
use PHPUnit\Framework\TestCase;

require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PolygonBasicTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function createDataBase ()
    {
        Pluf::start(
                array(
                        'test' => false,
                        'timezone' => 'Europe/Berlin',
                        'debug' => true,
                        'installed_apps' => array(
                                'Pluf', 'Geo'
                        ),
                        'tmp_folder' => dirname(__FILE__) . '/../tmp',
                        'templates_folder' => array(
                                dirname(__FILE__) . '/../templates'
                        ),
                        'pluf_use_rowpermission' => true,
                        'mimetype' => 'text/html',
                        'app_views' => dirname(__FILE__) . '/views.php',
                        'db_login' => 'root',
                        'db_password' => '',
                        'db_server' => 'localhost',
                        'db_database' => 'test',
                        'app_base' => '/testapp',
                        'url_format' => 'simple',
                        'db_table_prefix' => 'geo_unit_tests_',
                        'db_version' => '5.0',
                        'db_engine' => 'MySQL',
                        'bank_debug' => true,
                        'orm.typecasts' => array(
                                'Geo_DB_Field_Polygon' => array(
                                        'Geo_DB_GeometryFromDb',
                                        'Geo_DB_PolygonToDb'
                                ),
                                'Geo_DB_Field_Geometry' => array(
                                        'Geo_DB_GeometryFromDb',
                                        'Geo_DB_GeometryToDb'
                                ),
                                'Geo_DB_Field_Point' => array(
                                        'Geo_DB_GeometryFromDb',
                                        'Geo_DB_PointToDb'
                                )
                        )
                ));
        
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
                'Geo_Polygon'
        );
        foreach ($models as $model) {
            $schema->model = Pluf::factory($model);
            $schema->dropTables();
            if (true !== ($res = $schema->createTables())) {
                throw new Exception($res);
            }
        }
    }

    /**
     * @afterClass
     */
    public static function removeDatabses ()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
                'Geo_Polygon'
        );
        foreach ($models as $model) {
            $schema->model = Pluf::factory($model);
            $schema->dropTables();
        }
    }

    /**
     * @test
     */
    public function testClassInstance ()
    {
        $p = new Geo_Polygon();
        $this->assertTrue(isset($p));
    }

    /**
     * @test
     */
    public function testStore ()
    {
        $p = new Geo_Polygon();
        $p->polygon= 'POLYGON((0 0, 10 0, 0 10, 0 0))';
        $this->assertTrue(isset($p));
        $this->assertTrue($p->create());
        $this->assertFalse($p->isAnonymous());
        
        $p2 = Pluf::factory('Geo_Polygon', $p->id);
        $this->assertTrue(isset($p2));
        $this->assertFalse($p2->isAnonymous());
        
        $this->assertTrue(strrpos($p2->polygon, "POLYGON") !== FALSE);
    }
}

