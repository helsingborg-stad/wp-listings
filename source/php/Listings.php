<?php

namespace WpListings;

class Listings extends \WpListings\Entity\PostType
{
    public static $postTypeSlug;
    public static $taxonomySlug;

    public function __construct()
    {
        self::$postTypeSlug = $this->postType();
        self::$taxonomySlug = $this->taxonomies();

        add_action('created_term', array($this, 'createTermsFieldJson'), 10, 3);
        add_action('edited_term', array($this, 'createTermsFieldJson'), 10, 3);

        add_filter('acf/load_field/name=listing_place', '\WpListings\Listings::places');
    }

    public static function places($field = null)
    {
        $places = apply_filters('wp-listings/places', array(
            "Ale", "Alingsås", "Alvesta", "Aneby", "Arboga", "Arjeplog", "Arvidsjaur", "Arvika",
            "Askersund", "Askim", "Avesta", "Backa", "Bengtsfors", "Berg", "Bergsjön",
            "Biskopsgården", "Bjurholm", "Bjuv", "Boden", "Bollebygd", "Bollnäs", "Borgholm",
            "Borlänge", "Borås", "Botkyrka", "Boxholm", "Bromma", "Bromölla", "Bräcke", "Burlöv",
            "Båstad", "Dals-Ed", "Danderyd", "Degerfors", "Dorotea", "Eda", "Ekerö", "Eksjö",
            "Emmaboda", "Enköping", "Enskede", "Esikltuna", "Eslöv", "Essunga", "Fagersta",
            "Falkenberg", "Falköping", "Falun", "Farsta", "Filipstad", "Finspång", "Flen",
            "Forshaga", "Frölunda", "Färgelanda", "Gagnef", "GamlaStan", "Gislaved", "Gnesta",
            "Gnosjö", "Gotland", "Grums", "Grästorp", "Gullspång", "Gunnared", "Gällivare",
            "Gävle", "GöteborgC", "GöteborgStad", "Götene", "Habo", "Hagfors", "Hallsberg",
            "Hallstahammar", "Halmstad", "Hammarby", "Hammarö", "Haninge", "Haparanda", "Heby",
            "Hedemora", "Helsingborg", "Herrljunga", "Hjo", "Hofors", "Huddinge", "Hudiksvall",
            "Hultsfred", "Hylte", "Håbo", "Hägersten", "Hällefors", "Härjedalen", "Härlanda",
            "Härnösand", "Härryda", "Hässelby", "Hässleholm", "Höganäs", "Högsbo", "Högsby",
            "Hörby", "Höör", "Jokkmokk", "Järfälla", "Jönköping", "Kalix", "Kalmar", "Karlsborg",
            "Karlshamn", "Karlskoga", "Karlskrona", "Karlstad", "Katrineholm", "Kil", "Kinda",
            "Kiruna", "Kista", "Klippan", "Kortedala", "Kramfors", "Kristianstad", "Kristinehamn",
            "Krokom", "Kumla", "Kungsbacka", "Kungsholmen", "Kungsör", "Kungälv", "Kärra-Rödbo",
            "Kävlinge", "Köping", "Laholm", "Landskrona", "Laxå", "Lekeberg", "Leksand", "Lerum",
            "Lessebo", "Lidingö", "Lidköping", "Liljeholmen", "LillaEdet", "Lindesberg",
            "Linköping", "Linnestaden", "Ljungby", "Ljusdal", "Ljusnarsberg", "Lomma", "Ludvika",
            "Luleå", "Lund", "Lundby", "Lycksele", "Lysekil", "Lärjedalen", "Majorna", "Malmö",
            "Malung", "Malå", "Mariestad", "Mark", "Markaryd", "Mellerud", "Mjölby", "Mora",
            "Motala", "Mullsjö", "Munkedal", "Munkfors", "Mölndal", "Mönsterås", "Mörbylånga",
            "Nacka", "Nora", "Norberg", "Nordanstig", "Nordmaling", "Norrköping", "Norrmalm",
            "Norrtälje", "Norsjö", "Nybro", "Nykvarn", "Nyköping", "Nynäshamn", "Nässjö",
            "Ockelbo", "Olofström", "Orsa", "Orust", "Osby", "Oskarshamn", "Ovanåker",
            "Oxelösund", "Pajala", "Partille", "Perstorp", "Piteå", "Ragunda", "Rinkeby",
            "Robertsfors", "Ronneby", "Rättvik", "Sala", "Salem", "Sandviken", "Sigtuna",
            "Simrishamn", "Sjöbo", "Skara", "Skarpnäck", "Skellefteå", "Skinnskatteberg",
            "Skurup", "Skärholmen", "Skövde", "Smedjebacken", "Sollefteå", "Sollentuna", "Solna",
            "Sorsele", "Sotenäs", "Spånga/Tensta", "Staffanstorp", "Stenungsund", "StockholmsStad",
            "Storfors", "Storuman", "Strängnäs", "Strömstad", "Strömsund", "Styrsö", "Sundbyberg",
            "Sundsvall", "Sunne", "Surahammar", "Svalöv", "Svedala", "Svenljunga", "Säffle",
            "Säter", "Sävsjö", "Söderhamn", "Söderköping", "Söderled", "Södermalm", "Södertälje",
            "Sölvesborg", "Tanum", "Tibro", "Tidaholm", "Tierp", "Timrå", "Tingsryd", "Tjörn",
            "Tomelilla", "Torsby", "Torslanda", "Torsås", "Tranemo", "Tranås", "Trelleborg",
            "Trollhättan", "Trosa", "Tuve-Säve", "Tynnered", "Tyresö", "Täby", "Töreboda",
            "Uddevalla", "Ulricehamn", "Umeå", "UpplandsBro", "Upplandsväsby", "Uppsala",
            "Uppvidinge", "Vadstena", "Vaggeryd", "Valdemarsvik", "Vallentuna", "Vansbro", "Vantör",
            "Vara", "Varberg", "Vasastaden", "Vaxholm", "Vellinge", "Vetlanda", "Vilhelmina",
            "Vimmerby", "Vindeln", "Vingåker", "Vårgårda", "Vällingby", "Vänersborg", "Vännäs",
            "Värmdö", "Värnamo", "Västervik", "Västerås", "Växjö", "Ydre", "Ystad", "Åmål", "Ånge",
            "Åre", "Årjäng", "Åresta", "Åsele", "Åstorp", "Åtvidaberg", "Älmhult", "Älvdalen",
            "Älvkarleby", "Älsvborg", "Älvsbyn", "Älvsjö", "Ängelholm", "Öckerö", "Ödeshög",
            "Örebro", "Örgryte", "Örkelljunga", "Örnsköldsvik", "Östermalm/Djurgården", "Östersund",
            "Österåker", "Östhammar", "ÖstraGöinge", "Överkalix", "Övertorneå"
        ));

        $places = array_combine($places, $places);

        if (!$field) {
            return $places;
        }

        $field['choices'] = $places;
        return $field;
    }

