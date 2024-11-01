(function(plugin_id) {
var btn_slug = plugin_id.replace('-', '_'), params = window[btn_slug + '_params'];
tinymce.PluginManager.add(btn_slug, function(editor) {
function showDialog() {
var gridHtml, x, y, win;
gridHtml = '<table id="mce-' + plugin_id + '" role="presentation" cellspacing="0"><tbody>';
gridHtml += '<tr><th>Id</th><th>Carousel</th></tr>';
for (var i = 0; i < params.items.length; i += 1) {
gridHtml += '<tr><td>' + params.items[i].ID + '</td><td>' + params.items[i].name + '</td></tr>';
}
gridHtml += '</tbody></table>';
var carouselsPanel = { type : 'container',
html : gridHtml,
onclick : function(e) {
var target = e.target;
if (/^(TD|DIV)$/.test(target.nodeName)) {
var td = target.parentNode.firstChild;
if (td) {
editor.execCommand('mceInsertContent', false, '[' + plugin_id + ' id="' + tinymce.trim(td.innerHTML || td.textContent) + '"]');
if (!e.ctrlKey) {
win.close();
}
}
}
} };
win = editor.windowManager.open({ title : "WP MyCarousel",
spacing : 10,
padding : 10,
items : [ carouselsPanel, { type : 'container',
layout : 'flex',
direction : 'column',
align : 'center',
spacing : 5,
minWidth : 160,
minHeight : 160 } ],
buttons : [ { text : "Close",
onclick : function() {
win.close();
} } ] });
}
editor.addCommand(btn_slug, showDialog);
editor.addButton(btn_slug, { icon : 'dashicon dashicons-slides',
tooltip : 'Insert ' + plugin_id,
cmd : btn_slug });
});
})('wp-mycarousel');