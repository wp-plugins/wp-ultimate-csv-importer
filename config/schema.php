<?php
/**
 * Use the schema to generate database.sql file and the model files
 *
 * To create database.sql:
 *                          php skinnymvc.php generateSQL
 *
 *    database.sql will be stored in lib/skinnymvc/model/sql
 *
 * To create the model files:
 *                          php skinnymvc.php generateModel
 *
 *    model files will be stored in lib/skinnymvc/model
 *
 * Example schema code:
 * $model = array('table1'=>array(
 *                                 'field1'=>array('type'=>'int', 'null'=>false, 'special'=>'auto_increment'),
 *                                 'field2'=>'datetime',
 *                                 'field3'=>'varchar(255)',
 *                                 '_INDEXES'=>array('field3'),
 *                                 '_PRIMARY_KEY'=>array('field1'),
 *                               ),
 *                'table2'=>array(
 *                                 'field1'=>array('type'=>'int', 'null'=>false, 'special'=>'auto_increment'), //null is false by default
 *                                 'field2'=>'decimal(10,4)',
 *                                 'field3'=>'varchar(255)',
 *                                 '_INDEXES'=>array( array('field3','field4') ),
 *                                 '_PRIMARY_KEY'=>array('field1'),
 *                               ),
 *                'table3'=>array(
 *                                 'field1'=>array('type'=>'int', 'null'=>false, 'special'=>'auto_increment'),
 *                                 'field2'=>array('type'=>'varchar(255)', 'null'=>false),
 *                                 'field3'=>'text',
 *                                 'field4'=>'int',
 *                                 'field5'=>'int',
 *                                 '_UNIQUES'=>array( 'field2', array('field4','field5') ),
 *                                 '_FULLTEXT'=>array('field3'),
 *                                 '_PRIMARY_KEY'=>array('field1'),
 *                                 '_FOREIGN_KEYS'=>array('field4'=>array('table'=>'table1','field'=>'field1'), 'field5'=>array('table'=>'table2','field'=>'field1')),
 *                                 '_DATABASE_KEY'=>'db_key',
 *                                 '_TABLE_NAME'=>'table_name',
 *                               ),
 *                  );
 *
 */
