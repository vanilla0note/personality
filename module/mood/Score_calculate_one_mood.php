<?php
    /*************************************************

        �p���@�@����������

        $mood_id�G��e�n�p�⪺�@����

    *************************************************/


    function Score_calculate_one_mood($mood_id){

        //01.��Ʈw�s�u
        require_once( $_SERVER['DOCUMENT_ROOT'].'/conn/conn_user_php7.php');
        $link = create_connection() ;


        ////02.�ŧi���ܼ�
        $mood_ans_temp  = Array(); //�q��Ʈw���X ���ר�����(�Ȧs)
        $mood_ans       = Array(); //��z�n��     ���ר�����
        $mood_ans_score = 0 ;      //�p��n��     ���ר�����

        $temp = "" ; //�Ȧs�r��


        //03.���X���D���@��������
        $sql = "SELECT * FROM `mood` WHERE `mood_id`  = '{$mood_id}'" ;
        $result = mysqli_query($link, $sql) ;
        while( $row = mysqli_fetch_assoc($result) )
            $rows[] = $row;

        for ( $i=1 ; $i<=2 ; $i++ ){
            if ( $i == 1 ) $temp = $temp.$rows[0]["mood_ans_".$i]."," ;
            if ( $i == 2 ) $temp = $temp.$rows[0]["mood_ans_".$i] ;
        }
        $mood_ans_temp = explode(",",$temp) ; //�@�������� �s�� ���ר�����(�Ȧs)


        //////test//////
        //echo "mood_ans_temp<br/>";
        //print_r($mood_ans_temp);
        //echo "<br/><br/>";
        ///////////////


        //04.�P�_�@�����ת������
        //�Y�S�����o���� 14 �ӵ��סA�N���i����ƭp��
        if ( count($mood_ans_temp) == 14 ){

            //�N ���ר�����(�Ȧs) ��s�� $mood_ans[]�A�榡�G$mood_ans['CQA001'] = "1" ....
            for ( $i = 0 ; $i< count($mood_ans_temp) ; $i++ )
                $mood_ans[$i] = substr($mood_ans_temp[$i], -1) ;


            //05.�̷� ���ר�����($mood_ans)�A�i����Ʋ֥[�p��
            for ( $i = 0 ; $i< count($mood_ans) ; $i++ )
                $mood_ans_score = $mood_ans_score + $mood_ans[$i] ;

        }
        else
            $mood_ans_score = 0 ; //�@�����S�����㵪�סA���p��



        //////test//////
        //echo "mood_ans<br/>";
        //print_r($mood_ans);
        //echo "<br/><br/>";
        //echo "mood_ans_score=".$mood_ans_score."<br/>";
        ///////////////


        //06.�^�ǤH����������
        return $mood_ans_score;


    }

?>