    /**
     * Create post type
     * @return void
     */
    public function postType() : string
    {
        // Create posttype
        $postType = new \WpListings\Entity\PostType(
            _x('Listings', 'Post type plural', 'wp-listings'),
            _x('Listing', 'Post type singular', 'wp-listings'),
            'listing',
            array(
                'description'          =>   'Listings',
                'menu_icon'            =>   'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHRpdGxlPnRyYWRlPC90aXRsZT48cGF0aCBkPSJNNS41MiA2MC40OGE0LjY0IDQuNjQgMCAwIDEtNC42Mi00QTQ5LjIxIDQ5LjIxIDAgMCAxIDkuODYgMjFDMjUuNjQ2LS43IDU1LjgwNi01Ljk1NCA3OCA5LjEzbDIuMDktMi44OWExLjUgMS41IDAgMCAxIDIuNjIuMzRsNS41MiAxNC4zM2ExLjUgMS41IDAgMCAxLTEuNDcgMmwtMTUuMzMtLjc1YTEuNSAxLjUgMCAwIDEtMS4xNC0yLjM4TDcyLjM2IDE3QzU0LjUxMiA0Ljk5MyAzMC4zNjQgOS4yNTIgMTcuNyAyNi42NGEzOS41NyAzOS41NyAwIDAgMC0zIDQuOTNoMTIuNWEuOTEuOTEgMCAwIDEgLjggMS4zNiA3LjExIDcuMTEgMCAwIDAtLjY0IDUuNCA2LjMyIDYuMzIgMCAwIDAgMy44NSAzLjggMTEuNzggMTEuNzggMCAwIDAgOS43OS0uNzRsOS40LTUuMjZhLjY1LjY1IDAgMCAxIC44Mi4xNWwxOS42NSAyNCAyLjQzIDIuOTNhNCA0IDAgMCAxLS44OSA2IDQuMDYgNC4wNiAwIDAgMS00LjkzLS42MWwtLjMzLS4zMmEuMDkuMDkgMCAwIDAtLjE0LjA4IDQgNCAwIDAgMS0xLjcyIDQuMDggNC4xIDQuMSAwIDAgMS01LjA3LS41OGwtLjMtLjI5YS4wOS4wOSAwIDAgMC0uMTQuMDggNCA0IDAgMCAxLTEuNzIgNC4wOCA0LjEgNC4xIDAgMCAxLTUuMDYtLjU2bC0uMy0uMjlhLjA5LjA5IDAgMCAwLS4xNC4wOCA0IDQgMCAwIDEtNi4yIDRsLjM4LS42N2E3LjIyIDcuMjIgMCAwIDAtMS04LjcgNyA3IDAgMCAwLTMuODMtMS45MyA2LjkzIDYuOTMgMCAwIDAtNC4wNS0zLjE2QTYuOTQgNi45NCAwIDAgMCAzMyA1OS43MWE2LjkgNi45IDAgMCAwLTIuNzYtMy44NyA3LjMzIDcuMzMgMCAwIDAtOS40OSAxLjU0bC0yLjU0IDMuMTFINS41MnYtLjAxem0yMi41NS0xLjgyYTMuNDggMy40OCAwIDAgMC00LjQzLjg5TDIxIDYyLjc5YTMuMzQgMy4zNCAwIDAgMCAuNTMgNC43NWwuNC4zMmEzLjM0IDMuMzQgMCAwIDAgMy43NC4yNi4xNi4xNiAwIDAgMSAuMjQuMTcgMy4zNSAzLjM1IDAgMCAwIDEuMjIgMy4yOGwuMzMuMjZhMy4zNiAzLjM2IDAgMCAwIDMuNjEuMzUuMTcuMTcgMCAwIDEgLjI1LjE3IDMuMzcgMy4zNyAwIDAgMCAxLjI3IDNsLjIzLjE4YTMuMzkgMy4zOSAwIDAgMCAzLjY1LjM0LjE5LjE5IDAgMCAxIC4yOS4xNGMuMTMuODQuNTczIDEuNiAxLjI0IDIuMTNsLjE0LjExYTMuNDIgMy40MiAwIDAgMCA1LjA4LTFsLjQ5LS44N2EzLjUzIDMuNTMgMCAwIDAtLjU5LTQuMzggMy40IDMuNCAwIDAgMC0zLjctLjU4LjEuMSAwIDAgMS0uMTQtLjFBMy4zNiAzLjM2IDAgMCAwIDM0LjY5IDY4Yy0uNS4wOS0uMzktLjM1LS4zOS0uMzVhMy4zNiAzLjM2IDAgMCAwLTQuNzMtNC4yMmMtLjM4LjA1LS4zMy0uMjktLjMzLS4yOWEzLjM0IDMuMzQgMCAwIDAtMS4xNy00LjQ4em02OS4zNi0yMi44N2wtLjMyLTFhNC42NyA0LjY3IDAgMCAwLTQuNDMtMy4xOGgtMTFhMTEuODUgMTEuODUgMCAwIDEtNC4yMi0uNzhMNTYuNzcgMjIuOWExMi45MSAxMi45MSAwIDAgMC0xMi40MiAxLjc5bC0xMyA5Ljg3YTIuNDcgMi40NyAwIDAgMCAuNjUgNC4wOCA4LjE1IDguMTUgMCAwIDAgNy4yMy0uMzZsMTEuMDYtNi4xOWEyIDIgMCAwIDEgMi40OS40N2wyNC4xMSAyOS4zYS42MS42MSAwIDAgMCAuODYuMDlsMS0uODNhMi44MiAyLjgyIDAgMCAxIDEuNzgtLjYzaDcuODNhMzkuODQgMzkuODQgMCAwIDEtNjIuNTIgMjEuMjVMMjguMDUgNzlhMS41IDEuNSAwIDAgMC0xLTIuNDRMMTEuNzggNzVhMS41IDEuNSAwIDAgMC0xLjU4IDJsNC43MSAxNC42MWExLjUgMS41IDAgMCAwIDIuNTkuMzlsMi4yNC0yLjc3YzE3LjAzMiAxMy4xMzMgNDAuNTk0IDEzLjc3MyA1OC4zMTQgMS41ODQgMTcuNzItMTIuMTkgMjUuNTUtMzQuNDIgMTkuMzc2LTU1LjAyNHoiIGZpbGw9IiMwMDAiIGZpbGwtcnVsZT0iZXZlbm9kZCIvPjwvc3ZnPg==',
                'public'               =>   true,
                'publicly_queriable'   =>   true,
                'show_ui'              =>   true,
                'show_in_nav_menus'    =>   true,
                'has_archive'          =>   true,
                'rewrite'              =>   array(
                    'slug'       =>   'listing',
                    'with_front' =>   false
                ),
                'hierarchical'          =>  false,
                'exclude_from_search'   =>  false,
                'taxonomies'            =>  array(),
                'supports'              =>  array('title', 'revisions', 'editor')
            )
        );

        $postType->addTableColumn(
            'category',
            __('Category'),
            true,
            function ($column, $postId) {
                $i = 0;
                $categories = get_the_terms($postId, 'listing-category');
                foreach ((array)$categories as $category) {
                    if ($i > 0) {
                        echo ', ';
                    }

                    echo $category->name;
                    $i++;
                }
            }
        );

        return $postType->slug;
    }

