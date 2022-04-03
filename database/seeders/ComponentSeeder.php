<?php

namespace Database\Seeders;
use App\Models\ContainerComponent;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\Schema;
class ComponentSeeder extends Seeder
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
        $csv = array_map('str_getcsv',file(base_path('/public/component.csv')));

        $component_columns = Schema::getColumnListing('container_components');

        $keys=[];

        for($i=0;$i<count($csv);$i++){
            $component = [];
            for ($j=0;$j<count($csv[$i]);$j++){
                if($i==0)
                    $keys[].=$csv[$i][$j];
                else
                {
                    $col = $keys[$j];
                    if(in_array($col,$component_columns))
                    {
                        $component = array_merge($component, array($col => $csv[$i][$j]));
                    }
                }
            }

            if($i != 0){
                $component['created_at'] = $date;
                $component['updated_at'] = $date;
                ContainerComponent::insert($component);
            }
        }

        if($i!=0)
            echo "There are ".($i-1)." records inserted.\n";
    
    }
}
