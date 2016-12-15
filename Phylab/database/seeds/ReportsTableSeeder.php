<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Report;
class ReportsTableSeeder extends Seeder{
    public function run(){
        DB::table('reports')->delete();
        Report::create([
            "experiment_id" => 1010113,
            "experiment_name" =>"拉伸法测钢丝弹性模型扭摆法测定转动惯量",
            "prepare_link"  =>  "1011.pdf",
            "experiment_tag" => 1011
            ]);
        Report::create([
            "experiment_id" => 1010212,
            "experiment_name" =>"扭摆法测量转动惯量",
            "prepare_link"  =>  "1011.pdf",
            "experiment_tag" => 1011
            ]);
        Report::create([
            "experiment_id" => 1020113,
            "experiment_name" =>"测定冰的熔解热及电热法测量焦耳热的当量",
            "prepare_link"  =>  "1021.pdf",
            "experiment_tag" => 1021
            ]);
        Report::create([
            "experiment_id" => 1060111 ,
            "experiment_name" =>"物距像距法测量透镜焦距",
            "prepare_link"  =>  "1061.pdf",
            "experiment_tag" => 1061
            ]);
        Report::create([
            "experiment_id" => 1060213 ,
            "experiment_name" =>"自准直法测量透镜焦距",
            "prepare_link"  =>  "1061.pdf",
            "experiment_tag" => 1061
            ]);
        Report::create([
            "experiment_id" => 1070212,
            "experiment_name" =>"测量三棱镜的顶角",
            "prepare_link"  =>  "1071.pdf",
            "experiment_tag" => 1071
            ]);
        Report::create([
            "experiment_id" => 1070312,
            "experiment_name" =>"最小偏向角法测量棱镜的折射率",
            "prepare_link"  =>  "1071.pdf",
            "experiment_tag" => 1071
            ]);
        Report::create([
            "experiment_id" => 1070322,
            "experiment_name" =>"掠入射法测量棱镜的折射率",
            "prepare_link"  =>  "1071.pdf",
            "experiment_tag" => 1071
            ]);
        Report::create([
            "experiment_id" => 1080114 ,
            "experiment_name" =>"激光双棱镜干涉",
            "prepare_link"  =>  "1081.pdf",
            "experiment_tag" => 1081
            ]);
        Report::create([
            "experiment_id" => 1080124 ,
            "experiment_name" =>"激光劳埃镜干涉",
            "prepare_link"  =>  "1081.pdf",
            "experiment_tag" => 1081
            ]);
        Report::create([
            "experiment_id" => 1080215,
            "experiment_name" =>"钠光双棱镜干涉",
            "prepare_link"  =>  "1082.pdf",
            "experiment_tag" => 1082
            ]);
        Report::create([
            "experiment_id" => 1080225 ,
            "experiment_name" =>"钠光劳埃镜干涉",
            "prepare_link"  =>  "1082.pdf",
            "experiment_tag" => 1082
            ]);
        Report::create([
            "experiment_id" => 1090114  ,
            "experiment_name" =>"迈克尔逊干涉",
            "prepare_link"  =>  "1091.pdf",
            "experiment_tag" => 1091
            ]);   

    }
}
?>