    /**
     * Create category taxonomy
     * @return void
     */
    public function taxonomies() : string
    {
        $categories = new \WpListings\Entity\Taxonomy(
            __('Category', 'wp-listings'),
            __('Categories', 'wp-listings'),
            'listing-category',
            array('listing'),
            array(
                'hierarchical' => true
            )
        );

        return $categories->slug;
    }

    /**
     * Get fields for all categories
     * @return array
     */
    public static function getCategoryFieldgroups() : array
    {
        // Get all fieldgroups from ACF
        $fieldgroups = acf_get_field_groups();

        // Filter the fieldgroups to only include wp-listings fieldgroups
        $fieldgroups = array_values(array_filter($fieldgroups, function ($item) {
            return substr($item['key'], 0, 11) === 'wp-listings';
        }));

        // Get fields for each fieldgroup
        foreach ($fieldgroups as &$fieldgroup) {
            $fieldgroup['fields'] = acf_get_fields($fieldgroup['key']);
        }

        return apply_filters('wp-listings/get_category_fieldgroups', $fieldgroups);
    }

    /**
     * Create ACF JSON exports for category specific fields
     * @param  int    $termId    Term id
     * @param  int    $taxId     Tax id
     * @param  string $tax       Tax slug
     * @return void
     */
    public function createTermsFieldJson($termId, $taxId, $tax)
    {
        $terms = get_terms(
            $tax,
            array(
                'hide_empty' => false
            )
        );

        foreach ($terms as $term) {
            $fields = get_field('listing_category_fields', $tax . '_' . $term->term_id);

            if (empty($fields)) {
                continue;
            }

            $json = $this->getCategoryFields($tax, $term, $fields);

            $filename = \WpListings\App::$uploadDir . '/' . $tax . '-' . $term->slug . '.json';

            $fp = fopen($filename, 'w');
            fwrite($fp, $json);
            fclose($fp);
        }
    }

