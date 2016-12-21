<?php

namespace WpListings\PostType;

class Listings extends \WpListings\Entity\PostType
{
    public function __construct()
    {
        parent::__construct(
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
    }
}
