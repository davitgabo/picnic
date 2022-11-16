<?php

namespace App\Http\Controllers;

use App\Exports\CsvToExcelExport;
use App\Exports\SegmentExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function convert()
    {
        $stream = fopen("csv/resource.csv",'r');
        $results = [];
        while(($data = fgetcsv($stream)) !== false){
            $results[$data[2]]['count'][$data[3]] = $data[4];
            $results[$data[2]]['percentage'][$data[3]] = $data[5];
        }
        array_shift($results);
        return $results;
    }

    public function getSegmentDescriptions($segment)
    {
        $stream = fopen("csv/resource.csv",'r');
        $results = [];
        while(($data = fgetcsv($stream)) !== false){
            if ($data[1]==$segment){
                $results[] = $data[2];
            }
        }
        $response = array_values(array_unique($results));
        return $response;
    }

    public function multiKeyExists( $keys, $array)
    {
        if (empty($keys)){
            return true;
        } elseif (array_key_exists($keys[0],$array)){
            $key = array_shift($keys);
            return $this->multiKeyExists($keys, $array[$key]);
        }else {
            return false;
        }
    }

    public function countBySegment()
    {
        $stream = fopen("csv/resource.csv",'r');
        $results = [];
        while(($data = fgetcsv($stream)) !== false){

            if(!$this->multiKeyExists([$data[1],'count',$data[3]],$results)){
                $results[$data[1]]['count'][$data[3]] = $data[4];
            } else {
                $results[$data[1]]['count'][$data[3]] += $data[4];
            }

            if(!$this->multiKeyExists([$data[1],'percentage',$data[3],],$results)){
                $results[$data[1]]['percentage'][$data[3]] = $data[5];
            } else {
            $results[$data[1]]['percentage'][$data[3]] += $data[5];
            }
        }
        array_shift($results);
        return $results;
    }

    public function index()
    {
        //array from csv
        $results = array_keys($this->countBySegment());
        //return blade view
        return view('welcome',['results' => $results]);
    }

    public function export(Request $request)
    {
        $segment = $request->validate(['segmentDesc'=> 'required'])['segmentDesc'];

        $data = $this->convert();
        $data = collect($data[$segment]);
        return Excel::download(new CsvToExcelExport($segment, $data), 'resource.xlsx');
    }

    public function segmentExport()
    {
        $data = collect($this->countBySegment());

        return Excel::download(new SegmentExport($data), 'resource.xlsx');
    }

    public function segmentDescriptions(Request $request)
    {
        $segment = $request->get('segment');
        $segmentDescriptions = $this->getSegmentDescriptions($segment);
        return json_encode($segmentDescriptions);
    }
}
