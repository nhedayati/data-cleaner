<?php
require_once '\wamp64\www\hallam\wp-load.php'; /*replace this with below if on a remote server */
//require_once echo get_site_url(); 'wp-load.php';
global $wpdb;

function delete_based_on_form_ids($id){
    global $wpdb;
    $entry_meta = $wpdb->prefix . 'gf_entry_meta';
    if($id == "all_forms"){
        $execute_phase_one = $wpdb->get_results(sprintf("DELETE FROM %s",$entry_meta));
    }
    else{
        $execute_phase_one = $wpdb->get_results(sprintf("DELETE FROM %s WHERE form_id=%d",$entry_meta, $id));
    }
    return $execute_phase_one;
}

function delete_based_on_date($id,$date){
    global $wpdb;
    $entry_meta = $wpdb->prefix . 'gf_entry_meta';
    $entry_times = $wpdb->prefix . 'gf_entry';
    $where_sql = "";
    $where_sql2 = " ";
    if($id != "all_forms"){
       $where_sql = " WHERE p.form_id=".$id;
       $where_sql2 = " AND p.form_id=".$id;
    }
    switch($date){
        case "entire_history":
            $execute_phase_two = $wpdb->get_results(sprintf("DELETE FROM %s %s",$entry_meta, $where_sql));
        break;

        case "one_day":
            $execute_phase_two = $wpdb->get_results(
                sprintf("DELETE p, pa FROM %s p JOIN %s pa ON pa.id = p.entry_id WHERE UNIX_TIMESTAMP(pa.date_created) < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 DAY)) %s",
                    $entry_meta, $entry_times, $where_sql2
                ));
        break;

        case "one_week":
            $execute_phase_two = $wpdb->get_results(
                sprintf("DELETE p, pa FROM %s p JOIN %s pa ON pa.id = p.entry_id WHERE UNIX_TIMESTAMP(pa.date_created) < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 7 DAY)) %s",
                    $entry_meta, $entry_times, $where_sql2
                ));
        break;

        case "one_month":
            $execute_phase_two = $wpdb->get_results(
                sprintf("DELETE p, pa FROM %s p JOIN %s pa ON pa.id = p.entry_id WHERE UNIX_TIMESTAMP(pa.date_created) < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 31 DAY)) %s",
                    $entry_meta, $entry_times, $where_sql2
                ));
        break;

        case "one_year":
            $execute_phase_two = $wpdb->get_results(
                sprintf("DELETE p, pa FROM %s p JOIN %s pa ON pa.id = p.entry_id WHERE UNIX_TIMESTAMP(pa.date_created) < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 365 DAY)) %s",
                $entry_meta, $entry_times, $where_sql2
                ));
        break;
    }
    return $execute_phase_two;
}

if($_POST["history"] == "entire_history"){
    delete_based_on_form_ids($_POST["id"]);
}
else{
    delete_based_on_date($_POST["id"],$_POST["history"]);
}