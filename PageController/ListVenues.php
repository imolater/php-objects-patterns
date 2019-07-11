<?php
/**
 * Шаблон Page Controller
 */

namespace PageController;

class ListVenues extends Controller {
    public function process() {
        $request = $this->getRequest();
        $venue = $request->getProperty( 'venue' );

        if ( ! empty( $venue ) ) {
            $this->forward( 'listVenues', 'view' );
        }
    }
}