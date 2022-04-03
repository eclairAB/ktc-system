<?php

namespace Database\Seeders;
use App\Models\ContainerDamage;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\Schema;
class DamageSeeder extends Seeder
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
        $csv = array_map('str_getcsv',file(base_path('/public/damage.csv')));

        $damage_columns = Schema::getColumnListing('container_damages');

        $keys=[];

        for($i=0;$i<count($csv);$i++){
            $damage = [];
            for ($j=0;$j<count($csv[$i]);$j++){
                if($i==0)
                    $keys[].=$csv[$i][$j];
                else
                {
                    $col = $keys[$j];
                    if(in_array($col,$damage_columns))
                    {
                        $damage = array_merge($damage, array($col => $csv[$i][$j]));
                    }
                }
            }

            if($i != 0){
                $damage['created_at'] = $date;
                $damage['updated_at'] = $date;
                ContainerDamage::insert($damage);
            }
        }

        if($i!=0)
            echo "There are ".($i-1)." records inserted.\n";
    
    }
}
