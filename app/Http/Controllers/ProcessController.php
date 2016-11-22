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
            $cmd .= ("| grep " . $filter);
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


    /**
     * Kill process by pid
     *
     * @param \Illuminate\Http\Request $request
     * @return boolean $result
     */
    public function kill(Request $request) {

        $pid = $request->input('pid');
        if (empty($pid) || !is_numeric($pid)) {
            return response()
                ->json(['code' => -1, 'data' => "pid param error"]);
        }

        $cmd = "kill -9 {$pid} ";
        exec($cmd, $output, $return_val);
        $return_msg = array(
             '0' => 'kill process success',
             '1' => 'no such process'
        );
        return response()
            ->json(['code' => $return_val, 'data' => $return_msg[$return_val]]);
    }

}
