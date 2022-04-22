<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToArray;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $product = new Product([
            "name" => $row["name"],
            "category_id" => $row["category_id"],
            "description" => $row["description"],
            "active" => $row["active"],
            "price" => $row["price"],
            "images" => "",
        ]);
        $spreadsheet = IOFactory::load(request()->file('products'));
        for ($i = $row["start_index"] - 1; $i < $row["end_index"]; $i++) {
            $drawing = $spreadsheet->getActiveSheet()->getDrawingCollection()[$i];
            $zipReader = fopen($drawing->getPath(), 'r');
            $imageContents = '';
            while (!feof($zipReader)) {
                $imageContents .= fread($zipReader, 1024);
            }
            fclose($zipReader);
            $myFileName = Str::random(40) . '.' . $drawing->getExtension();
            $product->images .= $myFileName . (($i == $row["end_index"] - 1) ? "" : ",");
            file_put_contents('images/' . $myFileName, $imageContents);
        }
        return $product;
    }
}
