var $ = tinymce.dom.DomQuery;  // tinymce jQuery implementation

var form_cdp_article_feed = Object.create( window[ 'form_cdp_base' ] );

form_cdp_article_feed.height =  400;
form_cdp_article_feed.action = "fetch_data_cdp_article_feed";

form_cdp_article_feed.getFields = function ( data ) {
    var self = this;  // need to use bind not tself

    var fields = [ 
        { 
            type: 'form',
            padding: 0,
            items: [
                {
                    type: 'container',
                    items: this.getSites( data.sites ),
                    label: 'Sites',
                    name: 'sites'
                },
                {
                    type: 'textbox', 
                    label: 'How many posts to display:',
                    name: 'size', 
                    maxWidth: 70,
                    onPostRender: function (e) {
                        self.numbersOnly( e.target );
                    } 
                },
                {
                    type: 'textbox', 
                    label: 'Post types:',
                    name: 'types', 
                    maxWidth: 160
                },
                {
                    type: 'textbox', 
                    label: 'Language:',
                    name: 'langs', 
                    maxWidth: 160
                },
                {
                    type: 'textbox', 
                    label: 'Tags:',
                    name: 'tags', 
                    maxWidth: 160
                },
                {
                    type: 'textbox', 
                    label: 'Categories:',
                    name: 'categories', 
                    maxWidth: 160
                },
                {
                    type: 'textbox', 
                    label: 'Orientation:',
                    name: 'ui-direction', 
                    maxWidth: 160
                },
                {
                    type: 'textbox', 
                    label: 'UI Style:',
                    name: 'ui-style', 
                    maxWidth: 160
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

form_cdp_article_feed.getSites =  function () {
    return  [
        {type: 'checkbox', checked: true, text: 'share.america.gov', name: 'share'},
        {type: 'checkbox', checked: true, text: 'ylai.state.gov', name: 'ylai'}
    ]
};