	$.fbuilder.typeList.push(
		{
			id:"ftext",
			name:"Single Line Text",
			control_category:1
		}
	);
	$.fbuilder.controls[ 'ftext' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'ftext' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Untitled",
			ftype:"ftext",
			predefined:"",
			predefinedClick:false,
			required:false,
			exclude:false,
			readonly:false,
			size:"medium",
			minlength:"",
			maxlength:"",
			equalTo:"",
			regExp:"",
			regExpMssg:"",
			display:function()
				{
					return '<div class="fields '+this.name+'" id="field'+this.form_identifier+'-'+this.index+'" title="'+this.name+'"><div class="arrow ui-icon ui-icon-play "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><label>'+this.title+''+((this.required)?"*":"")+'</label><div class="dfield"><input class="field disabled '+this.size+'" type="text" value="'+$.fbuilder.htmlEncode(this.predefined)+'"/><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
				},
			editItemEvents:function()
				{
					var me = this, evt = [
						{s:"#sSize",e:"change", l:"size"},
						{s:"#sMinlength",e:"change keyup", l:"minlength"},
						{s:"#sMaxlength",e:"change keyup", l:"maxlength"},
						{s:"#sRegExp",e:"change keyup", l:"regExp"},
						{s:"#sRegExpMssg",e:"change keyup", l:"regExpMssg"},
						{s:"#sEqualTo",e:"change", l:"equalTo"}
					],
					items = this.fBuild.getItems();
					$('.equalTo').each(function(){
						var str = '<option value="" '+(("" == $(this).attr("dvalue"))?"selected":"")+'></option>';
						for (var i=0;i<items.length;i++)
						{
							if (
								$.inArray(items[i].ftype, ['ftext', 'femail', 'fpassword', 'ftextds', 'femailds']) != -1 &&
								items[i].name != $(this).attr("dname")
							)
							{
								str += '<option value="'+$.fbuilder.htmlEncode(items[i].name)+'" '+((items[i].name == $(this).attr("dvalue"))?"selected":"")+'>'+(items[i].title)+'</option>';
							}
						}
						$(this).html(str);
					});
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this,evt);
				},
			showSpecialDataInstance: function()
				{
					return '<div class="column"><label>Min length/characters</label><input type="text" name="sMinlength" id="sMinlength" value="'+$.fbuilder.htmlEncode(this.minlength)+'"></div><div class="column"><label>Max length/characters</label><input type="text" name="sMaxlength" id="sMaxlength" value="'+$.fbuilder.htmlEncode(this.maxlength)+'"></div><div class="clearer"></div><div><label>Validate against a regular expression</label><input type="text" name="sRegExp" id="sRegExp" value="'+$.fbuilder.htmlEncode(this.regExp)+'" class="large" /></div><div><label>Error message when the regular expression fails</label><input type="text" name="sRegExpMssg" id="sRegExpMssg" value="'+$.fbuilder.htmlEncode(this.regExpMssg)+'" class="large" /></div>';
				}
	});