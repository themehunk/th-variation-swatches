(function ($){
    var THVSsettingLib = {
        init: function (){
            this.bindEvents();
        },
        bindEvents: function (){
          var $this = this;
            $this.SettingTab();
            $this.ImageAdd();
            $this.RemoveImage();
            $this.SaveSetting();
            $this.ChangeSetting();
            $this.ColorPiker();
            $this.ShapeStyle();
            $this.TooltipToggle();
            $this.LiveStylePreview();
            $this.LiveTextPreview();
        },

                LiveStylePreview: function () {

  function applyPreview(inputId, value) {

    /* ---------- NUMBERS ---------- */

    applyNumberCss('font-size', 'data-th-font-size');
    applyNumberCss('width', 'data-th-width');
    applyNumberCss('height', 'data-th-height');
    applyNumberCss('line-height', 'data-th-line-height');
    applyNumberCss('border-radius', 'data-th-radius');
    applyNumberCss('padding', 'data-th-padding');
    applyNumberCss('margin', 'data-th-margin');
    applyNumberCss('letter-spacing', 'data-th-letter-spacing');
    applyNumberCss('top', 'data-th-top');
    applyNumberCss('right', 'data-th-right');
    applyNumberCss('bottom', 'data-th-bottom');
    applyNumberCss('left', 'data-th-left');
    applyNumberCss('max-width', 'data-th-max-width');
    applyNumberCss('min-height', 'data-th-min-height');

    function applyNumberCss(cssProp, attrName) {
      $('[' + attrName + '="' + inputId + '"]').each(function () {
        var unit = $(this).data('th-unit');
        unit = (unit === undefined) ? 'px' : unit;
        $(this).css(cssProp, value + unit);
      });
    }

    /* ---------- ADVANCED PROPERTIES ---------- */

    // gap
    $('[data-th-gap="' + inputId + '"]').each(function () {
      var unit = $(this).data('th-unit');
      unit = (unit === undefined) ? 'px' : unit;
      $(this).css('gap', value + unit);
    });

    // z-index
    $('[data-th-z-index="' + inputId + '"]').css('z-index', value);

    // opacity
    $('[data-th-opacity="' + inputId + '"]').css('opacity', value);

    // transform: scale
    $('[data-th-transform="' + inputId + '"]').css('transform', 'scale(' + value + ')');

  }


  // Listen to Number Control updates
  $(document).on('input change', 'input[type="number"]', function () {
    if (this.id) {
      applyPreview(this.id, $(this).val());
    }
  });

},



LiveTextPreview: function () {

  function applyText(inputId, value) {

    // TEXT ONLY
    $('[data-th-text="' + inputId + '"]').each(function () {
      $(this).text(value);
    });

    // HTML ALLOWED
    $('[data-th-text-html="' + inputId + '"]').each(function () {
      $(this).html(value);
    });

  }

  // Live typing
  $(document).on('input keyup change', 'input[type="text"], textarea', function () {
    if (!this.id) return;
    applyText(this.id, $(this).val());
  });

  // Apply saved values on page load
  $(document).ready(function () {
    $('input[type="text"], textarea').each(function () {
      if (this.id && $(this).val()) {
        applyText(this.id, $(this).val());
      }
    });
  });

},

         // =========================
        // Shape Style Function
        // =========================
   ShapeStyle: function (){

    var selector = 'input[name="th_variation_swatches[style]"]';

    function applyShapeStyle(){

        var shape = $(selector + ':checked').val();

        // re-select every time because elements may reload after refresh/ajax
        var $colorOption = $('.variation-options.colors .color-option, .variation-options.colors .color-option span');

        if(shape === 'rounded'){

            $colorOption.css({
                'border-radius' : '100%',
                'padding' : '0'
            });

        } else {

            $colorOption.css({
                'border-radius' : '0',
                'padding' : '0'
            });

        }
    }

    // DOM ready + page refresh
    $(window).on('load', function (){
        applyShapeStyle();
    });

    // radio change
    $(document).on('change', selector, applyShapeStyle);
},

TooltipToggle: function (){

    var selector = '#tooltip-field';
    var styleId  = 'thvs-tooltip-style';

    function applyTooltip(){

        var isEnabled = $(selector).is(':checked');

        // remove old style
        $('#' + styleId).remove();

        // if disabled then inject css via js
        if(!isEnabled){

            $('head').append(
                '<style id="' + styleId + '">' +
                '.variation-options.colors .color-option::before,' +
                '.variation-options.colors .color-option::after{' +
                'display:none !important;' +
                '}' +
                '</style>'
            );

            $('#tooltip_background_color-wrapper,#tooltip_text_color-wrapper').css('display', 'none');
        }
        else{
            $('#tooltip_background_color-wrapper,#tooltip_text_color-wrapper').css('display', 'flex');
        }
    }

    // page refresh/load
    $(window).on('load', applyTooltip);

    // checkbox change
    $(document).on('change', selector, applyTooltip);
},
        SettingTab: function (){
          $(document).ready(function(){ 
                 $('#thvs').on('click', '.nav-tab', function (event){
                  event.preventDefault()
                  var target = $(this).data('target')
                  $(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active')
                  $('#' + target).show().siblings().hide()
                  $('#_last_active_tab').val(target)

                  if ($("a[data-target='thvs_reset']").hasClass('nav-tab-active')){
                         $('.preview-reset-wrapper').show();
                    }else{
                         $('.preview-reset-wrapper').hide();
                    }

                if ($("a[data-target='thvs_help']").hasClass('nav-tab-active')){
                         $('.setting-preview-wrap.help-wrapper').show();
                    }else{
                         $('.setting-preview-wrap.help-wrapper').hide();
                    }

                    if ($("a[data-target='thvs_style']").hasClass('nav-tab-active')){
                         $('.setting-preview-wrap.style-wrapper').css('display', 'flex');
                    }else{
                         $('.setting-preview-wrap.style-wrapper').hide();
                    }

                   // ===== header title change =====
                  var tabText = $(this).clone().children().remove().end().text().trim();
                  $('.tabheading').text(tabText);

                    /* Dynamic class add */
                        var wrap = $('.setting-wrap');

                        // keep only 'setting-wrap'
                        wrap.attr('class', 'setting-wrap');

                        // add the clicked button's class
                        wrap.addClass(target);

                });

        $("#thvs_special input,#thvs_special select,#th-swatches-style-field").prop("disabled", true);
          });
        },
         ColorPiker: function (){
        jQuery(document).ready(function ($) {

            function applyHoverColor(inputId, value) {

          var styleId = 'hover-style-' + inputId;
          $('#' + styleId).remove();

          var css = '';

          // background hover
          $('[data-th-bg-hover="' + inputId + '"]').each(function () {
            var selector = getSelector(this);

            css += selector + ':hover { border-color: ' + value + ' !important; }';
            css += selector + ':nth-child(4){ background-color: ' + value + ' !important; }';

          });

          // text hover
          $('[data-th-color-hover="' + inputId + '"]').each(function () {
            var selector = getSelector(this);

            // css += selector + ':hover { color: ' + value + ' !important; }';
            css += selector + ':hover { color: #111!important; }';
            css += selector + ':nth-child(4){ color: ' + value + ' !important; }';
            
          });

           // Border Hover
          $('[data-th-border-hover="' + inputId + '"]').each(function () {
            var selector = getSelector(this);

            css += selector + ':hover { border-color: ' + value + ' !important; }';
            css += selector + ':nth-child(4){ border-color: ' + value + ' !important; }';

          });

           // tooltip bg
          $('[data-th-bg-tooltip="' + inputId + '"]').each(function () {
            var selector = getSelector(this);

            css += selector + ':after { border-top-color: ' + value + ' !important; }';
            css += selector + ':before { background-color: ' + value + ' !important; }';
          });

           // tooltip text color
          $('[data-th-color-tooltip="' + inputId + '"]').each(function () {
            var selector = getSelector(this);

            css += selector + ':before { color: ' + value + ' !important; }';
          });


          $('head').append('<style id="' + styleId + '">' + css + '</style>');
}

           function getSelector(el) {

  // priority 1: ID
  if (el.id) {
    return '#' + el.id;
  }

  // priority 2: unique class
  if (el.className) {
    var classes = el.className.trim().split(/\s+/).join('.');
    return el.tagName.toLowerCase() + '.' + classes;
  }

  // fallback (rare case)
  return el.tagName.toLowerCase();
}

  function applyPreview(inputId, value) {
    $('[data-th-bg="' + inputId + '"]').css('background-color', value);
    $('[data-th-color="' + inputId + '"]').css('color', value);
    $('[data-th-border="' + inputId + '"]').css('border-color', value);
  }

  // ✅ INIT ON LOAD
  $('.thvs-color-picker').each(function () {
    var val = $(this).val();
    if (val) applyPreview(this.id, val);
     // hover
    applyHoverColor(this.id, val);
  });

  // ✅ COLOR PICKER CHANGE
  $('.thvs-color-picker').wpColorPicker({
    change: function (event, ui) {
       var id = event.target.id;
    var value = ui.color.toString();

    // normal
    applyPreview(id, value);

    // hover
    applyHoverColor(id, value);
    }
  });

  // 🔥 IMPORTANT: ALPHA SLIDER LIVE TRACK
  $(document).on('mousemove', '.iris-slider, .iris-square', function () {

    $('.thvs-color-picker').each(function () {
      var inputId = this.id;
      var value = $(this).val(); // updated rgba

      if (value) {
        applyPreview(inputId, value);
      }
    });

  });

});
        },

        ImageAdd:function (){
           $(document).on('click', 'button.thvs_upload_image_button', function (event){
                var _this = this;
                event.preventDefault();
                event.stopPropagation();

                var file_frame = void 0;

                if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {

                    // If the media frame already exists, reopen it.
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.select_image = wp.media({
                        title: THVSPluginObject.media_title,
                        button: {
                            text: THVSPluginObject.button_title
                        },
                        multiple: false
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        var attachment = file_frame.state().get('selection').first().toJSON();

                        if ($.trim(attachment.id) !== '') {

                            var url = typeof attachment.sizes.thumbnail === 'undefined' ? attachment.sizes.full.url : attachment.sizes.thumbnail.url;

                            $(_this).prev().val(attachment.id);
                            $(_this).closest('.meta-image-field-wrapper').find('img').attr('src', url);
                            $(_this).next().show();
                        }
                        //file_frame.close();
                    });

                    // When open select selected
                    file_frame.on('open', function () {

                        // Grab our attachment selection and construct a JSON representation of the model.
                        var selection = file_frame.state().get('selection');
                        var current = $(_this).prev().val();
                        var attachment = wp.media.attachment(current);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });

                    // Finally, open the modal.
                    file_frame.open();
                }
          });
        },
        RemoveImage:function (){
          $(document).on('click', 'button.thvs_remove_image_button', function (event){
                 event.preventDefault();
                 event.stopPropagation();

                var placeholder = $(this).closest('.meta-image-field-wrapper').find('img').data('placeholder');
                $(this).closest('.meta-image-field-wrapper').find('img').attr('src', placeholder);
                $(this).prev().prev().val('');
                $(this).hide();
                return false;
              });

        },
        
        SaveSetting:function(){
        $(document).on('keyup change paste', '.thvs-setting-form input, .thvs-setting-form select', function () {
        
              $('#submit').removeAttr("disabled");
              
        });  
        $(document).on("click", ".thvs-button-wrapper #submit", function (e) {
        e.preventDefault();
        $(this).addClass('loader');
        
        var form_settting = $(".thvs-setting-form").serialize();
        $.ajax({
          url: THVSPluginObject.ajaxurl,
          type: "POST",
          data: form_settting +'&_wpnonce=' + THVSPluginObject.nonce +'',
          success: function (response) {
           
            $('#submit').removeClass('loader');
            $('#submit').attr("disabled","disabled");

          },
        });
      });
    },
    ChangeSetting:function(){
             $(document).on('click', '#show_title-field', function (event){
                   if($(this).is(':checked')){
                    $('#show_variation_label-wrapper').show('100');
                   }else{
                      $('#show_variation_label-wrapper').hide('100');
                   }
                   
             });
     },
}
THVSsettingLib.init();
})(jQuery);

jQuery(document).ready(function ($) {

    $('#thvs-toggle-sidebar').on('click', function () {

        $('#thvs .nav-tab-wrapper').toggleClass('thvs-sidebar-collapsed');

        // change arrow direction
        $(this).find('.dashicons')
        .toggleClass('dashicons-arrow-left-alt2 dashicons-arrow-right-alt2');

    });

       function handleSidebarOnResize() {
            if ($(window).width() <= 768) {
                $('#thvs .nav-tab-wrapper').addClass('thvs-sidebar-collapsed');
            } else {
                $('#thvs .nav-tab-wrapper').removeClass('thvs-sidebar-collapsed');
            }
        }

        // Run on load
        handleSidebarOnResize();

        // Run on resize
        $(window).on('resize', handleSidebarOnResize);


});

jQuery(document).ready(function($) {

    function updateBorder() {
        var borderWidth = $('#attr_brdr_size-field').val();

        if (borderWidth !== '') {
            borderWidth = borderWidth + 'px';
        }

        $('.style-wrapper .color-option,.style-wrapper.size-option').css({
            'border-width': borderWidth,
        });
    }

    // Run on page load
    updateBorder();

    // Run on input change
    $('#attr_brdr_size-field').on('input change', function() {
        updateBorder();
    });

});


jQuery(document).ready(function($) {

    function updateBorder() {
        var borderWidth = $('#attr_brdr_size-field').val();

        if (borderWidth !== '') {
            borderWidth = borderWidth + 'px';
        }

        $('.style-wrapper .color-option,.style-wrapper.size-option').css({
            'border-width': borderWidth,
        });
    }

    // Run on page load
    updateBorder();

    // Run on input change
    $('#attr_brdr_size-field').on('input change', function() {
        updateBorder();
    });


    // Swatches style in single page

    // 1. Apni saari settings aur unke rules ka ek configuration object banayein
const swatchesConfig = {
    // Pehli setting (Jo aapke paas pehle se thi - 2 options)
    "th_variation_swatches[th-swatches-style]": {
        "thswatche": function() {
            $('.variation-group .variation-options').css('margin-left', '0');
        },
        "default": function() { // Agar 'thswatche' nahi hai to ye chalega (Else condition)
            $('.variation-group .variation-options').css('margin-left', '39px');
        }
    },
    
    // Doosri setting (Jisme aapki 3 values hain)
    // ISME APNI REAL SETTING KA NAAM AUR CLASSES BADAL LEIN
    "th_variation_swatches[attribute_behavior]": { 
        "blur": function() {
            // Pehle baaki dono classes hatao, fir 'blur' add karo
            $('.variation-group.disabled').removeClass('blur-no-cross hide').addClass('blur');
        },
        "blur-no-cross": function() {
            // Pehle baaki dono classes hatao, fir 'blur-no-cross' add karo
            $('.variation-group.disabled').removeClass('blur hide').addClass('blur-no-cross');
        },
        "hide": function() {
            // Pehle baaki dono classes hatao, fir 'hide' add karo
            $('.variation-group.disabled').removeClass('blur blur-no-cross').addClass('hide');
        },
        "default": function() {
            // Agar koi bhi radio selected nahi hai ya default state chahiye, toh saari temporary classes hata do
            $('.variation-group.disabled').removeClass('blur blur-no-cross hide');
        }
    }
};

// 2. Main Reusable Function jo selected setting aur uski value ke hisab se action lega
function handleSettingToggle(settingName) {
    // Selected radio button ki value nikalen
    var selectedValue = $(`input[name='${settingName}']:checked`).val();
    
    // Check karein kya ye setting humare config object mein exist karti hai
    if (swatchesConfig[settingName]) {
        var actions = swatchesConfig[settingName];
        
        // Agar selected value ka specific function hai to use chalayein, nahi to default chalayein
        if (typeof actions[selectedValue] === 'function') {
            actions[selectedValue]();
        } else if (typeof actions['default'] === 'function') {
            actions['default']();
        }
    }
}

// 3. Document Ready / Page Load par saari configured settings ko initialize karein
    
    // Loop chala kar page load par hi sabhi settings ka default state set kar dein
    Object.keys(swatchesConfig).forEach(function(settingName) {
        handleSettingToggle(settingName);
        
        // 4. Dynamic Event Listener: Har setting ke change hone par ye chalega
        $(document).on('change', `input[name='${settingName}']`, function() {
            handleSettingToggle(settingName);
        });
    });

   
   // Shop Page Variation alignment
       function updateAlignment() {
        var alignment = $('#show_swatches_shop_attr_alignment-field').val();

        var justifyContent = 'flex-start';

        if (alignment === 'center') {
            justifyContent = 'center';
        } else if (alignment === 'right') {
            justifyContent = 'flex-end';
        }

        $('.variation-options').css('justify-content', justifyContent);
    }

    // Initial load
    updateAlignment();

    // On select change
    $('#show_swatches_shop_attr_alignment-field').on('change', function () {
        updateAlignment();
    });

});
