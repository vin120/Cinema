$(function(){var a=80;$(document).ready(function(){var d=$("#selected-seats"),f=$("#counter"),c=$("#total");var e=$("#seat-map").seatCharts({map:["aaaaaaaaaaaa aaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","_aaaaaaaaaaa aaaaaaaaaaa","_aaaaaaaaaaa aaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaa","_aaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","aaaaaaaaaaaa aaaaaaaaaaaa","__aaaaaaaaaa aaaaaaaaaa","aaaaaaaaaaaa aaaaaaaa",],naming:{top:false,getLabel:function(h,i,g){return g}},legend:{node:$("#legend"),items:[["a","available","可選座"],["a","selected","已選座"],["a","unavailable","已售出"]]},click:function(){if(this.status()=="available"){$("<li>"+(this.settings.row+1)+"行"+this.settings.label+"座</li>").attr("id","cart-item-"+this.settings.id).data("seatId",this.settings.id).appendTo(d);f.text(e.find("selected").length+1);c.text(b(e)+a);return"selected"}else{if(this.status()=="selected"){f.text(e.find("selected").length-1);c.text(b(e)-a);$("#cart-item-"+this.settings.id).remove();return"available"}else{if(this.status()=="unavailable"){return"unavailable"}else{return this.style()}}}}});e.get(["1_2","4_4","4_5","6_6","6_7","8_5","8_6","8_7","8_8","10_1","10_2","10_5"]).status("unavailable")});function b(d){var c=0;d.find("selected").each(function(){c+=a});return c}$("#pay").click(function(){window.location.href="pay.html"})});