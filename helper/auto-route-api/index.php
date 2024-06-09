<?php

add_action('rest_api_init', 'register_all_module_routes');

function register_all_module_routes()
{
    $modules_dir = get_template_directory() . '/pages/api';

    function register_module_routes($dir)
    {
        if (is_dir($dir)) {
            $items = scandir($dir);

            foreach ($items as $item) {
                if ($item !== '.' && $item !== '..') {
                    $path = $dir . '/' . $item;

                    if (is_dir($path)) {
                        $route_file = $path . '/index.php';

                        if (file_exists($route_file)) {
                            include_once $route_file;

                            $relative_path = str_replace(get_template_directory() . '/pages/api/', '', $path);
                            $function_name = 'register_' . str_replace(['/', '-'], '_', $relative_path) . '_routes';

                            if (function_exists($function_name)) {
                                error_log("Registering routes for $relative_path");
                                $function_name();
                            } else {
                                error_log("Function $function_name does not exist");
                            }
                        }

                        register_module_routes($path);
                    }
                }
            }
        } else {
            error_log("Modules directory $dir does not exist");
        }
    }

    // Start recursive registration from the base modules directory
    register_module_routes($modules_dir);
}
