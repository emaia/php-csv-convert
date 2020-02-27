<?php

require_once __DIR__ . '/vendor/autoload.php';

// Name of the first and second static columns
$data = array(['id', 'id_data']);

    $csv = new ParseCsv\Csv();
    $csv->encoding('UTF-8');
    $csv->delimiter = ";";

    // CSV file to import
    $csv->parse('data.csv');
    //var_dump($csv->data);

    // Add meta data as distinc columns in the header
    foreach ( $csv->data as $row ) {

        $keys = array_keys($row);
        $items = explode(',', $row[$keys[2]]);

        foreach ( $items as $item ) {
            if ( !in_array($item, $data[0]) ) {
                array_push($data[0], $item);
            }
        }

    }

    // Count the number of dynamic columns added minus static columns
    $numberOfColumns = sizeof($data[0]) - 2;

    foreach ( $csv->data as $row ) {

        $keys = array_keys($row);
        $arr = array(
            $row[$keys[0]],
            $row[$keys[1]]
        );

        // Fill all meta columns of the new row with zero
        for ( $i=1; $i<=$numberOfColumns; $i++ ) {
            array_push($arr, '0');
        }

        $keys = array_keys($row);
        $items = explode(',', $row[$keys[2]]);

        // Update meta items that match with columns
        foreach ( $items as $item ) {
            $key = array_search( $item, $data[0] );
            if ( in_array($item, $data[0]) ) {
                $arr[$key] = '1';
            }
        }

        // Insert the updated row in main data array
        array_push($data, $arr);

    }

    //var_dump($data);

    // Send data to download
    $savecsv = new ParseCsv\Csv();
    $savecsv->output('download.csv', $data, false, ';');
