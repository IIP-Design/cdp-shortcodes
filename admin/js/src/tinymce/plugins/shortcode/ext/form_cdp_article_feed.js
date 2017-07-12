var $ = tinymce.dom.DomQuery;  // tinymce jQuery implementation

var form_cdp_article_feed = Object.create( window[ 'form_cdp_base' ] );

form_cdp_article_feed.height =  200;
form_cdp_article_feed.action = "fetch_data_cdp_article_feed";

form_cdp_article_feed.getFields = function ( data ) {
    var self = this;  // need to use bind not tself

    var fields = [ 
        { 
            type: 'form',
            padding: 0,
            items: [
                {
                    type: 'textbox', 
                    label: 'Article Feed ID:',
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

form_cdp_article_feed.numbersOnly = function ( obj ) {
    var el =  obj.getEl();
    $(el).attr( 'type', 'number' ); 
    $(el).attr( 'min', '0' );
};