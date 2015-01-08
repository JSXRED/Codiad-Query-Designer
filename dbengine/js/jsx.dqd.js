/*
Script Name: Codiad Database Query Designer
Author: JSX.RED 
Author URI: http://www.jsx.red

Description: This plugin allow designing database queries through Codiad user interface.

Copyright (c) 2015 by JSX.RED 
distributed as-is and without warranty under the MIT License. See
[root]/license.txt for more. This information must remain intact.

Let us build a better future for all humanity.
Share knowledge. Share emotions. Share fun.
*/
(function (JSXRED){
	(function (DQD){
		DQD.editor;

		DQD.init = function(){
			jQuery(document).ready(function(){
				// resize query area
				jQuery(".dqd-right").width((jQuery(document).width()-(jQuery(".dqd-left").width()+15)));
				jQuery("#dbobjs").height(jQuery(document).height()-(jQuery(".dqd-left").height()+10));

				// init ace-editor
				jQuery(".editor").width(jQuery(".dqd-right").width());

				DQD.editor = ace.edit("editor");
				var textarea = $('textarea[name="editor"]').hide();
				DQD.editor.getSession().setValue(textarea.val());
				DQD.editor.getSession().on('change', function(){
					textarea.val(DQD.editor .getSession().getValue());
				});
				DQD.editor.setTheme("ace/theme/twilight");
				DQD.editor.getSession().setMode("ace/mode/sql");

				// resize result area
				jQuery(".dqd-result").width(jQuery(".dqd-right").width());
				jQuery(".dqd-result").height(jQuery(document).height()-(jQuery(".editor").height()+70));

				// load connections
				DQD.getConnections();
			});

			DQD.getConnections = function(){
				jQuery('#connection').change(DQD.refreshDatabaseInformation);

				jQuery.get('connectionmanager.php?action=connectionnames', function(data){
					var ret = JSON.parse(data);
					jQuery('#connection').find('option').remove().end();			

					for(i in ret){
						jQuery('#connection').append('<option value="'+ret[i]+'">'+ret[i]+'</option>');
						if(i==0)jQuery('#connection').val(ret[i]).change();
					}
				});

			};

			DQD.executeQuery = function(){
				jQuery(".dqd-tableresult *").remove().end();
				var query = JSXRED.DQD.editor.getSession().getValue();
				jQuery.post("connectionmanager.php?action=executeQuery&connectionname="+encodeURIComponent(jQuery("#connection").val()), {query:query}, function(data){
					data = JSON.parse(data);
					jQuery(".dqd-tableresult").append("<thead><tr></tr></thead>");
					jQuery(".dqd-tableresult").append("<tbody></tbody>");

					if(data.hasOwnProperty("affected_rows")){
						jQuery(".dqd-tableresult tbody").append("<tr><td>"+data.affected_rows+" Row(s) affected.</td></tr>");
					}else if(data.length>=1){
						var keys = Object.keys(data[0]);
			
						for(var i in keys){
							jQuery(".dqd-tableresult thead tr").append("<th>"+keys[i]+"</th>");
						}

						for(var bi in data){
							var tdstring = "";
							for(var i in keys){
								tdstring+="<td>"+data[bi][keys[i]]+"</td>";
							}
							jQuery(".dqd-tableresult tbody").append("<tr>"+tdstring+"</tr>");
						}
					}else{
						jQuery(".dqd-tableresult tbody").append("<tr><td># Unreadable Result.</td></tr>");
					}
				});
			};

			DQD.showColumns = function(obj){
				jQuery(obj).find(".dqd-columnobjs").fadeToggle(10);
			};

			DQD.refreshDatabaseInformation = function(){
				jQuery("#dbobjs").find("li").remove().end();
				jQuery.get('connectionmanager.php?action=dbobjs&connectionname='+encodeURIComponent(jQuery("#connection").val()), function(data){
					var ret = JSON.parse(data);
					if(ret.success=="false"){
						jQuery('#dbobjs').append('<li>'+ret.message+'</li>');
						return;
					}
						
					for(i in ret){
						var icon = "icon-search";
						if(ret[i].Type == "BASE TABLE") icon = "icon-list";

						var sub= "<ul class='dqd-columnobjs'>";
							var columns_json = JSON.parse(ret[i].Columns);
							for(ci in columns_json){
								sub += "<li><a class='icon-flow-cascade dqd-iconfix'></a>"+columns_json[ci].ColumnName +"<br> <span class=\"dqd-darker\">(" +columns_json[ci].ColumnType+")</span></li>";
							}
						sub +="</ul>";
 
						jQuery('#dbobjs').append('<li style="cursor: pointer;" onclick="JSXRED.DQD.showColumns(this); return false;"><a class="icon-right-dir dqd-iconfix"></a><a class="'+icon+' dqd-iconfix"></a>'+ret[i].Name+sub+'</li>');
					}
					
				});
			};
		}
	}(window.JSXRED.DQD = window.JSXRED.DQD || {}));
}(window.JSXRED = window.JSXRED || {}));
JSXRED.DQD.init();
