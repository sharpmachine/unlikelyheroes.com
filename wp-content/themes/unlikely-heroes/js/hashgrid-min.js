/**
 * hashgrid (jQuery version, adapters are on the way)
 * http://github.com/dotjay/hashgrid
 * Version 9, 30 May 2013
 * Written by Jon Gibbins, http://dotjay.co.uk/
 *
 * Contibutors:
 * James Aitken, http://loonypandora.co.uk/
 * Tom Arnold, http://www.tomarnold.de/
 * Sean Coates, http://seancoates.com/
 * Phil Dokas, http://jetless.org/
 * Andrew Jaswa, http://andrewjaswa.com/
 * Callum Macrae, http://lynx.io/
 *//**
 * @license Copyright 2013 Analog Coop Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *//**
 * Usage
 *
 * // The basic #grid setup looks like this
 * var grid = new hashgrid();
 *
 * // But there are a whole bunch of additional options you can set
 * var grid = new hashgrid({
 *     id: 'mygrid',            // set a custom id for the grid container
 *     modifierKey: 'alt',      // optional 'ctrl', 'alt' or 'shift'
 *     showGridKey: 's',        // key to show the grid
 *     holdGridKey: 'enter',    // key to hold the grid in place
 *     foregroundKey: 'f',      // key to toggle foreground/background
 *     jumpGridsKey: 'd',       // key to cycle through the grid classes
 *     numberOfGrids: 2,        // number of grid classes used
 *     classPrefix: 'myclass',  // prefix for the grid classes
 *     cookiePrefix: 'mygrid'   // prefix for the cookie name
 * });
 *//**
 * Make sure we have the library
 * TODO: Use an adapter
 */typeof jQuery=="undefined"&&alert("Hashgrid: jQuery not loaded. Make sure it's linked to your pages.");var hashgrid=function(e){function T(e){if(n.modifierKey===null)return!0;var t=!0;switch(n.modifierKey){case"ctrl":t=e.ctrlKey?e.ctrlKey:!1;break;case"alt":t=e.altKey?e.altKey:!1;break;case"shift":t=e.shiftKey?e.shiftKey:!1}return t}function N(e){var t=!1,n=e.keyCode?e.keyCode:e.which;n==13?t="enter":t=String.fromCharCode(n).toLowerCase();return t}function C(){O(n.cookiePrefix+n.id,(S?"1":"0")+"-"+m+"-"+i,1)}function k(){c.show();v.css({width:c.width()});v.children(".vert").each(function(){var e=t(this);e.css("display","inline-block");e.offset().top>e.parent().offset().top&&e.hide()})}function L(e){var t,s,o=e.target.tagName.toLowerCase();if(o=="input"||o=="textarea"||o=="select")return!0;s=T(e);if(!s)return!0;t=N(e);if(!t)return!0;if(r[t])return!0;r[t]=!0;switch(t){case n.showGridKey:if(!d){k();d=!0}else if(S){c.hide();d=!1;S=!1;C()}break;case n.holdGridKey:if(d&&!S){S=!0;C()}break;case n.foregroundKey:if(d){if(c.css("z-index")==y){c.css("z-index",g);m="B"}else{c.css("z-index",y);m="F"}C()}break;case n.jumpGridsKey:if(d&&n.numberOfGrids>1){c.removeClass(n.classPrefix+i);i++;i>n.numberOfGrids&&(i=1);c.addClass(n.classPrefix+i);k();/webkit/.test(navigator.userAgent.toLowerCase())&&D();C()}}return!0}function A(e){var t,i=T(e);if(!i)return!0;t=N(e);r[t]=!1;if(t&&t==n.showGridKey&&!S){c.hide();d=!1}return!0}function O(e,t,n){var r,i="";if(n){r=new Date;r.setTime(r.getTime()+n*24*60*60*1e3);i="; expires="+r.toGMTString()}document.cookie=e+"="+t+i+"; path=/"}function M(e){var t,n=document.cookie.split(";"),r=0,i=n.length,s=e+"=";for(;r<i;r++){t=n[r];while(t.charAt(0)==" ")t=t.substring(1,t.length);if(t.indexOf(s)===0)return t.substring(s.length,t.length)}return null}function _(e){O(e,"",-1)}function D(){var e=document.styleSheets[0];try{e.addRule(".xxxxxx","position: relative");e.removeRule(e.rules.length-1)}catch(t){}}var t=jQuery,n={id:"grid",modifierKey:null,showGridKey:"g",holdGridKey:"h",foregroundKey:"f",jumpGridsKey:"j",numberOfGrids:1,classPrefix:"grid-",cookiePrefix:"hashgrid"},r,i=1,s,o,u,a,f,l,c,h,p,d=!1,v,m="B",g=-1,y=9999,b,w,E,S=!1,x;if(typeof e=="object")for(w in e)n[w]=e[w];else typeof e=="string"&&(n.id=e);t("#"+n.id).length>0&&t("#"+n.id).remove();p=t("<div></div>");p.attr("id",n.id).css({display:"none",pointerEvents:"none"});t("body").prepend(p);c=t("#"+n.id);c.css("z-index")=="auto"&&c.css("z-index",g);b=parseFloat(t(document).height());c.height(b);c.append('<div id="'+n.id+'-horiz" class="horiz first-line">');x=c.css("top");c.css({top:"-999px",display:"block"});a=t("#"+n.id+"-horiz");f=a.outerHeight();c.css({display:"none",top:x});if(f<=0)return!1;l=Math.floor(b/f);s="";for(u=l-1;u>=1;u--)s+='<div class="horiz"></div>';c.append(s);c.append(t('<div class="vert-container"></div>'));v=c.children(".vert-container");o=c.width();v.css({width:o,position:"absolute",top:0});v.append('<div class="vert first-line">&nbsp;</div>');s="";for(u=0;u<30;u++)s+='<div class="vert">&nbsp;</div>';v.append(s);v.children().height(b).css({display:"inline-block"});h=M(n.cookiePrefix+n.id);if(typeof h=="string"){E=h.split("-");E[2]=Number(E[2]);if(typeof E[2]=="number"&&!isNaN(E[2])){i=E[2].toFixed(0);c.addClass(n.classPrefix+i)}if(E[1]=="F"){m="F";c.css("z-index",y)}if(E[0]=="1"){d=!0;S=!0;k()}}else c.addClass(n.classPrefix+i);t(document).bind("keydown",L);t(document).bind("keyup",A);r={};return{}};