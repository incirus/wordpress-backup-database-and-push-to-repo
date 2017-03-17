<?php
add_action('admin_menu', 'add_repo_admin_menu');
function add_repo_admin_menu() {
    global $submenu;
    $url = get_site_url().'/backup.php';
    $submenu['tools.php'][] = array('<div id="backup">Update REPO</div>', 'manage_options', $url);
}
add_action( 'admin_footer', 'make_link_blank' );
function make_link_blank()
{
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#backup').click(function(){
          window.open('<?php echo get_site_url().'/backup.php'; ?>','popup','width=300,height=300'); return false;
        })
    });
    </script>
    <?php
}
?>