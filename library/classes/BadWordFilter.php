<?php
/**
 * This class provides an static method to filter bad words
 *
 * @author enriqueareyan
 */
class library_classes_BadWordFilter {
    //put your code here
    const threshold = 10;
    
    public static function fetchWords(){
        $model_words = new library_models_word;
        $words = $model_words->select()->query()->fetchAll();
        return $words;
    }
    
    public static function Filter($message){
        $message = strtolower($message);
        $words = library_classes_BadWordFilter::fetchWords();
        $total_bad_words = 0;
        foreach($words as $id=>$word){
            $total_bad_words += substr_count($message, $word['word']);
            $message = str_replace($word['word'], "***", $message);
        }
        if($total_bad_words > library_classes_BadWordFilter::threshold){
            throw new library_classes_BTownException("The system has detected inappropriate use of words in your message. Please review the wording of your message.");
        }else{
            return array('filtered_message'=>$message,'total_bad_words'=>$total_bad_words);
        }
    }
}

?>
