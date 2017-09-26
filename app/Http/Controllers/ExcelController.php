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

    /**
     * 匯出檔案
     */
    public function export() {
        $users = $this->user->all();
        $filename = "使用者資料";
        $cellTitle = ['姓名', '信箱'];
        $cellData = array($cellTitle);
        foreach ($users as $key => $info) {
            $cellData[$key + 1] = [$info->name, $info->email];
        }

        Excel::create($filename, function ($excel) use ($cellData) {
            $tabName = date("Y-m-d");
            $excel->sheet($tabName, function ($sheet) use ($cellData) {
                $sheet->rows($cellData);

                //設定標題列樣式
                $sheet->row(1, function ($row) {
                    $row->setBackground('#fdcaa0');
                    $row->setFont(array('family' => 'Calibri', 'size' => '12', 'bold' => true));
                });
            });
        })->export('xlsx');
    }
}
