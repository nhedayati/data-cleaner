<?php
/*
Plugin Name:  DataBadger Submission Cleaner Plugin
Description:  An extension for Gravity Forms that allows admin access to delete submitted data
Version:      1.1
Author:       Nima Hedayati
Author URI:   http://nimahedayati.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

//block direct access to this plugin
defined('ABSPATH' ) or die( 'Access denied!' );

//initialise plugin
add_action('init','run_my_plugin');
function run_my_plugin(){

    //only run if current user is admin
    $user = wp_get_current_user();
    if ( in_array( 'administrator', (array) $user->roles ) ) {

        //add sub menu item to Gravity Forms menu
        add_filter('gform_addon_navigation', 'create_menu');
        function create_menu($menus)
        {
            $menus[] = array(
                'name' => 'submissions_cleaner',
                'label' => __('Submissions Cleaner'),
                'callback' => 'submissions_cleaner'
            );
            return $menus;
        }

        // add content to new sub menu
        function submissions_cleaner()
        {
?>
            <div class="wrap">
                <h1>Gravity Forms Submissions Cleaner</h1>
                <p>Use the controls below to delete all form submissions or those older than one day, week, month or year. You can choose which forms you wish to delete submitted data from.</p>
                <form id="submissions-cleaner-form" method="post">
                    <p>Start by selecting the form you wish to delete data from or choose <em>'All forms'</em> for all Gravity forms on this website.</p>
                    <select id="id-a" style="display:block;margin-bottom:15px">
                        <option name="id" value="all_forms">All forms</option>
                        <?php
                        global $wpdb;
                        $table = $wpdb->prefix . 'gf_form';
                        $results = $wpdb->get_results(sprintf("SELECT * FROM %s", $table), ARRAY_A );
                        if(!empty($results)):
                            foreach($results as $form): ?>
                                 <option name="id" value="<?=$form['id'];?>"><?=$form['title'];?></option>
                            <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <p>Next choose to delete submissions older than one day, week, month or year; alternatively, you can delete the entire history of submissions.</p>
                    <select id="history-a" style="display:block;margin-bottom:15px">
                        <option name="history" value="entire_history">Entire history</option>
                        <option name="history" value="one_day">Older than one day</option>
                        <option name="history" value="one_week">Older than one week</option>
                        <option name="history" value="one_month">Older than one month</option>
                        <option name="history" value="one_year">Older than one year</option>
                    </select>
                    <input type="submit" value="Submit"/>
                </form>
            </div>
            <script>
                jQuery(document).ready(function($){

                    $("#submissions-cleaner-form").submit(function(e){
                        e.preventDefault();
                        if(!confirm('Are you sure you want to delete the selected data from the database? this action cannot be undone')){
                            return false;
                        } else {
                            var id = $("#id-a").val();
                            var history = $("#history-a").val();
                            $.ajax({
                                method: "POST",
                                url: '<?=plugin_dir_url(__FILE__);?>delete-entries-script.php',
                                data: {id: id, history: history},
                                dataType: 'text',
                                success: function (data) {
                                    alert("The selected data has successfully been deleted from the database.");
                                }
                            });
                        }
                    });


                });
            </script>
<?php
        }
    }
}
?>
