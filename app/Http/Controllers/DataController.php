<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SheetDB\SheetDB;



class DataController extends Controller
{
    public function index(){
        $data = array();
        return view('data.index', $data);
    }

    public function get_grafic(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $type = $request->type;

        $sheetdb = new SheetDB(env('SHEETDB_KEY'));
        

        if($type == ''){
            $response = $sheetdb->get(); 
        }else{
            $response = $sheetdb->search(['Tipe'=>$type]);
        }
        $filtered = array();

        

        foreach ($response as $rr) {
            unset($rr->Timestamp);
            $rr->Tanggal = date('Y-d-m',strtotime($rr->Tanggal));
            $rr->Action = '';
            
            if($start_date != ''){
                if(strtotime($rr->Tanggal) < strtotime($start_date)){
                    $rr->Action = 'Takeout';
                }
            }

            if($end_date != ''){
                if(strtotime($rr->Tanggal) > strtotime($end_date)){
                    $rr->Action = 'Takeout';
                }
            }

            if($type != ''){
                if($rr->Tipe != $type){
                    $rr->Action = 'Takeout';
                }
            }

            if($rr->Action == ''){ //yang tidak di-takeout, masukkan ke array
                $filtered[] = $rr;
            }
        }

        // usort($filtered, function ($a, $b) { //urutkan datanya dari terkecil ke terbesar bedasarkan tanggal
        //     if ($a->Tanggal == $b->Tanggal) return 0;
        //     return ($a->Tanggal < $b->Tanggal) ? -1 : 1;
        // });
        

        // $start_date = $this->get_smallest_date_fromjson($filtered, 'Tanggal');
        // $end_date = $this->get_largest_date_fromjson($filtered, 'Tanggal');
        
        $final_data = $this->fill_the_null_data($start_date,$end_date,'Tanggal',$filtered);
        echo json_encode($final_data);
    }

    public function get_smallest_date_fromjson($data, $field_name){
        $smallest_date = null;

        foreach ($data as $item) {
            $tanggal = date('Y-m-d',strtotime($item->$field_name));
            
            if (!$tanggal) {
                continue;
            }
            
            if ($smallest_date === null || $tanggal < $smallest_date) {
                $smallest_date = $tanggal;
            }

        }
        return $smallest_date;
    }

    public function get_largest_date_fromjson($data, $field_name){
        $largest_date = null;

        foreach ($data as $item) {
            $tanggal = date('Y-m-d',strtotime($item->$field_name));
            
            if (!$tanggal) {
                continue;
            }
            
            if ($largest_date === null || $tanggal > $largest_date) {
                $largest_date = $tanggal;
            }

        }
        return $largest_date;
    }

    public function fill_the_null_data($start_date, $end_date, $field_date, $array)
    {
        $generated_data = [];
                
        while ($start_date <= $end_date) {
            $array_temp = [];
            $is_found_pemasukkan = false;
            $is_found_pengeluaran = false;
            
            foreach($array as $ud){
                if($ud->Tanggal == $start_date){
                    if($ud->Tipe == 'Pengeluaran'){
                        $is_found_pengeluaran = true;
                    }
                    
                    if($ud->Tipe == 'Pemasukkan'){
                        $is_found_pemasukkan = true;
                    }
                    $generated_data[] = $ud;
                    
                    if($is_found_pemasukkan == true && $is_found_pengeluaran == true){
                        break;
                    }
                }
            }

            if($is_found_pemasukkan == true && $is_found_pengeluaran == true){
                unset($array_temp);
            }else{
                if($is_found_pemasukkan == true){
                    $array_temp = [
                        "Tanggal" => $start_date,
                        "Tipe" => 'Pengeluaran',
                        "Detail" => null,
                        "Nominal" => 0,
                        "Action" => null
                    ];
                    $generated_data[] = $array_temp;
                }elseif($is_found_pengeluaran == true){
                    $array_temp = [
                        "Tanggal" => $start_date,
                        "Tipe" => 'Pemasukkan',
                        "Detail" => null,
                        "Nominal" => 0,
                        "Action" => null
                    ];
                    $generated_data[] = $array_temp;
                }else{
                    $array_temp = [
                        "Tanggal" => $start_date,
                        "Tipe" => 'Pengeluaran',
                        "Detail" => null,
                        "Nominal" => 0,
                        "Action" => null
                    ];
                    $generated_data[] = $array_temp;

                    $array_temp = [
                        "Tanggal" => $start_date,
                        "Tipe" => 'Pemasukkan',
                        "Detail" => null,
                        "Nominal" => 0,
                        "Action" => null
                    ];
                    $generated_data[] = $array_temp;
                }
            }
            $start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));
        }
        return $generated_data;
    }
    
}
