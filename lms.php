<?php
    /*
        Plugin Name: LMS
        Description: PLUGIN PRUEBA LMS
        Version: 1.0
        Author: Giuseppe Lidonnici
    */
    function lms_settings_init() {
        // Register a new setting for the options group
        register_setting('lms_options_group', 'lms_endpoint_url');
    
        // Add a new section to the settings page
        add_settings_section(
            'lms_settings_section',
            'Endpoint URL Settings',
            'lms_settings_section_callback',
            'lms-settings'
        );
    
        // Add a new field to the section
        add_settings_field(
            'lms_endpoint_url',
            'Endpoint URL',
            'lms_endpoint_url_callback',
            'lms-settings',
            'lms_settings_section'
        );
    }
    add_action('admin_init', 'lms_settings_init');
    
    function lms_settings_section_callback() {
        echo '<p>Enter the endpoint URL for the LMS Courses.</p>';
    }
    
    function lms_endpoint_url_callback() {
        $endpoint_url = get_option('lms_endpoint_url');
        echo '<input type="text" id="lms_endpoint_url" name="lms_endpoint_url" value="' . esc_attr($endpoint_url) . '" size="100" />';
    }
    
    function lms_activate() {
        
    }
    function get_lms_endpoint_url() {
        return get_option('lms_endpoint_url');
    }
    register_activation_hook(_FILE_, 'lms_activate');
    
    function lms_deactivate() {
        delete_option('lms_endpoint_url');
    }
    register_deactivation_hook(_FILE_, 'lms_deactivate');
    
    add_action('admin_menu', 'lms_menu');

    function lms_menu() {
        add_menu_page(
            'LMS Courses',         // List courses
            'LMS COURSES',        // Menu title
            'manage_options',         // Capability
            'lms-plugin',        // Menu slug
            'lms_page',   // Callback function
            'dashicons-admin-generic' // Icon
        );
        add_submenu_page(
            'lms-plugin',        // Menu slug
            'Api settings', // Título de la página
            'Api settings', // Título del submenú
            'manage_options', // Capacidad
            'lms-settings', // Slug del submenú
            'lms_settings_page' // Callback function
        );
    }
    function lms_settings_page() {
    ?>
    <div class="wrap">
        <h1>LMS COURSES Api Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('lms_options_group');
            do_settings_sections('lms-settings');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}
    function lms_page() {
        $lsm_date = get_lms_endpoint_url();
        $url = (isset($lsm_date) && $lsm_date != '') ? get_lms_endpoint_url() : '';
        ?>
        <div class="wrap">
                <h1>Welcome to LMS TEST PLUGIN</h1>
                <hr>
        <?php
        if ($url != '') {
            $data = get_external_lms_data($url);
            // Pagination settings
            $items_per_page = 4; // Number of items per page
            $total_items = count($data);
            $total_pages = ceil($total_items / $items_per_page);
            if ($total_pages == 0 && is_array($data) && isset($data[0]["name"]) &&  $data[0]["name"] != '') {
                ?>
                <div class="wrap">
                    There are not valid resultts.
                </div>
                <?php
            } else {
                // Get the current page from query parameters; default to 1 if not set
                $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
            
                // Calculate the offset and slice the items array to get the items for the current page
                $offset = ($current_page - 1) * $items_per_page;
                $current_items = array_slice($data, $offset, $items_per_page);
                $page = 'page='.$_REQUEST["page"]."&";
                ?>
                    <div class="wrap">
                        <div id="sorting-buttons">
                            <button onclick="sortGrid('id')">Sort by ID</button>
                            <button onclick="sortGrid('name')">Sort by Name</button>
                            <button onclick="sortGrid('start_at')">Sort by Start Date</button>
                        </div>
                        <div id="grid-container">
                            <?php foreach ($current_items as $course) : ?>
                                <div class="grid-item" data-id="<?php echo esc_attr($course['id']); ?>" data-name="<?php echo esc_attr($course['name']); ?>" data-start_at="<?php echo esc_attr($course['start_at']); ?>">
                                    <div class="grid-item-id">ID: <?php echo $course['id']; ?></div>
                                    <div class="grid-item-name">Name: <?php echo $course['name']; ?></div>
                                    <div class="grid-item-description">Course Code: <?php echo $course['course_code']; ?></div>
                                    <div class="grid-item-description grid-item-workflow_state">Workflor State: <?php echo $course['workflow_state']; ?></div>
                                    <div class="grid-item-start_at grid-item-date">Start Date: <span class='grid-item-date-val'><?php echo $course['start_at']; ?></span></div>
                                    <div class="grid-item-end-at grid-item-date">End Date: <span class='grid-item-date-val'><?php echo $course['end_at']; ?></span></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Pagination Controls -->
                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?<?php echo $page;?>paged=<?php echo $current_page - 1; ?>" class="prev-page button">Previous</a>
                        <?php endif; ?>
            
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?<?php echo $page;?>paged=<?php echo $i; ?>" class="page-numbers <?php echo ($i == $current_page) ? 'current' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
            
                        <?php if ($current_page < $total_pages): ?>
                            <a href="?<?php echo $page;?>paged=<?php echo $current_page + 1; ?>" class="next-page button">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
                <div class="wrap">
                    Please define API url in settings.    
                </div>
            <?php
        }
    }
    
    function get_external_lms_data($url) {
        $url = $url;
        $res = wp_remote_get($url);
        if (is_wp_error($res)) { 
            return false;   
        }
        $body = wp_remote_retrieve_body($res);
        $content = json_decode($body, true);
        return $content;
    }
    add_action('admin_enqueue_scripts', 'grid_admin_plugin_styles');
    function grid_admin_plugin_styles() {
        wp_enqueue_style('grid-admin-plugin-css', plugin_dir_url(__FILE__) . 'style.css');
        wp_enqueue_script('grid-admin-plugin-js', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), null, true);
    }
