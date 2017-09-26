<?php

namespace App\Http\Controllers;

use App\User;
use Excel;
use Faker\Factory;
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

    /**
     * 匯入 Excel 檔
     *
     * @param Request $request
     */
    public function import(Request $request) {
        //取得檔案副檔名
        $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

        //判斷是否有來自於 import_file 的檔案，並只允許上傳 Excel 檔
        if ($request->hasFile('import_file') && ($extension === "xlsx" || $extension === "xls")) {
            $filePath = $request->file('import_file')->getRealPath();

            Excel::load($filePath, function ($reader) {
                $data = $reader->get(array('姓名', '信箱'));
                $faker = Factory::create();

                foreach ($data as $rows) {
                    $this->user->create([
                        'name'     => $rows->get("姓名"),
                        'email'    => $rows->get("信箱"),
                        'password' => $faker->bothify('##?##?##?')
                    ]);
                }
            });
        } else {
            abort(404);
        }
    }
}
