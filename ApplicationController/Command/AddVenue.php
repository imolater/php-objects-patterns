<?

namespace ApplicationController\Command;

include("domain/Venue.php");
use ApplicationController\Domain\Venue;
use ApplicationController\Request;

class AddVenue extends Command {
    public function doExecute( Request $request ) {
        $name = $request->getProperty( "venueName" );

        if ( is_null( $name ) ) {
            $request->addTemplateData('errorMsg', "Имя заведения не задано" );
            return self::getStatusCode( 'INCORRECT_DATA' );
        } else {
            $venue = new Venue( $name );
            $request->setProperty( 'venue', $venue );
            $request->addTemplateData('sucMsg', "'$name' успешно добавлено с id = {$venue->getId()}" );
            return self::getStatusCode( 'OK' );
        }
    }
}