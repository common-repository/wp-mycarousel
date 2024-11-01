(function($, plugin_id) {
function renderMediaUploader() {
'use strict';
var file_frame, image_data, json;
if (undefined !== file_frame) {
file_frame.open();
return;
}
file_frame = wp.media.frames.file_frame = wp.media({ frame : 'post',
state : 'insert',
multiple : false });
file_frame.on('insert', function() {
json = file_frame.state().get('selection').first().toJSON();
if (0 > $.trim(json.url.length)) {
return;
}
$('#featured-footer-image-container').children('img').attr('src', json.url).attr('alt', json.caption).attr('title', json.title).show().parent()
.removeClass('hidden');
$('#featured-footer-image-container').prev().hide();
$('#featured-footer-image-container').next().show();
$('.thumbnail-src').val(json.url);
$('#' + plugin_id + '_description').val(json.description);
$('#' + plugin_id + '_annotation').val(json.caption);
});
file_frame.open();
}
function resetUploadForm() {
'use strict';
$('#featured-footer-image-container').children('img').hide();
$('#featured-footer-image-container').prev().show();
$('#featured-footer-image-container').next().hide().addClass('hidden');
$('#featured-footer-image-meta').children().val('');
}
function renderFeaturedImage() {
var img_src = $.trim($('.thumbnail-src').val());
if ('' !== img_src) {
$('#featured-footer-image-container img').attr('src', img_src);
$('#featured-footer-image-container').removeClass('hidden');
$('#set-footer-thumbnail').hide();
$('#remove-footer-thumbnail').parent().removeClass('hidden');
}
}
'use strict';
$(function() {
$('#set-footer-thumbnail').on('click', function(evt) {
evt.preventDefault();
renderMediaUploader();
});
$('#remove-footer-thumbnail').on('click', function(evt) {
evt.preventDefault();
resetUploadForm($);
});
renderFeaturedImage();
});
})(jQuery, 'wp-mycarousel');