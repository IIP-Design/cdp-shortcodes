var $ = tinymce.dom.DomQuery;  // tinymce jQuery implementation

var form_cdp_course = Object.create( window[ 'form_cdp_base' ] );

form_cdp_course.height =  200;
form_cdp_course.action = "fetch_data_cdp_course";

form_cdp_course.getFields = function ( data ) {
    var self = this;  // need to use bind not tself

    var fields = [ 
        { 
            type: 'form',
            padding: 0,
            items: [
                {
                    type: 'textbox', 
                    label: 'Course ID:',
                    name: 'id', 
                    maxWidth: 70,
                    onPostRender: function (e) {
                        self.numbersOnly( e.target );
                    } 
                }
            ]
        }
    ];

    return fields;
};

form_cdp_course.numbersOnly = function ( obj ) {
    var el =  obj.getEl();
    $(el).attr( 'type', 'number' ); 
    $(el).attr( 'min', '0' );
};