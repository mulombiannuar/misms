<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Plot\LinePlot;

class Graphs extends Model
{
    use HasFactory;
    public static function getStudentPeformanceGraph($student_id)
    {
        $score = new Score();
        $exams = $score->fetchStudentAnalysedExams($student_id, null);
        $ydata = array();
        $xdata = array();
        for ($s=0; $s <count($exams) ; $s++) 
        { 
            array_push($ydata, $exams[$s]->average_points);
            array_push($xdata, 'T'.$exams[$s]->term.'Y'.$exams[$s]->year);
        }
        // $ydata = [358, 465, 405, 316, 308, 289];
        // $xdata = ['One', 'Two', 'Three', 'Four', 'Five', 'Six'];

        $graph = new Graph(800, 200, 'auto');
        $graph->SetScale('intlin');
        $graph->SetMargin(40,20,20,20);
        $graph->title->Set('Student Performance Graph');
        $graph->subtitle->Set(date('D, d M Y'));
        //$graph->yaxis->title->Set('Peformance Points');
       // $graph->xaxis->title->Set('Exams Done');
        $graph->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->xaxis->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->xaxis->SetTickLabels($xdata);
        $lineplot= new LinePlot($ydata);
        $lineplot->mark->SetType(MARK_UTRIANGLE);
        $lineplot->mark->SetColor('blue');
        $lineplot->mark->SetFillColor('red');
        $graph->Add($lineplot);
        $img = $graph->Stroke(_IMG_HANDLER);
        ob_start();
        imagepng($img);
        $imageData = ob_get_contents();
        ob_end_clean();
        return $imageData;
    }

    public function getClassSubjectsPeformanceGraph($graphData)
    {
        //$ydata = [ 12, 11.67, 10.5, 9.4, 9, 8.67, 8.4, 8, 7.2, 7, 6 ];
        //$xdata = ["CST", "PHY", "HST", "ENG", "MAT", "BST", "KSW", "BIO", "CHE", "GEO", "AGR" ];

        $ydata = json_decode($graphData->mean_scores);
        $xdata = json_decode($graphData->subjects);

        $graph = new Graph(1000, 500, 'auto');
        $graph->SetScale('intlin');
        $graph->SetMargin(40,20,20,20);
        $graph->title->Set('Class Subjects Performance Graph');
        $graph->subtitle->Set(date('D, d M Y'));
        $graph->yaxis->title->Set('Subjects Points');
        $graph->xaxis->title->Set('Subjects');
        $graph->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->xaxis->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->xaxis->SetTickLabels($xdata);
        $lineplot= new LinePlot($ydata);
        $lineplot->mark->SetType(MARK_UTRIANGLE);
        $lineplot->mark->SetColor('blue');
        $lineplot->mark->SetFillColor('red');
        $graph->Add($lineplot);
        $img = $graph->Stroke(_IMG_HANDLER);
        ob_start();
        imagepng($img);
        $imageData = ob_get_contents();
        ob_end_clean();
        return $imageData;
    }
}
