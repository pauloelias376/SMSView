<?php

class satisfactionSummary {

    function render($data, $ref) {

        $temp = 'temp/';
        $datastring = $data['get_all_question_props'];
        //konqord JSON is false becuse escape character on '
        $datastring = str_replace('\\\'', '\'', $datastring);
        $all_questions  = json_decode($datastring);
        //add graphic to docx
        $summary_docx = new CreateDocx();
//        $satisfactionPriorityScatter_docx->importStyles('./templates/otp-muis.docx', 'merge', array('Normal','ListParagraphPHPDOCX'));
        $summary_docx->importStyles('./templates/otp-muis.docx', 'merge', array('Normal', 'List Paragraph PHPDOCX'));


        $summary_docx->addText($category->groupname,array(
                    'sz' => 10,
                    'color' => 'F78E1E',
                    'b' => 'double',
                    'font' => 'Century Gothic'
        ));
        $paramsList = array(
            'val' => 0,
            'sz' => 10,
            'font' => 'Century Gothic',
        );

        $satisfactionSummary = array();
        foreach($all_questions as $question_number=>$question){
            $satisfactionArray = array(3,14,16,17,33,38,39,41,43,9823,46,47,9944,9948,9963,9973,9986); //TODO: id's>9000 are subject to change with live'
            if (in_array($question->{'id'}, $satisfactionArray)){
                $satisfactionSummary[] = 
                    $question->{'statistics'}->{'percentage'}->{3}->{'gte'}->{'peiling'} . '% van de ouders is tevreden over ' . html_entity_decode($question->{'short_description'},null, 'UTF-8').'.';
            }
        }
        foreach($all_questions as $question_number=>$question){
            if ($question->{'id'} == 65){
                $satisfactionSummary[] = 
                    $question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'peiling'}.' procent van de ouders ziet hun kind met plezier naar school gaan (landelijk is dit '.$question->{'statistics'}->{'percentage'}->{2}->{'gte'}->{'alle_scholen'}.' %)';
            }
        }
        $summary_docx -> addList($satisfactionSummary, $paramsList);

        $summary_docx -> createDocx($temp . 'satisfactionSummary');
        unset($summary_docx);
        return $temp . 'satisfactionSummary.docx';

    }


    function _error_dump($object){
        ob_start();
        //var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}
