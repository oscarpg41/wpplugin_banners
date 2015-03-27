<?php
/*
Plugin Name: OPG Banners
Plugin URI: http://www.oscarperez.es/wordpress-plugins/opg_banners.zip
Description: This banners plugin helps to manage the banners easily over the WordPress blog. 
This db table opg_plugin_banners have four fields: idBanner, name, url and image
Author: Oskar Pérez
Author URI: http://www.oscarperez.es/
Version: 1.0
License: GPLv2
*/
?>
<?php

    //Lo que hacemos es añadir los scripts necesarios para que el cargador de medios de wordpress se muestre
    function my_admin_scripts_banners() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_register_script('my-upload', WP_PLUGIN_URL.'/opg_banners/opg_banners.js', array('jquery','media-upload','thickbox'));
        wp_enqueue_script('my-upload');
    }
    function my_admin_styles_banners() {
        wp_enqueue_style('thickbox');
    }
    if (isset($_GET['page']) && $_GET['page'] == 'opg_banners') {
        add_action('admin_print_scripts', 'my_admin_scripts_banners');
        add_action('admin_print_styles', 'my_admin_styles_banners');
    }
    // cargador de medios de wordpress



    function opg_show_menu_banners(){
        add_menu_page('Oscar Pérez Plugins','Oscar Pérez Plugins','manage_options','opg_plugins','opg_plugin_banners_show_form_in_wpadmin', '', 110);
        add_submenu_page( 'opg_plugins', 'Banners', 'Banners', 'manage_options', 'opg_banners', 'opg_plugin_banners_show_form_in_wpadmin');
        remove_submenu_page( 'opg_plugins', 'opg_plugins' );        
    }
    add_action( 'admin_menu', 'opg_show_menu_banners' );


    //Hook al activar y desactivar el plugin
    register_activation_hook( __FILE__, 'opg_plugin_banners_activate' );
    register_uninstall_hook( __FILE__, 'opg_plugin_banners_uninstall' );


    // Se crea la tabla al activar el plugin
    function opg_plugin_banners_activate() {
        global $wpdb;

        $sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'opg_plugin_banners` 
            ( `idBanner` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , 
              `name` VARCHAR( 100 ) COLLATE utf8_spanish_ci NOT NULL, 
              `url` VARCHAR( 140 ) NOT NULL,
              `image` VARCHAR( 140 ) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci';
        $wpdb->query($sql);
    }

    // Se borra la tabla al desisntalar el plugin
    function opg_plugin_banners_uninstall() {
        global $wpdb;
        $sql = 'DROP TABLE `' . $wpdb->prefix . 'opg_plugin_banners`';
        $wpdb->query($sql);
    }





    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        F U N C I O N E S   D E   A C C E S O   A   B A S E   D E   D A T O S
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    //función que guarda en base de datos la información introducida en el formulario
    function opg_banners_save($name, $url, $image)
    {
        global $wpdb;
        if (!( isset($name) && isset($url) )) {
            _e('cannot get \$_POST[]');
            exit;
        }

        $name = trim($name);
        $url  = trim($url);

        //comprobamos si empieza por http
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        $save_or_no = $wpdb->insert($wpdb->prefix . 'opg_plugin_banners', array(
                'idBanner' => NULL, 'name' => esc_js($name), 'url' => $url, 'image' => $image,
            ),
            array('%d', '%s', '%s', '%s' )
        );

        if (!$save_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Información del banner guardada correctamente</strong></p></div>');            
        }
        return true;
    }


    //función que borra un banner de la base de datos
    function opg_banners_remove($id)
    {
        global $wpdb;
        if ( !isset($id) ) {
            _e('cannot get \$_GET[]');
            exit;
        }

        $delete_or_no = $wpdb->delete($wpdb->prefix . 'opg_plugin_banners', array('idBanner' => $id), array( '%d' ) );
        if (!$delete_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Se ha borrado la información del banner</strong></p></div>');            
        }
        return true;
    }

    //función para actualizar un banner
    function opg_banners_update($id, $name, $url, $image)
    {
        global $wpdb;
        if (!( isset($name) && isset($url) )) {
            _e('cannot get \$_POST[]');
            exit;
        }

        //Actualizamos la información de la imagen
        if ( isset($image) && (strlen($image)>0) ){
            $update_or_no = $wpdb->update($wpdb->prefix . 'opg_plugin_banners', 
                array('name' => esc_js(trim ($name)), 'url' => trim ($url), 'image' => $image),
                array('idBanner' => $id),
                array('%s', '%s', '%s')
            );
        }
        //Si no se ha modificado la imagen, no actualizamos la información de la imagen ya guardada
        else{
            $update_or_no = $wpdb->update($wpdb->prefix . 'opg_plugin_banners', 
                array('name' => esc_js(trim ($name)), 'url' => trim ($url)),
                array('idBanner' => $id),
                array('%s', '%s')
            );         
        }

        if (!$update_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Banner modificado correctamente</strong></p></div>');            
        }
        return true;
    }


    //función que recupera un banner usando el ID
    function opg_plugin_banners_getId($id)
    {
        global $wpdb;
        $row1 = $wpdb->get_row("SELECT name, url, image  FROM " . $wpdb->prefix . "opg_plugin_banners  WHERE idBanner=".$id);
        return $row1;
    }


    //función que recupera los banners guardados de la base de datos
    function opg_plugin_banners_getData()
    {
        global $wpdb;

        $banners = $wpdb->get_results( 'SELECT idBanner, name, url, image FROM ' . $wpdb->prefix . 'opg_plugin_banners
         ORDER BY name' );
        if (count($banners)>0){            
?>
            <hr style="width:94%; margin:20px 0">   
            <h2>Listado de Banners</h2>
            <table class="wp-list-table widefat manage-column" style="width:98%">            
             <thead>
                <tr>
                    <th scope="col" class="manage-column"><span>Nombre</span></th>
                    <th scope="col" class="manage-column"><span>Url</span></th>
                    <th scope="col" class="manage-column"><span>Imagen</span></th>
                    <th scope="col" class="manage-column">&nbsp;</th>
                    <th scope="col" class="manage-column">&nbsp;</th>
                </tr>
             </thead>
             <tbody>

<?php
            $cont = 0;
            foreach ( $banners as $banner ) {
                $cont++;
                if ($cont%2 ==1){ echo '<tr class="alternate">'; }
                else{ echo '<tr>'; }

?>
                    <td><?php echo( $banner->name ); ?></td>
                    <td><?php echo( $banner->url ); ?></td>
                    <td><img src="<?php echo $banner->image ?>" width="150px"></td>
                    <td><a href="admin.php?page=opg_banners&amp;task=edit_banners&amp;id=<?php echo( $banner->idBanner ); ?>"><img src="<?php echo WP_PLUGIN_URL.'/opg_banners/img/modificar.png'?>" alt="Modificar"></a></td>
                    <td><a href="#"><img src="<?php echo WP_PLUGIN_URL.'/opg_opg_banners/img/papelera.png'?>" alt="Borrar" id="<?php echo( $banner->idBanner ); ?>" class="btnDeleteBanner"></a></td>
                </tr>
<?php                
            }
        }

?>
                </tbody>
            </table>
<?php
        return true;
    }



    /*
       F U N C I O N   Q U E   S E   E J E C U T A   A L   A C C E D E R   A L   P L U G I N   D E S D E   A D M I N I S T R A C I O N
       La función la definimos en la llamada add_menu_page()
    */
    function opg_plugin_banners_show_form_in_wpadmin(){
 
        $valueInputUrl   = "";
        $valueInputName  = "";
        $valueInputId    = "";

        if(isset($_POST['action']) && $_POST['action'] == 'salvaropciones'){

            //si el input idBanner (hidden) está vacio, se trata de un nuevo registro
            if( strlen($_POST['idBanner']) == 0 ){
                //guardamos el teléfono
                opg_banners_save($_POST['name'], $_POST['url'], $_POST['upload_image']);
            }
            else{
                opg_banners_update($_POST['idBanner'], $_POST['name'], $_POST['url'], $_POST['upload_image']);
            }   
        }
        else{
            //recuperamos la tarea a realizar (edit o delete)
            if (isset($_GET["task"]))
                $task = $_GET["task"]; //get task for choosing function
            else
                $task = '';
            //recuperamos el id del telefono
            if (isset($_GET["id"]))
                $id = $_GET["id"];
            else
                $id = 0;


            switch ($task) {
                case 'edit_banners':
                    echo("<div class='wrap'><h2>Modificar información del banner</h2></div>"); 

                    $row = opg_plugin_banners_getId($id);
                    $valueInputUrl   = $row->url;
                    $valueInputName  = $row->name;
                    $valueInputImage = $row->image;
                    $valueInputId    = $id;
                    break;
                case 'remove_banners':
                    opg_banners_remove($id);
                    break;
                default:
                    echo("<div class='wrap'><h2>Añadir un nuevo banner</h2></div>"); 
                    break;
            }
        }
?>
        <form method='post' action='admin.php?page=opg_banners' name='opgPluginAdminForm' id='opgPluginAdminForm' enctype="multipart/form-data">
            <input type='hidden' name='action' value='salvaropciones'> 
            <table class='form-table' style="width:95%">
                <tbody>
                    <tr>
                        <th><label for='name'>Nombre</label></th>
                        <td>
                            <input type='text' name='name' id='name' placeholder='Introduzca el nombre' value="<?php echo $valueInputName ?>" style='width: 500px'>
                        </td>
                    </tr>
                    <tr>
                        <th><label for='url'>Url</label></th>
                        <td>
                            <input type='text' name='url' id='url' placeholder='Introduzca la url' value="<?php echo $valueInputUrl ?>" style='width: 500px'>
                        </td>
                    </tr>
                    <tr>
                        <th><label for='url'>Imagen</label></th>
                        <td>
                        <?php 
                            if (strlen($valueInputImage)>0){
                        ?>
                            <img src="<?php echo $valueInputImage ?>" width="150px" align="right">                         
                        <?php                                                         
                            }
                        ?>
                            <input type="text" name="upload_image" id="upload_image" value="" size='40' />
                            <input type="button" class='button-secondary' id="upload_image_button" value="Subir nueva imagen" />  
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' style='padding-left:140px'>
                            <input type='submit' value='Enviar'>
                            <input type='hidden' name="idBanner" value="<?php echo $valueInputId ?>">
                        </td>
                    </tr>
                </tbody>
            </table>        
        </form>

<?php
        //se muestra el listado de todos los teléfonos guardados
        opg_plugin_banners_getData();
?>        
<?php }?>