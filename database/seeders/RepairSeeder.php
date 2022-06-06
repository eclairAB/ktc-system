<?php

namespace Database\Seeders;
use App\Models\ContainerRepair;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\Schema;
class RepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $date = date('Y-m-d H:i:s');
        $csv = array_map('str_getcsv',file(base_path('/public/repair.csv')));

        $repair_columns = Schema::getColumnListing('container_repairs');

        $keys=[];

        for($i=0;$i<count($csv);$i++){
            $repair = [];
            for ($j=0;$j<count($csv[$i]);$j++){
                if($i==0)
                    $keys[].=$csv[$i][$j];
                else
                {
                    $col = $keys[$j];
                    if(in_array($col,$repair_columns))
                    {
                        $repair = array_merge($repair, array($col => $csv[$i][$j]));
                    }
                }
            }

            if($i != 0){
                $repair['created_at'] = $date;
                $repair['updated_at'] = $date;
                ContainerRepair::insert($repair);
            }
        }

        if($i!=0)
            echo "There are ".($i-1)." records inserted.\n";
    
    }
}
