<?php
    /*************************************************

        �p���@�@����������

        $exam_id�G��e�n�p�⪺�@����

    *************************************************/


    function Score_calculate_one_exam($exam_id){

        //01.��Ʈw�s�u
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php');
        $link = create_connection() ;


        //02.�ŧi���ܼ�
        $exam_ans_temp = Array(); //�q��Ʈw���X ���ר�����(�Ȧs)
        $exam_ans      = Array(); //��z�n��     ���ר�����

        $type_explain_key = Array('A','B','C','D','E','F','G','H','I'); //�H��E�������N��

        //�̷ӵ��ר��A�s��p����������
        $exam_ans_type = Array( 'A'=> 0 , 'B'=> 0 , 'C'=> 0 , 'D'=> 0 , 'E'=> 0 ,
                                'F'=> 0 , 'G'=> 0 , 'H'=> 0 , 'I'=> 0 );

        $temp = "" ; //�Ȧs�r��


        //03.���X���D���@��������
        $sql = "SELECT * FROM `exam` WHERE `exam_id`  = '{$exam_id}'" ;
        $result = mysqli_query($link, $sql) ;
        while( $row = mysqli_fetch_assoc($result) )
            $rows[] = $row;
        //$state = $rows[0]["state"] ;

        for ( $i = 1 ; $i<=11 ; $i++ ){
            if ( $i < 11  ) $temp = $temp.$rows[0]["exam_ans_".$i]."," ;
            if ( $i == 11 ) $temp = $temp.$rows[0]["exam_ans_".$i] ;
        }
        $exam_ans_temp = explode(",",$temp) ; //�@�������� �s�� ���ר�����(�Ȧs)


        //04.�P�_�@�����ת������

        //�Y�S�����o���� 108 �ӵ��סA�N���i����ƭp��
        if ( count($exam_ans_temp) == 108 ){

            //�N ���ר�����(�Ȧs) ��s�� $exam_ans[]�A�榡�G$exam_ans['QA001'] = "Y" ....
            for ( $i = 0 ; $i< count($exam_ans_temp) ; $i++ )
                $exam_ans[ substr($exam_ans_temp[$i], 0 ,5) ] = substr($exam_ans_temp[$i], -1) ;


            //05.�̷� ���ר�����($exam_ans)�A��ӤH�������D��(topic_question)�A�i����ƭp��
            for ( $i = 0 ; $i< count($type_explain_key) ; $i++ ){

                $sql = "SELECT * FROM `system-question-exam` WHERE `type` = '{$type_explain_key[$i]}' ORDER BY `num` ASC" ;
                $result = mysqli_query($link, $sql) ;

                while( $row = mysqli_fetch_assoc($result) ){
                    $key = $row['id'] ;
                    if ( $exam_ans[$key] == 'Y')
                        $exam_ans_type[ $type_explain_key[$i] ] = $exam_ans_type[ $type_explain_key[$i] ] + 1 ;
                }

            }

        }
        else
            $exam_ans_type = "" ; //�@�����S�����㵪�סA�^�ǪŦr��


        //06.�^�ǤH����������
        return $exam_ans_type;


    }

?>