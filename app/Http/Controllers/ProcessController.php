<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProcessController extends Controller
{

    public function __construct() {

    }

    /**
     * List all process
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        $filter = $request->input('grep', NULL);
        $grep = "";
        $cmd = "ps aux ";

        if (!empty($filter)) {
            $cmd .= ("|" . $filter);
        }
        exec($cmd, $output, $return_val);
        if ($return_val != 0) {
            return response()
                ->json(['code' => -1, 'data' => NULL]);
        }
        $header = explode(" ",
            preg_replace("/\s(?=\s)/","\\1",$output[0]) );

        $process_infos = array();
        for ($i = 1; $i < count($output); $i++) {
            $body = explode(" ",
               preg_replace("/\s(?=\s)/", "\\1", $output[$i]) );
            $process_item = array();
            for ($j = 0; $j < count($header); $j++) {
                $process_item[$header[$j]] = $body[$j];
            }
            $process_infos[] = $process_item;
        }
        return response()
            ->json(['code' => 0, 'data' => $process_infos]);
    }


}
