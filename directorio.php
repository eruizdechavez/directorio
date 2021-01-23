<?php

/** 
 * Plugin Name: Directorio
 * Description: Activa la funcionalidad necesaria para crear directorio de personas.
 * Version: 1.0.0
 * License: MIT
 */

add_action('init', function () {
    register_post_type(
        'personas',
        array(
            'labels' => array(
                'name' => __('Personas', 'directorio'),
                'singular_name' => __('Persona', 'directorio'),
                'add_new' => __('Añadir nueva', 'directorio'),
                'add_new_item' => __('Añadir nueva persona', 'directorio'),
                'edit_item' => __('Editar persona', 'directorio'),
                'new_item' => __('Nueva persona', 'directorio'),
                'view_item' => __('Ver persona', 'directorio'),
                'view_items' => __('Ver personas', 'directorio'),
                'search_items' => __('Buscar personas', 'directorio'),
                'not_found' => __('No se encontraron personas', 'directorio'),
                'not_found_in_trash' => __('Persona no encotrada en la papelera', 'directorio'),
                'parent_item_colon' => __('Persona superior', 'directorio'),
                'all_items' => __('Todas las personas', 'directorio'),
                'archives' => __('Archivo de personas', 'directorio'),
                'attributes' => __('Atributos de las personas', 'directorio'),
                'insert_into_item' => __('Insertar en persona', 'directorio'),
                'uploaded_to_this_item' => __('Cargado en esta persona', 'directorio'),
                'featured_image' => __('Imagen destacada', 'directorio'),
                'set_featured_image' => __('Definir imagen destacada', 'directorio'),
                'remove_featured_image' => __('Remover imagen destacada', 'directorio'),
                'use_featured_image' => __('Usar como imagen destacada', 'directorio'),
                'filter_items_list' => __('Filtrar lista de personas', 'directorio'),
                'items_list_navigation' => __('Navegación de lista de personas', 'directorio'),
                'items_list' => __('Listado de personas', 'directorio'),
                'item_published' => __('Persona publicada', 'directorio'),
                'item_published_privately' => __('Persona publicada de forma privada', 'directorio'),
                'item_reverted_to_draft' => __('Persona restaurada como borrador', 'directorio'),
                'item_scheduled' => __('Persona programada', 'directorio'),
                'item_updated' => __('Persona actualizada', 'directorio'),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'revisions', 'excerpt', 'thumbnail'),
            'menu_position' => 5,
        )
    );

    require_once __DIR__ . '/acf/persona.php';
});

add_shortcode('lista-de-personas', function ($atts) {
    $personas = get_posts(array('post_type' => 'personas'));
    $html = array_reduce($personas, function ($html, $persona) {
        return $html . persona_template($persona);
    }, '');

    return <<<HTML
    <div class="persona">$html</div>
    HTML;
});

add_shortcode('tabla-de-personas', function () {
    $personas = get_posts(array('post_type' => 'personas'));
    return tabla_personas($personas);
});

add_shortcode('persona', function ($atts) {
    $atts = shortcode_atts(array(
        'id' => ''
    ), $atts);

    if (empty($atts['id'])) {
        return 'Error: ID de Persona inválido';
    }

    $persona = get_post($atts['id']);

    return persona_template($persona);
});

add_filter('template_include', function ($template) {
    $post_types = array('personas');

    if (is_post_type_archive($post_types)) {
        if (is_search()) {
            $template = plugin_dir_path(__FILE__) . '/templates/search.php';
        } else {
            $template = plugin_dir_path(__FILE__) . '/templates/archive.php';
        }
    }

    return $template;
});

function persona_template($persona)
{
    $image = get_the_post_thumbnail_url($persona->ID, 'thumbnail');
    if (!empty($image)) {
        $image = "<img src=\"${image}\">";
    } else {
        $image = '';
    }

    $permalink = get_the_permalink($persona->ID);
    $fields = get_fields($persona->ID);

    return <<<HTML
    <div class="persona-foto-miniatura">{$image}</div>
    <div class="persona-nombre"><a href="{$permalink}">{$persona->post_title}</a></div>
    <div class="persona-twitter"><a href="https://twitter.com{$fields['twitter']}">@{$fields['twitter']}</a></div>
    <div class="persona-descripcion">{$persona->post_excerpt}</div>
    HTML;
}

function tabla_personas($personas)
{
    ob_start();
?>
    <table style="min-width: 100%;">
        <thead>
            <tr>
                <th style="width: 10%">Imagen</th>
                <th style="width: 15%">Nombre</th>
                <th style="width: 45%">Bio</th>
                <th style="width: 15%">Twitter</th>
                <th style="width: 15%">Instagram</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($personas as $persona) :

                $image = get_the_post_thumbnail_url($persona->ID, 'thumbnail');
                if (!empty($image)) {
                    $image = "<img src=\"${image}\">";
                } else {
                    $image = '';
                }

                $permalink = get_the_permalink($persona->ID);
                $fields = get_fields($persona->ID);

            ?>
                <tr>
                    <td><?php echo $image; ?></td>
                    <td>
                        <a href="<?php echo $permalink ?>">
                            <?php echo $persona->post_title; ?>
                        </a>
                    </td>
                    <td><?php echo $persona->post_excerpt; ?></td>
                    <td>
                        <a href="https://twitter.com/<?php echo $fields['twitter']; ?>">
                            <?php echo '@' . $fields['twitter']; ?>
                        </a>
                    </td>
                    <td><?php echo $fields['instagram']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php
    $result = ob_get_clean();

    return $result;
}
