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
        return response()
            ->json(['code' => $return_val, 'data' => $output]);
    }


}