    /**
     * Adds fields for category
     * @param string $tax    Taxonomy slug
     * @param object $term   Term object
     * @param array $fields  Fields data
     */
    public function getCategoryFields($tax, $term, $fields) : string
    {
        // Add local field group for category
        $fieldgroup = array(
            'key' => 'wp-listings_' . $tax . '_' . $term->slug . '_' . $term->term_id,
            'title' => $term->name,
            'fields' => array(),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'listing',
                    ),
                    array(
                        'param' => 'post_taxonomy',
                        'operator' => '==',
                        'value' => $tax . ':' . $term->slug,
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        );

        foreach ($fields as $field) {
            $fieldgroup['fields'][] = $this->getFieldArray($field);
        }

        return json_encode($fieldgroup);
    }

    /**
     * Get field array acf style
     * @param  array $field Field params
     * @return array        Acf field array
     */
    public function getFieldArray($field) : array
    {
        $array = array(
            'key' => 'field_' . uniqid(),
            'label' => $field['label'],
            'name' => sanitize_title($field['label']),
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        );

        switch ($field['type']) {
            case 'select':
                $array['type'] = 'select';
                $array['choices'] = array();
                foreach ($field['alternatives'] as $alternative) {
                    $array['choices'][$alternative['value']] = $alternative['value'];
                }
                break;

            case 'text':
                $array['type'] = 'text';
                break;
        }

        return $array;
    }
}
