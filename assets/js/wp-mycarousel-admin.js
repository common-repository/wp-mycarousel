/**
 * ################################################################################
 * WP MyCarousel
 * 
 * Copyright 2016 Eugen Mihailescu <eugenmihailescux@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * ################################################################################
 * 
 * Short description:
 * URL: http://mycarousel.mynixworld.info
 * 
 * Git revision information:
 * 
 * @version : 0.1-10 $
 * @commit  : 2757080da1745ce2d7d12fd377c87e3367ab56a3 $
 * @author  : eugenmihailescu <eugenmihailescux@gmail.com> $
 * @date    : Wed Dec 7 22:53:26 2016 +0100 $
 * @file    : wp-mycarousel-admin.js $
 * 
 * @id      : wp-mycarousel-admin.js | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

(function($, plugin_id) {
$(document).ready(function() {
var wordwrap = function(str, int_width, str_break, cut) {
var m = ((arguments.length >= 2) ? arguments[1] : 75);
var b = ((arguments.length >= 3) ? arguments[2] : '\n');
var c = ((arguments.length >= 4) ? arguments[3] : false);
var i, j, l, s, r;
str += '';
if (m < 1) {
return str;
}
for (i = -1, l = (r = str.split(/\r\n|\n|\r/)).length; ++i < l; r[i] += s) {
for (s = r[i], r[i] = ''; s.length > m; r[i] += s.slice(0, j) + ((s = s.slice(j)).length ? b : '')) {
j = c == 2 || (j = s.slice(0, m + 1).match(/\S*(\s)?$/))[1] ? m : j.input.length - j[0].length || c == 1 && m || j.input.length + (j = s
.slice(m).match(/^\S*/))[0].length;
}
}
return r.join('\n');
}
$('.button-discard-changes').click(function() {
var referer = window.location.href.match(/[&$]uri=([^&$]+)/);
if (null !== referer) {
var ref = atob(decodeURIComponent(referer[1]));
window.location.assign(ref);
} else
window.history.back();
});
$('span.edit>a').each(function(index, value) {
value.href += '&uri=' + btoa(window.location.href);
});
var carousel_id = window.location.href.match(/[?&]carousel_id=[^&]+/);
var status = window.location.href.match(/[?&]status=[^&]+/);
$('span.delete>a').each(function(index, value) {
(null !== carousel_id) && (value.href += carousel_id[0]);
value.href += '&uri=' + btoa(window.location.href);
});
$('a.table-action-new').each(function(index, value) {
(null !== carousel_id) && (value.href += carousel_id[0]);
(null !== status) && (value.href += status[0]);
value.href += '&uri=' + btoa(window.location.href);
});
$('.help_tip').each(function(index, value) {
$(value).attr('title', wordwrap($(this).attr('data-tip'), 30));
});
$('#' + plugin_id + '_progress').change(function() {
var e = $('#' + plugin_id + '_timer').closest('tr');
if (this.checked)
e.show();
else
e.hide();
});
$('#' + plugin_id + '_event').change(function() {
var e = $('#' + plugin_id + '_target').closest('tr'), f = $('#' + plugin_id + '_click').closest('tr'), s = $(this).val();
('url' != s) && e.hide() || e.show();
('' == s) && f.hide() || f.show();
});
$('#' + plugin_id + '_event').change();
$('#' + plugin_id + '_progress').change();
});
})(jQuery, 'wp-mycarousel');