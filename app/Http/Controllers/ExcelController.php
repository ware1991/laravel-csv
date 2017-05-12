<?php

namespace App\Http\Controllers;

use App\User;
use Excel;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    protected $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function export(){
        $users = $this->user->all();
        $cell_title = ['姓名','信箱'];
        $cellData = array();
        $cellData = array($cell_title);
        foreach ($users as $user => $info){
            $cellData = array( $user => [$info->name, $info->email]);
        }
        dd($cellData);

        Excel::create('使用者資料',function($excel) use ($cellData) {
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
//        $cellData = [
//            ['学号','姓名','成绩'],
//            ['10001','AAAAA','99'],
//            ['10002','BBBBB','92'],
//            ['10003','CCCCC','95'],
//            ['10004','DDDDD','89'],
//            ['10005','EEEEE','96'],
//        ];
//        Excel::create('学生成绩',function($excel) use ($cellData){
//            $excel->sheet('score', function($sheet) use ($cellData){
//                $sheet->rows($cellData);
//            });
//        })->export('xls');
    }
}
