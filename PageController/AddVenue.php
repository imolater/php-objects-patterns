<?
/**
 * Шаблон Page Controller
 */

namespace PageController;

use ApplicationController\Domain\Venue;

class AddVenue extends Controller {
    public function process() {
        try {
            $request = $this->getRequest();
            $name = $request->getProperty( 'venueName' );
            $owner = $request->getProperty( 'venueOwner' );

            if ( empty( $name ) ) {
                $request->addMessage( 'Укажите имя' );
                $this->forward( 'addVenue', 'view' );
            } else if ( empty( $owner ) ) {
                $request->addMessage( 'Укадите владельца' );
                $this->forward( 'addVenue', 'view' );
            }

            $venue = new Venue( $name );
            $request->setProperty( 'venue', $venue );
            $this->forward( 'ListVenues' );
        } catch ( \Exception $e ) {
            $this->forward( 'error', 'view' );
        }
    }
}
