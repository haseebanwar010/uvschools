<html>
    <head>
        <base href = "<?php echo base_url(); ?>" />
        <link href="assets/page_editor/stylesheets/toastr.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/page_editor/stylesheets/grapes.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/page_editor/stylesheets/grapesjs-preset-webpage.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/page_editor/stylesheets/tooltip.css" rel="stylesheet" type="text/css" />
        <link href="assets/page_editor/stylesheets/grapesjs-plugin-filestack.css" rel="stylesheet" type="text/css" />
        <link href="assets/page_editor/stylesheets/demos.css?v3" rel="stylesheet" type="text/css" />
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- <link href="assets/page_editor/stylesheets/page_style.css" rel="stylesheet" type="text/css" /> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="assets/page_editor/js/toastr.min.js"></script>
        <script src="assets/page_editor/js/grapes.min.js?v0.15.10"></script>
        <script src="assets/page_editor/js/grapesjs-preset-webpage.min.js?v0.1.11"></script>
        <script src="assets/page_editor/js/grapesjs-lory-slider.min.js?0.1.5"></script>
        <script src="assets/page_editor/js/grapesjs-tabs.min.js?0.1.1"></script>
        <script src="assets/page_editor/js/grapesjs-custom-code.min.js?0.1.2"></script>
        <script src="assets/page_editor/js/grapesjs-touch.min.js?0.1.1"></script>
        <script src="assets/page_editor/js/grapesjs-parser-postcss.min.js?0.1.1"></script>
        <script src="assets/page_editor/js/grapesjs-tooltip.min.js?0.1.1"></script>
        <script src="assets/page_editor/js/grapesjs-tui-image-editor.min.js?0.1.2"></script>
        <script src="assets/page_editor/js/grapesjs-typed.min.js?1.0.5"></script>
    </head>
    <body>
        <?php 
        echo $html;
        ?>
        <script type="text/javascript">
            var lp = './img/';
            var plp = '//placehold.it/350x250/';
            var images = [
                lp + 'team1.jpg', lp + 'team2.jpg', lp + 'team3.jpg', plp + '78c5d6/fff/image1.jpg', plp + '459ba8/fff/image2.jpg', plp + '79c267/fff/image3.jpg',
                plp + 'c5d647/fff/image4.jpg', plp + 'f28c33/fff/image5.jpg', plp + 'e868a2/fff/image6.jpg', plp + 'cc4360/fff/image7.jpg',
                lp + 'work-desk.jpg', lp + 'phone-app.png', lp + 'bg-gr-v.png'
            ];
            var base_url = '<?php echo base_url(); ?>';
            
            var editor = grapesjs.init({
                avoidInlineStyle: 1,
                height: '100%',
                storageManager:{
                 type: null
                },
                container: '#azeem',
                fromElement: 1,
                showOffsets: 1,
                assetManager: {
                    embedAsBase64: 1,
                    assets: images
                },
                
                styleManager: {clearProperties: 1},
                plugins: [
                    'gjs-preset-webpage',
                    'grapesjs-lory-slider',
                    'grapesjs-tabs',
                    'grapesjs-custom-code',
                    'grapesjs-touch',
                    'grapesjs-parser-postcss',
                    'grapesjs-tooltip',
                    'grapesjs-tui-image-editor',
                    'grapesjs-typed',
                ],
                pluginsOpts: {
                    'grapesjs-lory-slider': {
                        sliderBlock: {
                            category: 'Extra'
                        }
                    },
                    'grapesjs-tabs': {
                        tabsBlock: {
                            category: 'Extra'
                        }
                    },
                    'grapesjs-typed': {
                        block: {
                            category: 'Extra',
                            content: {
                                type: 'typed',
                                'type-speed': 40,
                                strings: [
                                    'Text row one',
                                    'Text row two',
                                    'Text row three',
                                ],
                            }
                        }
                    },
                    'gjs-preset-webpage': {
                        modalImportTitle: 'Import Template',
                        modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
                        modalImportContent: function (editor) {
                            return editor.getHtml() + '<style>' + editor.getCss() + '</style>'
                        },
                        filestackOpts: null, //{ key: 'AYmqZc2e8RLGLE7TGkX3Hz' },
                        aviaryOpts: false,
                        blocksBasicOpts: {flexGrid: 1},
                        customStyleManager: [{
                                name: 'General',
                                buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom'],
                                properties: [{
                                        name: 'Alignment',
                                        property: 'float',
                                        type: 'radio',
                                        defaults: 'none',
                                        list: [
                                            {value: 'none', className: 'fa fa-times'},
                                            {value: 'left', className: 'fa fa-align-left'},
                                            {value: 'right', className: 'fa fa-align-right'}
                                        ],
                                    },
                                    {property: 'position', type: 'select'}
                                ],
                            }, {
                                name: 'Dimension',
                                open: false,
                                buildProps: ['width', 'flex-width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
                                properties: [{
                                        id: 'flex-width',
                                        type: 'integer',
                                        name: 'Width',
                                        units: ['px', '%'],
                                        property: 'flex-basis',
                                        toRequire: 1,
                                    }, {
                                        property: 'margin',
                                        properties: [
                                            {name: 'Top', property: 'margin-top'},
                                            {name: 'Right', property: 'margin-right'},
                                            {name: 'Bottom', property: 'margin-bottom'},
                                            {name: 'Left', property: 'margin-left'}
                                        ],
                                    }, {
                                        property: 'padding',
                                        properties: [
                                            {name: 'Top', property: 'padding-top'},
                                            {name: 'Right', property: 'padding-right'},
                                            {name: 'Bottom', property: 'padding-bottom'},
                                            {name: 'Left', property: 'padding-left'}
                                        ],
                                    }],
                            }, {
                                name: 'Typography',
                                open: false,
                                buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-decoration', 'text-shadow'],
                                properties: [
                                    {name: 'Font', property: 'font-family'},
                                    {name: 'Weight', property: 'font-weight'},
                                    {name: 'Font color', property: 'color'},
                                    {
                                        property: 'text-align',
                                        type: 'radio',
                                        defaults: 'left',
                                        list: [
                                            {value: 'left', name: 'Left', className: 'fa fa-align-left'},
                                            {value: 'center', name: 'Center', className: 'fa fa-align-center'},
                                            {value: 'right', name: 'Right', className: 'fa fa-align-right'},
                                            {value: 'justify', name: 'Justify', className: 'fa fa-align-justify'}
                                        ],
                                    }, {
                                        property: 'text-decoration',
                                        type: 'radio',
                                        defaults: 'none',
                                        list: [
                                            {value: 'none', name: 'None', className: 'fa fa-times'},
                                            {value: 'underline', name: 'underline', className: 'fa fa-underline'},
                                            {value: 'line-through', name: 'Line-through', className: 'fa fa-strikethrough'}
                                        ],
                                    }, {
                                        property: 'text-shadow',
                                        properties: [
                                            {name: 'X position', property: 'text-shadow-h'},
                                            {name: 'Y position', property: 'text-shadow-v'},
                                            {name: 'Blur', property: 'text-shadow-blur'},
                                            {name: 'Color', property: 'text-shadow-color'}
                                        ],
                                    }],
                            }, {
                                name: 'Decorations',
                                open: false,
                                buildProps: ['opacity', 'background-color', 'border-radius', 'border', 'box-shadow', 'background'],
                                properties: [{
                                        type: 'slider',
                                        property: 'opacity',
                                        defaults: 1,
                                        step: 0.01,
                                        max: 1,
                                        min: 0,
                                    }, {
                                        property: 'border-radius',
                                        properties: [
                                            {name: 'Top', property: 'border-top-left-radius'},
                                            {name: 'Right', property: 'border-top-right-radius'},
                                            {name: 'Bottom', property: 'border-bottom-left-radius'},
                                            {name: 'Left', property: 'border-bottom-right-radius'}
                                        ],
                                    }, {
                                        property: 'box-shadow',
                                        properties: [
                                            {name: 'X position', property: 'box-shadow-h'},
                                            {name: 'Y position', property: 'box-shadow-v'},
                                            {name: 'Blur', property: 'box-shadow-blur'},
                                            {name: 'Spread', property: 'box-shadow-spread'},
                                            {name: 'Color', property: 'box-shadow-color'},
                                            {name: 'Shadow type', property: 'box-shadow-type'}
                                        ],
                                    }, {
                                        property: 'background',
                                        properties: [
                                            {name: 'Image', property: 'background-image'},
                                            {name: 'Repeat', property: 'background-repeat'},
                                            {name: 'Position', property: 'background-position'},
                                            {name: 'Attachment', property: 'background-attachment'},
                                            {name: 'Size', property: 'background-size'}
                                        ],
                                    }, ],
                            }, {
                                name: 'Extra',
                                open: false,
                                buildProps: ['transition', 'perspective', 'transform'],
                                properties: [{
                                        property: 'transition',
                                        properties: [
                                            {name: 'Property', property: 'transition-property'},
                                            {name: 'Duration', property: 'transition-duration'},
                                            {name: 'Easing', property: 'transition-timing-function'}
                                        ],
                                    }, {
                                        property: 'transform',
                                        properties: [
                                            {name: 'Rotate X', property: 'transform-rotate-x'},
                                            {name: 'Rotate Y', property: 'transform-rotate-y'},
                                            {name: 'Rotate Z', property: 'transform-rotate-z'},
                                            {name: 'Scale X', property: 'transform-scale-x'},
                                            {name: 'Scale Y', property: 'transform-scale-y'},
                                            {name: 'Scale Z', property: 'transform-scale-z'}
                                        ],
                                    }]
                            }, {
                                name: 'Flex',
                                open: false,
                                properties: [{
                                        name: 'Flex Container',
                                        property: 'display',
                                        type: 'select',
                                        defaults: 'block',
                                        list: [
                                            {value: 'block', name: 'Disable'},
                                            {value: 'flex', name: 'Enable'}
                                        ],
                                    }, {
                                        name: 'Flex Parent',
                                        property: 'label-parent-flex',
                                        type: 'integer',
                                    }, {
                                        name: 'Direction',
                                        property: 'flex-direction',
                                        type: 'radio',
                                        defaults: 'row',
                                        list: [{
                                                value: 'row',
                                                name: 'Row',
                                                className: 'icons-flex icon-dir-row',
                                                title: 'Row',
                                            }, {
                                                value: 'row-reverse',
                                                name: 'Row reverse',
                                                className: 'icons-flex icon-dir-row-rev',
                                                title: 'Row reverse',
                                            }, {
                                                value: 'column',
                                                name: 'Column',
                                                title: 'Column',
                                                className: 'icons-flex icon-dir-col',
                                            }, {
                                                value: 'column-reverse',
                                                name: 'Column reverse',
                                                title: 'Column reverse',
                                                className: 'icons-flex icon-dir-col-rev',
                                            }],
                                    }, {
                                        name: 'Justify',
                                        property: 'justify-content',
                                        type: 'radio',
                                        defaults: 'flex-start',
                                        list: [{
                                                value: 'flex-start',
                                                className: 'icons-flex icon-just-start',
                                                title: 'Start',
                                            }, {
                                                value: 'flex-end',
                                                title: 'End',
                                                className: 'icons-flex icon-just-end',
                                            }, {
                                                value: 'space-between',
                                                title: 'Space between',
                                                className: 'icons-flex icon-just-sp-bet',
                                            }, {
                                                value: 'space-around',
                                                title: 'Space around',
                                                className: 'icons-flex icon-just-sp-ar',
                                            }, {
                                                value: 'center',
                                                title: 'Center',
                                                className: 'icons-flex icon-just-sp-cent',
                                            }],
                                    }, {
                                        name: 'Align',
                                        property: 'align-items',
                                        type: 'radio',
                                        defaults: 'center',
                                        list: [{
                                                value: 'flex-start',
                                                title: 'Start',
                                                className: 'icons-flex icon-al-start',
                                            }, {
                                                value: 'flex-end',
                                                title: 'End',
                                                className: 'icons-flex icon-al-end',
                                            }, {
                                                value: 'stretch',
                                                title: 'Stretch',
                                                className: 'icons-flex icon-al-str',
                                            }, {
                                                value: 'center',
                                                title: 'Center',
                                                className: 'icons-flex icon-al-center',
                                            }],
                                    }, {
                                        name: 'Flex Children',
                                        property: 'label-parent-flex',
                                        type: 'integer',
                                    }, {
                                        name: 'Order',
                                        property: 'order',
                                        type: 'integer',
                                        defaults: 0,
                                        min: 0
                                    }, {
                                        name: 'Flex',
                                        property: 'flex',
                                        type: 'composite',
                                        properties: [{
                                                name: 'Grow',
                                                property: 'flex-grow',
                                                type: 'integer',
                                                defaults: 0,
                                                min: 0
                                            }, {
                                                name: 'Shrink',
                                                property: 'flex-shrink',
                                                type: 'integer',
                                                defaults: 0,
                                                min: 0
                                            }, {
                                                name: 'Basis',
                                                property: 'flex-basis',
                                                type: 'integer',
                                                units: ['px', '%', ''],
                                                unit: '',
                                                defaults: 'auto',
                                            }],
                                    }, {
                                        name: 'Align',
                                        property: 'align-self',
                                        type: 'radio',
                                        defaults: 'auto',
                                        list: [{
                                                value: 'auto',
                                                name: 'Auto',
                                            }, {
                                                value: 'flex-start',
                                                title: 'Start',
                                                className: 'icons-flex icon-al-start',
                                            }, {
                                                value: 'flex-end',
                                                title: 'End',
                                                className: 'icons-flex icon-al-end',
                                            }, {
                                                value: 'stretch',
                                                title: 'Stretch',
                                                className: 'icons-flex icon-al-str',
                                            }, {
                                                value: 'center',
                                                title: 'Center',
                                                className: 'icons-flex icon-al-center',
                                            }],
                                    }]
                            }
                        ],
                    },
                },

            });

            
            
            var pn = editor.Panels;
            var modal = editor.Modal;
            var cmdm = editor.Commands;
            cmdm.add('canvas-clear', function () {
                if (confirm('Areeee you sure to clean the canvas?')) {
                    var comps = editor.DomComponents.clear();
                    setTimeout(function () {
                        localStorage.clear()
                    }, 0)
                }
            });
            cmdm.add('set-device-desktop', {
                run: function (ed) {
                    ed.setDevice('Desktop')
                },
                stop: function () {},
            });
            cmdm.add('set-device-tablet', {
                run: function (ed) {
                    ed.setDevice('Tablet')
                },
                stop: function () {},
            });
            cmdm.add('set-device-mobile', {
                run: function (ed) {
                    ed.setDevice('Mobile portrait')
                },
                stop: function () {},
            });



            // Add info command
            var mdlClass = 'gjs-mdl-dialog-sm';
            cmdm.add('save-database', {
                run: function (em, sender) {
                    if (confirm('Are you sure do you want to Save this Page?')) {
                        sender.set('active', true);
                        var InnerHtml = editor.getHtml();
                        console.log(InnerHtml);
                        //var InnerHtml = this.frameEl.contentDocument.activeElement.innerHTML;
                        var csspage = editor.getCss();

                       
                         // console.log(InnerHtml);
                        //base_url+'calendar/getEvents'
                        // if(confirm('Are you sure to Save this Page?')) {
                        //   var comps = editor.DomComponents.clear();
                        //   setTimeout(function(){ localStorage.clear()}, 0)
                        // }
                        $.post(base_url + 'page/template_save', {html: InnerHtml, css: csspage},
                                function (result) {
                                    alert("Your Page Has Been Saved");
                                    sender.set('active', false);
                                }).fail(function (fail) {
                            console.log("there ia an error");
                            sender.set('active', false);
                        });
                    }
                },
            });


            pn.addButton('options', {
                id: 'save-database',
                className: 'fa fa-floppy-o',
                command: 'save-database',
                attributes: {
                    'title': 'Save',
                    'data-tooltip-pos': 'bottom',
                }
            });

            // Simple warn notifier
            var origWarn = console.warn;
            toastr.options = {
                closeButton: true,
                preventDuplicates: true,
                showDuration: 250,
                hideDuration: 150
            };
            console.warn = function (msg) {
                if (msg.indexOf('[undefined]') == -1) {
                    toastr.warning(msg);
                }
                origWarn(msg);
            };


            // Add and beautify tooltips
            [['sw-visibility', 'Show Borders'], ['preview', 'Preview'], ['fullscreen', 'Fullscreen'],
                ['export-template', 'Export'], ['undo', 'Undo'], ['redo', 'Redo'],
                ['gjs-open-import-webpage', 'Import'], ['canvas-clear', 'Clear canvas']]
                    .forEach(function (item) {
                        pn.getButton('options', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
                    });
            [['open-sm', 'Style Manager'], ['open-layers', 'Layers'], ['open-blocks', 'Blocks']]
                    .forEach(function (item) {
                        pn.getButton('views', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
                    });
            var titles = document.querySelectorAll('*[title]');

            for (var i = 0; i < titles.length; i++) {
                var el = titles[i];
                var title = el.getAttribute('title');
                title = title ? title.trim() : '';
                if (!title)
                    break;
                el.setAttribute('data-tooltip', title);
                el.setAttribute('title', '');
            }

            // Show borders by default
            pn.getButton('options', 'sw-visibility').set('active', 1);


            // Store and load events
            editor.on('storage:load', function (e) {
                console.log('Loaded ', e)
            });
            editor.on('storage:store', function (e) {
                console.log('Stored ', e)
            });


            // Do stuff on load
            editor.on('load', function () {
                var $ = grapesjs.$;

                // Show logo with the version
                var logoCont = document.querySelector('.gjs-logo-cont');
                document.querySelector('.gjs-logo-version').innerHTML = 'v' + grapesjs.version;
                var logoPanel = document.querySelector('.gjs-pn-commands');
                logoPanel.appendChild(logoCont);


                // Load and show settings and style manager
                var openTmBtn = pn.getButton('views', 'open-tm');
                openTmBtn && openTmBtn.set('active', 1);
                var openSm = pn.getButton('views', 'open-sm');
                openSm && openSm.set('active', 1);

                // Add Settings Sector
                var traitsSector = $('<div class="gjs-sm-sector no-select">' +
                        '<div class="gjs-sm-title"><span class="icon-settings fa fa-cog"></span> Settings</div>' +
                        '<div class="gjs-sm-properties" style="display: none;"></div></div>');
                var traitsProps = traitsSector.find('.gjs-sm-properties');
                traitsProps.append($('.gjs-trt-traits'));
                $('.gjs-sm-sectors').before(traitsSector);
                traitsSector.find('.gjs-sm-title').on('click', function () {
                    var traitStyle = traitsProps.get(0).style;
                    var hidden = traitStyle.display == 'none';
                    if (hidden) {
                        traitStyle.display = 'block';
                    } else {
                        traitStyle.display = 'none';
                    }
                });

                // Open block manager
                var openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
                openBlocksBtn && openBlocksBtn.set('active', 1);


                // Move Ad
                $('#azeem').append($('.ad-cont'));
            });
        </script>
        <script>
            var items = document.querySelectorAll('#iu7bu');
            for (var i = 0, len = items.length; i < len; i++) {
                (function () {
                    var e, t = 0, n = function () {
                        var e, t = document.createElement("void"), n = {
                            transition: "transitionend", OTransition: "oTransitionEnd", MozTransition: "transitionend", WebkitTransition: "webkitTransitionEnd"};
                        for (e in n)
                            if (void 0 !== t.style[e])
                                return n[e]
                    }
                    (), r = function (e) {
                        var t = window.getComputedStyle(e), n = t.display, r = (t.position, t.visibility, t.height, parseInt(t["max-height"]));
                        if ("none" !== n && "0" !== r)
                            return e.offsetHeight;
                        e.style.height = "auto", e.style.display = "block", e.style.position = "absolute", e.style.visibility = "hidden";
                        var i = e.offsetHeight;
                        return e.style.height = "", e.style.display = "", e.style.position = "", e.style.visibility = "", i
                    }
                    , i = function (e) {
                        t = 1;
                        var n = r(e), i = e.style;
                        i.display = "block", i.transition = "max-height 0.25s ease-in-out", i.overflowY = "hidden", "" == i["max-height"] && (i["max-height"] = 0), 0 == parseInt(i["max-height"]) ? (i["max-height"] = "0", setTimeout(function () {
                            i["max-height"] = n + "px"
                        }
                        , 10)) : i["max-height"] = "0"
                    }
                    , a = function (r) {
                        if (r.preventDefault(), !t) {
                            var a = this.closest("[data-gjs=navbar]"), o = a.querySelector("[data-gjs=navbar-items]");
                            i(o), e || (o.addEventListener(n, function () {
                                t = 0;
                                var e = o.style;
                                0 == parseInt(e["max-height"]) && (e.display = "", e["max-height"] = "")
                            }
                            ), e = 1)
                        }
                    };
                    "gjs-collapse"in this || this.addEventListener("click", a), this["gjs-collapse"] = 1
                }
                .bind(items[i]))();
            }
        </script>
          
    </body>

 <div id="azeem">
  <div data-gjs="navbar" class="navbar">
   <div class="navbar-container"><a href="/" class="navbar-brand">
    <img id="i0vg3" src="uploads/logos/uvs.png"/>
   </a>
   <div id="i0pas" class="navbar-burger">
    <div class="navbar-burger-line">
                        </div><div class="navbar-burger-line">
                        </div><div class="navbar-burger-line">
                        </div>
                       </div>
         <div data-gjs="navbar-items" class="navbar-items-c">
          <nav data-gjs="navbar-menu" class="navbar-menu"><a href="#home_landing" class="navbar-menu-link">HOME</a><a href="#principlemessage_school" class="navbar-menu-link">OUR SCHOOL</a><a href="#" class="navbar-menu-link">NEWS</a><a href="#Team_school" class="navbar-menu-link">Team</a><a href="#contct" class="navbar-menu-link">CONTACT US</a><a href="#why_choose_us" class="navbar-menu-link">ABOUT</a></nav></div></div><header id="home_landing" class="header-banner"><div class="container-width"><div class="logo-container"><div class="logo" id="irdb1">rizwan</div></div><div class="clearfix"></div><div class="lead-title">Build your templates without coding</div><div class="sub-lead-title">All text blocks could be edited easily with double clicking on it. You can create new text blocks with the command from the left panel</div><div class="lead-btn">Admissions</div></div></header><!-- <img id="ibcor" src="https://localhost/uv/myschool2/uploads/default_landing/mainSlid.jpg"/> --></div><section id="principlemessage_school" class="schl-sect"><div class="container-width"><div class="am-container"><div class="am-content"><div class="am-pre">Why Choose Us</div><div class="am-title">Manage your images with Asset Manager</div><div class="am-desc">You can create image blocks with the command from the left panel and edit them with double click</div><div class="am-post">Image uploading is not allowed in this demo</div></div><img onmousedown="return false" src="uploads/default_landing/Choose.jpg" class="img-principle"/></div></div></section><section id="why_choose_us" class="am-sect"><div class="overlay padding-120"><div class="container-width"><div class="am-container"><img onmousedown="return false" src="uploads/default_landing/choose_students.jpeg" class="img-phone"/><div class="am-content"><div class="supheading" id="ij3lw">Why Choose Us</div><div class="am-title" id="iukg2">Manage your images with Asset Manager</div><div class="am-desc"><p class="text-white" id="iq8o6">Dolor sit amet, dolor gravida placerat liberolorem ipsum dolor consectetur adipiscing elit, sed do eiusmod. Dolor sit amet consectetuer adipiscing elit, sed diam nonummy nibh euismod. Praesent interdum est gravida vehicula est node maecenas loareet morbi a dosis luctus novum est praesent. Praesent interdum est gravida vehicula est node maecenas loareet morbi a dosis luctus novum est praesent.</p></div><div class="am-post" id="it8lp">More benefit nonummy nibh euismod. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</div></div></div></div></div></section><div data-background="uploads/default_landing/event-bg.png" class="section bgi-cover-center"><div class="content-wrap"><div class="container"><div class="row"><div class="col-sm-12 col-md-12"><p class="supheading text-center">Our Events</p><h2 class="section-heading text-center mb-5">
                                    Do not miss our event
                                </h2></div></div><div class="row mt-4"><!-- Item 1 --><div class="col-sm-12 col-md-12 col-lg-4 mb-5"><div class="rs-news-1"><div class="media-box"><img src="images/event-1.jpg" alt="" class="img-fluid"/></div><div class="body-box"><div class="title">English Day on Carfree day</div><div class="meta-date">March 19, 2016 / 08:00 am - 10:00 am</div><p>We provide high quality design at vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores...</p><div class="text-center"><a href="page-events-single.html" class="btn btn-primary">JOIN NOW</a></div></div></div></div><!-- Item 2 --><div class="col-sm-12 col-md-12 col-lg-4 mb-5"><div class="rs-news-1"><div class="media-box"><img src="images/event-2.jpg" alt="" class="img-fluid"/></div><div class="body-box"><div class="title">Play & Study with Mrs. Smith</div><div class="meta-date">March 19, 2016 / 08:00 am - 10:00 am</div><p>We provide high quality design at vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores...</p><div class="text-center"><a href="page-events-single.html" class="btn btn-primary">JOIN NOW</a></div></div></div></div><!-- Item 3 --><div class="col-sm-12 col-md-12 col-lg-4 mb-5"><div class="rs-news-1"><div class="media-box"><img src="images/event-3.jpg" alt="" class="img-fluid"/></div><div class="body-box"><div class="title">Drawing at City Park</div><div class="meta-date">March 19, 2016 / 08:00 am - 10:00 am</div><p>We provide high quality design at vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores...</p><div class="text-center"><a href="page-events-single.html" class="btn btn-primary">JOIN NOW</a></div></div></div></div></div></div></div></div><section id="Team_school" class="bdg-sect"><div class="container-width"><h1 class="bdg-title">Meet Our Teachers</h1><div class="badges"><div class="badge"><div class="badge-header"></div><img src="assets/img/team1.jpg" class="badge-avatar"/><div class="badge-body"><div class="badge-name">Adam Smith</div><div class="badge-role">CEO</div><div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div></div><div class="badge-foot"><span class="badge-link">f</span><span class="badge-link">t</span><span class="badge-link">ln</span></div></div><div class="badge"><div class="badge-header"></div><img src="img/team2.jpg" class="badge-avatar"/><div class="badge-body"><div class="badge-name">John Black</div><div class="badge-role">Software Engineer</div><div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div></div><div class="badge-foot"><span class="badge-link">f</span><span class="badge-link">t</span><span class="badge-link">ln</span></div></div><div class="badge"><div class="badge-header"></div><img src="img/team3.jpg" class="badge-avatar"/><div class="badge-body"><div class="badge-name">Jessica White</div><div class="badge-role">Web Designer</div><div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div></div><div class="badge-foot"><span class="badge-link">f</span><span class="badge-link">t</span><span class="badge-link">ln</span></div></div></div></div></section><footer class="footer-under"><div class="container-width"><div class="footer-container"><div class="form-sub"><div class="foot-form-cont"><div class="footer-title">About Us</div><div class="foot-form-desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy.</div></div></div><div id="contct" class="footer-item"><div class="footer-title">Contact Info</div><ul class="list-info"><li><div class="info-icon"><span class="fa fa-map-marker"></span></div><div class="info-text">99 S.t Jomblo Park Pekanbaru 28292. Indonesia</div></li><li><div class="info-icon"><span class="fa fa-phone"></span></div><div class="info-text">(0761) 654-123987</div></li><li><div class="info-icon"><span class="fa fa-envelope"></span></div><div class="info-text">info@yoursite.com</div></li><li><div class="info-icon"><span class="fa fa-clock-o"></span></div><div class="info-text">Mon - Sat 09:00 - 17:00</div></li></ul></div><div class="footer-item"><div class="footer-title">Useful Links</div><ul class="list-info"><li><a href="#home_landing" class="navbar-menu-link">HOME</a></li><li><a href="#principlemessage_school" class="navbar-menu-link">Our School</a></li><li><a href="#home_landing" class="navbar-menu-link">News</a></li><li><a href="#why_choose_us" class="navbar-menu-link">ABOUT</a></li></ul></div><div class="clearfix"></div></div></div><div class="copyright"><div class="container-width"><div class="made-with">
                            Powered By United Vision Pvt. Ltd.
                        </div><div class="clearfix"></div></div></div></footer><div class="rizwan" id="irjrud"><div class="ftex" id="isvj8i">Copyright 2020 © <span id="im4zpu">UV SCHOOLS Template</span>. Designed by <span id="i0i1yc">United Vision Pvt. Ltd.</span></div></div></div>
</html>